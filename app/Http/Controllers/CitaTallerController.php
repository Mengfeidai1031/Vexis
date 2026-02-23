<?php

namespace App\Http\Controllers;

use App\Models\CitaTaller;
use App\Models\Mecanico;
use App\Models\Taller;
use App\Models\Marca;
use App\Models\Empresa;
use Illuminate\Http\Request;

class CitaTallerController extends Controller
{
    public function index(Request $request)
    {
        $query = CitaTaller::with(['mecanico', 'taller', 'marca']);
        if ($request->filled('search')) { $s = $request->search; $query->where(function ($q) use ($s) { $q->where('cliente_nombre', 'like', "%$s%")->orWhere('vehiculo_info', 'like', "%$s%"); }); }
        if ($request->filled('taller_id')) $query->where('taller_id', $request->taller_id);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('fecha')) $query->whereDate('fecha', $request->fecha);
        $citas = $query->orderByDesc('fecha')->orderBy('hora_inicio')->paginate(15)->withQueryString();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();

        // Calendario semanal
        $semanaInicio = $request->filled('semana') ? \Carbon\Carbon::parse($request->semana)->startOfWeek() : now()->startOfWeek();
        $semanaFin = $semanaInicio->copy()->endOfWeek();
        $citasSemana = CitaTaller::with('mecanico')
            ->whereBetween('fecha', [$semanaInicio, $semanaFin])
            ->when($request->filled('taller_id'), fn($q) => $q->where('taller_id', $request->taller_id))
            ->orderBy('fecha')->orderBy('hora_inicio')->get()
            ->groupBy(fn($c) => $c->fecha->format('Y-m-d'));

        return view('citas.index', compact('citas', 'talleres', 'citasSemana', 'semanaInicio', 'semanaFin'));
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
        $request->validate(['mecanico_id'=>'required|exists:mecanicos,id','taller_id'=>'required|exists:talleres,id','empresa_id'=>'required|exists:empresas,id','cliente_nombre'=>'required|max:255','fecha'=>'required|date','hora_inicio'=>'required','estado'=>'required|in:pendiente,confirmada,en_curso,completada,cancelada']);
        CitaTaller::create($request->all());
        return redirect()->route('citas.index')->with('success', 'Cita creada correctamente.');
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
        $request->validate(['mecanico_id'=>'required|exists:mecanicos,id','taller_id'=>'required|exists:talleres,id','empresa_id'=>'required|exists:empresas,id','cliente_nombre'=>'required|max:255','fecha'=>'required|date','hora_inicio'=>'required','estado'=>'required|in:pendiente,confirmada,en_curso,completada,cancelada']);
        $cita->update($request->all());
        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(CitaTaller $cita)
    {
        $cita->delete();
        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }
}
