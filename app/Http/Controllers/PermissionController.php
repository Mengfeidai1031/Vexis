<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::withCount('roles')->orderBy('name');

        if ($request->filled('id')) {
            $query->where('id', (int) $request->id);
        }
        if ($request->filled('nombre')) {
            $query->where('name', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('roles_min')) {
            $query->has('roles', '>=', (int) $request->roles_min);
        }

        $permissions = $query->paginate(30)->withQueryString();
        $permissions_all = Permission::orderBy('name')->pluck('name', 'name');

        return view('permisos.index', compact('permissions', 'permissions_all'));
    }

    public function create()
    {
        return view('permisos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:120|unique:permissions,name|regex:/^[a-zñáéíóú\- ]+$/i',
        ], [
            'name.regex' => 'El nombre sólo puede contener letras, espacios y guiones.',
            'name.unique' => 'Ese permiso ya existe.',
        ]);

        Permission::create(['name' => strtolower($request->name), 'guard_name' => 'web']);

        return redirect()->route('permisos.index')->with('success', 'Permiso creado correctamente.');
    }

    public function destroy(Permission $permiso): RedirectResponse
    {
        $permiso->delete();

        return redirect()->route('permisos.index')->with('success', 'Permiso eliminado correctamente.');
    }
}
