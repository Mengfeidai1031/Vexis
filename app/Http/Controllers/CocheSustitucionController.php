<?php

namespace App\Http\Controllers;

use App\Models\CocheSustitucion;
use App\Models\ReservaSustitucion;
use App\Models\Taller;
use App\Models\Marca;
use App\Models\Empresa;
use Illuminate\Http\Request;

class CocheSustitucionController extends Controller
{
    public function index(Request $request)
    {
        $query = CocheSustitucion::with(['marca', 'taller', 'empresa']);
        if ($request->filled('taller_id')) $query->where('taller_id', $request->taller_id);
        if ($request->filled('marca_id')) $query->where('marca_id', $request->marca_id);
        if ($request->filled('disponible')) $query->where('disponible', $request->disponible);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('matricula')) $query->where('matricula', $request->matricula);
        if ($request->filled('modelo')) $query->where('modelo', $request->modelo);
        // Sorting
        $sortable = ['id', 'matricula', 'modelo', 'marca_id', 'color', 'taller_id', 'disponible'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $coches = $query->paginate(15)->withQueryString();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();

        // Reservas del mes para calendario
        $mes = $request->filled('mes') ? \Carbon\Carbon::parse($request->mes . '-01') : now()->startOfMonth();
        $reservas = ReservaSustitucion::with('coche')
            ->where('estado', '!=', 'cancelado')
            ->where(function ($q) use ($mes) { $q->whereBetween('fecha_inicio', [$mes, $mes->copy()->endOfMonth()])->orWhereBetween('fecha_fin', [$mes, $mes->copy()->endOfMonth()]); })
            ->get()->map(fn($r) => ['title' => $r->coche->matricula . ' — ' . $r->cliente_nombre, 'start' => $r->fecha_inicio->format('Y-m-d'), 'end' => $r->fecha_fin->addDay()->format('Y-m-d'), 'color' => match($r->estado) { 'reservado' => '#f39c12', 'entregado' => '#3498db', default => '#2ecc71' }]);

        $marcas = Marca::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $matriculas_cs = CocheSustitucion::distinct()->orderBy('matricula')->pluck('matricula');
        $modelos_cs = CocheSustitucion::distinct()->orderBy('modelo')->pluck('modelo');

        return view('coches-sustitucion.index', compact('coches', 'talleres', 'reservas', 'mes', 'marcas', 'empresas', 'matriculas_cs', 'modelos_cs'));
    }

    public function create()
    {
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        return view('coches-sustitucion.create', compact('talleres', 'marcas', 'empresas'));
    }

    public function store(Request $request)
    {
        $request->validate(['matricula'=>'required|max:10|unique:coches_sustitucion','modelo'=>'required|max:100','marca_id'=>'required|exists:marcas,id','taller_id'=>'required|exists:talleres,id','empresa_id'=>'required|exists:empresas,id']);
        CocheSustitucion::create($request->all());
        return redirect()->route('coches-sustitucion.index')->with('success', 'Coche de sustitución registrado.');
    }

    public function show(CocheSustitucion $coches_sustitucion)
    {
        $coches_sustitucion->load(['marca', 'taller', 'empresa', 'reservas']);
        return view('coches-sustitucion.show', compact('coches_sustitucion'));
    }

    public function edit(CocheSustitucion $coches_sustitucion)
    {
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        return view('coches-sustitucion.edit', compact('coches_sustitucion', 'talleres', 'marcas', 'empresas'));
    }

    public function update(Request $request, CocheSustitucion $coches_sustitucion)
    {
        $request->validate(['matricula'=>'required|max:10|unique:coches_sustitucion,matricula,'.$coches_sustitucion->id,'modelo'=>'required|max:100','marca_id'=>'required|exists:marcas,id','taller_id'=>'required|exists:talleres,id','empresa_id'=>'required|exists:empresas,id']);
        $coches_sustitucion->update([...$request->all(), 'disponible' => $request->boolean('disponible', true)]);
        return redirect()->route('coches-sustitucion.index')->with('success', 'Coche actualizado.');
    }

    public function destroy(CocheSustitucion $coches_sustitucion)
    {
        $coches_sustitucion->delete();
        return redirect()->route('coches-sustitucion.index')->with('success', 'Coche eliminado.');
    }

    public function reservar(Request $request, CocheSustitucion $coche)
    {
        $request->validate(['cliente_nombre'=>'required|max:255','fecha_inicio'=>'required|date','fecha_fin'=>'required|date|after_or_equal:fecha_inicio']);
        ReservaSustitucion::create([...$request->only('cliente_nombre', 'fecha_inicio', 'fecha_fin', 'observaciones'), 'coche_id' => $coche->id]);
        return back()->with('success', 'Reserva creada correctamente.');
    }
}
