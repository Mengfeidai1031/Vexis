<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\UserRestrictionHelper;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\OfertaCabecera;
use App\Models\Vehiculo;
use App\Repositories\Interfaces\OfertaRepositoryInterface;
use App\Traits\FiltersByEmpresa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class OfertaRepository implements OfertaRepositoryInterface
{
    use FiltersByEmpresa;

    public function all(): LengthAwarePaginator
    {
        $query = OfertaCabecera::with(['cliente.empresa', 'vehiculo', 'lineas']);
        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_CLIENTE, 'cliente_id', 'cliente');

        return $query->orderByDesc('fecha')->paginate(10);
    }

    public function search(string $searchTerm): LengthAwarePaginator
    {
        $query = OfertaCabecera::with(['cliente.empresa', 'vehiculo', 'lineas'])
            ->where(function ($q) use ($searchTerm) {
                $q->whereHas('cliente', function ($qq) use ($searchTerm) {
                    $qq->where('nombre', 'like', "%{$searchTerm}%")
                        ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                        ->orWhere('dni', 'like', "%{$searchTerm}%");
                })->orWhereHas('vehiculo', function ($qq) use ($searchTerm) {
                    $qq->where('modelo', 'like', "%{$searchTerm}%")
                        ->orWhere('chasis', 'like', "%{$searchTerm}%");
                });
            });

        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_CLIENTE, 'cliente_id', 'cliente');

        return $query->orderByDesc('fecha')->paginate(10);
    }

    public function find(int $id): OfertaCabecera
    {
        $oferta = OfertaCabecera::with(['cliente', 'vehiculo', 'lineas'])->findOrFail($id);

        if (! $this->canAccessEmpresa($oferta->cliente->empresa_id) || ! $this->canAccessCliente($oferta->cliente_id)) {
            throw new ModelNotFoundException('Oferta no encontrada o no tienes permiso para acceder.');
        }

        return $oferta;
    }

    public function delete(int $id): bool
    {
        $oferta = OfertaCabecera::findOrFail($id);

        if (! $this->canAccessEmpresa($oferta->cliente->empresa_id) || ! $this->canAccessCliente($oferta->cliente_id)) {
            throw new ModelNotFoundException('Oferta no encontrada o no tienes permiso para acceder.');
        }

        return (bool) $oferta->delete();
    }

    public function getClientes(): Collection
    {
        $query = Cliente::orderBy('apellidos');
        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_CLIENTE, 'id');

        return $query->get();
    }

    public function getVehiculos(): Collection
    {
        $query = Vehiculo::orderBy('modelo');
        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_VEHICULO, 'id');

        return $query->get();
    }

    public function getEmpresas(): Collection
    {
        $user = $this->getAuthUser();

        if (! $user || ! UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
            return Empresa::orderBy('nombre')->get();
        }

        $allowedEmpresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);

        return Empresa::whereIn('id', $allowedEmpresaIds)->orderBy('nombre')->get();
    }

    public function filter(array $filters): LengthAwarePaginator
    {
        $query = OfertaCabecera::with(['cliente.empresa', 'vehiculo', 'lineas']);
        $this->applyRestrictionPriority($query, UserRestrictionHelper::TYPE_CLIENTE, 'cliente_id', 'cliente');

        if (! empty($filters['id'])) {
            $query->where('id', (int) $filters['id']);
        }

        if (! empty($filters['fecha_desde'])) {
            $query->whereDate('fecha', '>=', $filters['fecha_desde']);
        }

        if (! empty($filters['fecha_hasta'])) {
            $query->whereDate('fecha', '<=', $filters['fecha_hasta']);
        }

        if (! empty($filters['cliente_id'])) {
            $query->where('cliente_id', $filters['cliente_id']);
        }

        if (! empty($filters['vehiculo_id'])) {
            $query->where('vehiculo_id', $filters['vehiculo_id']);
        }

        if (! empty($filters['empresa_id'])) {
            $user = $this->getAuthUser();
            $empresaId = (int) $filters['empresa_id'];
            $canFilter = ! $user
                || ! UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)
                || UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $empresaId);

            if ($canFilter) {
                $query->whereHas('cliente', function ($q) use ($empresaId) {
                    $q->where('empresa_id', $empresaId);
                });
            }
        }

        if (! empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('cliente', function ($qq) use ($searchTerm) {
                    $qq->where('nombre', 'like', "%{$searchTerm}%")
                        ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                        ->orWhere('dni', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })->orWhereHas('vehiculo', function ($qq) use ($searchTerm) {
                    $qq->where('modelo', 'like', "%{$searchTerm}%")
                        ->orWhere('version', 'like', "%{$searchTerm}%")
                        ->orWhere('chasis', 'like', "%{$searchTerm}%");
                })->orWhereHas('lineas', function ($qq) use ($searchTerm) {
                    $qq->where('descripcion', 'like', "%{$searchTerm}%")
                        ->orWhere('tipo', 'like', "%{$searchTerm}%");
                });
            });
        }

        return $query->orderByDesc('fecha')->paginate(10);
    }
}
