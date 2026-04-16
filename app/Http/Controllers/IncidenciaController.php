<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\IncidenciaArchivo;
use App\Models\User;
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

        // Sorting
        $sortable = ['id', 'codigo_incidencia', 'titulo', 'prioridad', 'estado', 'usuario_id', 'tecnico_id', 'fecha_apertura'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $incidencias = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Incidencia::count(),
            'abiertas' => Incidencia::where('estado', 'abierta')->count(),
            'en_progreso' => Incidencia::where('estado', 'en_progreso')->count(),
            'resueltas' => Incidencia::whereIn('estado', ['resuelta', 'cerrada'])->count(),
            'criticas' => Incidencia::where('prioridad', 'critica')->whereNotIn('estado', ['resuelta', 'cerrada'])->count(),
        ];

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

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'estado' => 'required|in:abierta,en_progreso,resuelta,cerrada',
            'tecnico_id' => 'nullable|exists:users,id',
            'archivos_usuario.*' => 'nullable|file|max:10240',
            'archivos_tecnico.*' => 'nullable|file|max:10240',
        ]);

        $codigo = 'INC-'.date('Ym').'-'.str_pad(
            Incidencia::whereYear('fecha_apertura', date('Y'))->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        $data = [
            'codigo_incidencia' => $codigo,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'estado' => $request->estado,
            'usuario_id' => Auth::id(),
            'tecnico_id' => $request->tecnico_id,
            'fecha_apertura' => now(),
        ];

        if (in_array($request->estado, ['resuelta', 'cerrada'])) {
            $data['fecha_cierre'] = now();
        }

        $incidencia = Incidencia::create($data);

        // Archivos del usuario
        if ($request->hasFile('archivos_usuario')) {
            foreach ($request->file('archivos_usuario') as $file) {
                $path = $file->store('incidencias/'.$incidencia->id, 'public');
                IncidenciaArchivo::create([
                    'incidencia_id' => $incidencia->id,
                    'user_id' => Auth::id(),
                    'ruta' => $path,
                    'nombre_original' => $file->getClientOriginalName(),
                    'tipo' => 'usuario',
                ]);
            }
        }

        // Archivos del técnico
        if ($request->hasFile('archivos_tecnico')) {
            foreach ($request->file('archivos_tecnico') as $file) {
                $path = $file->store('incidencias/'.$incidencia->id, 'public');
                IncidenciaArchivo::create([
                    'incidencia_id' => $incidencia->id,
                    'user_id' => Auth::id(),
                    'ruta' => $path,
                    'nombre_original' => $file->getClientOriginalName(),
                    'tipo' => 'tecnico',
                ]);
            }
        }

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

    public function update(Request $request, Incidencia $incidencia)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'estado' => 'required|in:abierta,en_progreso,resuelta,cerrada',
            'tecnico_id' => 'nullable|exists:users,id',
            'comentario_tecnico' => 'nullable|string',
            'archivos_usuario.*' => 'nullable|file|max:10240',
            'archivos_tecnico.*' => 'nullable|file|max:10240',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'prioridad', 'estado', 'tecnico_id', 'comentario_tecnico']);

        if (in_array($request->estado, ['resuelta', 'cerrada']) && ! $incidencia->fecha_cierre) {
            $data['fecha_cierre'] = now();
        }
        if (in_array($request->estado, ['abierta', 'en_progreso'])) {
            $data['fecha_cierre'] = null;
        }

        $incidencia->update($data);

        // Archivos del usuario
        if ($request->hasFile('archivos_usuario')) {
            foreach ($request->file('archivos_usuario') as $file) {
                $path = $file->store('incidencias/'.$incidencia->id, 'public');
                IncidenciaArchivo::create([
                    'incidencia_id' => $incidencia->id,
                    'user_id' => Auth::id(),
                    'ruta' => $path,
                    'nombre_original' => $file->getClientOriginalName(),
                    'tipo' => 'usuario',
                ]);
            }
        }

        // Archivos del técnico
        if ($request->hasFile('archivos_tecnico')) {
            foreach ($request->file('archivos_tecnico') as $file) {
                $path = $file->store('incidencias/'.$incidencia->id, 'public');
                IncidenciaArchivo::create([
                    'incidencia_id' => $incidencia->id,
                    'user_id' => Auth::id(),
                    'ruta' => $path,
                    'nombre_original' => $file->getClientOriginalName(),
                    'tipo' => 'tecnico',
                ]);
            }
        }

        return redirect()->route('incidencias.show', $incidencia)
            ->with('success', 'Incidencia actualizada correctamente.');
    }

    public function destroy(Incidencia $incidencia)
    {
        // Eager-load and delete each archivo individually to trigger model's deleting event (file cleanup)
        $incidencia->archivos->each->delete();
        Storage::disk('public')->deleteDirectory('incidencias/' . $incidencia->id);
        $incidencia->delete();

        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada correctamente.');
    }

    public function eliminarArchivo(IncidenciaArchivo $archivo)
    {
        $incidencia = $archivo->incidencia;
        $archivo->delete();

        return redirect()->route('incidencias.show', $incidencia)->with('success', 'Archivo eliminado.');
    }
}
