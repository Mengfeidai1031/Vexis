<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\TasacionesExport;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\Tasacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TasacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Tasacion::with(['cliente', 'empresa', 'marca', 'tasador']);
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('estado_vehiculo')) {
            $query->where('estado_vehiculo', $request->estado_vehiculo);
        }
        if ($request->filled('combustible')) {
            $query->where('combustible', $request->combustible);
        }
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_tasacion', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_tasacion', '<=', $request->fecha_hasta);
        }
        if ($request->filled('marca')) {
            $query->where('vehiculo_marca', $request->marca);
        }
        if ($request->filled('matricula')) {
            $query->where('matricula', $request->matricula);
        }
        if ($request->filled('tasador_id')) {
            $query->where('tasador_id', $request->tasador_id);
        }
        if ($request->filled('valor_min')) {
            $query->where('valor_estimado', '>=', $request->valor_min);
        }
        if ($request->filled('valor_max')) {
            $query->where('valor_estimado', '<=', $request->valor_max);
        }
        if ($request->filled('km_min')) {
            $query->where('kilometraje', '>=', $request->km_min);
        }
        if ($request->filled('km_max')) {
            $query->where('kilometraje', '<=', $request->km_max);
        }
        if ($request->filled('codigo_tasacion')) {
            $query->where('codigo_tasacion', $request->codigo_tasacion);
        }
        // Sorting
        $sortable = ['id', 'codigo_tasacion', 'vehiculo_marca', 'vehiculo_modelo', 'vehiculo_anio', 'kilometraje', 'matricula', 'estado_vehiculo', 'valor_estimado', 'estado', 'fecha_tasacion'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $tasaciones = $query->paginate(15)->withQueryString();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $marcas_tasacion = Tasacion::whereNotNull('vehiculo_marca')->distinct()->orderBy('vehiculo_marca')->pluck('vehiculo_marca');
        $tasadorIds = Tasacion::whereNotNull('tasador_id')->distinct()->pluck('tasador_id');
        $tasadores = \App\Models\User::whereIn('id', $tasadorIds)->orderBy('nombre')->get();
        $matriculas_tasacion = Tasacion::whereNotNull('matricula')->distinct()->orderBy('matricula')->pluck('matricula');
        $codigos_tasacion = Tasacion::distinct()->orderBy('codigo_tasacion')->pluck('codigo_tasacion');

        return view('tasaciones.index', compact('tasaciones', 'clientes', 'empresas', 'marcas_tasacion', 'tasadores', 'matriculas_tasacion', 'codigos_tasacion'));
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
        $request->validate(['vehiculo_marca' => 'required|max:100', 'vehiculo_modelo' => 'required|max:150', 'vehiculo_anio' => 'required|integer|min:1990|max:2030', 'kilometraje' => 'required|integer|min:0', 'empresa_id' => 'required|exists:empresas,id', 'estado_vehiculo' => 'required|in:excelente,bueno,regular,malo', 'fecha_tasacion' => 'required|date']);
        $codigo = 'TAS-'.date('Ym').'-'.str_pad(Tasacion::whereYear('fecha_tasacion', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);
        Tasacion::create([...$request->only(['vehiculo_marca', 'vehiculo_modelo', 'vehiculo_anio', 'kilometraje', 'empresa_id', 'estado_vehiculo', 'fecha_tasacion', 'cliente_id', 'marca_id', 'matricula', 'combustible', 'valor_estimado', 'observaciones']), 'codigo_tasacion' => $codigo, 'tasador_id' => Auth::id()]);

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
        $request->validate(['vehiculo_marca' => 'required|max:100', 'vehiculo_modelo' => 'required|max:150', 'vehiculo_anio' => 'required|integer', 'kilometraje' => 'required|integer|min:0', 'empresa_id' => 'required|exists:empresas,id', 'estado' => 'required|in:pendiente,valorada,aceptada,rechazada', 'fecha_tasacion' => 'required|date']);
        $tasacion->update($request->only(['vehiculo_marca', 'vehiculo_modelo', 'vehiculo_anio', 'kilometraje', 'empresa_id', 'estado_vehiculo', 'estado', 'fecha_tasacion', 'cliente_id', 'marca_id', 'matricula', 'combustible', 'valor_estimado', 'valor_final', 'observaciones']));

        return redirect()->route('tasaciones.index')->with('success', 'Tasación actualizada correctamente.');
    }

    public function destroy(Tasacion $tasacion)
    {
        $tasacion->delete();

        return redirect()->route('tasaciones.index')->with('success', 'Tasación eliminada correctamente.');
    }

    public function export()
    {
        $fileName = 'tasaciones_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new TasacionesExport, $fileName);
    }

    public function exportPdf()
    {
        $tasaciones = Tasacion::with(['cliente', 'empresa', 'marca'])->orderByDesc('fecha_tasacion')->get();
        $pdf = Pdf::loadView('tasaciones.pdf', compact('tasaciones'));
        $fileName = 'tasaciones_'.date('Y-m-d_His').'.pdf';

        return $pdf->download($fileName);
    }

    public function singlePdf(Tasacion $tasacion)
    {
        $tasacion->load(['cliente', 'empresa', 'marca', 'tasador']);
        $pdf = Pdf::loadView('tasaciones.single-pdf', compact('tasacion'))->setPaper('a4', 'portrait');

        return $pdf->download('tasacion_'.$tasacion->codigo_tasacion.'.pdf');
    }
}
