<?php

namespace App\Repositories;

use App\Helpers\UserRestrictionHelper;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Repositories\Interfaces\ClienteRepositoryInterface;
use App\Traits\FiltersByEmpresa;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClienteRepository implements ClienteRepositoryInterface
{
    use FiltersByEmpresa;

    public function all()
    {
        $query = Cliente::with('empresa');
        $user = $this->getAuthUser();
        
        // Si tiene restricciones de cliente específico, usar solo esas (prioridad)
        if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_CLIENTE)) {
            $allowedClienteIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_CLIENTE);
            if (!empty($allowedClienteIds)) {
                $query->whereIn('id', $allowedClienteIds);
            } else {
                // Si no hay clientes permitidos, no mostrar nada
                $query->whereRaw('1 = 0');
            }
        } 
        // Si NO tiene restricciones de cliente pero SÍ tiene restricciones de empresa, filtrar por empresa
        elseif ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            $query = $this->filterByUserRestrictions($query, UserRestrictionHelper::TYPE_EMPRESA, 'empresa_id');
        }
        // Si no tiene restricciones, mostrar todo (no aplicar filtros)
        
        return $query->orderBy('apellidos', 'asc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        $query = Cliente::with('empresa')
            ->where(function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%")
                    ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                    ->orWhere('dni', 'like', "%{$searchTerm}%")
                    ->orWhere('domicilio', 'like', "%{$searchTerm}%")
                    ->orWhere('codigo_postal', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('empresa', function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%");
            });
        
        $user = $this->getAuthUser();
        
        // Si tiene restricciones de cliente específico, usar solo esas (prioridad)
        if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_CLIENTE)) {
            $allowedClienteIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_CLIENTE);
            if (!empty($allowedClienteIds)) {
                $query->whereIn('id', $allowedClienteIds);
            } else {
                // Si no hay clientes permitidos, no mostrar nada
                $query->whereRaw('1 = 0');
            }
        } 
        // Si NO tiene restricciones de cliente pero SÍ tiene restricciones de empresa, filtrar por empresa
        elseif ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            $query = $this->filterByUserRestrictions($query, UserRestrictionHelper::TYPE_EMPRESA, 'empresa_id');
        }
        // Si no tiene restricciones, mostrar todo (no aplicar filtros)
        
        return $query->orderBy('apellidos', 'asc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        $cliente = Cliente::with('empresa')->findOrFail($id);
        
        // Verificar acceso por empresa y cliente
        if (!$this->canAccessEmpresa($cliente->empresa_id) || !$this->canAccessCliente($cliente->id)) {
            throw new ModelNotFoundException('Cliente no encontrado o no tienes permiso para acceder.');
        }
        
        return $cliente;
    }

    public function create(array $data)
    {
        // Si hay restricciones de empresa, validar que la empresa esté permitida
        $user = $this->getAuthUser();
        if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            if (!UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $data['empresa_id'])) {
                throw new \Exception('No tienes permiso para crear clientes en esta empresa.');
            }
        }
        
        return Cliente::create($data);
    }

    public function update(int $id, array $data)
    {
        $cliente = Cliente::findOrFail($id);
        
        // Verificar acceso
        if (!$this->canAccessEmpresa($cliente->empresa_id) || !$this->canAccessCliente($cliente->id)) {
            throw new ModelNotFoundException('Cliente no encontrado o no tienes permiso para acceder.');
        }
        
        // Si hay restricciones de empresa, validar que la nueva empresa esté permitida
        if (isset($data['empresa_id'])) {
            $user = $this->getAuthUser();
            if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
                if (!UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $data['empresa_id'])) {
                    throw new \Exception('No tienes permiso para asignar clientes a esta empresa.');
                }
            }
        }
        
        $cliente->update($data);
        return $cliente;
    }

    public function delete(int $id)
    {
        $cliente = Cliente::findOrFail($id);
        
        // Verificar acceso
        if (!$this->canAccessEmpresa($cliente->empresa_id) || !$this->canAccessCliente($cliente->id)) {
            throw new ModelNotFoundException('Cliente no encontrado o no tienes permiso para acceder.');
        }
        
        return $cliente->delete();
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