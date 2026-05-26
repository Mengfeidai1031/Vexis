<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncidenciaRequest;
use App\Http\Requests\UpdateIncidenciaRequest;
use App\Models\Incidencia;
use App\Models\IncidenciaArchivo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncidenciaController extends Controller
{
    public function index(Request $request)
    {
        $query = Incidencia::with(['usuario', 'tecnico']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }
        if ($request->filled('tecnico_id')) {
            $query->where('tecnico_id', $request->tecnico_id);
        }
        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_apertura', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_apertura', '<=', $request->fecha_hasta);
        }
        if ($request->filled('codigo_incidencia')) {
            $query->where('codigo_incidencia', $request->codigo_incidencia);
        }
        if ($request->filled('titulo')) {
            $query->where('titulo', $request->titulo);
        }

        $sortable = ['id', 'codigo_incidencia', 'titulo', 'prioridad', 'estado', 'usuario_id', 'tecnico_id', 'fecha_apertura'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $incidencias = $query->paginate(15)->withQueryString();

        // Consolidar 5 COUNT queries en 1 sola
        $stats = Incidencia::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN estado = 'abierta' THEN 1 ELSE 0 END) as abiertas")
            ->selectRaw("SUM(CASE WHEN estado = 'en_progreso' THEN 1 ELSE 0 END) as en_progreso")
            ->selectRaw("SUM(CASE WHEN estado IN ('resuelta','cerrada') THEN 1 ELSE 0 END) as resueltas")
            ->selectRaw("SUM(CASE WHEN prioridad = 'critica' AND estado NOT IN ('resuelta','cerrada') THEN 1 ELSE 0 END) as criticas")
            ->first();

        $tecnicos = User::role(['Super Admin', 'Administrador'])->orderBy('nombre')->get();
        $usuarios = User::orderBy('nombre')->get();
        $codigos_incidencia = Incidencia::distinct()->orderBy('codigo_incidencia')->pluck('codigo_incidencia');
        $titulos_incidencia = Incidencia::distinct()->orderBy('titulo')->pluck('titulo');

        return view('incidencias.index', compact('incidencias', 'stats', 'tecnicos', 'usuarios', 'codigos_incidencia', 'titulos_incidencia'));
    }

    public function create()
    {
        $tecnicos = User::role(['Super Admin', 'Administrador'])->orderBy('nombre')->get();

        return view('incidencias.create', compact('tecnicos'));
    }

    public function store(StoreIncidenciaRequest $request): RedirectResponse
    {
        $codigo = 'INC-'.date('Ym').'-'.str_pad(
            (string) (Incidencia::whereYear('fecha_apertura', date('Y'))->count() + 1),
            4, '0', STR_PAD_LEFT
        );

        $data = [
            'codigo_incidencia' => $codigo,
            'titulo' => $request->validated('titulo'),
            'descripcion' => $request->validated('descripcion'),
            'prioridad' => $request->validated('prioridad'),
            'estado' => $request->validated('estado'),
            'usuario_id' => Auth::id(),
            'tecnico_id' => $request->validated('tecnico_id'),
            'fecha_apertura' => now(),
        ];

        if (in_array($request->validated('estado'), ['resuelta', 'cerrada'])) {
            $data['fecha_cierre'] = now();
        }

        $incidencia = Incidencia::create($data);

        $this->guardarArchivos($incidencia, $request);

        return redirect()->route('incidencias.show', $incidencia)
            ->with('success', "Incidencia {$codigo} creada correctamente.");
    }

    public function show(Incidencia $incidencia)
    {
        $incidencia->load(['usuario', 'tecnico', 'archivos.user']);
        $tecnicos = User::role(['Super Admin', 'Administrador'])->orderBy('nombre')->get();

        return view('incidencias.show', compact('incidencia', 'tecnicos'));
    }

    public function edit(Incidencia $incidencia)
    {
        $incidencia->load(['usuario', 'tecnico', 'archivos']);
        $tecnicos = User::role(['Super Admin', 'Administrador'])->orderBy('nombre')->get();

        return view('incidencias.edit', compact('incidencia', 'tecnicos'));
    }

    public function update(UpdateIncidenciaRequest $request, Incidencia $incidencia): RedirectResponse
    {
        $data = $request->only(['titulo', 'descripcion', 'prioridad', 'estado', 'tecnico_id', 'comentario_tecnico']);

        if (in_array($request->validated('estado'), ['resuelta', 'cerrada']) && ! $incidencia->fecha_cierre) {
            $data['fecha_cierre'] = now();
        }
        if (in_array($request->validated('estado'), ['abierta', 'en_progreso'])) {
            $data['fecha_cierre'] = null;
        }

        $incidencia->update($data);

        $this->guardarArchivos($incidencia, $request);

        return redirect()->route('incidencias.show', $incidencia)
            ->with('success', 'Incidencia actualizada correctamente.');
    }

    public function destroy(Incidencia $incidencia): RedirectResponse
    {
        $incidencia->archivos->each->delete();
        Storage::disk('public')->deleteDirectory('incidencias/'.$incidencia->id);
        $incidencia->delete();

        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada correctamente.');
    }

    public function eliminarArchivo(IncidenciaArchivo $archivo): RedirectResponse
    {
        $incidencia = $archivo->incidencia;
        $archivo->delete();

        return redirect()->route('incidencias.show', $incidencia)->with('success', 'Archivo eliminado.');
    }

    /**
     * Almacena archivos adjuntos de usuario y técnico (DRY: extrae lógica duplicada de store/update).
     */
    private function guardarArchivos(Incidencia $incidencia, Request $request): void
    {
        foreach (['archivos_usuario' => 'usuario', 'archivos_tecnico' => 'tecnico'] as $field => $tipo) {
            if (! $request->hasFile($field)) {
                continue;
            }

            foreach ($request->file($field) as $file) {
                $path = $file->store('incidencias/'.$incidencia->id, 'public');
                IncidenciaArchivo::create([
                    'incidencia_id' => $incidencia->id,
                    'user_id' => Auth::id(),
                    'ruta' => $path,
                    'nombre_original' => $file->getClientOriginalName(),
                    'tipo' => $tipo,
                ]);
            }
        }
    }
}
