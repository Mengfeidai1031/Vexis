<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\FacturasExport;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Models\Centro;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\Marca;
use App\Models\Venta;
use App\Services\AeatVerifactuService;
use App\Services\FacturaCreationService;
use App\Services\ImpuestoService;
use App\Services\VerifactuRegistrationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class FacturaController extends Controller
{
    public function __construct(
        private readonly ImpuestoService $impuestoService,
        private readonly FacturaCreationService $facturaCreationService,
        private readonly VerifactuRegistrationService $verifactuRegistration,
    ) {}

    public function index(Request $request)
    {
        $query = Factura::with(['venta', 'cliente', 'empresa', 'centro', 'marca', 'emisor']);
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_factura', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_factura', '<=', $request->fecha_hasta);
        }
        if ($request->filled('concepto')) {
            $query->where('concepto', $request->concepto);
        }
        if ($request->filled('codigo_factura')) {
            $query->where('codigo_factura', $request->codigo_factura);
        }
        if ($request->filled('total_min')) {
            $query->where('total', '>=', $request->total_min);
        }
        if ($request->filled('total_max')) {
            $query->where('total', '<=', $request->total_max);
        }

        $sortable = ['id', 'codigo_factura', 'cliente_id', 'marca_id', 'concepto', 'subtotal', 'iva_importe', 'total', 'estado', 'fecha_factura'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $facturas = $query->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $codigos_factura = Factura::distinct()->orderBy('codigo_factura')->pluck('codigo_factura');
        $conceptos_factura = Factura::whereNotNull('concepto')->distinct()->orderBy('concepto')->pluck('concepto');

        return view('facturas.index', compact('facturas', 'marcas', 'clientes', 'empresas', 'codigos_factura', 'conceptos_factura'));
    }

    public function create(Request $request)
    {
        $ventas = Venta::with(['vehiculo', 'cliente', 'conceptos'])->orderByDesc('fecha_venta')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $ventaPreseleccionada = $request->venta_id
            ? Venta::with(['cliente', 'empresa', 'centro', 'marca', 'conceptos'])->find($request->venta_id)
            : null;

        return view('facturas.create', compact('ventas', 'clientes', 'empresas', 'centros', 'marcas', 'ventaPreseleccionada'));
    }

    public function store(StoreFacturaRequest $request): RedirectResponse
    {
        $venta = Venta::findOrFail($request->validated('venta_id'));
        $subtotal = (float) ($venta->subtotal ?? $venta->precio_final);
        $ivaPct = (float) ($venta->impuesto_porcentaje ?? $request->validated('iva_porcentaje'));
        $recalculo = $this->impuestoService->recalcularFactura($subtotal, $ivaPct);

        $result = $this->facturaCreationService->crearDesdeDatos([
            ...$request->only(['venta_id', 'cliente_id', 'empresa_id', 'centro_id', 'marca_id', 'fecha_factura', 'fecha_vencimiento', 'concepto', 'estado', 'observaciones']),
            'subtotal' => $subtotal,
            'iva_porcentaje' => $ivaPct,
            'iva_importe' => $recalculo['iva_importe'],
            'total' => $recalculo['total'],
        ]);

        return redirect()->route('facturas.index')
            ->with('success', 'Factura creada correctamente.'.$result['verifactu_msg']);
    }

    public function show(Factura $factura)
    {
        $factura->load(['venta.vehiculo', 'venta.conceptos', 'cliente', 'empresa', 'centro', 'marca', 'emisor']);

        return view('facturas.show', compact('factura'));
    }

    public function edit(Factura $factura)
    {
        $ventas = Venta::with(['vehiculo', 'cliente', 'conceptos'])->orderByDesc('fecha_venta')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();

        return view('facturas.edit', compact('factura', 'ventas', 'clientes', 'empresas', 'centros', 'marcas'));
    }

    public function update(UpdateFacturaRequest $request, Factura $factura): RedirectResponse
    {
        $venta = Venta::findOrFail($request->validated('venta_id'));
        $subtotal = (float) ($venta->subtotal ?? $venta->precio_final);
        $ivaPct = (float) ($venta->impuesto_porcentaje ?? 7);
        $recalculo = $this->impuestoService->recalcularFactura($subtotal, $ivaPct);

        $estadoAnterior = $factura->estado;

        $factura->update([
            ...$request->only(['venta_id', 'cliente_id', 'empresa_id', 'centro_id', 'marca_id', 'fecha_factura', 'fecha_vencimiento', 'concepto', 'estado', 'observaciones']),
            'subtotal' => $subtotal,
            'iva_porcentaje' => $ivaPct,
            'iva_importe' => $recalculo['iva_importe'],
            'total' => $recalculo['total'],
        ]);

        $verifactuMsg = '';
        if ($request->validated('estado') === 'anulada' && $estadoAnterior !== 'anulada') {
            $verifactuMsg = $this->verifactuRegistration->registrarAnulacion($factura);
        }

        return redirect()->route('facturas.index')
            ->with('success', 'Factura actualizada correctamente.'.$verifactuMsg);
    }

    public function destroy(Factura $factura): RedirectResponse
    {
        $factura->delete();

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada correctamente.');
    }

    public function generatePdf(Factura $factura)
    {
        $factura->load(['venta.vehiculo', 'venta.conceptos', 'cliente', 'empresa', 'centro', 'marca', 'emisor']);

        $logoMarca = null;
        if ($factura->marca) {
            $slug = Str::lower($factura->marca->nombre);
            $path = storage_path("app/public/logos/{$slug}.png");
            if (file_exists($path)) {
                $logoMarca = $path;
            }
        }

        $verifactuRegistro = $this->verifactuRegistration->registroActivoDeFactura($factura);
        $qrBase64 = null;
        if ($verifactuRegistro?->url_qr) {
            $qrBase64 = AeatVerifactuService::generateQrImage($verifactuRegistro->url_qr);
        }

        $pdf = Pdf::loadView('facturas.factura-pdf', compact('factura', 'logoMarca', 'qrBase64', 'verifactuRegistro'))
            ->setPaper('a4', 'portrait');

        $fileName = 'factura_'.$factura->codigo_factura.'.pdf';
        $storagePath = 'facturas/'.$fileName;
        Storage::disk('public')->put($storagePath, $pdf->output());
        $factura->update(['pdf_path' => $storagePath]);

        return $pdf->download($fileName);
    }

    public function export()
    {
        return Excel::download(new FacturasExport, 'facturas_'.date('Y-m-d_His').'.xlsx');
    }

    public function exportPdf()
    {
        $facturas = Factura::with(['venta', 'cliente', 'empresa', 'marca'])->orderByDesc('fecha_factura')->get();
        $pdf = Pdf::loadView('facturas.pdf', compact('facturas'));

        return $pdf->download('facturas_'.date('Y-m-d_His').'.pdf');
    }
}
