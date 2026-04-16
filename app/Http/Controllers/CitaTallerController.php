<?php

namespace App\Http\Controllers;

use App\Models\CitaTaller;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\Mecanico;
use App\Models\Taller;
use Illuminate\Http\Request;

class CitaTallerController extends Controller
{
    public function index(Request $request)
    {
        $query = CitaTaller::with(['mecanico', 'taller', 'marca']);
        if ($request->filled('taller_id')) {
            $query->where('taller_id', $request->taller_id);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }
        if ($request->filled('mecanico_id')) {
            $query->where('mecanico_id', $request->mecanico_id);
        }
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }
        if ($request->filled('cliente_nombre')) {
            $query->where('cliente_nombre', $request->cliente_nombre);
        }
        // Sorting
        $sortable = ['id', 'fecha', 'hora_inicio', 'hora_fin', 'cliente_nombre', 'vehiculo_info', 'mecanico_id', 'taller_id', 'estado'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $citas = $query->paginate(15)->withQueryString();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();

        // Calendario semanal
        $semanaInicio = $request->filled('semana') ? \Carbon\Carbon::parse($request->semana)->startOfWeek() : now()->startOfWeek();
        $semanaFin = $semanaInicio->copy()->endOfWeek();
        $citasSemana = CitaTaller::with('mecanico')
            ->whereBetween('fecha', [$semanaInicio, $semanaFin])
            ->when($request->filled('taller_id'), fn ($q) => $q->where('taller_id', $request->taller_id))
            ->orderBy('fecha')->orderBy('hora_inicio')->get()
            ->groupBy(fn ($c) => $c->fecha->format('Y-m-d'));

        $mecanicos = Mecanico::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $clientes_citas = CitaTaller::distinct()->orderBy('cliente_nombre')->pluck('cliente_nombre');

        return view('citas.index', compact('citas', 'talleres', 'citasSemana', 'semanaInicio', 'semanaFin', 'mecanicos', 'marcas', 'clientes_citas'));
    }

    public function create()
    {
        $mecanicos = Mecanico::where('activo', true)->orderBy('apellidos')->get();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();

        return view('citas.create', compact('mecanicos', 'talleres', 'marcas', 'empresas'));
    }

    public function store(Request $request)
    {
        $request->validate(['mecanico_id' => 'required|exists:mecanicos,id', 'taller_id' => 'required|exists:talleres,id', 'empresa_id' => 'required|exists:empresas,id', 'cliente_nombre' => 'required|max:255', 'fecha' => 'required|date', 'hora_inicio' => 'required', 'estado' => 'required|in:pendiente,confirmada,en_curso,completada,cancelada']);
        CitaTaller::create($request->only(['mecanico_id', 'taller_id', 'marca_id', 'empresa_id', 'cliente_nombre', 'vehiculo_info', 'fecha', 'hora_inicio', 'hora_fin', 'descripcion', 'estado']));

        return redirect()->route('citas.index')->with('success', 'Cita creada correctamente.');
    }

    public function show(CitaTaller $cita)
    {
        $cita->load(['mecanico', 'taller', 'marca', 'empresa']);

        return view('citas.show', compact('cita'));
    }

    public function edit(CitaTaller $cita)
    {
        $mecanicos = Mecanico::where('activo', true)->orderBy('apellidos')->get();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();

        return view('citas.edit', compact('cita', 'mecanicos', 'talleres', 'marcas', 'empresas'));
    }

    public function update(Request $request, CitaTaller $cita)
    {
        $request->validate(['mecanico_id' => 'required|exists:mecanicos,id', 'taller_id' => 'required|exists:talleres,id', 'empresa_id' => 'required|exists:empresas,id', 'cliente_nombre' => 'required|max:255', 'fecha' => 'required|date', 'hora_inicio' => 'required', 'estado' => 'required|in:pendiente,confirmada,en_curso,completada,cancelada']);
        $cita->update($request->only(['mecanico_id', 'taller_id', 'marca_id', 'empresa_id', 'cliente_nombre', 'vehiculo_info', 'fecha', 'hora_inicio', 'hora_fin', 'descripcion', 'estado']));

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(CitaTaller $cita)
    {
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }
}
