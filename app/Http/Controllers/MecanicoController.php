<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use App\Models\Taller;
use Illuminate\Http\Request;

class MecanicoController extends Controller
{
    public function index(Request $request)
    {
        $query = Mecanico::with('taller');
        if ($request->filled('search')) { $s = $request->search; $query->where(function ($q) use ($s) { $q->where('nombre', 'like', "%$s%")->orWhere('apellidos', 'like', "%$s%")->orWhere('especialidad', 'like', "%$s%"); }); }
        if ($request->filled('taller_id')) $query->where('taller_id', $request->taller_id);
        $mecanicos = $query->orderBy('apellidos')->paginate(15)->withQueryString();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        return view('mecanicos.index', compact('mecanicos', 'talleres'));
    }

    public function create()
    {
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        return view('mecanicos.create', compact('talleres'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre'=>'required|max:100','apellidos'=>'required|max:150','taller_id'=>'required|exists:talleres,id']);
        Mecanico::create($request->all());
        return redirect()->route('mecanicos.index')->with('success', 'Mecánico registrado correctamente.');
    }

    public function edit(Mecanico $mecanico)
    {
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        return view('mecanicos.edit', compact('mecanico', 'talleres'));
    }

    public function update(Request $request, Mecanico $mecanico)
    {
        $request->validate(['nombre'=>'required|max:100','apellidos'=>'required|max:150','taller_id'=>'required|exists:talleres,id']);
        $mecanico->update([...$request->all(), 'activo' => $request->boolean('activo', true)]);
        return redirect()->route('mecanicos.index')->with('success', 'Mecánico actualizado correctamente.');
    }

    public function destroy(Mecanico $mecanico)
    {
        $mecanico->delete();
        return redirect()->route('mecanicos.index')->with('success', 'Mecánico eliminado correctamente.');
    }
}
