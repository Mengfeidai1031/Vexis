<?php

namespace App\Repositories;

use App\Helpers\UserRestrictionHelper;
use App\Models\Vehiculo;
use App\Models\Empresa;
use App\Repositories\Interfaces\VehiculoRepositoryInterface;
use App\Traits\FiltersByEmpresa;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VehiculoRepository implements VehiculoRepositoryInterface
{
    use FiltersByEmpresa;

    public function all()
    {
        $query = Vehiculo::with('empresa');
        $user = $this->getAuthUser();
        
        // Si tiene restricciones de vehículo específico, usar solo esas (prioridad)
        if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_VEHICULO)) {
            $allowedVehiculoIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_VEHICULO);
            if (!empty($allowedVehiculoIds)) {
                $query->whereIn('id', $allowedVehiculoIds);
            } else {
                // Si no hay vehículos permitidos, no mostrar nada
                $query->whereRaw('1 = 0');
            }
        } 
        // Si NO tiene restricciones de vehículo pero SÍ tiene restricciones de empresa, filtrar por empresa
        elseif ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            $query = $this->filterByUserRestrictions($query, UserRestrictionHelper::TYPE_EMPRESA, 'empresa_id');
        }
        // Si no tiene restricciones, mostrar todo (no aplicar filtros)
        
        return $query->orderBy('modelo', 'asc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        $query = Vehiculo::with('empresa')
            ->where(function($query) use ($searchTerm) {
                $query->where('chasis', 'like', "%{$searchTerm}%")
                    ->orWhere('modelo', 'like', "%{$searchTerm}%")
                    ->orWhere('version', 'like', "%{$searchTerm}%")
                    ->orWhere('color_externo', 'like', "%{$searchTerm}%")
                    ->orWhere('color_interno', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('empresa', function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%");
            });
        
        $user = $this->getAuthUser();
        
        // Si tiene restricciones de vehículo específico, usar solo esas (prioridad)
        if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_VEHICULO)) {
            $allowedVehiculoIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_VEHICULO);
            if (!empty($allowedVehiculoIds)) {
                $query->whereIn('id', $allowedVehiculoIds);
            } else {
                // Si no hay vehículos permitidos, no mostrar nada
                $query->whereRaw('1 = 0');
            }
        } 
        // Si NO tiene restricciones de vehículo pero SÍ tiene restricciones de empresa, filtrar por empresa
        elseif ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            $query = $this->filterByUserRestrictions($query, UserRestrictionHelper::TYPE_EMPRESA, 'empresa_id');
        }
        // Si no tiene restricciones, mostrar todo (no aplicar filtros)
        
        return $query->orderBy('modelo', 'asc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        $vehiculo = Vehiculo::with('empresa')->findOrFail($id);
        
        // Verificar acceso por empresa y vehículo
        if (!$this->canAccessEmpresa($vehiculo->empresa_id) || !$this->canAccessVehiculo($vehiculo->id)) {
            throw new ModelNotFoundException('Vehículo no encontrado o no tienes permiso para acceder.');
        }
        
        return $vehiculo;
    }

    public function create(array $data)
    {
        // Si hay restricciones de empresa, validar que la empresa esté permitida
        $user = $this->getAuthUser();
        if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            if (!UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $data['empresa_id'])) {
                throw new \Exception('No tienes permiso para crear vehículos en esta empresa.');
            }
        }
        
        return Vehiculo::create($data);
    }

    public function update(int $id, array $data)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        
        // Verificar acceso
        if (!$this->canAccessEmpresa($vehiculo->empresa_id) || !$this->canAccessVehiculo($vehiculo->id)) {
            throw new ModelNotFoundException('Vehículo no encontrado o no tienes permiso para acceder.');
        }
        
        // Si hay restricciones de empresa, validar que la nueva empresa esté permitida
        if (isset($data['empresa_id'])) {
            $user = $this->getAuthUser();
            if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
                if (!UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $data['empresa_id'])) {
                    throw new \Exception('No tienes permiso para asignar vehículos a esta empresa.');
                }
            }
        }
        
        $vehiculo->update($data);
        return $vehiculo;
    }

    public function delete(int $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        
        // Verificar acceso
        if (!$this->canAccessEmpresa($vehiculo->empresa_id) || !$this->canAccessVehiculo($vehiculo->id)) {
            throw new ModelNotFoundException('Vehículo no encontrado o no tienes permiso para acceder.');
        }
        
        return $vehiculo->delete();
    }

    public function getEmpresas()
    {
        $user = $this->getAuthUser();
        
        // Si no tiene restricciones de empresa, devolver todas
        if (!$user || !UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            return Empresa::all();
        }
        
        // Si tiene restricciones, devolver solo las permitidas
        $allowedEmpresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);
        return Empresa::whereIn('id', $allowedEmpresaIds)->get();
    }
}