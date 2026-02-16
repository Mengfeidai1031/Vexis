<?php

namespace App\Repositories;

use App\Models\Vehiculo;
use App\Models\Empresa;
use App\Repositories\Interfaces\VehiculoRepositoryInterface;

class VehiculoRepository implements VehiculoRepositoryInterface
{
    public function all()
    {
        return Vehiculo::with('empresa')
            ->orderBy('modelo', 'asc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        return Vehiculo::with('empresa')
            ->where(function($query) use ($searchTerm) {
                $query->where('chasis', 'like', "%{$searchTerm}%")
                    ->orWhere('modelo', 'like', "%{$searchTerm}%")
                    ->orWhere('version', 'like', "%{$searchTerm}%")
                    ->orWhere('color_externo', 'like', "%{$searchTerm}%")
                    ->orWhere('color_interno', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('empresa', function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%");
            })
            ->orderBy('modelo', 'asc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        return Vehiculo::with('empresa')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Vehiculo::create($data);
    }

    public function update(int $id, array $data)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update($data);
        return $vehiculo;
    }

    public function delete(int $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        return $vehiculo->delete();
    }

    public function getEmpresas()
    {
        return Empresa::all();
    }
}