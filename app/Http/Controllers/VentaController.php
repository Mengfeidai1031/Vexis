<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Vehiculo;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Centro;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['vehiculo', 'cliente', 'empresa', 'marca', 'vendedor']);
        if ($request->filled('search')) { $s = $request->search; $query->where(function ($q) use ($s) { $q->where('codigo_venta', 'like', "%$s%")->orWhereHas('cliente', fn($q2) => $q2->where('nombre', 'like', "%$s%")->orWhere('apellidos', 'like', "%$s%")); }); }
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('marca_id')) $query->where('marca_id', $request->marca_id);
        $ventas = $query->orderByDesc('fecha_venta')->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('ventas.index', compact('ventas', 'marcas'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::orderBy('modelo')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('ventas.create', compact('vehiculos', 'clientes', 'empresas', 'centros', 'marcas'));
    }

    public function store(Request $request)
    {
        $request->validate(['vehiculo_id'=>'required|exists:vehiculos,id','empresa_id'=>'required|exists:empresas,id','centro_id'=>'required|exists:centros,id','precio_venta'=>'required|numeric|min:0','precio_final'=>'required|numeric|min:0','forma_pago'=>'required|in:contado,financiado,leasing,renting','fecha_venta'=>'required|date']);
        $codigo = 'VTA-' . date('Ym') . '-' . str_pad(Venta::whereYear('fecha_venta', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);
        Venta::create([...$request->all(), 'codigo_venta' => $codigo, 'vendedor_id' => Auth::id()]);
        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente.');
    }

    public function show(Venta $venta)
    {
        $venta->load(['vehiculo', 'cliente', 'empresa', 'centro', 'marca', 'vendedor']);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $vehiculos = Vehiculo::orderBy('modelo')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('ventas.edit', compact('venta', 'vehiculos', 'clientes', 'empresas', 'centros', 'marcas'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate(['vehiculo_id'=>'required|exists:vehiculos,id','empresa_id'=>'required|exists:empresas,id','centro_id'=>'required|exists:centros,id','precio_venta'=>'required|numeric|min:0','precio_final'=>'required|numeric|min:0','estado'=>'required|in:reservada,pendiente_entrega,entregada,cancelada','fecha_venta'=>'required|date']);
        $venta->update($request->all());
        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }
}
