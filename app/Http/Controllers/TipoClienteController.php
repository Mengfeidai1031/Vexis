<?php

namespace App\Http\Controllers;

use App\Models\TipoCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TipoClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = TipoCliente::withCount('clientes');

        if ($request->filled('nombre')) $query->where('nombre', $request->nombre);
        if ($request->filled('descripcion')) $query->where('descripcion', $request->descripcion);
        if ($request->filled('activo')) $query->where('activo', $request->activo);

        $sortable = ['id', 'nombre', 'slug', 'activo', 'clientes_count'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        } else {
            $query->orderBy('nombre');
        }

        $tipos = $query->paginate(15)->withQueryString();
        $tipos_all = TipoCliente::orderBy('nombre')->get();
        return view('tipos_cliente.index', compact('tipos', 'tipos_all'));
    }

    public function create()
    {
        return view('tipos_cliente.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $tipo = TipoCliente::create($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'tipo' => $tipo]);
        }

        return redirect()->route('tipos-cliente.index')->with('success', 'Tipo de cliente creado.');
    }

    public function edit(TipoCliente $tipoCliente)
    {
        return view('tipos_cliente.edit', ['tipo' => $tipoCliente]);
    }

    public function update(Request $request, TipoCliente $tipoCliente)
    {
        $tipoCliente->update($this->validated($request, $tipoCliente->id));
        return redirect()->route('tipos-cliente.index')->with('success', 'Tipo de cliente actualizado.');
    }

    public function destroy(TipoCliente $tipoCliente)
    {
        if ($tipoCliente->clientes()->exists()) {
            return redirect()->route('tipos-cliente.index')->with('error', 'No se puede eliminar: tiene clientes asociados.');
        }
        $tipoCliente->delete();
        return redirect()->route('tipos-cliente.index')->with('success', 'Tipo de cliente eliminado.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_cliente,nombre'.($ignoreId ? ','.$ignoreId : ''),
            'descripcion' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:9',
            'activo' => 'nullable|boolean',
        ]);
        $data['slug'] = Str::slug($data['nombre']);
        $data['activo'] = $request->boolean('activo', true);
        $data['color'] = $data['color'] ?? '#33AADD';
        return $data;
    }
}
