<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Centro;
use App\Exports\StocksExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['almacen', 'empresa', 'centro']);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('referencia', 'like', "%$s%")
                  ->orWhere('nombre_pieza', 'like', "%$s%")
                  ->orWhere('marca_pieza', 'like', "%$s%");
            });
        }
        if ($request->filled('almacen_id')) $query->where('almacen_id', $request->almacen_id);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('bajo_stock')) $query->whereColumn('cantidad', '<=', 'stock_minimo');

        $stocks = $query->orderBy('nombre_pieza')->paginate(15)->withQueryString();
        $almacenes = Almacen::where('activo', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        return view('stocks.index', compact('stocks', 'almacenes', 'empresas'));
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
        Stock::create($request->all());
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
        $stock->update([...$request->all(), 'activo' => $request->boolean('activo', true)]);
        return redirect()->route('stocks.index')->with('success', 'Stock actualizado correctamente.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('stocks.index')->with('success', 'Stock eliminado correctamente.');
    }

    public function export()
    {
        $fileName = 'stock_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new StocksExport(), $fileName);
    }

    public function exportPdf()
    {
        $stocks = Stock::with(['almacen', 'empresa', 'centro'])->orderBy('nombre_pieza')->get();
        $pdf = Pdf::loadView('stocks.pdf', compact('stocks'));
        $fileName = 'stock_' . date('Y-m-d_His') . '.pdf';
        return $pdf->download($fileName);
    }
}
