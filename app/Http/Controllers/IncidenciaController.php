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

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('codigo_incidencia', 'like', "%$s%")
                  ->orWhere('titulo', 'like', "%$s%");
            });
        }
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('prioridad')) $query->where('prioridad', $request->prioridad);
        if ($request->filled('tecnico_id')) $query->where('tecnico_id', $request->tecnico_id);

        $incidencias = $query->orderByDesc('fecha_apertura')->paginate(15)->withQueryString();

        $stats = [
            'total' => Incidencia::count(),
            'abiertas' => Incidencia::where('estado', 'abierta')->count(),
            'en_progreso' => Incidencia::where('estado', 'en_progreso')->count(),
            'resueltas' => Incidencia::whereIn('estado', ['resuelta', 'cerrada'])->count(),
            'criticas' => Incidencia::where('prioridad', 'critica')->whereNotIn('estado', ['resuelta', 'cerrada'])->count(),
        ];

        $tecnicos = User::role(['Super Admin', 'Administrador'])->orderBy('name')->get();

        return view('incidencias.index', compact('incidencias', 'stats', 'tecnicos'));
    }

    public function create()
    {
        return view('incidencias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'archivos.*' => 'nullable|file|max:10240',
        ]);

        $codigo = 'INC-' . date('Ym') . '-' . str_pad(
            Incidencia::whereYear('fecha_apertura', date('Y'))->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        $incidencia = Incidencia::create([
            'codigo_incidencia' => $codigo,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'usuario_id' => Auth::id(),
            'fecha_apertura' => now(),
        ]);

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $file) {
                $path = $file->store('incidencias/' . $incidencia->id, 'public');
                IncidenciaArchivo::create([
                    'incidencia_id' => $incidencia->id,
                    'user_id' => Auth::id(),
                    'ruta' => $path,
                    'nombre_original' => $file->getClientOriginalName(),
                    'tipo' => 'usuario',
                ]);
            }
        }

        return redirect()->route('incidencias.show', $incidencia)
            ->with('success', "Incidencia {$codigo} creada correctamente.");
    }

    public function show(Incidencia $incidencia)
    {
        $incidencia->load(['usuario', 'tecnico', 'archivos.user']);
        $tecnicos = User::role(['Super Admin', 'Administrador'])->orderBy('name')->get();
        return view('incidencias.show', compact('incidencia', 'tecnicos'));
    }

    public function edit(Incidencia $incidencia)
    {
        $incidencia->load(['usuario', 'tecnico', 'archivos']);
        $tecnicos = User::role(['Super Admin', 'Administrador'])->orderBy('name')->get();
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
            'archivos.*' => 'nullable|file|max:10240',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'prioridad', 'estado', 'tecnico_id', 'comentario_tecnico']);

        if (in_array($request->estado, ['resuelta', 'cerrada']) && !$incidencia->fecha_cierre) {
            $data['fecha_cierre'] = now();
        }
        if (in_array($request->estado, ['abierta', 'en_progreso'])) {
            $data['fecha_cierre'] = null;
        }

        $incidencia->update($data);

        if ($request->hasFile('archivos')) {
            $tipo = Auth::user()->hasRole(['Super Admin', 'Administrador']) ? 'tecnico' : 'usuario';
            foreach ($request->file('archivos') as $file) {
                $path = $file->store('incidencias/' . $incidencia->id, 'public');
                IncidenciaArchivo::create([
                    'incidencia_id' => $incidencia->id,
                    'user_id' => Auth::id(),
                    'ruta' => $path,
                    'nombre_original' => $file->getClientOriginalName(),
                    'tipo' => $tipo,
                ]);
            }
        }

        return redirect()->route('incidencias.show', $incidencia)
            ->with('success', 'Incidencia actualizada correctamente.');
    }

    public function destroy(Incidencia $incidencia)
    {
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
