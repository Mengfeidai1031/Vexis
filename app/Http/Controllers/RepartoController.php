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
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('codigo_reparto', 'like', "%$s%")
                  ->orWhere('solicitado_por', 'like', "%$s%");
            });
        }
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);

        $repartos = $query->orderByDesc('fecha_solicitud')->paginate(15)->withQueryString();
        $empresas = Empresa::orderBy('nombre')->get();
        return view('repartos.index', compact('repartos', 'empresas'));
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
