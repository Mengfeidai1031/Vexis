<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use App\Models\Empresa;
use App\Models\Centro;
use App\Models\Marca;
use Illuminate\Http\Request;

class TallerController extends Controller
{
    public function index(Request $request)
    {
        $query = Taller::with(['empresa', 'centro', 'marca'])->withCount(['mecanicos', 'citas']);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) { $q->where('nombre', 'like', "%$s%")->orWhere('codigo', 'like', "%$s%")->orWhere('localidad', 'like', "%$s%"); });
        }
        if ($request->filled('isla')) $query->where('isla', $request->isla);
        if ($request->filled('marca_id')) $query->where('marca_id', $request->marca_id);
        $talleres = $query->orderBy('nombre')->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('talleres.index', compact('talleres', 'marcas'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('talleres.create', compact('empresas', 'centros', 'marcas'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre'=>'required|max:150','codigo'=>'required|max:20|unique:talleres','domicilio'=>'required|max:255','empresa_id'=>'required|exists:empresas,id','centro_id'=>'required|exists:centros,id','capacidad_diaria'=>'required|integer|min:1']);
        Taller::create($request->all());
        return redirect()->route('talleres.index')->with('success', 'Taller creado correctamente.');
    }

    public function show(Taller $taller)
    {
        $taller->load(['empresa', 'centro', 'marca', 'mecanicos']);
        $taller->loadCount(['citas', 'cochesSustitucion']);
        return view('talleres.show', compact('taller'));
    }

    public function edit(Taller $taller)
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('talleres.edit', compact('taller', 'empresas', 'centros', 'marcas'));
    }

    public function update(Request $request, Taller $taller)
    {
        $request->validate(['nombre'=>'required|max:150','codigo'=>'required|max:20|unique:talleres,codigo,'.$taller->id,'domicilio'=>'required|max:255','empresa_id'=>'required|exists:empresas,id','centro_id'=>'required|exists:centros,id','capacidad_diaria'=>'required|integer|min:1']);
        $taller->update([...$request->all(), 'activo' => $request->boolean('activo', true)]);
        return redirect()->route('talleres.index')->with('success', 'Taller actualizado correctamente.');
    }

    public function destroy(Taller $taller)
    {
        if ($taller->mecanicos()->count() > 0) return back()->with('error', 'No se puede eliminar un taller con mecánicos asignados.');
        $taller->delete();
        return redirect()->route('talleres.index')->with('success', 'Taller eliminado correctamente.');
    }
}
