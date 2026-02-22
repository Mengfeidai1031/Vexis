<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $query = Empresa::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nombre', 'like', "%$s%")
                  ->orWhere('abreviatura', 'like', "%$s%")
                  ->orWhere('cif', 'like', "%$s%")
                  ->orWhere('domicilio', 'like', "%$s%");
            });
        }
        $empresas = $query->orderBy('nombre')->paginate(15)->withQueryString();
        return view('empresas.index', compact('empresas'));
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

        Empresa::create($request->all());
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
            'cif' => 'required|string|max:10|unique:empresas,cif,' . $empresa->id,
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'nullable|string|size:5',
            'telefono' => 'required|string|max:12',
        ]);

        $empresa->update($request->all());
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
