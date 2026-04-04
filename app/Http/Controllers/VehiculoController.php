<?php

namespace App\Http\Controllers;

use App\Exports\VehiculosExport;
use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Models\CatalogoPrecio;
use App\Models\Centro;
use App\Models\Marca;
use App\Models\Empresa;
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
        $query = Vehiculo::with(['marca', 'empresa']);

        if ($request->filled('marca_id')) $query->where('marca_id', $request->marca_id);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('modelo')) $query->where('modelo', $request->modelo);
        if ($request->filled('version')) $query->where('version', $request->version);
        if ($request->filled('color_externo')) $query->where('color_externo', $request->color_externo);
        if ($request->filled('color_interno')) $query->where('color_interno', $request->color_interno);

        // Sorting
        $sortable = ['id', 'chasis', 'matricula', 'marca_id', 'modelo', 'version', 'color_externo', 'color_interno', 'empresa_id'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $vehiculos = $query->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $modelos = Vehiculo::whereNotNull('modelo')->distinct()->orderBy('modelo')->pluck('modelo');
        $versiones = Vehiculo::whereNotNull('version')->distinct()->orderBy('version')->pluck('version');
        $colores_ext = Vehiculo::whereNotNull('color_externo')->where('color_externo', '!=', '')->distinct()->orderBy('color_externo')->pluck('color_externo');
        $colores_int = Vehiculo::whereNotNull('color_interno')->where('color_interno', '!=', '')->distinct()->orderBy('color_interno')->pluck('color_interno');

        return view('vehiculos.index', compact('vehiculos', 'marcas', 'empresas', 'modelos', 'versiones', 'colores_ext', 'colores_int'));
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
