<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Vacacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacacionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $anio = (int) $request->input('anio', now()->year);
        $isSuperAdmin = $user->hasRole('Super Admin') || $user->hasRole('Administrador');

        $query = Vacacion::query()->with('user');
        if (! $isSuperAdmin) {
            $query->where('vacaciones.user_id', $user->id);
        }
        if ($request->filled('estado')) {
            $query->where('vacaciones.estado', $request->input('estado'));
        }
        if ($isSuperAdmin && $request->filled('user_id')) {
            $query->where('vacaciones.user_id', (int) $request->input('user_id'));
        }
        $query->whereYear('vacaciones.fecha_inicio', $anio);

        $sortable = ['id', 'user_id', 'fecha_inicio', 'fecha_fin', 'dias_solicitados', 'estado', 'motivo'];
        $sortBy = $request->input('sort_by');
        if ($sortBy && in_array($sortBy, $sortable, true)) {
            $dir = $request->input('sort_dir') === 'desc' ? 'desc' : 'asc';
            $query->reorder('vacaciones.'.$sortBy, $dir);
        } else {
            $query->reorder('vacaciones.fecha_inicio', 'desc');
        }

        $vacaciones = $query->paginate(15)->withQueryString();

        $diasUsados = Vacacion::diasUsados($user->id, $anio);
        $diasDisponibles = Vacacion::diasAsignados() - $diasUsados;

        // Eventos para calendario
        $eventos = Vacacion::with('user')
            ->whereYear('fecha_inicio', $anio)
            ->when(! $isSuperAdmin, fn ($q) => $q->where('user_id', $user->id))
            ->get()
            ->map(fn ($v) => [
                'title' => ($isSuperAdmin ? $v->user->nombre.' ' : '').$v->dias_solicitados.'d',
                'start' => $v->fecha_inicio->format('Y-m-d'),
                'end' => $v->fecha_fin->addDay()->format('Y-m-d'),
                'color' => match ($v->estado) {
                    'aprobada' => '#2ecc71',
                    'rechazada' => '#e74c3c',
                    default => '#f39c12',
                },
                'estado' => $v->estado,
            ]);

        $usuarios_vac = \App\Models\User::orderBy('nombre')->get();
        $anios_disponibles = Vacacion::selectRaw('YEAR(fecha_inicio) as anio')->distinct()->orderByDesc('anio')->pluck('anio');

        return view('vacaciones.index', compact('vacaciones', 'diasUsados', 'diasDisponibles', 'anio', 'eventos', 'isSuperAdmin', 'usuarios_vac', 'anios_disponibles'));
    }

    public function create()
    {
        $diasUsados = Vacacion::diasUsados(Auth::id());
        $diasDisponibles = Vacacion::diasAsignados() - $diasUsados;

        return view('vacaciones.create', compact('diasUsados', 'diasDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'nullable|string|max:500',
        ]);

        $inicio = \Carbon\Carbon::parse($request->fecha_inicio);
        $fin = \Carbon\Carbon::parse($request->fecha_fin);
        $dias = 0;
        for ($d = $inicio->copy(); $d->lte($fin); $d->addDay()) {
            if (! $d->isWeekend()) {
                $dias++;
            }
        }
        if ($dias === 0) {
            return back()->with('error', 'El rango seleccionado no contiene días laborables.')->withInput();
        }

        $diasUsados = Vacacion::diasUsados(Auth::id());
        if ($diasUsados + $dias > Vacacion::diasAsignados()) {
            return back()->with('error', "No tienes suficientes días disponibles. Solicitas $dias días pero solo te quedan ".(Vacacion::diasAsignados() - $diasUsados).'.')->withInput();
        }

        Vacacion::create([
            'user_id' => Auth::id(),
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'dias_solicitados' => $dias,
            'motivo' => $request->motivo,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('vacaciones.index')->with('success', "Solicitud de $dias días creada correctamente.");
    }

    public function show(Vacacion $vacacion)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin') || $user->hasRole('Administrador');
        if (! $isSuperAdmin && $vacacion->user_id !== $user->id) {
            abort(403);
        }
        $vacacion->load(['user', 'aprobador']);

        return view('vacaciones.show', compact('vacacion'));
    }

    public function gestionar(Request $request, Vacacion $vacacion)
    {
        $request->validate([
            'estado' => 'required|in:aprobada,rechazada',
            'respuesta' => 'nullable|string|max:500',
        ]);

        $vacacion->update([
            'estado' => $request->estado,
            'respuesta' => $request->respuesta,
            'aprobado_por' => Auth::id(),
        ]);

        $accion = $request->estado === 'aprobada' ? 'aprobada' : 'rechazada';

        return back()->with('success', "Solicitud $accion correctamente.");
    }

    public function destroy(Vacacion $vacacion)
    {
        if ($vacacion->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden eliminar solicitudes pendientes.');
        }
        if ($vacacion->user_id !== Auth::id() && ! Auth::user()->hasAnyRole(['Super Admin', 'Administrador'])) {
            return back()->with('error', 'No tienes permiso para eliminar esta solicitud.');
        }
        $vacacion->delete();

        return back()->with('success', 'Solicitud eliminada correctamente.');
    }
}
