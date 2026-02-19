<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\UserRestriction;
use Illuminate\Auth\Access\Response;

final class UserRestrictionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('ver restricciones');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserRestriction $userRestriction): bool
    {
        // Verificar permiso general
        return $user->can('ver restricciones');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('crear restricciones');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserRestriction $userRestriction): bool
    {
        // Verificar permiso general
        return $user->can('editar restricciones');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserRestriction $userRestriction): bool
    {
        // Verificar permiso general
        return $user->can('eliminar restricciones');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserRestriction $userRestriction): bool
    {
        return false; // No implementado
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserRestriction $userRestriction): bool
    {
        return false; // No implementado
    }
}
