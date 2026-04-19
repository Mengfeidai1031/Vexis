<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Centro;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\Taller;
use Illuminate\Http\Request;

class TallerController extends Controller
{
    public function index(Request $request)
    {
        $query = Taller::with(['empresa', 'centro', 'marca'])->withCount(['mecanicos', 'citas']);
        if ($request->filled('isla')) {
            $query->where('isla', $request->isla);
        }
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->input('activo') !== null && $request->input('activo') !== '') {
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
        $sortable = ['id', 'codigo', 'nombre', 'marca_id', 'isla', 'localidad', 'capacidad_diaria', 'activo'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $talleres = $query->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $nombres_talleres = Taller::distinct()->orderBy('nombre')->pluck('nombre');
        $localidades_talleres = Taller::whereNotNull('localidad')->distinct()->orderBy('localidad')->pluck('localidad');
        $codigos_talleres = Taller::distinct()->orderBy('codigo')->pluck('codigo');

        return view('talleres.index', compact('talleres', 'marcas', 'empresas', 'nombres_talleres', 'localidades_talleres', 'codigos_talleres'));
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
        $request->validate(['nombre' => 'required|max:150', 'codigo' => 'required|max:20|unique:talleres', 'domicilio' => 'required|max:255', 'empresa_id' => 'required|exists:empresas,id', 'centro_id' => 'required|exists:centros,id', 'capacidad_diaria' => 'required|integer|min:1']);
        Taller::create($request->only(['nombre', 'codigo', 'domicilio', 'codigo_postal', 'localidad', 'isla', 'telefono', 'empresa_id', 'centro_id', 'marca_id', 'capacidad_diaria', 'observaciones']));

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
        $request->validate(['nombre' => 'required|max:150', 'codigo' => 'required|max:20|unique:talleres,codigo,'.$taller->id, 'domicilio' => 'required|max:255', 'empresa_id' => 'required|exists:empresas,id', 'centro_id' => 'required|exists:centros,id', 'capacidad_diaria' => 'required|integer|min:1']);
        $taller->update([...$request->only(['nombre', 'codigo', 'domicilio', 'codigo_postal', 'localidad', 'isla', 'telefono', 'empresa_id', 'centro_id', 'marca_id', 'capacidad_diaria', 'observaciones']), 'activo' => $request->boolean('activo', true)]);

        return redirect()->route('talleres.index')->with('success', 'Taller actualizado correctamente.');
    }

    public function destroy(Taller $taller)
    {
        if ($taller->mecanicos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un taller con mecánicos asignados.');
        }
        $taller->delete();

        return redirect()->route('talleres.index')->with('success', 'Taller eliminado correctamente.');
    }
}
