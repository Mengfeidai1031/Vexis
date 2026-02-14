<?php

namespace App\Repositories;

use App\Models\Centro;
use App\Models\Empresa;
use App\Repositories\Interfaces\CentroRepositoryInterface;

class CentroRepository implements CentroRepositoryInterface
{
    public function all()
    {
        return Centro::with('empresa')
            ->orderBy('nombre', 'asc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        return Centro::with('empresa')
            ->where(function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%")
                    ->orWhere('direccion', 'like', "%{$searchTerm}%")
                    ->orWhere('provincia', 'like', "%{$searchTerm}%")
                    ->orWhere('municipio', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('empresa', function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%");
            })
            ->orderBy('nombre', 'asc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        return Centro::with('empresa')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Centro::create($data);
    }

    public function update(int $id, array $data)
    {
        $centro = Centro::findOrFail($id);
        $centro->update($data);
        return $centro;
    }

    public function delete(int $id)
    {
        $centro = Centro::findOrFail($id);
        return $centro->delete();
    }

    public function getEmpresas()
    {
        return Empresa::all();
    }
}