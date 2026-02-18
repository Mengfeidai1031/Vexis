<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function all()
    {
        return Role::withCount('permissions', 'users')
            ->orderBy('name', 'asc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        return Role::where('name', 'like', "%{$searchTerm}%")
            ->withCount('permissions', 'users')
            ->orderBy('name', 'asc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function create(array $data, array $permissions = [])
    {
        $role = Role::create($data);
        
        if (!empty($permissions)) {
            // Convertir IDs a instancias de Permission
            $permissionModels = Permission::whereIn('id', $permissions)->get();
            $role->syncPermissions($permissionModels);
        }
        
        return $role;
    }

    public function update(int $id, array $data, array $permissions = [])
    {
        $role = Role::findOrFail($id);
        $role->update($data);
        
        if (!empty($permissions)) {
            // Convertir IDs a instancias de Permission
            $permissionModels = Permission::whereIn('id', $permissions)->get();
            $role->syncPermissions($permissionModels);
        } else {
            $role->syncPermissions([]);
        }
        
        return $role;
    }

    public function delete(int $id)
    {
        $role = Role::findOrFail($id);
        return $role->delete();
    }

    public function getAllPermissions()
    {
        return Permission::all()->groupBy(function($permission) {
            // Agrupar por módulo (primera palabra del nombre)
            return explode(' ', $permission->name)[1] ?? 'otros';
        });
    }
}