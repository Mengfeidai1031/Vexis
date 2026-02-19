<?php

declare(strict_types=1);

namespace App\Policies;

use App\Helpers\UserRestrictionHelper;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Auth\Access\Response;

final class VehiculoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('ver vehículos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vehiculo $vehiculo): bool
    {
        // Verificar permiso general
        if (!$user->can('ver vehículos')) {
            return false;
        }

        // Verificar restricciones de empresa y vehículo
        return UserRestrictionHelper::canAccess($user, 'empresa', $vehiculo->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'vehiculo', $vehiculo->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('crear vehículos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vehiculo $vehiculo): bool
    {
        // Verificar permiso general
        if (!$user->can('editar vehículos')) {
            return false;
        }

        // Verificar restricciones de empresa y vehículo
        return UserRestrictionHelper::canAccess($user, 'empresa', $vehiculo->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'vehiculo', $vehiculo->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehiculo $vehiculo): bool
    {
        // Verificar permiso general
        if (!$user->can('eliminar vehículos')) {
            return false;
        }

        // Verificar restricciones de empresa y vehículo
        return UserRestrictionHelper::canAccess($user, 'empresa', $vehiculo->empresa_id) &&
               UserRestrictionHelper::canAccess($user, 'vehiculo', $vehiculo->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vehiculo $vehiculo): bool
    {
        return false; // No implementado
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vehiculo $vehiculo): bool
    {
        return false; // No implementado
    }
}
