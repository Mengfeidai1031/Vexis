<?php

declare(strict_types=1);

namespace App\Policies;

use App\Helpers\UserRestrictionHelper;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class DepartamentoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('ver departamentos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Departamento $departamento): bool
    {
        // Verificar permiso general
        if (!$user->can('ver departamentos')) {
            return false;
        }

        // Verificar restricciones de departamento
        // Los departamentos no tienen empresa_id, solo se verifica la restricción directa
        return UserRestrictionHelper::canAccess($user, 'departamento', $departamento->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('crear departamentos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Departamento $departamento): bool
    {
        // Verificar permiso general
        if (!$user->can('editar departamentos')) {
            return false;
        }

        // Verificar restricciones de departamento
        return UserRestrictionHelper::canAccess($user, 'departamento', $departamento->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Departamento $departamento): bool
    {
        // Verificar permiso general
        if (!$user->can('eliminar departamentos')) {
            return false;
        }

        // Verificar restricciones de departamento
        return UserRestrictionHelper::canAccess($user, 'departamento', $departamento->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Departamento $departamento): bool
    {
        return false; // No implementado
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Departamento $departamento): bool
    {
        return false; // No implementado
    }
}
