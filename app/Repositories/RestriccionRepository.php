<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\UserRestrictionHelper;
use App\Models\Centro;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\User;
use App\Models\UserRestriction;
use App\Models\Vehiculo;
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
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('user', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', "%{$searchTerm}%")
                        ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHasMorph('restrictable', [Empresa::class, Cliente::class, Vehiculo::class, Centro::class, Departamento::class], function ($q, $type) use ($searchTerm) {
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
        $centros = Centro::with('empresa')->orderBy('nombre', 'asc')->get();
        $departamentos = Departamento::orderBy('nombre', 'asc')->get();

        return [
            'empresas' => $empresas,
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

        UserRestrictionHelper::removeAllRestrictions($user);

        $typeMapping = [
            'empresas' => UserRestrictionHelper::TYPE_EMPRESA,
            'centros' => UserRestrictionHelper::TYPE_CENTRO,
            'departamentos' => UserRestrictionHelper::TYPE_DEPARTAMENTO,
        ];

        foreach ($restrictions as $formType => $ids) {
            if (! empty($ids) && is_array($ids) && isset($typeMapping[$formType])) {
                $helperType = $typeMapping[$formType];
                foreach ($ids as $id) {
                    UserRestrictionHelper::addRestriction($user, $helperType, (int) $id);
                }
            }
        }
    }

    private function getTypeFromClass(string $class): ?string
    {
        return match ($class) {
            Empresa::class => UserRestrictionHelper::TYPE_EMPRESA,
            Centro::class => UserRestrictionHelper::TYPE_CENTRO,
            Departamento::class => UserRestrictionHelper::TYPE_DEPARTAMENTO,
            default => null,
        };
    }
}
