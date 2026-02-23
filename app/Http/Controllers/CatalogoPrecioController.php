<?php

namespace App\Http\Controllers;

use App\Models\CatalogoPrecio;
use App\Models\Marca;
use Illuminate\Http\Request;

class CatalogoPrecioController extends Controller
{
    public function index(Request $request)
    {
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $marcaSeleccionada = $request->filled('marca_id') ? $request->marca_id : ($marcas->first()->id ?? null);

        $query = CatalogoPrecio::with('marca');
        if ($marcaSeleccionada) $query->where('marca_id', $marcaSeleccionada);
        if ($request->filled('search')) { $s = $request->search; $query->where(function ($q) use ($s) { $q->where('modelo', 'like', "%$s%")->orWhere('version', 'like', "%$s%"); }); }
        if ($request->filled('combustible')) $query->where('combustible', $request->combustible);
        $catalogo = $query->orderBy('modelo')->orderBy('precio_base')->paginate(20)->withQueryString();

        return view('catalogo-precios.index', compact('catalogo', 'marcas', 'marcaSeleccionada'));
    }

    public function create()
    {
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('catalogo-precios.create', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate(['marca_id'=>'required|exists:marcas,id','modelo'=>'required|max:150','precio_base'=>'required|numeric|min:0']);
        CatalogoPrecio::create($request->all());
        return redirect()->route('catalogo-precios.index', ['marca_id' => $request->marca_id])->with('success', 'Modelo añadido al catálogo.');
    }

    public function edit(CatalogoPrecio $catalogo_precio)
    {
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('catalogo-precios.edit', compact('catalogo_precio', 'marcas'));
    }

    public function update(Request $request, CatalogoPrecio $catalogo_precio)
    {
        $request->validate(['marca_id'=>'required|exists:marcas,id','modelo'=>'required|max:150','precio_base'=>'required|numeric|min:0']);
        $catalogo_precio->update([...$request->all(), 'disponible' => $request->boolean('disponible', true)]);
        return redirect()->route('catalogo-precios.index', ['marca_id' => $catalogo_precio->marca_id])->with('success', 'Modelo actualizado.');
    }

    public function destroy(CatalogoPrecio $catalogo_precio)
    {
        $marcaId = $catalogo_precio->marca_id;
        $catalogo_precio->delete();
        return redirect()->route('catalogo-precios.index', ['marca_id' => $marcaId])->with('success', 'Modelo eliminado del catálogo.');
    }
}
