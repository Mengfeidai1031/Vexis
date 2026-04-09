<?php

namespace App\Http\Controllers;

use App\Models\Reparto;
use App\Models\Stock;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Centro;
use Illuminate\Http\Request;

class RepartoController extends Controller
{
    public function index(Request $request)
    {
        $query = Reparto::with(['stock', 'almacenOrigen', 'almacenDestino', 'empresa']);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('almacen_origen_id')) $query->where('almacen_origen_id', $request->almacen_origen_id);
        if ($request->filled('almacen_destino_id')) $query->where('almacen_destino_id', $request->almacen_destino_id);
        if ($request->filled('fecha_desde')) $query->whereDate('fecha_solicitud', '>=', $request->fecha_desde);
        if ($request->filled('fecha_hasta')) $query->whereDate('fecha_solicitud', '<=', $request->fecha_hasta);
        if ($request->filled('codigo_reparto')) $query->where('codigo_reparto', $request->codigo_reparto);
        if ($request->filled('stock_id')) $query->where('stock_id', $request->stock_id);

        // Sorting
        $sortable = ['id', 'codigo_reparto', 'stock_id', 'cantidad', 'almacen_origen_id', 'almacen_destino_id', 'estado', 'fecha_solicitud'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $repartos = $query->paginate(15)->withQueryString();
        $empresas = Empresa::orderBy('nombre')->get();
        $almacenes = Almacen::orderBy('nombre')->get();
        $codigos_reparto = Reparto::distinct()->orderBy('codigo_reparto')->pluck('codigo_reparto');
        $stocks_reparto = Stock::orderBy('nombre_pieza')->get();
        return view('repartos.index', compact('repartos', 'empresas', 'almacenes', 'codigos_reparto', 'stocks_reparto'));
    }

    public function create()
    {
        $stocks = Stock::where('activo', true)->where('cantidad', '>', 0)->orderBy('nombre_pieza')->get();
        $almacenes = Almacen::where('activo', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        return view('repartos.create', compact('stocks', 'almacenes', 'empresas', 'centros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'almacen_origen_id' => 'required|exists:almacenes,id',
            'almacen_destino_id' => 'nullable|exists:almacenes,id|different:almacen_origen_id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_solicitud' => 'required|date',
            'solicitado_por' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $codigo = 'REP-' . strtoupper(uniqid());
        Reparto::create([...$request->all(), 'codigo_reparto' => $codigo, 'estado' => 'pendiente']);
        return redirect()->route('repartos.index')->with('success', 'Reparto creado correctamente.');
    }

    public function show(Reparto $reparto)
    {
        $reparto->load(['stock', 'almacenOrigen', 'almacenDestino', 'empresa', 'centro']);
        return view('repartos.show', compact('reparto'));
    }

    public function edit(Reparto $reparto)
    {
        $stocks = Stock::where('activo', true)->orderBy('nombre_pieza')->get();
        $almacenes = Almacen::where('activo', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        return view('repartos.edit', compact('reparto', 'stocks', 'almacenes', 'empresas', 'centros'));
    }

    public function update(Request $request, Reparto $reparto)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'almacen_origen_id' => 'required|exists:almacenes,id',
            'almacen_destino_id' => 'nullable|exists:almacenes,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'cantidad' => 'required|integer|min:1',
            'estado' => 'required|in:pendiente,en_transito,entregado,cancelado',
            'fecha_solicitud' => 'required|date',
            'fecha_entrega' => 'nullable|date',
            'solicitado_por' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);
        $reparto->update($request->all());
        return redirect()->route('repartos.index')->with('success', 'Reparto actualizado correctamente.');
    }

    public function destroy(Reparto $reparto)
    {
        $reparto->delete();
        return redirect()->route('repartos.index')->with('success', 'Reparto eliminado correctamente.');
    }
}
