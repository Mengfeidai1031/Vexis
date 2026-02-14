<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCentroRequest;
use App\Http\Requests\UpdateCentroRequest;
use App\Repositories\Interfaces\CentroRepositoryInterface;
use Illuminate\Http\Request;

class CentroController extends Controller
{
    protected $centroRepository;

    public function __construct(CentroRepositoryInterface $centroRepository)
    {
        $this->centroRepository = $centroRepository;
    }

    /**
     * Mostrar la lista de centros
     */
    public function index(Request $request)
    {
        if ($request->has('search') && !empty($request->search)) {
            $centros = $this->centroRepository->search($request->search);
        } else {
            $centros = $this->centroRepository->all();
        }

        return view('centros.index', compact('centros'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $empresas = $this->centroRepository->getEmpresas();
        return view('centros.create', compact('empresas'));
    }

    /**
     * Guardar nuevo centro
     */
    public function store(StoreCentroRequest $request)
    {
        $this->centroRepository->create($request->validated());

        return redirect()->route('centros.index')
            ->with('success', 'Centro creado exitosamente.');
    }

    /**
     * Mostrar un centro específico
     */
    public function show(int $id)
    {
        $centro = $this->centroRepository->find($id);
        return view('centros.show', compact('centro'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(int $id)
    {
        $centro = $this->centroRepository->find($id);
        $empresas = $this->centroRepository->getEmpresas();
        return view('centros.edit', compact('centro', 'empresas'));
    }

    /**
     * Actualizar centro
     */
    public function update(UpdateCentroRequest $request, int $id)
    {
        $this->centroRepository->update($id, $request->validated());

        return redirect()->route('centros.index')
            ->with('success', 'Centro actualizado exitosamente.');
    }

    /**
     * Eliminar centro
     */
    public function destroy(int $id)
    {
        try {
            $this->centroRepository->delete($id);
            return redirect()->route('centros.index')
                ->with('success', 'Centro eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('centros.index')
                ->with('error', 'No se puede eliminar el centro porque tiene usuarios asociados.');
        }
    }
}