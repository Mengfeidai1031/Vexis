<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Setting;
use App\Models\Verifactu;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Centro;
use App\Models\Marca;
use App\Services\AeatVerifactuService;
use App\Exports\FacturasExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with(['venta', 'cliente', 'empresa', 'centro', 'marca', 'emisor']);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('marca_id')) $query->where('marca_id', $request->marca_id);
        if ($request->filled('cliente_id')) $query->where('cliente_id', $request->cliente_id);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('fecha_desde')) $query->whereDate('fecha_factura', '>=', $request->fecha_desde);
        if ($request->filled('fecha_hasta')) $query->whereDate('fecha_factura', '<=', $request->fecha_hasta);
        $facturas = $query->orderByDesc('fecha_factura')->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        return view('facturas.index', compact('facturas', 'marcas', 'clientes', 'empresas'));
    }

    public function create(Request $request)
    {
        $ventas = Venta::with(['vehiculo', 'cliente', 'conceptos'])->orderByDesc('fecha_venta')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $ventaPreseleccionada = $request->venta_id ? Venta::with(['cliente', 'empresa', 'centro', 'marca', 'conceptos'])->find($request->venta_id) : null;
        return view('facturas.create', compact('ventas', 'clientes', 'empresas', 'centros', 'marcas', 'ventaPreseleccionada'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'fecha_factura' => 'required|date',
            'subtotal' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        // Get amounts from the venta (server-side, not from form)
        $venta = Venta::findOrFail($request->venta_id);
        $subtotal = (float) ($venta->subtotal ?? $venta->precio_final);
        $ivaPct = (float) ($venta->impuesto_porcentaje ?? $request->iva_porcentaje);
        $ivaImporte = round($subtotal * $ivaPct / 100, 2);
        $total = round($subtotal + $ivaImporte, 2);

        $codigo = 'FAC-' . date('Ym') . '-' . str_pad(Factura::whereYear('fecha_factura', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        $factura = Factura::create([
            ...$request->except(['_token', '_method']),
            'codigo_factura' => $codigo,
            'subtotal' => $subtotal,
            'iva_porcentaje' => $ivaPct,
            'iva_importe' => $ivaImporte,
            'total' => $total,
            'emisor_id' => Auth::id(),
        ]);

        // Auto-register in Verifactu if module is enabled
        $verifactuMsg = '';
        if (Setting::get('modulo_verifactu', true)) {
            try {
                $factura->load(['empresa', 'cliente']);
                $service = new AeatVerifactuService();
                $registro = $service->registrarFactura($factura, 'alta');
                $verifactuMsg = " Registro Verifactu {$registro->codigo_registro} generado automáticamente.";
            } catch (\Exception $e) {
                $verifactuMsg = ' (Error al registrar en Verifactu: ' . $e->getMessage() . ')';
            }
        }

        return redirect()->route('facturas.index')->with('success', 'Factura creada correctamente.' . $verifactuMsg);
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

    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'fecha_factura' => 'required|date',
            'estado' => 'required|in:emitida,pagada,vencida,anulada',
        ]);

        // Sync amounts from venta
        $venta = Venta::findOrFail($request->venta_id);
        $subtotal = (float) ($venta->subtotal ?? $venta->precio_final);
        $ivaPct = (float) ($venta->impuesto_porcentaje ?? 7);
        $ivaImporte = round($subtotal * $ivaPct / 100, 2);
        $total = round($subtotal + $ivaImporte, 2);

        $estadoAnterior = $factura->estado;

        $factura->update([
            ...$request->except(['_token', '_method', 'subtotal', 'iva_porcentaje']),
            'subtotal' => $subtotal,
            'iva_porcentaje' => $ivaPct,
            'iva_importe' => $ivaImporte,
            'total' => $total,
        ]);

        // Auto-register anulación in Verifactu when factura changes to anulada
        $verifactuMsg = '';
        if ($request->estado === 'anulada' && $estadoAnterior !== 'anulada' && Setting::get('modulo_verifactu', true)) {
            try {
                $factura->load(['empresa', 'cliente']);
                $service = new AeatVerifactuService();
                $registro = $service->registrarFactura($factura, 'anulacion');
                $verifactuMsg = " Registro de anulación Verifactu {$registro->codigo_registro} generado.";
            } catch (\Exception $e) {
                $verifactuMsg = ' (Error Verifactu: ' . $e->getMessage() . ')';
            }
        }

        return redirect()->route('facturas.index')->with('success', 'Factura actualizada correctamente.' . $verifactuMsg);
    }

    public function destroy(Factura $factura)
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

        // Get Verifactu registro and QR code if exists
        $verifactuRegistro = Verifactu::where('factura_id', $factura->id)
            ->whereNotIn('estado', ['anulado', 'rechazado'])
            ->first();
        $qrBase64 = null;
        if ($verifactuRegistro && $verifactuRegistro->url_qr) {
            $qrBase64 = AeatVerifactuService::generateQrImage($verifactuRegistro->url_qr);
        }

        $pdf = Pdf::loadView('facturas.factura-pdf', compact('factura', 'logoMarca', 'qrBase64', 'verifactuRegistro'))
            ->setPaper('a4', 'portrait');

        $fileName = 'factura_' . $factura->codigo_factura . '.pdf';
        $storagePath = 'facturas/' . $fileName;
        Storage::disk('public')->put($storagePath, $pdf->output());

        $factura->update(['pdf_path' => $storagePath]);

        return $pdf->download($fileName);
    }

    public function export()
    {
        $fileName = 'facturas_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new FacturasExport(), $fileName);
    }

    public function exportPdf()
    {
        $facturas = Factura::with(['venta', 'cliente', 'empresa', 'marca'])->orderByDesc('fecha_factura')->get();
        $pdf = Pdf::loadView('facturas.pdf', compact('facturas'));
        return $pdf->download('facturas_' . date('Y-m-d_His') . '.pdf');
    }
}
