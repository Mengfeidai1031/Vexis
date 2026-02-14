<?php

namespace App\Repositories;

use App\Models\Departamento;
use App\Repositories\Interfaces\DepartamentoRepositoryInterface;

class DepartamentoRepository implements DepartamentoRepositoryInterface
{
    public function all()
    {
        return Departamento::orderBy('nombre', 'asc')->paginate(10);
    }

    public function search($searchTerm)
    {
        return Departamento::where('nombre', 'like', "%{$searchTerm}%")
            ->orWhere('abreviatura', 'like', "%{$searchTerm}%")
            ->orderBy('nombre', 'asc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        return Departamento::findOrFail($id);
    }

    public function create(array $data)
    {
        return Departamento::create($data);
    }

    public function update(int $id, array $data)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->update($data);
        return $departamento;
    }

    public function delete(int $id)
    {
        $departamento = Departamento::findOrFail($id);
        return $departamento->delete();
    }
}