<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Centro;
use App\Models\Empresa;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        $query = Almacen::with(['empresa', 'centro']);
        if ($request->filled('isla')) {
            $query->where('isla', $request->isla);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->filled('centro_id')) {
            $query->where('centro_id', $request->centro_id);
        }
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }
        if ($request->filled('nombre')) {
            $query->where('nombre', $request->nombre);
        }
        if ($request->filled('localidad')) {
            $query->where('localidad', $request->localidad);
        }
        if ($request->filled('codigo')) {
            $query->where('codigo', $request->codigo);
        }
        // Sorting
        $sortable = ['id', 'codigo', 'nombre', 'localidad', 'isla', 'empresa_id', 'centro_id', 'activo'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $almacenes = $query->paginate(15)->withQueryString();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $nombres_almacenes = Almacen::distinct()->orderBy('nombre')->pluck('nombre');
        $localidades_almacenes = Almacen::whereNotNull('localidad')->distinct()->orderBy('localidad')->pluck('localidad');
        $codigos_almacenes = Almacen::distinct()->orderBy('codigo')->pluck('codigo');

        return view('almacenes.index', compact('almacenes', 'empresas', 'centros', 'nombres_almacenes', 'localidades_almacenes', 'codigos_almacenes'));
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

        Almacen::create($request->only(['nombre', 'codigo', 'domicilio', 'codigo_postal', 'localidad', 'isla', 'telefono', 'empresa_id', 'centro_id', 'observaciones']));

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
            'codigo' => 'required|string|max:20|unique:almacenes,codigo,'.$almacen->id,
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'nullable|string|size:5',
            'localidad' => 'nullable|string|max:100',
            'isla' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:12',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'observaciones' => 'nullable|string',
        ]);

        $almacen->update([...$request->only(['nombre', 'codigo', 'domicilio', 'codigo_postal', 'localidad', 'isla', 'telefono', 'empresa_id', 'centro_id', 'observaciones']), 'activo' => $request->boolean('activo', true)]);

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
