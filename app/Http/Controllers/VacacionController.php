<?php

namespace App\Http\Controllers;

use App\Models\Vacacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacacionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $anio = $request->input('anio', now()->year);
        $isSuperAdmin = $user->hasRole('Super Admin') || $user->hasRole('Administrador');

        $query = Vacacion::with('user');
        if (!$isSuperAdmin) {
            $query->where('user_id', $user->id);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        $vacaciones = $query->whereYear('fecha_inicio', $anio)->orderByDesc('created_at')->paginate(15)->withQueryString();

        $diasUsados = Vacacion::diasUsados($user->id, $anio);
        $diasDisponibles = Vacacion::DIAS_TOTALES - $diasUsados;

        // Eventos para calendario
        $eventos = Vacacion::with('user')
            ->whereYear('fecha_inicio', $anio)
            ->when(!$isSuperAdmin, fn($q) => $q->where('user_id', $user->id))
            ->get()
            ->map(fn($v) => [
                'title' => ($isSuperAdmin ? $v->user->nombre . ' ' : '') . $v->dias_solicitados . 'd',
                'start' => $v->fecha_inicio->format('Y-m-d'),
                'end' => $v->fecha_fin->addDay()->format('Y-m-d'),
                'color' => match($v->estado) {
                    'aprobada' => '#2ecc71',
                    'rechazada' => '#e74c3c',
                    default => '#f39c12',
                },
                'estado' => $v->estado,
            ]);

        return view('vacaciones.index', compact('vacaciones', 'diasUsados', 'diasDisponibles', 'anio', 'eventos', 'isSuperAdmin'));
    }

    public function create()
    {
        $diasUsados = Vacacion::diasUsados(Auth::id());
        $diasDisponibles = Vacacion::DIAS_TOTALES - $diasUsados;
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
        $dias = $inicio->diffInWeekdays($fin) + 1;

        $diasUsados = Vacacion::diasUsados(Auth::id());
        if ($diasUsados + $dias > Vacacion::DIAS_TOTALES) {
            return back()->with('error', "No tienes suficientes días disponibles. Solicitas $dias días pero solo te quedan " . (Vacacion::DIAS_TOTALES - $diasUsados) . ".")->withInput();
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
        if ($vacacion->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['Super Admin', 'Administrador'])) {
            return back()->with('error', 'No tienes permiso para eliminar esta solicitud.');
        }
        $vacacion->delete();
        return back()->with('success', 'Solicitud eliminada correctamente.');
    }
}
