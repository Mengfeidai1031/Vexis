<?php

namespace App\Repositories;

use App\Helpers\UserRestrictionHelper;
use App\Models\OfertaCabecera;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Repositories\Interfaces\OfertaRepositoryInterface;
use App\Traits\FiltersByEmpresa;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OfertaRepository implements OfertaRepositoryInterface
{
    use FiltersByEmpresa;

    public function all()
    {
        $query = OfertaCabecera::with(['cliente.empresa', 'vehiculo', 'lineas']);
        
        // Aplicar filtros por restricciones a través del cliente
        $user = $this->getAuthUser();
        if ($user) {
            // Si tiene restricciones de cliente específico, usar solo esas (prioridad)
            if (UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_CLIENTE)) {
                $allowedClienteIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_CLIENTE);
                if (!empty($allowedClienteIds)) {
                    $query->whereIn('cliente_id', $allowedClienteIds);
                } else {
                    // Si no hay clientes permitidos, no mostrar nada
                    $query->whereRaw('1 = 0');
                }
            }
            // Si NO tiene restricciones de cliente pero SÍ tiene restricciones de empresa, filtrar por empresa del cliente
            elseif (UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
                $allowedEmpresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);
                $query->whereHas('cliente', function($q) use ($allowedEmpresaIds) {
                    $q->whereIn('empresa_id', $allowedEmpresaIds);
                });
            }
            // Si no tiene restricciones, mostrar todo (no aplicar filtros)
        }
        
        return $query->orderBy('fecha', 'desc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        $query = OfertaCabecera::with(['cliente.empresa', 'vehiculo', 'lineas'])
            ->where(function($query) use ($searchTerm) {
                $query->whereHas('cliente', function($q) use ($searchTerm) {
                    $q->where('nombre', 'like', "%{$searchTerm}%")
                      ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                      ->orWhere('dni', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('vehiculo', function($q) use ($searchTerm) {
                    $q->where('modelo', 'like', "%{$searchTerm}%")
                      ->orWhere('chasis', 'like', "%{$searchTerm}%");
                });
            });
        
        // Aplicar filtros por restricciones
        $user = $this->getAuthUser();
        if ($user) {
            // Si tiene restricciones de cliente específico, usar solo esas (prioridad)
            if (UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_CLIENTE)) {
                $allowedClienteIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_CLIENTE);
                if (!empty($allowedClienteIds)) {
                    $query->whereIn('cliente_id', $allowedClienteIds);
                } else {
                    // Si no hay clientes permitidos, no mostrar nada
                    $query->whereRaw('1 = 0');
                }
            }
            // Si NO tiene restricciones de cliente pero SÍ tiene restricciones de empresa, filtrar por empresa del cliente
            elseif (UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
                $allowedEmpresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);
                $query->whereHas('cliente', function($q) use ($allowedEmpresaIds) {
                    $q->whereIn('empresa_id', $allowedEmpresaIds);
                });
            }
            // Si no tiene restricciones, mostrar todo (no aplicar filtros)
        }
        
        return $query->orderBy('fecha', 'desc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        $oferta = OfertaCabecera::with(['cliente', 'vehiculo', 'lineas'])->findOrFail($id);
        
        // Verificar acceso por empresa y cliente
        if (!$this->canAccessEmpresa($oferta->cliente->empresa_id) || !$this->canAccessCliente($oferta->cliente_id)) {
            throw new ModelNotFoundException('Oferta no encontrada o no tienes permiso para acceder.');
        }
        
        return $oferta;
    }

    public function delete(int $id)
    {
        $oferta = OfertaCabecera::findOrFail($id);
        
        // Verificar acceso por empresa y cliente
        if (!$this->canAccessEmpresa($oferta->cliente->empresa_id) || !$this->canAccessCliente($oferta->cliente_id)) {
            throw new ModelNotFoundException('Oferta no encontrada o no tienes permiso para acceder.');
        }
        
        return $oferta->delete();
    }

    public function getClientes()
    {
        $query = Cliente::orderBy('apellidos', 'asc');
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
        
        return $query->get();
    }

    public function getVehiculos()
    {
        $query = Vehiculo::orderBy('modelo', 'asc');
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
        
        return $query->get();
    }

    public function getEmpresas()
    {
        $user = $this->getAuthUser();
        
        // Si no tiene restricciones de empresa, devolver todas
        if (!$user || !UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            return \App\Models\Empresa::orderBy('nombre', 'asc')->get();
        }
        
        // Si tiene restricciones, devolver solo las permitidas
        $allowedEmpresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);
        return \App\Models\Empresa::whereIn('id', $allowedEmpresaIds)->orderBy('nombre', 'asc')->get();
    }

    public function filter(array $filters)
    {
        $query = OfertaCabecera::with(['cliente.empresa', 'vehiculo', 'lineas']);

        // Aplicar filtros por restricciones
        $user = $this->getAuthUser();
        if ($user) {
            // Si tiene restricciones de cliente específico, usar solo esas (prioridad)
            if (UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_CLIENTE)) {
                $allowedClienteIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_CLIENTE);
                if (!empty($allowedClienteIds)) {
                    $query->whereIn('cliente_id', $allowedClienteIds);
                } else {
                    // Si no hay clientes permitidos, no mostrar nada
                    $query->whereRaw('1 = 0');
                }
            }
            // Si NO tiene restricciones de cliente pero SÍ tiene restricciones de empresa, filtrar por empresa del cliente
            elseif (UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
                $allowedEmpresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);
                $query->whereHas('cliente', function($q) use ($allowedEmpresaIds) {
                    $q->whereIn('empresa_id', $allowedEmpresaIds);
                });
            }
            // Si no tiene restricciones, mostrar todo (no aplicar filtros)
        }

        // Filtro por fecha desde
        if (!empty($filters['fecha_desde'])) {
            $query->whereDate('fecha', '>=', $filters['fecha_desde']);
        }

        // Filtro por fecha hasta
        if (!empty($filters['fecha_hasta'])) {
            $query->whereDate('fecha', '<=', $filters['fecha_hasta']);
        }

        // Filtro por cliente
        if (!empty($filters['cliente_id'])) {
            $query->where('cliente_id', $filters['cliente_id']);
        }

        // Filtro por vehículo
        if (!empty($filters['vehiculo_id'])) {
            $query->where('vehiculo_id', $filters['vehiculo_id']);
        }

        // Filtro por empresa (a través del cliente) - solo si está permitida
        if (!empty($filters['empresa_id'])) {
            $user = $this->getAuthUser();
            if (!$user || !UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA) || 
                UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $filters['empresa_id'])) {
                $query->whereHas('cliente', function($q) use ($filters) {
                    $q->where('empresa_id', $filters['empresa_id']);
                });
            }
        }

        // Búsqueda mejorada (por descripción, cliente, vehículo)
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function($q) use ($searchTerm) {
                // Búsqueda en cliente
                $q->whereHas('cliente', function($query) use ($searchTerm) {
                    $query->where('nombre', 'like', "%{$searchTerm}%")
                          ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                          ->orWhere('dni', 'like', "%{$searchTerm}%")
                          ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                // Búsqueda en vehículo
                ->orWhereHas('vehiculo', function($query) use ($searchTerm) {
                    $query->where('modelo', 'like', "%{$searchTerm}%")
                          ->orWhere('version', 'like', "%{$searchTerm}%")
                          ->orWhere('chasis', 'like', "%{$searchTerm}%");
                })
                // Búsqueda en líneas de oferta (descripción)
                ->orWhereHas('lineas', function($query) use ($searchTerm) {
                    $query->where('descripcion', 'like', "%{$searchTerm}%")
                          ->orWhere('tipo', 'like', "%{$searchTerm}%");
                });
            });
        }

        return $query->orderBy('fecha', 'desc')->paginate(10);
    }
}