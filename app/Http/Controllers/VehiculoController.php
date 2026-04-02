<?php

namespace App\Http\Controllers;

use App\Exports\VehiculosExport;
use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Models\CatalogoPrecio;
use App\Models\Centro;
use App\Models\Marca;
use App\Models\Vehiculo;
use App\Repositories\Interfaces\VehiculoRepositoryInterface;
use App\Services\MatriculaService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $this->authorize('create', Vehiculo::class);

        $empresas = $this->vehiculoRepository->getEmpresas();
        $marcas = Marca::where('activa', true)->orderByRaw("FIELD(LOWER(nombre), 'renault', 'dacia', 'nissan') ASC, nombre ASC")->get();
        $centros = Centro::orderBy('nombre')->get();
        $catalogoModelos = CatalogoPrecio::where('disponible', true)
            ->select('marca_id', 'modelo', 'version')
            ->orderBy('modelo')->orderBy('version')
            ->get()
            ->groupBy('marca_id');
        return view('vehiculos.create', compact('empresas', 'marcas', 'centros', 'catalogoModelos'));
    }

    public function store(StoreVehiculoRequest $request)
    {
        $this->authorize('create', Vehiculo::class);
        
        $this->vehiculoRepository->create($request->validated());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo creado exitosamente.');
    }

    public function show(Vehiculo $vehiculo)
    {
        $this->authorize('view', $vehiculo);
        
        return view('vehiculos.show', compact('vehiculo'));
    }

    public function edit(Vehiculo $vehiculo)
    {
        $this->authorize('update', $vehiculo);

        $empresas = $this->vehiculoRepository->getEmpresas();
        $marcas = Marca::where('activa', true)->orderByRaw("FIELD(LOWER(nombre), 'renault', 'dacia', 'nissan') ASC, nombre ASC")->get();
        $centros = Centro::orderBy('nombre')->get();
        $catalogoModelos = CatalogoPrecio::where('disponible', true)
            ->select('marca_id', 'modelo', 'version')
            ->orderBy('modelo')->orderBy('version')
            ->get()
            ->groupBy('marca_id');
        return view('vehiculos.edit', compact('vehiculo', 'empresas', 'marcas', 'centros', 'catalogoModelos'));
    }

    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo)
    {
        $this->authorize('update', $vehiculo);
        
        $this->vehiculoRepository->update($vehiculo->id, $request->validated());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        $this->authorize('delete', $vehiculo);
        
        try {
            $this->vehiculoRepository->delete($vehiculo->id);
            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('vehiculos.index')
                ->with('error', 'No se puede eliminar el vehículo porque tiene ofertas asociadas.');
        }
    }

    /**
     * Obtener modelos y versiones por marca (AJAX)
     */
    public function modelosPorMarca(Marca $marca)
    {
        $catalogo = CatalogoPrecio::where('marca_id', $marca->id)
            ->where('disponible', true)
            ->select('modelo', 'version')
            ->orderBy('modelo')->orderBy('version')
            ->get();

        $modelos = $catalogo->pluck('modelo')->unique()->values();
        $versiones = $catalogo->groupBy('modelo')->map(fn($items) => $items->pluck('version')->unique()->values());

        return response()->json(['modelos' => $modelos, 'versiones' => $versiones]);
    }

    /**
     * Generar siguiente matrícula disponible (AJAX)
     */
    public function generarMatricula()
    {
        $service = new MatriculaService();
        $matricula = $service->generarSiguiente();
        return response()->json(['matricula' => $matricula]);
    }

    /**
     * Exportar vehículos a Excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $fileName = 'vehiculos_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new VehiculosExport(), $fileName);
    }

    /**
     * Exportar vehículos a PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $userEmpresaId = \Illuminate\Support\Facades\Auth::user()?->empresa_id;
        
        $query = \App\Models\Vehiculo::with(['empresa', 'marca']);
        
        if ($userEmpresaId) {
            $query->where('empresa_id', $userEmpresaId);
        }
        
        $vehiculos = $query->orderBy('modelo', 'asc')
            ->orderBy('version', 'asc')
            ->get();
        
        $fileName = 'vehiculos_' . date('Y-m-d_His') . '.pdf';
        
        $pdf = Pdf::loadView('vehiculos.pdf', compact('vehiculos'));
        
        return $pdf->download($fileName);
    }
}
