<?php

namespace App\Http\Controllers;

use App\Models\Tasacion;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Marca;
use App\Exports\TasacionesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TasacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Tasacion::with(['cliente', 'empresa', 'marca', 'tasador']);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('estado_vehiculo')) $query->where('estado_vehiculo', $request->estado_vehiculo);
        if ($request->filled('combustible')) $query->where('combustible', $request->combustible);
        if ($request->filled('cliente_id')) $query->where('cliente_id', $request->cliente_id);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('fecha_desde')) $query->whereDate('fecha_tasacion', '>=', $request->fecha_desde);
        if ($request->filled('fecha_hasta')) $query->whereDate('fecha_tasacion', '<=', $request->fecha_hasta);
        if ($request->filled('marca')) $query->where('vehiculo_marca', $request->marca);
        $tasaciones = $query->orderByDesc('fecha_tasacion')->paginate(15)->withQueryString();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $marcas_tasacion = Tasacion::whereNotNull('vehiculo_marca')->distinct()->orderBy('vehiculo_marca')->pluck('vehiculo_marca');
        return view('tasaciones.index', compact('tasaciones', 'clientes', 'empresas', 'marcas_tasacion'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('tasaciones.create', compact('clientes', 'empresas', 'marcas'));
    }

    public function store(Request $request)
    {
        $request->validate(['vehiculo_marca'=>'required|max:100','vehiculo_modelo'=>'required|max:150','vehiculo_anio'=>'required|integer|min:1990|max:2030','kilometraje'=>'required|integer|min:0','empresa_id'=>'required|exists:empresas,id','estado_vehiculo'=>'required|in:excelente,bueno,regular,malo','fecha_tasacion'=>'required|date']);
        $codigo = 'TAS-' . date('Ym') . '-' . str_pad(Tasacion::whereYear('fecha_tasacion', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);
        Tasacion::create([...$request->all(), 'codigo_tasacion' => $codigo, 'tasador_id' => Auth::id()]);
        return redirect()->route('tasaciones.index')->with('success', 'Tasación creada correctamente.');
    }

    public function show(Tasacion $tasacion)
    {
        $tasacion->load(['cliente', 'empresa', 'marca', 'tasador']);
        return view('tasaciones.show', compact('tasacion'));
    }

    public function edit(Tasacion $tasacion)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('tasaciones.edit', compact('tasacion', 'clientes', 'empresas', 'marcas'));
    }

    public function update(Request $request, Tasacion $tasacion)
    {
        $request->validate(['vehiculo_marca'=>'required|max:100','vehiculo_modelo'=>'required|max:150','vehiculo_anio'=>'required|integer','kilometraje'=>'required|integer|min:0','empresa_id'=>'required|exists:empresas,id','estado'=>'required|in:pendiente,valorada,aceptada,rechazada','fecha_tasacion'=>'required|date']);
        $tasacion->update($request->all());
        return redirect()->route('tasaciones.index')->with('success', 'Tasación actualizada correctamente.');
    }

    public function destroy(Tasacion $tasacion)
    {
        $tasacion->delete();
        return redirect()->route('tasaciones.index')->with('success', 'Tasación eliminada correctamente.');
    }

    public function export()
    {
        $fileName = 'tasaciones_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new TasacionesExport(), $fileName);
    }

    public function exportPdf()
    {
        $tasaciones = Tasacion::with(['cliente', 'empresa', 'marca'])->orderByDesc('fecha_tasacion')->get();
        $pdf = Pdf::loadView('tasaciones.pdf', compact('tasaciones'));
        $fileName = 'tasaciones_' . date('Y-m-d_His') . '.pdf';
        return $pdf->download($fileName);
    }
}
