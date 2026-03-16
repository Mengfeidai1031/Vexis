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
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nombre_equipo', 'like', "%$s%")
                  ->orWhere('direccion_ip', 'like', "%$s%")
                  ->orWhere('ubicacion', 'like', "%$s%");
            });
        }
        if ($request->filled('tipo')) $query->where('tipo', $request->tipo);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        $namingPcs = $query->orderBy('nombre_equipo')->paginate(15)->withQueryString();
        $empresas = Empresa::orderBy('nombre')->get();
        return view('naming-pcs.index', compact('namingPcs', 'empresas'));
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
