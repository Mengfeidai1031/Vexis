<?php

declare(strict_types=1);

namespace App\Policies;

use App\Helpers\UserRestrictionHelper;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class ClientePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('ver clientes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cliente $cliente): bool
    {
        // Verificar permiso general
        if (!$user->can('ver clientes')) {
            return false;
        }

        // Verificar restricciones de empresa (a través del cliente)
        return UserRestrictionHelper::canAccess($user, 'empresa', $cliente->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'cliente', $cliente->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('crear clientes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cliente $cliente): bool
    {
        // Verificar permiso general
        if (!$user->can('editar clientes')) {
            return false;
        }

        // Verificar restricciones de empresa (a través del cliente)
        return UserRestrictionHelper::canAccess($user, 'empresa', $cliente->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'cliente', $cliente->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cliente $cliente): bool
    {
        // Verificar permiso general
        if (!$user->can('eliminar clientes')) {
            return false;
        }

        // Verificar restricciones de empresa (a través del cliente)
        return UserRestrictionHelper::canAccess($user, 'empresa', $cliente->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'cliente', $cliente->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cliente $cliente): bool
    {
        return false; // No implementado
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cliente $cliente): bool
    {
        return false; // No implementado
    }
}
