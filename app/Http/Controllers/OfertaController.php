<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OfertaCabecera;
use App\Models\Vehiculo;
use App\Repositories\Interfaces\OfertaRepositoryInterface;
use App\Services\OfertaPdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    protected $ofertaRepository;

    protected $pdfService;

    public function __construct(
        OfertaRepositoryInterface $ofertaRepository,
        OfertaPdfService $pdfService
    ) {
        $this->ofertaRepository = $ofertaRepository;
        $this->pdfService = $pdfService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', OfertaCabecera::class);

        // Recopilar todos los filtros
        $filters = [
            'id' => $request->input('id'),
            'fecha_desde' => $request->input('fecha_desde'),
            'fecha_hasta' => $request->input('fecha_hasta'),
            'cliente_id' => $request->input('cliente_id'),
            'vehiculo_id' => $request->input('vehiculo_id'),
            'empresa_id' => $request->input('empresa_id'),
            'search' => null,
        ];

        // Verificar si hay algún filtro activo
        $hasFilters = ! empty(array_filter($filters, function ($value) {
            return $value !== null && $value !== '';
        }));

        if ($hasFilters) {
            $ofertas = $this->ofertaRepository->filter($filters);
        } else {
            $ofertas = $this->ofertaRepository->all();
        }

        // Sorting
        $sortable = ['id', 'fecha', 'cliente_id', 'vehiculo_id'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $sorted = $ofertas->getCollection()->sortBy($request->sort_by, SORT_REGULAR, $dir === 'desc')->values();
            $ofertas->setCollection($sorted);
        }

        // Obtener datos para los selectores de filtros
        $clientes = $this->ofertaRepository->getClientes();
        $vehiculos = $this->ofertaRepository->getVehiculos();
        $empresas = $this->ofertaRepository->getEmpresas();

        return view('ofertas.index', compact('ofertas', 'clientes', 'vehiculos', 'empresas', 'filters'));
    }

    public function create()
    {
        $this->authorize('create', OfertaCabecera::class);

        return view('ofertas.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', OfertaCabecera::class);

        $request->validate([
            'marca' => 'required|in:nissan,renault_dacia',
            'pdf_file' => 'required|file|mimes:pdf|max:10240',
        ], [
            'marca.required' => 'Debe seleccionar una marca de vehículo.',
            'marca.in' => 'La marca seleccionada no es válida.',
            'pdf_file.required' => 'Debe seleccionar un archivo PDF.',
            'pdf_file.mimes' => 'El archivo debe ser un PDF.',
            'pdf_file.max' => 'El archivo no puede superar los 10MB.',
        ]);

        try {
            $marca = $request->input('marca');
            $oferta = $this->pdfService->procesarPdf($request->file('pdf_file'), $marca);

            $mensaje = 'Oferta procesada exitosamente. Se encontraron '.$oferta->lineas->count().' líneas.';
            if (! $oferta->vehiculo_id) {
                $mensaje .= ' (Documento informativo - sin vehículo registrado)';
            }

            return redirect()->route('ofertas.show', $oferta->id)
                ->with('success', $mensaje);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al procesar el PDF: '.$e->getMessage());
        }
    }

    public function show(OfertaCabecera $oferta)
    {
        $this->authorize('view', $oferta);

        return view('ofertas.show', compact('oferta'));
    }

    public function edit(OfertaCabecera $oferta)
    {
        $this->authorize('update', $oferta);

        $oferta->load('lineas');
        $clientes = Cliente::orderBy('nombre')->get();
        $vehiculos = Vehiculo::orderBy('modelo')->get();

        return view('ofertas.edit', compact('oferta', 'clientes', 'vehiculos'));
    }

    public function update(Request $request, OfertaCabecera $oferta)
    {
        $this->authorize('update', $oferta);

        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'vehiculo_id' => 'nullable|exists:vehiculos,id',
            'fecha' => 'required|date',
            'base_imponible' => 'nullable|numeric|min:0',
            'impuestos' => 'nullable|numeric|min:0',
            'total_sin_impuestos' => 'nullable|numeric|min:0',
            'total_con_impuestos' => 'nullable|numeric|min:0',
        ]);

        $oferta->update($request->only([
            'cliente_id', 'vehiculo_id', 'fecha',
            'base_imponible', 'impuestos', 'total_sin_impuestos', 'total_con_impuestos',
        ]));

        return redirect()->route('ofertas.show', $oferta)
            ->with('success', 'Oferta actualizada correctamente.');
    }

    public function presupuestoPdf(OfertaCabecera $oferta)
    {
        $this->authorize('view', $oferta);

        $oferta->load(['cliente', 'vehiculo.marca', 'lineas']);
        $pdf = Pdf::loadView('ofertas.presupuesto-pdf', compact('oferta'))->setPaper('a4', 'portrait');

        return $pdf->download('presupuesto_oferta_'.$oferta->id.'.pdf');
    }

    public function destroy(OfertaCabecera $oferta)
    {
        $this->authorize('delete', $oferta);

        try {
            $this->pdfService->eliminarOferta($oferta);

            return redirect()->route('ofertas.index')
                ->with('success', 'Oferta eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('ofertas.index')
                ->with('error', 'Error al eliminar la oferta: '.$e->getMessage());
        }
    }
}
