<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Centro;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        $query = Almacen::with(['empresa', 'centro']);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nombre', 'like', "%$s%")
                  ->orWhere('codigo', 'like', "%$s%")
                  ->orWhere('domicilio', 'like', "%$s%")
                  ->orWhere('localidad', 'like', "%$s%");
            });
        }
        if ($request->filled('isla')) {
            $query->where('isla', $request->isla);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        $almacenes = $query->orderBy('nombre')->paginate(15)->withQueryString();
        $empresas = Empresa::orderBy('nombre')->get();
        return view('almacenes.index', compact('almacenes', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        return view('almacenes.create', compact('empresas', 'centros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:almacenes,codigo',
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'nullable|string|size:5',
            'localidad' => 'nullable|string|max:100',
            'isla' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:12',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'observaciones' => 'nullable|string',
        ]);

        Almacen::create($request->all());
        return redirect()->route('almacenes.index')->with('success', 'Almacén creado correctamente.');
    }

    public function show(Almacen $almacen)
    {
        $almacen->load(['empresa', 'centro']);
        $almacen->loadCount('stocks');
        return view('almacenes.show', compact('almacen'));
    }

    public function edit(Almacen $almacen)
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        return view('almacenes.edit', compact('almacen', 'empresas', 'centros'));
    }

    public function update(Request $request, Almacen $almacen)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:almacenes,codigo,' . $almacen->id,
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'nullable|string|size:5',
            'localidad' => 'nullable|string|max:100',
            'isla' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:12',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'observaciones' => 'nullable|string',
        ]);

        $almacen->update([...$request->all(), 'activo' => $request->boolean('activo', true)]);
        return redirect()->route('almacenes.index')->with('success', 'Almacén actualizado correctamente.');
    }

    public function destroy(Almacen $almacen)
    {
        if ($almacen->stocks()->count() > 0) {
            return redirect()->route('almacenes.index')->with('error', 'No se puede eliminar: tiene stock asociado.');
        }
        $almacen->delete();
        return redirect()->route('almacenes.index')->with('success', 'Almacén eliminado correctamente.');
    }
}
