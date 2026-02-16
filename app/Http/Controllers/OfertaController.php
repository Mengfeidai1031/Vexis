<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfertaRequest;
use App\Repositories\Interfaces\OfertaRepositoryInterface;
use App\Services\OfertaPdfService;
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
        if ($request->has('search') && !empty($request->search)) {
            $ofertas = $this->ofertaRepository->search($request->search);
        } else {
            $ofertas = $this->ofertaRepository->all();
        }

        return view('ofertas.index', compact('ofertas'));
    }

    public function create()
    {
        $clientes = $this->ofertaRepository->getClientes();
        $vehiculos = $this->ofertaRepository->getVehiculos();
        return view('ofertas.create', compact('clientes', 'vehiculos'));
    }

    public function store(StoreOfertaRequest $request)
    {
        try {
            $oferta = $this->pdfService->procesarPdf(
                $request->file('pdf_file'),
                $request->cliente_id,
                $request->vehiculo_id
            );

            return redirect()->route('ofertas.show', $oferta->id)
                ->with('success', 'Oferta procesada exitosamente. Se encontraron ' . $oferta->lineas->count() . ' líneas.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al procesar el PDF: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $oferta = $this->ofertaRepository->find($id);
        return view('ofertas.show', compact('oferta'));
    }

    public function destroy(int $id)
    {
        try {
            $oferta = $this->ofertaRepository->find($id);
            $this->pdfService->eliminarOferta($oferta);
            
            return redirect()->route('ofertas.index')
                ->with('success', 'Oferta eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('ofertas.index')
                ->with('error', 'Error al eliminar la oferta: ' . $e->getMessage());
        }
    }
}