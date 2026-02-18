<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartamentoRequest;
use App\Http\Requests\UpdateDepartamentoRequest;
use App\Models\Departamento;
use App\Repositories\Interfaces\DepartamentoRepositoryInterface;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    protected $departamentoRepository;

    public function __construct(DepartamentoRepositoryInterface $departamentoRepository)
    {
        $this->departamentoRepository = $departamentoRepository;
    }

    /**
     * Mostrar la lista de departamentos
     */
    public function index(Request $request)
    {
        if ($request->has('search') && !empty($request->search)) {
            $departamentos = $this->departamentoRepository->search($request->search);
        } else {
            $departamentos = $this->departamentoRepository->all();
        }

        return view('departamentos.index', compact('departamentos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->authorize('create', Departamento::class);
        
        return view('departamentos.create');
    }

    /**
     * Guardar nuevo departamento
     */
    public function store(StoreDepartamentoRequest $request)
    {
        $this->authorize('create', Departamento::class);
        
        $this->departamentoRepository->create($request->validated());

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento creado exitosamente.');
    }

    /**
     * Mostrar un departamento específico
     */
    public function show(Departamento $departamento)
    {
        $this->authorize('view', $departamento);
        
        return view('departamentos.show', compact('departamento'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Departamento $departamento)
    {
        $this->authorize('update', $departamento);
        
        return view('departamentos.edit', compact('departamento'));
    }

    /**
     * Actualizar departamento
     */
    public function update(UpdateDepartamentoRequest $request, Departamento $departamento)
    {
        $this->authorize('update', $departamento);
        
        $this->departamentoRepository->update($departamento->id, $request->validated());

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento actualizado exitosamente.');
    }

    /**
     * Eliminar departamento
     */
    public function destroy(Departamento $departamento)
    {
        $this->authorize('delete', $departamento);
        
        try {
            $this->departamentoRepository->delete($departamento->id);
            return redirect()->route('departamentos.index')
                ->with('success', 'Departamento eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('departamentos.index')
                ->with('error', 'No se puede eliminar el departamento porque tiene usuarios asociados.');
        }
    }
}