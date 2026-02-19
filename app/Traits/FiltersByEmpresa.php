<?php

namespace App\Traits;

use App\Helpers\UserRestrictionHelper;
use Illuminate\Support\Facades\Auth;

trait FiltersByEmpresa
{
    /**
     * Obtener el usuario autenticado
     */
    protected function getAuthUser()
    {
        return Auth::user();
    }

    /**
     * Aplicar filtro por restricciones del usuario autenticado
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $restrictionType Tipo de restricción (empresa, centro, vehiculo, cliente, departamento)
     * @param string $columnName Nombre de la columna en la tabla (por defecto: {restrictionType}_id)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByUserRestrictions($query, string $restrictionType, ?string $columnName = null)
    {
        $user = $this->getAuthUser();
        
        if (!$user) {
            return $query;
        }

        // Si no tiene restricciones de este tipo, puede ver todo
        if (!UserRestrictionHelper::hasRestrictionsOfType($user, $restrictionType)) {
            return $query;
        }

        // Si tiene restricciones, filtrar por los valores permitidos
        $allowedValues = UserRestrictionHelper::getRestrictionValues($user, $restrictionType);
        
        if (empty($allowedValues)) {
            // Si no hay valores permitidos, no mostrar nada
            return $query->whereRaw('1 = 0');
        }

        $column = $columnName ?? ($restrictionType . '_id');
        return $query->whereIn($column, $allowedValues);
    }

    /**
     * Aplicar filtro por empresa (compatibilidad con código anterior)
     */
    protected function filterByUserEmpresa($query)
    {
        return $this->filterByUserRestrictions($query, UserRestrictionHelper::TYPE_EMPRESA, 'empresa_id');
    }

    /**
     * Verificar si el usuario puede acceder a un registro específico
     * 
     * @param int|null $valueId ID del registro
     * @param string $restrictionType Tipo de restricción
     * @return bool
     */
    protected function canAccessValue(?int $valueId, string $restrictionType): bool
    {
        $user = $this->getAuthUser();
        
        if (!$user) {
            return false;
        }

        // Si no se especifica valor, permitir acceso (para casos especiales)
        if ($valueId === null) {
            return true;
        }

        // Si no tiene restricciones de este tipo, puede acceder
        if (!UserRestrictionHelper::hasRestrictionsOfType($user, $restrictionType)) {
            return true;
        }

        // Verificar que el valor esté permitido
        return UserRestrictionHelper::canAccess($user, $restrictionType, $valueId);
    }

    /**
     * Verificar si el usuario puede acceder a un registro de una empresa específica (compatibilidad)
     */
    protected function canAccessEmpresa(?int $empresaId): bool
    {
        return $this->canAccessValue($empresaId, UserRestrictionHelper::TYPE_EMPRESA);
    }

    /**
     * Verificar si el usuario puede acceder a un cliente específico
     */
    protected function canAccessCliente(?int $clienteId): bool
    {
        return $this->canAccessValue($clienteId, UserRestrictionHelper::TYPE_CLIENTE);
    }

    /**
     * Verificar si el usuario puede acceder a un vehículo específico
     */
    protected function canAccessVehiculo(?int $vehiculoId): bool
    {
        return $this->canAccessValue($vehiculoId, UserRestrictionHelper::TYPE_VEHICULO);
    }

    /**
     * Verificar si el usuario puede acceder a un centro específico
     */
    protected function canAccessCentro(?int $centroId): bool
    {
        return $this->canAccessValue($centroId, UserRestrictionHelper::TYPE_CENTRO);
    }
}
