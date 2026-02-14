<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface
{
    public function all();
    public function search($searchTerm);
    public function find(int $id);
    public function create(array $data, array $permissions);
    public function update(int $id, array $data, array $permissions);
    public function delete(int $id);
    public function getAllPermissions();
}