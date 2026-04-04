<?php

namespace App\Http\Controllers;

use App\Models\NamingPc;
use App\Models\Centro;
use App\Models\Empresa;
use Illuminate\Http\Request;

class NamingPcController extends Controller
{
    public function index(Request $request)
    {
        $query = NamingPc::with(['centro', 'empresa']);
        if ($request->filled('tipo')) $query->where('tipo', $request->tipo);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('centro_id')) $query->where('centro_id', $request->centro_id);
        if ($request->filled('sistema_operativo')) $query->where('sistema_operativo', $request->sistema_operativo);
        if ($request->filled('activo')) $query->where('activo', $request->activo);
        // Sorting
        $sortable = ['id', 'nombre_equipo', 'tipo', 'direccion_ip', 'empresa_id', 'centro_id', 'sistema_operativo', 'version_so', 'activo'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $namingPcs = $query->paginate(15)->withQueryString();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        return view('naming-pcs.index', compact('namingPcs', 'empresas', 'centros'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        return view('naming-pcs.create', compact('empresas', 'centros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_equipo' => 'required|string|max:100',
            'tipo' => 'required|string|max:50',
            'sistema_operativo' => 'nullable|string|max:100',
            'version_so' => 'nullable|string|max:10',
            'direccion_ip' => 'nullable|string|max:45',
            'direccion_mac' => 'nullable|string|max:17',
        ]);
        NamingPc::create($request->all());
        return redirect()->route('naming-pcs.index')->with('success', 'Equipo registrado correctamente.');
    }

    public function show(NamingPc $namingPc)
    {
        $namingPc->load(['centro', 'empresa']);
        return view('naming-pcs.show', compact('namingPc'));
    }

    public function edit(NamingPc $namingPc)
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        return view('naming-pcs.edit', compact('namingPc', 'empresas', 'centros'));
    }

    public function update(Request $request, NamingPc $namingPc)
    {
        $request->validate([
            'nombre_equipo' => 'required|string|max:100',
            'tipo' => 'required|string|max:50',
            'sistema_operativo' => 'nullable|string|max:100',
            'version_so' => 'nullable|string|max:10',
            'direccion_ip' => 'nullable|string|max:45',
            'direccion_mac' => 'nullable|string|max:17',
        ]);
        $namingPc->update([...$request->all(), 'activo' => $request->boolean('activo', true)]);
        return redirect()->route('naming-pcs.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(NamingPc $namingPc)
    {
        $namingPc->delete();
        return redirect()->route('naming-pcs.index')->with('success', 'Equipo eliminado correctamente.');
    }
}
