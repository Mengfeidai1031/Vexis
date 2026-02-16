<?php

namespace App\Repositories;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Repositories\Interfaces\ClienteRepositoryInterface;

class ClienteRepository implements ClienteRepositoryInterface
{
    public function all()
    {
        return Cliente::with('empresa')
            ->orderBy('apellidos', 'asc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        return Cliente::with('empresa')
            ->where(function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%")
                    ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                    ->orWhere('dni', 'like', "%{$searchTerm}%")
                    ->orWhere('domicilio', 'like', "%{$searchTerm}%")
                    ->orWhere('codigo_postal', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('empresa', function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%");
            })
            ->orderBy('apellidos', 'asc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        return Cliente::with('empresa')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Cliente::create($data);
    }

    public function update(int $id, array $data)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($data);
        return $cliente;
    }

    public function delete(int $id)
    {
        $cliente = Cliente::findOrFail($id);
        return $cliente->delete();
    }

    public function getEmpresas()
    {
        return Empresa::all();
    }
}