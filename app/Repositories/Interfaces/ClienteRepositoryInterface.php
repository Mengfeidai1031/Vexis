<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Cliente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ClienteRepositoryInterface
{
    public function all(): LengthAwarePaginator;

    public function search(string $searchTerm): LengthAwarePaginator;

    public function find(int $id): Cliente;

    public function create(array $data): Cliente;

    public function update(int $id, array $data): Cliente;

    public function delete(int $id): bool;

    public function getEmpresas(): Collection;
}
