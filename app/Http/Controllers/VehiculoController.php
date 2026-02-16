<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Repositories\Interfaces\VehiculoRepositoryInterface;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    protected $vehiculoRepository;

    public function __construct(VehiculoRepositoryInterface $vehiculoRepository)
    {
        $this->vehiculoRepository = $vehiculoRepository;
    }

    public function index(Request $request)
    {
        if ($request->has('search') && !empty($request->search)) {
            $vehiculos = $this->vehiculoRepository->search($request->search);
        } else {
            $vehiculos = $this->vehiculoRepository->all();
        }

        return view('vehiculos.index', compact('vehiculos'));
    }

    public function create()
    {
        $empresas = $this->vehiculoRepository->getEmpresas();
        return view('vehiculos.create', compact('empresas'));
    }

    public function store(StoreVehiculoRequest $request)
    {
        $this->vehiculoRepository->create($request->validated());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo creado exitosamente.');
    }

    public function show(int $id)
    {
        $vehiculo = $this->vehiculoRepository->find($id);
        return view('vehiculos.show', compact('vehiculo'));
    }

    public function edit(int $id)
    {
        $vehiculo = $this->vehiculoRepository->find($id);
        $empresas = $this->vehiculoRepository->getEmpresas();
        return view('vehiculos.edit', compact('vehiculo', 'empresas'));
    }

    public function update(UpdateVehiculoRequest $request, int $id)
    {
        $this->vehiculoRepository->update($id, $request->validated());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    public function destroy(int $id)
    {
        try {
            $this->vehiculoRepository->delete($id);
            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('vehiculos.index')
                ->with('error', 'No se puede eliminar el vehículo porque tiene ofertas asociadas.');
        }
    }
}