<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\UserRestrictionHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

trait FiltersByEmpresa
{
    /**
     * Obtener el usuario autenticado.
     */
    protected function getAuthUser(): ?\App\Models\User
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user;
    }

    /**
     * Aplicar filtro por restricciones del usuario autenticado.
     *
     * @param  Builder|Relation  $query
     * @param  string  $restrictionType  Tipo de restricción (empresa, centro, vehiculo, cliente, departamento)
     * @param  string|null  $columnName  Nombre de la columna en la tabla (por defecto: {restrictionType}_id)
     */
    protected function filterByUserRestrictions($query, string $restrictionType, ?string $columnName = null)
    {
        $user = $this->getAuthUser();

        if (! $user) {
            return $query;
        }

        if (! UserRestrictionHelper::hasRestrictionsOfType($user, $restrictionType)) {
            return $query;
        }

        $allowedValues = UserRestrictionHelper::getRestrictionValues($user, $restrictionType);

        if (empty($allowedValues)) {
            return $query->whereRaw('1 = 0');
        }

        $column = $columnName ?? ($restrictionType.'_id');

        return $query->whereIn($column, $allowedValues);
    }

    /**
     * Aplicar filtro por empresa (compatibilidad).
     */
    protected function filterByUserEmpresa($query)
    {
        return $this->filterByUserRestrictions($query, UserRestrictionHelper::TYPE_EMPRESA, 'empresa_id');
    }

    /**
     * Aplicar patrón de prioridad de restricciones:
     * 1) Si el usuario tiene restricciones del tipo prioritario (ej: cliente, vehiculo), filtrar por esos IDs.
     * 2) En caso contrario, si tiene restricciones de empresa, aplicar filtro de empresa (opcionalmente
     *    como subquery sobre una relación).
     * 3) Si no tiene restricciones, no se aplica filtrado.
     *
     * @param  Builder|Relation  $query
     * @param  string  $priorityType  Tipo prioritario (cliente, vehiculo, etc.)
     * @param  string  $priorityColumn  Columna para el filtro prioritario (ej: 'id', 'cliente_id').
     * @param  string|null  $empresaRelation  Relación usada para aplicar restricción por empresa.
     *                                        Si es null → filtra directamente por columna empresa_id.
     */
    protected function applyRestrictionPriority(
        $query,
        string $priorityType,
        string $priorityColumn,
        ?string $empresaRelation = null
    ) {
        $user = $this->getAuthUser();

        if (! $user) {
            return $query;
        }

        if (UserRestrictionHelper::hasRestrictionsOfType($user, $priorityType)) {
            $allowedIds = UserRestrictionHelper::getRestrictionValues($user, $priorityType);

            return empty($allowedIds)
                ? $query->whereRaw('1 = 0')
                : $query->whereIn($priorityColumn, $allowedIds);
        }

        if (UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            if ($empresaRelation === null) {
                return $this->filterByUserRestrictions($query, UserRestrictionHelper::TYPE_EMPRESA, 'empresa_id');
            }

            $allowedEmpresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);

            return $query->whereHas($empresaRelation, static function ($q) use ($allowedEmpresaIds) {
                $q->whereIn('empresa_id', $allowedEmpresaIds);
            });
        }

        return $query;
    }

    /**
     * Verificar acceso genérico a un valor.
     */
    protected function canAccessValue(?int $valueId, string $restrictionType): bool
    {
        $user = $this->getAuthUser();

        if (! $user) {
            return false;
        }

        if ($valueId === null) {
            return true;
        }

        if (! UserRestrictionHelper::hasRestrictionsOfType($user, $restrictionType)) {
            return true;
        }

        return UserRestrictionHelper::canAccess($user, $restrictionType, $valueId);
    }

    protected function canAccessEmpresa(?int $empresaId): bool
    {
        return $this->canAccessValue($empresaId, UserRestrictionHelper::TYPE_EMPRESA);
    }

    protected function canAccessCliente(?int $clienteId): bool
    {
        return $this->canAccessValue($clienteId, UserRestrictionHelper::TYPE_CLIENTE);
    }

    protected function canAccessVehiculo(?int $vehiculoId): bool
    {
        return $this->canAccessValue($vehiculoId, UserRestrictionHelper::TYPE_VEHICULO);
    }

    protected function canAccessCentro(?int $centroId): bool
    {
        return $this->canAccessValue($centroId, UserRestrictionHelper::TYPE_CENTRO);
    }

    /**
     * Verificar acceso a una empresa antes de asignar/crear.
     *
     * @throws \RuntimeException Cuando el usuario no puede acceder a la empresa indicada.
     */
    protected function assertCanAssignEmpresa(?int $empresaId, string $errorMessage): void
    {
        $user = $this->getAuthUser();

        if (! $user || ! UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            return;
        }

        if ($empresaId === null || ! UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $empresaId)) {
            throw new \RuntimeException($errorMessage);
        }
    }
}
