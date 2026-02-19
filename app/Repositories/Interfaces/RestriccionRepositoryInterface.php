<?php

namespace App\Repositories\Interfaces;

interface RestriccionRepositoryInterface
{
    public function all();
    public function search($searchTerm);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getUsers();
    public function getAvailableRestrictions();
    public function getUserRestrictions(int $userId);
    public function syncUserRestrictions(int $userId, array $restrictions);
}
