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
        if ($request->filled('taller_id')) {
            $query->where('taller_id', $request->taller_id);
        }
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }
        if ($request->filled('mecanico_id')) {
            $query->where('id', $request->mecanico_id);
        }
        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }
        // Sorting
        $sortable = ['id', 'nombre', 'apellidos', 'especialidad', 'taller_id', 'activo'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $mecanicos = $query->paginate(15)->withQueryString();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $mecanicos_all = Mecanico::orderBy('nombre')->get();
        $especialidades = Mecanico::whereNotNull('especialidad')->distinct()->orderBy('especialidad')->pluck('especialidad');

        return view('mecanicos.index', compact('mecanicos', 'talleres', 'mecanicos_all', 'especialidades'));
    }

    public function create()
    {
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();

        return view('mecanicos.create', compact('talleres'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|max:100', 'apellidos' => 'required|max:150', 'taller_id' => 'required|exists:talleres,id']);
        Mecanico::create($request->only(['nombre', 'apellidos', 'especialidad', 'taller_id']));

        return redirect()->route('mecanicos.index')->with('success', 'Mecánico registrado correctamente.');
    }

    public function show(Mecanico $mecanico)
    {
        $mecanico->load(['taller.empresa', 'taller.centro']);
        $mecanico->loadCount('citas');

        return view('mecanicos.show', compact('mecanico'));
    }

    public function edit(Mecanico $mecanico)
    {
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();

        return view('mecanicos.edit', compact('mecanico', 'talleres'));
    }

    public function update(Request $request, Mecanico $mecanico)
    {
        $request->validate(['nombre' => 'required|max:100', 'apellidos' => 'required|max:150', 'taller_id' => 'required|exists:talleres,id']);
        $mecanico->update([...$request->only(['nombre', 'apellidos', 'especialidad', 'taller_id']), 'activo' => $request->boolean('activo', true)]);

        return redirect()->route('mecanicos.index')->with('success', 'Mecánico actualizado correctamente.');
    }

    public function destroy(Mecanico $mecanico)
    {
        $mecanico->delete();

        return redirect()->route('mecanicos.index')->with('success', 'Mecánico eliminado correctamente.');
    }
}
