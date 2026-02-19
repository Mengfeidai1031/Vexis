<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\UserRestriction;
use Illuminate\Support\Collection;

class UserRestrictionHelper
{
    /**
     * Tipos de restricciones disponibles
     */
    public const TYPE_EMPRESA = 'empresa';
    public const TYPE_CENTRO = 'centro';
    public const TYPE_VEHICULO = 'vehiculo';
    public const TYPE_CLIENTE = 'cliente';
    public const TYPE_DEPARTAMENTO = 'departamento';

    /**
     * Mapeo de tipos a modelos
     */
    private static function getModelClass(string $type): string
    {
        return match($type) {
            self::TYPE_EMPRESA => \App\Models\Empresa::class,
            self::TYPE_CENTRO => \App\Models\Centro::class,
            self::TYPE_VEHICULO => \App\Models\Vehiculo::class,
            self::TYPE_CLIENTE => \App\Models\Cliente::class,
            self::TYPE_DEPARTAMENTO => \App\Models\Departamento::class,
            default => throw new \InvalidArgumentException("Tipo de restricción no válido: {$type}"),
        };
    }

    /**
     * Añadir una restricción a un usuario
     *
     * @param User|int $user Usuario o ID del usuario
     * @param string $type Tipo de restricción
     * @param int|Model $value ID del registro o modelo
     * @return UserRestriction
     */
    public static function addRestriction(User|int $user, string $type, int|\Illuminate\Database\Eloquent\Model $value): UserRestriction
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        // Si es un modelo, usar directamente
        if ($value instanceof \Illuminate\Database\Eloquent\Model) {
            return UserRestriction::firstOrCreate([
                'user_id' => $userId,
                'restrictable_type' => get_class($value),
                'restrictable_id' => $value->id,
            ]);
        }
        
        // Si es un ID, obtener el modelo
        $modelClass = self::getModelClass($type);
        $model = $modelClass::findOrFail($value);
        
        return UserRestriction::firstOrCreate([
            'user_id' => $userId,
            'restrictable_type' => $modelClass,
            'restrictable_id' => $model->id,
        ]);
    }

    /**
     * Añadir múltiples restricciones de un tipo a un usuario
     *
     * @param User|int $user Usuario o ID del usuario
     * @param string $type Tipo de restricción
     * @param array $values Array de IDs
     * @return Collection
     */
    public static function addRestrictions(User|int $user, string $type, array $values): Collection
    {
        $userId = $user instanceof User ? $user->id : $user;
        $restrictions = collect();
        
        foreach ($values as $value) {
            $restrictions->push(self::addRestriction($userId, $type, $value));
        }
        
        return $restrictions;
    }

    /**
     * Eliminar una restricción específica
     *
     * @param User|int $user Usuario o ID del usuario
     * @param string $type Tipo de restricción
     * @param int|\Illuminate\Database\Eloquent\Model $value ID del registro o modelo
     * @return bool
     */
    public static function removeRestriction(User|int $user, string $type, int|\Illuminate\Database\Eloquent\Model $value): bool
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        if ($value instanceof \Illuminate\Database\Eloquent\Model) {
            return UserRestriction::where('user_id', $userId)
                ->where('restrictable_type', get_class($value))
                ->where('restrictable_id', $value->id)
                ->delete() > 0;
        }
        
        $modelClass = self::getModelClass($type);
        
        return UserRestriction::where('user_id', $userId)
            ->where('restrictable_type', $modelClass)
            ->where('restrictable_id', $value)
            ->delete() > 0;
    }

    /**
     * Eliminar todas las restricciones de un tipo para un usuario
     *
     * @param User|int $user Usuario o ID del usuario
     * @param string $type Tipo de restricción
     * @return int Número de restricciones eliminadas
     */
    public static function removeRestrictionsByType(User|int $user, string $type): int
    {
        $userId = $user instanceof User ? $user->id : $user;
        $modelClass = self::getModelClass($type);
        
        return UserRestriction::where('user_id', $userId)
            ->where('restrictable_type', $modelClass)
            ->delete();
    }

    /**
     * Eliminar todas las restricciones de un usuario
     *
     * @param User|int $user Usuario o ID del usuario
     * @return int Número de restricciones eliminadas
     */
    public static function removeAllRestrictions(User|int $user): int
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        return UserRestriction::where('user_id', $userId)->delete();
    }

    /**
     * Obtener todas las restricciones de un usuario
     *
     * @param User|int $user Usuario o ID del usuario
     * @return Collection
     */
    public static function getRestrictions(User|int $user): Collection
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        return UserRestriction::where('user_id', $userId)->get();
    }

    /**
     * Obtener restricciones de un tipo específico
     *
     * @param User|int $user Usuario o ID del usuario
     * @param string $type Tipo de restricción
     * @return Collection
     */
    public static function getRestrictionsByType(User|int $user, string $type): Collection
    {
        $userId = $user instanceof User ? $user->id : $user;
        $modelClass = self::getModelClass($type);
        
        return UserRestriction::where('user_id', $userId)
            ->where('restrictable_type', $modelClass)
            ->with('restrictable')
            ->get();
    }

    /**
     * Obtener los IDs de restricciones de un tipo específico
     *
     * @param User|int $user Usuario o ID del usuario
     * @param string $type Tipo de restricción
     * @return array
     */
    public static function getRestrictionValues(User|int $user, string $type): array
    {
        return self::getRestrictionsByType($user, $type)
            ->pluck('restrictable_id')
            ->toArray();
    }

    /**
     * Verificar si un usuario tiene restricciones
     *
     * @param User|int $user Usuario o ID del usuario
     * @return bool
     */
    public static function hasRestrictions(User|int $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        return UserRestriction::where('user_id', $userId)->exists();
    }

    /**
     * Verificar si un usuario tiene restricciones de un tipo específico
     *
     * @param User|int $user Usuario o ID del usuario
     * @param string $type Tipo de restricción
     * @return bool
     */
    public static function hasRestrictionsOfType(User|int $user, string $type): bool
    {
        $userId = $user instanceof User ? $user->id : $user;
        $modelClass = self::getModelClass($type);
        
        return UserRestriction::where('user_id', $userId)
            ->where('restrictable_type', $modelClass)
            ->exists();
    }

    /**
     * Verificar si un usuario puede acceder a un valor específico
     *
     * @param User|int $user Usuario o ID del usuario
     * @param string $type Tipo de restricción
     * @param int $value ID del registro
     * @return bool True si no tiene restricciones o si el valor está permitido
     */
    public static function canAccess(User|int $user, string $type, int $value): bool
    {
        // Si no tiene restricciones de este tipo, puede acceder
        if (!self::hasRestrictionsOfType($user, $type)) {
            return true;
        }
        
        // Si tiene restricciones, verificar que el valor esté permitido
        $allowedValues = self::getRestrictionValues($user, $type);
        return in_array($value, $allowedValues);
    }
}
