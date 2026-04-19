<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\UserRestrictionHelper;
use App\Models\Empresa;
use App\Models\Vehiculo;
use App\Repositories\Interfaces\VehiculoRepositoryInterface;
use App\Traits\FiltersByEmpresa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class VehiculoRepository implements VehiculoRepositoryInterface
{
    use FiltersByEmpresa;

    public function all(): LengthAwarePaginator
    {
        $query = Vehiculo::with(['empresa', 'marca']);
        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_VEHICULO, 'id');

        return $query->orderBy('modelo')->paginate(10);
    }

    public function search(string $searchTerm): LengthAwarePaginator
    {
        $query = Vehiculo::with(['empresa', 'marca'])
            ->where(function ($q) use ($searchTerm) {
                $q->where('chasis', 'like', "%{$searchTerm}%")
                    ->orWhere('matricula', 'like', "%{$searchTerm}%")
                    ->orWhere('modelo', 'like', "%{$searchTerm}%")
                    ->orWhere('version', 'like', "%{$searchTerm}%")
                    ->orWhere('color_externo', 'like', "%{$searchTerm}%")
                    ->orWhere('color_interno', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('empresa', function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', "%{$searchTerm}%");
            });

        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_VEHICULO, 'id');

        return $query->orderBy('modelo')->paginate(10);
    }

    public function find(int $id): Vehiculo
    {
        $vehiculo = Vehiculo::with(['empresa', 'marca'])->findOrFail($id);

        if (! $this->canAccessEmpresa($vehiculo->empresa_id) || ! $this->canAccessVehiculo($vehiculo->id)) {
            throw new ModelNotFoundException('Vehículo no encontrado o no tienes permiso para acceder.');
        }

        return $vehiculo;
    }

    public function create(array $data): Vehiculo
    {
        $this->assertCanAssignEmpresa(
            isset($data['empresa_id']) ? (int) $data['empresa_id'] : null,
            'No tienes permiso para crear vehículos en esta empresa.'
        );

        return Vehiculo::create($data);
    }

    public function update(int $id, array $data): Vehiculo
    {
        $vehiculo = Vehiculo::findOrFail($id);

        if (! $this->canAccessEmpresa($vehiculo->empresa_id) || ! $this->canAccessVehiculo($vehiculo->id)) {
            throw new ModelNotFoundException('Vehículo no encontrado o no tienes permiso para acceder.');
        }

        if (array_key_exists('empresa_id', $data)) {
            $this->assertCanAssignEmpresa(
                (int) $data['empresa_id'],
                'No tienes permiso para asignar vehículos a esta empresa.'
            );
        }

        $vehiculo->update($data);

        return $vehiculo;
    }

    public function delete(int $id): bool
    {
        $vehiculo = Vehiculo::findOrFail($id);

        if (! $this->canAccessEmpresa($vehiculo->empresa_id) || ! $this->canAccessVehiculo($vehiculo->id)) {
            throw new ModelNotFoundException('Vehículo no encontrado o no tienes permiso para acceder.');
        }

        return (bool) $vehiculo->delete();
    }

    public function getEmpresas(): Collection
    {
        $user = $this->getAuthUser();

        if (! $user || ! UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            return Empresa::all();
        }

        $allowedEmpresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);

        return Empresa::whereIn('id', $allowedEmpresaIds)->get();
    }
}
