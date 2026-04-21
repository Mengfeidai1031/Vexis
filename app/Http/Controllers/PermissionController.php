<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(30);

        return view('permisos.index', compact('permissions'));
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
