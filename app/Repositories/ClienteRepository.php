<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\UserRestrictionHelper;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Repositories\Interfaces\ClienteRepositoryInterface;
use App\Traits\FiltersByEmpresa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ClienteRepository implements ClienteRepositoryInterface
{
    use FiltersByEmpresa;

    public function all(): LengthAwarePaginator
    {
        $query = Cliente::with('empresa');
        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_CLIENTE, 'id');

        return $query->orderBy('apellidos')->paginate(10);
    }

    public function search(string $searchTerm): LengthAwarePaginator
    {
        $query = Cliente::with('empresa')
            ->where(function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', "%{$searchTerm}%")
                    ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                    ->orWhere('dni', 'like', "%{$searchTerm}%")
                    ->orWhere('domicilio', 'like', "%{$searchTerm}%")
                    ->orWhere('codigo_postal', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('empresa', function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', "%{$searchTerm}%");
            });

        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_CLIENTE, 'id');

        return $query->orderBy('apellidos')->paginate(10);
    }

    public function find(int $id): Cliente
    {
        $cliente = Cliente::with('empresa')->findOrFail($id);

        if (! $this->canAccessEmpresa($cliente->empresa_id) || ! $this->canAccessCliente($cliente->id)) {
            throw new ModelNotFoundException('Cliente no encontrado o no tienes permiso para acceder.');
        }

        return $cliente;
    }

    public function create(array $data): Cliente
    {
        $this->assertCanAssignEmpresa(
            isset($data['empresa_id']) ? (int) $data['empresa_id'] : null,
            'No tienes permiso para crear clientes en esta empresa.'
        );

        return Cliente::create($data);
    }

    public function update(int $id, array $data): Cliente
    {
        $cliente = Cliente::findOrFail($id);

        if (! $this->canAccessEmpresa($cliente->empresa_id) || ! $this->canAccessCliente($cliente->id)) {
            throw new ModelNotFoundException('Cliente no encontrado o no tienes permiso para acceder.');
        }

        if (array_key_exists('empresa_id', $data)) {
            $this->assertCanAssignEmpresa(
                (int) $data['empresa_id'],
                'No tienes permiso para asignar clientes a esta empresa.'
            );
        }

        $cliente->update($data);

        return $cliente;
    }

    public function delete(int $id): bool
    {
        $cliente = Cliente::findOrFail($id);

        if (! $this->canAccessEmpresa($cliente->empresa_id) || ! $this->canAccessCliente($cliente->id)) {
            throw new ModelNotFoundException('Cliente no encontrado o no tienes permiso para acceder.');
        }

        return (bool) $cliente->delete();
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
