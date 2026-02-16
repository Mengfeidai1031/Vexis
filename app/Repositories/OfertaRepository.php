<?php

namespace App\Repositories;

use App\Models\OfertaCabecera;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Repositories\Interfaces\OfertaRepositoryInterface;

class OfertaRepository implements OfertaRepositoryInterface
{
    public function all()
    {
        return OfertaCabecera::with(['cliente', 'vehiculo', 'lineas'])
            ->orderBy('fecha', 'desc')
            ->paginate(10);
    }

    public function search($searchTerm)
    {
        return OfertaCabecera::with(['cliente', 'vehiculo', 'lineas'])
            ->where(function($query) use ($searchTerm) {
                $query->whereHas('cliente', function($q) use ($searchTerm) {
                    $q->where('nombre', 'like', "%{$searchTerm}%")
                      ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                      ->orWhere('dni', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('vehiculo', function($q) use ($searchTerm) {
                    $q->where('modelo', 'like', "%{$searchTerm}%")
                      ->orWhere('chasis', 'like', "%{$searchTerm}%");
                });
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10);
    }

    public function find(int $id)
    {
        return OfertaCabecera::with(['cliente', 'vehiculo', 'lineas'])->findOrFail($id);
    }

    public function delete(int $id)
    {
        $oferta = OfertaCabecera::findOrFail($id);
        return $oferta->delete();
    }

    public function getClientes()
    {
        return Cliente::orderBy('apellidos', 'asc')->get();
    }

    public function getVehiculos()
    {
        return Vehiculo::orderBy('modelo', 'asc')->get();
    }
}