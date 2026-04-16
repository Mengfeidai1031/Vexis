<?php

namespace App\Http\Controllers;

use App\Exports\StocksExport;
use App\Models\Almacen;
use App\Models\Centro;
use App\Models\Empresa;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['almacen', 'empresa', 'centro']);
        if ($request->filled('almacen_id')) {
            $query->where('almacen_id', $request->almacen_id);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->filled('bajo_stock')) {
            $query->whereColumn('cantidad', '<=', 'stock_minimo');
        }
        if ($request->input('activo') !== null && $request->input('activo') !== '') {
            $query->where('activo', $request->activo);
        }
        if ($request->filled('referencia')) {
            $query->where('referencia', $request->referencia);
        }
        if ($request->filled('nombre_pieza')) {
            $query->where('nombre_pieza', $request->nombre_pieza);
        }
        if ($request->filled('marca_pieza')) {
            $query->where('marca_pieza', $request->marca_pieza);
        }
        if ($request->filled('cantidad_min')) {
            $query->where('cantidad', '>=', $request->cantidad_min);
        }
        if ($request->filled('cantidad_max')) {
            $query->where('cantidad', '<=', $request->cantidad_max);
        }
        if ($request->filled('precio_min')) {
            $query->where('precio_unitario', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio_unitario', '<=', $request->precio_max);
        }

        // Sorting
        $sortable = ['id', 'referencia', 'nombre_pieza', 'marca_pieza', 'cantidad', 'stock_minimo', 'precio_unitario', 'almacen_id', 'empresa_id'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $stocks = $query->paginate(15)->withQueryString();
        $almacenes = Almacen::where('activo', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $referencias = Stock::distinct()->orderBy('referencia')->pluck('referencia');
        $nombres_pieza = Stock::distinct()->orderBy('nombre_pieza')->pluck('nombre_pieza');
        $marcas_pieza = Stock::whereNotNull('marca_pieza')->distinct()->orderBy('marca_pieza')->pluck('marca_pieza');

        return view('stocks.index', compact('stocks', 'almacenes', 'empresas', 'referencias', 'nombres_pieza', 'marcas_pieza'));
    }

    public function create()
    {
        $almacenes = Almacen::where('activo', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();

        return view('stocks.create', compact('almacenes', 'empresas', 'centros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'referencia' => 'required|string|max:50',
            'nombre_pieza' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'marca_pieza' => 'nullable|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
            'ubicacion_almacen' => 'nullable|string|max:100',
            'almacen_id' => 'required|exists:almacenes,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
        ]);
        Stock::create($request->only(['referencia', 'nombre_pieza', 'descripcion', 'marca_pieza', 'cantidad', 'stock_minimo', 'precio_unitario', 'ubicacion_almacen', 'almacen_id', 'empresa_id', 'centro_id']));

        return redirect()->route('stocks.index')->with('success', 'Stock registrado correctamente.');
    }

    public function show(Stock $stock)
    {
        $stock->load(['almacen', 'empresa', 'centro']);

        return view('stocks.show', compact('stock'));
    }

    public function edit(Stock $stock)
    {
        $almacenes = Almacen::where('activo', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();

        return view('stocks.edit', compact('stock', 'almacenes', 'empresas', 'centros'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'referencia' => 'required|string|max:50',
            'nombre_pieza' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'marca_pieza' => 'nullable|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
            'ubicacion_almacen' => 'nullable|string|max:100',
            'almacen_id' => 'required|exists:almacenes,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
        ]);
        $stock->update([...$request->only(['referencia', 'nombre_pieza', 'descripcion', 'marca_pieza', 'cantidad', 'stock_minimo', 'precio_unitario', 'ubicacion_almacen', 'almacen_id', 'empresa_id', 'centro_id']), 'activo' => $request->boolean('activo', true)]);

        return redirect()->route('stocks.index')->with('success', 'Stock actualizado correctamente.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock eliminado correctamente.');
    }

    public function export()
    {
        $fileName = 'stock_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new StocksExport, $fileName);
    }

    public function exportPdf()
    {
        $stocks = Stock::with(['almacen', 'empresa', 'centro'])->orderBy('nombre_pieza')->get();
        $pdf = Pdf::loadView('stocks.pdf', compact('stocks'));
        $fileName = 'stock_'.date('Y-m-d_His').'.pdf';

        return $pdf->download($fileName);
    }
}
