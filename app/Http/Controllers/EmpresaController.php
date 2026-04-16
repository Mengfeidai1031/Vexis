<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $query = Empresa::query();
        if ($request->filled('nombre')) {
            $query->where('nombre', $request->nombre);
        }
        if ($request->filled('abreviatura')) {
            $query->where('abreviatura', $request->abreviatura);
        }
        if ($request->filled('cif')) {
            $query->where('cif', $request->cif);
        }
        if ($request->filled('codigo_postal')) {
            $query->where('codigo_postal', $request->codigo_postal);
        }
        if ($request->filled('domicilio')) {
            $query->where('domicilio', $request->domicilio);
        }
        if ($request->filled('telefono')) {
            $query->where('telefono', $request->telefono);
        }
        // Sorting
        $sortable = ['id', 'nombre', 'abreviatura', 'cif', 'domicilio', 'codigo_postal', 'telefono'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $empresas = $query->paginate(15)->withQueryString();
        $empresas_all = Empresa::orderBy('nombre')->get();
        $abreviaturas = Empresa::whereNotNull('abreviatura')->distinct()->orderBy('abreviatura')->pluck('abreviatura');
        $codigos_postales = Empresa::whereNotNull('codigo_postal')->distinct()->orderBy('codigo_postal')->pluck('codigo_postal');

        return view('empresas.index', compact('empresas', 'empresas_all', 'abreviaturas', 'codigos_postales'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'abreviatura' => 'required|string|max:10',
            'cif' => 'required|string|max:10|unique:empresas,cif',
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'nullable|string|size:5',
            'telefono' => 'required|string|max:12',
        ]);

        Empresa::create($request->only(['nombre', 'abreviatura', 'cif', 'domicilio', 'codigo_postal', 'telefono']));

        return redirect()->route('empresas.index')->with('success', 'Empresa creada correctamente.');
    }

    public function show(Empresa $empresa)
    {
        $empresa->loadCount(['centros', 'users']);

        return view('empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'abreviatura' => 'required|string|max:10',
            'cif' => 'required|string|max:10|unique:empresas,cif,'.$empresa->id,
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'nullable|string|size:5',
            'telefono' => 'required|string|max:12',
        ]);

        $empresa->update($request->only(['nombre', 'abreviatura', 'cif', 'domicilio', 'codigo_postal', 'telefono']));

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Empresa $empresa)
    {
        if ($empresa->users()->count() > 0 || $empresa->centros()->count() > 0) {
            return redirect()->route('empresas.index')->with('error', 'No se puede eliminar: tiene usuarios o centros asociados.');
        }
        $empresa->delete();

        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada correctamente.');
    }
}
