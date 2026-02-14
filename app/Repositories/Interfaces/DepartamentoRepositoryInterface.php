<?php

namespace App\Repositories\Interfaces;

interface DepartamentoRepositoryInterface
{
    public function all();
    public function search($searchTerm);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}