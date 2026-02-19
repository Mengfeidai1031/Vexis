<?php

declare(strict_types=1);

namespace App\Policies;

use App\Helpers\UserRestrictionHelper;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class EmpresaPolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * Nota: No hay un permiso específico para "ver empresas" en las rutas,
     * pero esta política puede ser útil para futuras funcionalidades.
     */
    public function viewAny(User $user): bool
    {
        // Por defecto, permitir si el usuario tiene algún permiso administrativo
        // O puedes crear un permiso específico "ver empresas" si es necesario
        return $user->hasAnyPermission(['ver usuarios', 'ver clientes', 'ver vehículos', 'ver centros']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Empresa $empresa): bool
    {
        // Verificar restricciones de empresa
        return UserRestrictionHelper::canAccess($user, 'empresa', $empresa->id);
    }

    /**
     * Determine whether the user can create models.
     * 
     * Nota: No hay un permiso específico para "crear empresas" en las rutas,
     * pero esta política puede ser útil para futuras funcionalidades.
     */
    public function create(User $user): bool
    {
        // Por defecto, solo administradores pueden crear empresas
        // O puedes crear un permiso específico "crear empresas" si es necesario
        return $user->hasPermissionTo('crear usuarios');
    }

    /**
     * Determine whether the user can update the model.
     * 
     * Nota: No hay un permiso específico para "editar empresas" en las rutas,
     * pero esta política puede ser útil para futuras funcionalidades.
     */
    public function update(User $user, Empresa $empresa): bool
    {
        // Verificar restricciones de empresa
        return UserRestrictionHelper::canAccess($user, 'empresa', $empresa->id);
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * Nota: No hay un permiso específico para "eliminar empresas" en las rutas,
     * pero esta política puede ser útil para futuras funcionalidades.
     */
    public function delete(User $user, Empresa $empresa): bool
    {
        // Verificar restricciones de empresa
        return UserRestrictionHelper::canAccess($user, 'empresa', $empresa->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Empresa $empresa): bool
    {
        return false; // No implementado
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Empresa $empresa): bool
    {
        return false; // No implementado
    }
}
