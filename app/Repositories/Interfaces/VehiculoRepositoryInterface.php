<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Vehiculo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface VehiculoRepositoryInterface
{
    public function all(): LengthAwarePaginator;

    public function search(string $searchTerm): LengthAwarePaginator;

    public function find(int $id): Vehiculo;

    public function create(array $data): Vehiculo;

    public function update(int $id, array $data): Vehiculo;

    public function delete(int $id): bool;

    public function getEmpresas(): Collection;
}
