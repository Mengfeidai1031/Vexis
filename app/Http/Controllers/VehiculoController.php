<?php

namespace App\Http\Controllers;

use App\Exports\VehiculosExport;
use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Models\Vehiculo;
use App\Repositories\Interfaces\VehiculoRepositoryInterface;
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
        return view('vehiculos.create', compact('empresas'));
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
        return view('vehiculos.edit', compact('vehiculo', 'empresas'));
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
        
        $query = \App\Models\Vehiculo::with('empresa');
        
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
