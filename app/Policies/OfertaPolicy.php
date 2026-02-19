<?php

declare(strict_types=1);

namespace App\Policies;

use App\Helpers\UserRestrictionHelper;
use App\Models\OfertaCabecera;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class OfertaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('ver ofertas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OfertaCabecera $oferta): bool
    {
        // Verificar permiso general
        if (!$user->can('ver ofertas')) {
            return false;
        }

        // Si el usuario no tiene restricciones, puede ver todas las ofertas
        if (!UserRestrictionHelper::hasRestrictions($user)) {
            return true;
        }

        // Cargar la relación cliente si no está cargada
        if (!$oferta->relationLoaded('cliente') && $oferta->cliente_id) {
            $oferta->load('cliente');
        }

        // Verificar restricciones a través del cliente de la oferta
        // Las ofertas se filtran por el cliente, que a su vez tiene una empresa
        if ($oferta->cliente) {
            return UserRestrictionHelper::canAccess($user, 'empresa', $oferta->cliente->empresa_id) &&
                   UserRestrictionHelper::canAccess($user, 'cliente', $oferta->cliente_id);
        }

        // Si la oferta no tiene cliente pero el usuario tiene restricciones, denegar acceso
        // (porque no podemos verificar a qué empresa pertenece)
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Verificar permiso general de Spatie
        return $user->can('crear ofertas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OfertaCabecera $oferta): bool
    {
        // Verificar permiso general
        if (!$user->can('editar ofertas')) {
            return false;
        }

        // Si el usuario no tiene restricciones, puede editar todas las ofertas
        if (!UserRestrictionHelper::hasRestrictions($user)) {
            return true;
        }

        // Cargar la relación cliente si no está cargada
        if (!$oferta->relationLoaded('cliente') && $oferta->cliente_id) {
            $oferta->load('cliente');
        }

        // Verificar restricciones a través del cliente de la oferta
        if ($oferta->cliente) {
            return UserRestrictionHelper::canAccess($user, 'empresa', $oferta->cliente->empresa_id) &&
                   UserRestrictionHelper::canAccess($user, 'cliente', $oferta->cliente_id);
        }

        // Si la oferta no tiene cliente pero el usuario tiene restricciones, denegar acceso
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OfertaCabecera $oferta): bool
    {
        // Verificar permiso general
        if (!$user->can('eliminar ofertas')) {
            return false;
        }

        // Si el usuario no tiene restricciones, puede eliminar todas las ofertas
        if (!UserRestrictionHelper::hasRestrictions($user)) {
            return true;
        }

        // Cargar la relación cliente si no está cargada
        if (!$oferta->relationLoaded('cliente') && $oferta->cliente_id) {
            $oferta->load('cliente');
        }

        // Verificar restricciones a través del cliente de la oferta
        if ($oferta->cliente) {
            return UserRestrictionHelper::canAccess($user, 'empresa', $oferta->cliente->empresa_id) &&
                   UserRestrictionHelper::canAccess($user, 'cliente', $oferta->cliente_id);
        }

        // Si la oferta no tiene cliente pero el usuario tiene restricciones, denegar acceso
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OfertaCabecera $oferta): bool
    {
        return false; // No implementado
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OfertaCabecera $oferta): bool
    {
        return false; // No implementado
    }
}
