<?php

declare(strict_types=1);

namespace App\Policies;

use App\Helpers\UserRestrictionHelper;
use App\Models\Centro;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class CentroPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('ver centros');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Centro $centro): bool
    {
        // Verificar permiso general
        if (!$user->can('ver centros')) {
            return false;
        }

        // Verificar restricciones de empresa y centro
        return UserRestrictionHelper::canAccess($user, 'empresa', $centro->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'centro', $centro->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('crear centros');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Centro $centro): bool
    {
        // Verificar permiso general
        if (!$user->can('editar centros')) {
            return false;
        }

        // Verificar restricciones de empresa y centro
        return UserRestrictionHelper::canAccess($user, 'empresa', $centro->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'centro', $centro->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Centro $centro): bool
    {
        // Verificar permiso general
        if (!$user->can('eliminar centros')) {
            return false;
        }

        // Verificar restricciones de empresa y centro
        return UserRestrictionHelper::canAccess($user, 'empresa', $centro->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'centro', $centro->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Centro $centro): bool
    {
        return false; // No implementado
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Centro $centro): bool
    {
        return false; // No implementado
    }
}
