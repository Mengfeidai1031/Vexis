<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(Request $request)
    {
        if ($request->has('search') && !empty($request->search)) {
            $roles = $this->roleRepository->search($request->search);
        } else {
            $roles = $this->roleRepository->all();
        }

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->roleRepository->getAllPermissions();
        return view('roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        $this->roleRepository->create(
            ['name' => $request->name],
            $request->permissions ?? []
        );

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    public function show(int $id)
    {
        $role = $this->roleRepository->find($id);
        return view('roles.show', compact('role'));
    }

    public function edit(int $id)
    {
        $role = $this->roleRepository->find($id);
        $permissions = $this->roleRepository->getAllPermissions();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(UpdateRoleRequest $request, int $id)
    {
        $this->roleRepository->update(
            $id,
            ['name' => $request->name],
            $request->permissions ?? []
        );

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(int $id)
    {
        try {
            $this->roleRepository->delete($id);
            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asociados.');
        }
    }
}