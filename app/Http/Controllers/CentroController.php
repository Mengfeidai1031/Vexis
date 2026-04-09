<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCentroRequest;
use App\Http\Requests\UpdateCentroRequest;
use App\Models\Centro;
use App\Models\Empresa;
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
        $query = Centro::with('empresa');

        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('municipio')) $query->where('municipio', $request->municipio);
        if ($request->filled('provincia')) $query->where('provincia', $request->provincia);
        if ($request->filled('nombre')) $query->where('nombre', $request->nombre);
        if ($request->filled('direccion')) $query->where('direccion', $request->direccion);

        // Sorting
        $sortable = ['id', 'nombre', 'empresa_id', 'direccion', 'municipio', 'provincia'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $centros = $query->paginate(15)->withQueryString();
        $empresas = Empresa::orderBy('nombre')->get();
        $municipios = Centro::whereNotNull('municipio')->distinct()->orderBy('municipio')->pluck('municipio');
        $provincias = Centro::whereNotNull('provincia')->distinct()->orderBy('provincia')->pluck('provincia');
        $nombres_centros = Centro::distinct()->orderBy('nombre')->pluck('nombre');
        $direcciones_centros = Centro::whereNotNull('direccion')->distinct()->orderBy('direccion')->pluck('direccion');

        return view('centros.index', compact('centros', 'empresas', 'municipios', 'provincias', 'nombres_centros', 'direcciones_centros'));
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