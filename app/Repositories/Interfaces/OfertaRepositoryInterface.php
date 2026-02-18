<?php

namespace App\Repositories\Interfaces;

interface OfertaRepositoryInterface
{
    public function all();
    public function search($searchTerm);
    public function find(int $id);
    public function delete(int $id);
    public function getClientes();
    public function getVehiculos();
    public function getEmpresas();
    public function filter(array $filters);
}