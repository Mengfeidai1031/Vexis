<?php

declare(strict_types=1);

namespace App\Policies;

use App\Helpers\UserRestrictionHelper;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('ver usuarios');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Verificar permiso general
        if (!$user->can('ver usuarios')) {
            return false;
        }

        // Verificar restricciones de empresa (a través del usuario)
        // Si el usuario tiene restricciones de empresa, solo puede ver usuarios de esas empresas
        if ($model->empresa_id) {
            return UserRestrictionHelper::canAccess($user, 'empresa', $model->empresa_id);
        }

        // Si el usuario no tiene empresa asignada, se permite verlo si no hay restricciones
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('crear usuarios');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Verificar permiso general
        if (!$user->can('editar usuarios')) {
            return false;
        }

        // Verificar restricciones de empresa (a través del usuario)
        if ($model->empresa_id) {
            return UserRestrictionHelper::canAccess($user, 'empresa', $model->empresa_id);
        }

        // Si el usuario no tiene empresa asignada, se permite editarlo si no hay restricciones
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Verificar permiso general
        if (!$user->can('eliminar usuarios')) {
            return false;
        }

        // No permitir que un usuario se elimine a sí mismo
        if ($user->id === $model->id) {
            return false;
        }

        // Verificar restricciones de empresa (a través del usuario)
        if ($model->empresa_id) {
            return UserRestrictionHelper::canAccess($user, 'empresa', $model->empresa_id);
        }

        // Si el usuario no tiene empresa asignada, se permite eliminarlo si no hay restricciones
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false; // No implementado
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false; // No implementado
    }
}
