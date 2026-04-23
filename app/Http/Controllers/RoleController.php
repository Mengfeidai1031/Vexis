<?php

declare(strict_types=1);

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
        $roles = $this->roleRepository->all();

        if ($request->filled('id')) {
            $roles = $roles->filter(fn ($r) => (int) $r->id === (int) $request->id)->values();
        }
        if ($request->filled('nombre')) {
            $roles = $roles->filter(fn ($r) => $r->name === $request->nombre)->values();
        }
        if ($request->filled('permisos_min')) {
            $min = (int) $request->permisos_min;
            $roles = $roles->filter(fn ($r) => $r->permissions_count >= $min)->values();
        }
        if ($request->filled('usuarios_min')) {
            $min = (int) $request->usuarios_min;
            $roles = $roles->filter(fn ($r) => $r->users_count >= $min)->values();
        }
        if ($request->filled('creado_desde')) {
            $desde = $request->creado_desde;
            $roles = $roles->filter(fn ($r) => $r->created_at && $r->created_at->format('Y-m-d') >= $desde)->values();
        }

        // Sorting
        $sortable = ['id', 'name', 'created_at'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            if ($roles instanceof \Illuminate\Pagination\AbstractPaginator) {
                $sorted = $roles->getCollection()->sortBy($request->sort_by, SORT_REGULAR, $dir === 'desc')->values();
                $roles->setCollection($sorted);
            } else {
                $roles = $roles->sortBy($request->sort_by, SORT_REGULAR, $dir === 'desc')->values();
            }
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
