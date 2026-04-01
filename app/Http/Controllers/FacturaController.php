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
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('codigo_factura', 'like', "%$s%")
                  ->orWhereHas('cliente', fn($q2) => $q2->where('nombre', 'like', "%$s%")->orWhere('apellidos', 'like', "%$s%"));
            });
        }
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('marca_id')) $query->where('marca_id', $request->marca_id);
        $facturas = $query->orderByDesc('fecha_factura')->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('facturas.index', compact('facturas', 'marcas'));
    }

    public function create(Request $request)
    {
        $ventas = Venta::with(['vehiculo', 'cliente'])->orderByDesc('fecha_venta')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $ventaPreseleccionada = $request->venta_id ? Venta::with(['cliente', 'empresa', 'centro', 'marca'])->find($request->venta_id) : null;
        return view('facturas.create', compact('ventas', 'clientes', 'empresas', 'centros', 'marcas', 'ventaPreseleccionada'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'fecha_factura' => 'required|date',
            'subtotal' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        $subtotal = (float) $request->subtotal;
        $ivaPct = (float) $request->iva_porcentaje;
        $ivaImporte = round($subtotal * $ivaPct / 100, 2);
        $total = round($subtotal + $ivaImporte, 2);

        $codigo = 'FAC-' . date('Ym') . '-' . str_pad(Factura::whereYear('fecha_factura', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        $factura = Factura::create([
            ...$request->except(['_token', '_method']),
            'codigo_factura' => $codigo,
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
        $factura->load(['venta.vehiculo', 'cliente', 'empresa', 'centro', 'marca', 'emisor']);
        return view('facturas.show', compact('factura'));
    }

    public function edit(Factura $factura)
    {
        $ventas = Venta::with(['vehiculo', 'cliente'])->orderByDesc('fecha_venta')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('facturas.edit', compact('factura', 'ventas', 'clientes', 'empresas', 'centros', 'marcas'));
    }

    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'fecha_factura' => 'required|date',
            'subtotal' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
            'estado' => 'required|in:emitida,pagada,vencida,anulada',
        ]);

        $subtotal = (float) $request->subtotal;
        $ivaPct = (float) $request->iva_porcentaje;
        $ivaImporte = round($subtotal * $ivaPct / 100, 2);
        $total = round($subtotal + $ivaImporte, 2);

        $estadoAnterior = $factura->estado;

        $factura->update([
            ...$request->except(['_token', '_method']),
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
        $factura->load(['venta.vehiculo', 'cliente', 'empresa', 'centro', 'marca', 'emisor']);

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
