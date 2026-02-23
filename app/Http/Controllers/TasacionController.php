<?php

namespace App\Http\Controllers;

use App\Models\Tasacion;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Tasacion::with(['cliente', 'empresa', 'marca', 'tasador']);
        if ($request->filled('search')) { $s = $request->search; $query->where(function ($q) use ($s) { $q->where('codigo_tasacion', 'like', "%$s%")->orWhere('vehiculo_marca', 'like', "%$s%")->orWhere('vehiculo_modelo', 'like', "%$s%")->orWhere('matricula', 'like', "%$s%"); }); }
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        $tasaciones = $query->orderByDesc('fecha_tasacion')->paginate(15)->withQueryString();
        return view('tasaciones.index', compact('tasaciones'));
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
}
