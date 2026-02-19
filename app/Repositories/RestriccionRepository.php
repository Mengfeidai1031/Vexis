<?php

namespace App\Repositories;

use App\Helpers\UserRestrictionHelper;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use App\Models\UserRestriction;
use App\Models\Vehiculo;
use App\Models\Centro;
use App\Models\Departamento;
use App\Repositories\Interfaces\RestriccionRepositoryInterface;

class RestriccionRepository implements RestriccionRepositoryInterface
{
    public function all()
    {
        return UserRestriction::with(['user.empresa', 'restrictable'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        return UserRestriction::with(['user.empresa', 'restrictable'])
            ->where(function($query) use ($searchTerm) {
                $query->whereHas('user', function($q) use ($searchTerm) {
                    $q->where('nombre', 'like', "%{$searchTerm}%")
                        ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                ->orWhereHasMorph('restrictable', [Empresa::class, Cliente::class, Vehiculo::class, Centro::class, Departamento::class], function($q, $type) use ($searchTerm) {
                    if ($type === Empresa::class) {
                        $q->where('nombre', 'like', "%{$searchTerm}%");
                    } elseif ($type === Cliente::class) {
                        $q->where('nombre', 'like', "%{$searchTerm}%")
                            ->orWhere('apellidos', 'like', "%{$searchTerm}%");
                    } elseif ($type === Vehiculo::class) {
                        $q->where('modelo', 'like', "%{$searchTerm}%")
                            ->orWhere('version', 'like', "%{$searchTerm}%");
                    } elseif ($type === Centro::class) {
                        $q->where('nombre', 'like', "%{$searchTerm}%");
                    } elseif ($type === Departamento::class) {
                        $q->where('nombre', 'like', "%{$searchTerm}%");
                    }
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        return UserRestriction::with(['user', 'restrictable'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return UserRestrictionHelper::addRestriction(
            $data['user_id'],
            $data['type'],
            $data['restrictable_id']
        );
    }

    public function update(int $id, array $data)
    {
        $restriction = UserRestriction::findOrFail($id);
        
        // Eliminar la restricción antigua
        $restriction->delete();
        
        // Crear la nueva restricción
        return UserRestrictionHelper::addRestriction(
            $data['user_id'],
            $data['type'],
            $data['restrictable_id']
        );
    }

    public function delete(int $id)
    {
        $restriction = UserRestriction::findOrFail($id);
        return $restriction->delete();
    }

    public function getUsers()
    {
        return User::orderBy('apellidos', 'asc')->orderBy('nombre', 'asc')->get();
    }

    public function getAvailableRestrictions(): array
    {
        $empresas = Empresa::orderBy('nombre', 'asc')->get();
        $clientes = Cliente::with('empresa')->orderBy('apellidos', 'asc')->orderBy('nombre', 'asc')->get();
        $vehiculos = Vehiculo::with('empresa')->orderBy('modelo', 'asc')->orderBy('version', 'asc')->get();
        $centros = Centro::with('empresa')->orderBy('nombre', 'asc')->get();
        $departamentos = Departamento::orderBy('nombre', 'asc')->get();
        
        // Añadir nombre_completo a los clientes para el JSON
        $clientes->each(function($cliente) {
            $cliente->setAttribute('nombre_completo', $cliente->nombre_completo);
        });
        
        return [
            'empresas' => $empresas,
            'clientes' => $clientes,
            'vehiculos' => $vehiculos,
            'centros' => $centros,
            'departamentos' => $departamentos,
        ];
    }

    public function getUserRestrictions(int $userId): array
    {
        $user = User::findOrFail($userId);
        $restrictions = $user->restrictions()->with('restrictable')->get();
        
        $grouped = [
            'empresas' => [],
            'clientes' => [],
            'vehiculos' => [],
            'centros' => [],
            'departamentos' => [],
        ];
        
        foreach ($restrictions as $restriction) {
            $type = $this->getTypeFromClass($restriction->restrictable_type);
            if ($type && isset($grouped[$type])) {
                $grouped[$type][] = $restriction->restrictable_id;
            }
        }
        
        return $grouped;
    }

    public function syncUserRestrictions(int $userId, array $restrictions): void
    {
        $user = User::findOrFail($userId);
        
        // Eliminar todas las restricciones existentes
        UserRestrictionHelper::removeAllRestrictions($user);
        
        // Mapeo de tipos del formulario a tipos del helper
        $typeMapping = [
            'empresas' => UserRestrictionHelper::TYPE_EMPRESA,
            'clientes' => UserRestrictionHelper::TYPE_CLIENTE,
            'vehiculos' => UserRestrictionHelper::TYPE_VEHICULO,
            'centros' => UserRestrictionHelper::TYPE_CENTRO,
            'departamentos' => UserRestrictionHelper::TYPE_DEPARTAMENTO,
        ];
        
        // Añadir nuevas restricciones
        foreach ($restrictions as $formType => $ids) {
            if (!empty($ids) && is_array($ids) && isset($typeMapping[$formType])) {
                $helperType = $typeMapping[$formType];
                foreach ($ids as $id) {
                    UserRestrictionHelper::addRestriction($user, $helperType, (int)$id);
                }
            }
        }
    }

    private function getTypeFromClass(string $class): ?string
    {
        return match($class) {
            Empresa::class => UserRestrictionHelper::TYPE_EMPRESA,
            Cliente::class => UserRestrictionHelper::TYPE_CLIENTE,
            Vehiculo::class => UserRestrictionHelper::TYPE_VEHICULO,
            Centro::class => UserRestrictionHelper::TYPE_CENTRO,
            Departamento::class => UserRestrictionHelper::TYPE_DEPARTAMENTO,
            default => null,
        };
    }
}
