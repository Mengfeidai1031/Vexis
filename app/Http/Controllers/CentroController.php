<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCentroRequest;
use App\Http\Requests\UpdateCentroRequest;
use App\Models\Centro;
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
        $this->authorize('create', Centro::class);
        
        $empresas = $this->centroRepository->getEmpresas();
        return view('centros.create', compact('empresas'));
    }

    /**
     * Guardar nuevo centro
     */
    public function store(StoreCentroRequest $request)
    {
        $this->authorize('create', Centro::class);
        
        $this->centroRepository->create($request->validated());

        return redirect()->route('centros.index')
            ->with('success', 'Centro creado exitosamente.');
    }

    /**
     * Mostrar un centro específico
     */
    public function show(Centro $centro)
    {
        $this->authorize('view', $centro);
        
        return view('centros.show', compact('centro'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Centro $centro)
    {
        $this->authorize('update', $centro);
        
        $empresas = $this->centroRepository->getEmpresas();
        return view('centros.edit', compact('centro', 'empresas'));
    }

    /**
     * Actualizar centro
     */
    public function update(UpdateCentroRequest $request, Centro $centro)
    {
        $this->authorize('update', $centro);
        
        $this->centroRepository->update($centro->id, $request->validated());

        return redirect()->route('centros.index')
            ->with('success', 'Centro actualizado exitosamente.');
    }

    /**
     * Eliminar centro
     */
    public function destroy(Centro $centro)
    {
        $this->authorize('delete', $centro);
        
        try {
            $this->centroRepository->delete($centro->id);
            return redirect()->route('centros.index')
                ->with('success', 'Centro eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('centros.index')
                ->with('error', 'No se puede eliminar el centro porque tiene usuarios asociados.');
        }
    }
}