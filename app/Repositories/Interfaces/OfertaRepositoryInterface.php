<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\OfertaCabecera;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OfertaRepositoryInterface
{
    public function all(): LengthAwarePaginator;

    public function search(string $searchTerm): LengthAwarePaginator;

    public function find(int $id): OfertaCabecera;

    public function delete(int $id): bool;

    public function getClientes(): Collection;

    public function getVehiculos(): Collection;

    public function getEmpresas(): Collection;

    public function filter(array $filters): LengthAwarePaginator;
}
