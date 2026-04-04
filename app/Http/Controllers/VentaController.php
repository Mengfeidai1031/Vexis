<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaConcepto;
use App\Models\Vehiculo;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Centro;
use App\Models\Marca;
use App\Models\CatalogoPrecio;
use App\Models\Factura;
use App\Models\Setting;
use App\Services\AeatVerifactuService;
use App\Exports\VentasExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['vehiculo', 'cliente', 'empresa', 'marca', 'vendedor']);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('marca_id')) $query->where('marca_id', $request->marca_id);
        if ($request->filled('forma_pago')) $query->where('forma_pago', $request->forma_pago);
        if ($request->filled('cliente_id')) $query->where('cliente_id', $request->cliente_id);
        if ($request->filled('empresa_id')) $query->where('empresa_id', $request->empresa_id);
        if ($request->filled('fecha_desde')) $query->whereDate('fecha_venta', '>=', $request->fecha_desde);
        if ($request->filled('fecha_hasta')) $query->whereDate('fecha_venta', '<=', $request->fecha_hasta);
        if ($request->filled('vendedor_id')) $query->where('vendedor_id', $request->vendedor_id);
        // Sorting
        $sortable = ['id', 'codigo_venta', 'vehiculo_id', 'cliente_id', 'marca_id', 'precio_final', 'forma_pago', 'estado', 'fecha_venta'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $ventas = $query->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $vendedores = \App\Models\User::orderBy('nombre')->get();
        return view('ventas.index', compact('ventas', 'marcas', 'clientes', 'empresas', 'vendedores'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::with('marca')->orderBy('modelo')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $preciosCatalogo = CatalogoPrecio::select('marca_id', 'modelo', 'version', 'precio_base')->get()
            ->keyBy(fn($c) => $c->marca_id . '|' . $c->modelo . '|' . $c->version);
        return view('ventas.create', compact('vehiculos', 'clientes', 'empresas', 'centros', 'marcas', 'preciosCatalogo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'precio_venta' => 'required|numeric|min:0',
            'forma_pago' => 'required|in:contado,financiado,leasing,renting',
            'fecha_venta' => 'required|date',
            'conceptos.*.tipo' => 'required_with:conceptos|in:extra,descuento',
            'conceptos.*.descripcion' => 'required_with:conceptos|string|max:255',
            'conceptos.*.importe' => 'required_with:conceptos|numeric|min:0',
        ]);

        // Server-side tax calculation
        $empresa = Empresa::findOrFail($request->empresa_id);
        $cp = $empresa->codigo_postal ?? '';
        $esCanarias = str_starts_with($cp, '35') || str_starts_with($cp, '38');
        $impNombre = $esCanarias ? 'IGIC' : 'IVA';
        $impPct = $esCanarias ? 7 : 21;

        $precioVenta = (float) $request->precio_venta;
        $descuento = (float) ($request->descuento ?? 0);
        $conceptos = $request->input('conceptos', []);

        $sumExtras = 0;
        $sumDescuentos = 0;
        foreach ($conceptos as $c) {
            if ($c['tipo'] === 'extra') $sumExtras += (float) $c['importe'];
            if ($c['tipo'] === 'descuento') $sumDescuentos += (float) $c['importe'];
        }

        $precioFinal = $precioVenta - $descuento + $sumExtras - $sumDescuentos;
        $subtotal = $precioFinal;
        $impImporte = round($subtotal * $impPct / 100, 2);
        $total = round($subtotal + $impImporte, 2);

        $codigo = 'VTA-' . date('Ym') . '-' . str_pad(Venta::whereYear('fecha_venta', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        $venta = DB::transaction(function () use ($request, $codigo, $precioFinal, $subtotal, $impNombre, $impPct, $impImporte, $total, $conceptos, $descuento) {
            $venta = Venta::create([
                'codigo_venta' => $codigo,
                'vehiculo_id' => $request->vehiculo_id,
                'cliente_id' => $request->cliente_id,
                'empresa_id' => $request->empresa_id,
                'centro_id' => $request->centro_id,
                'marca_id' => $request->marca_id,
                'vendedor_id' => Auth::id(),
                'precio_venta' => $request->precio_venta,
                'descuento' => $descuento,
                'precio_final' => $precioFinal,
                'subtotal' => $subtotal,
                'impuesto_nombre' => $impNombre,
                'impuesto_porcentaje' => $impPct,
                'impuesto_importe' => $impImporte,
                'total' => $total,
                'forma_pago' => $request->forma_pago,
                'estado' => 'reservada',
                'fecha_venta' => $request->fecha_venta,
                'fecha_entrega' => $request->fecha_entrega,
                'observaciones' => $request->observaciones,
            ]);

            foreach ($conceptos as $c) {
                VentaConcepto::create([
                    'venta_id' => $venta->id,
                    'tipo' => $c['tipo'],
                    'descripcion' => $c['descripcion'],
                    'importe' => $c['importe'],
                ]);
            }

            return $venta;
        });

        // Auto-create factura if requested
        if ($request->filled('crear_factura')) {
            return $this->crearFacturaDesdeVenta($venta);
        }

        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente.');
    }

    public function show(Venta $venta)
    {
        $venta->load(['vehiculo', 'cliente', 'empresa', 'centro', 'marca', 'vendedor', 'conceptos']);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $venta->load('conceptos');
        $vehiculos = Vehiculo::with('marca')->orderBy('modelo')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $preciosCatalogo = CatalogoPrecio::select('marca_id', 'modelo', 'version', 'precio_base')->get()
            ->keyBy(fn($c) => $c->marca_id . '|' . $c->modelo . '|' . $c->version);
        return view('ventas.edit', compact('venta', 'vehiculos', 'clientes', 'empresas', 'centros', 'marcas', 'preciosCatalogo'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'empresa_id' => 'required|exists:empresas,id',
            'centro_id' => 'required|exists:centros,id',
            'precio_venta' => 'required|numeric|min:0',
            'estado' => 'required|in:reservada,pendiente_entrega,entregada,cancelada',
            'fecha_venta' => 'required|date',
            'conceptos.*.tipo' => 'required_with:conceptos|in:extra,descuento',
            'conceptos.*.descripcion' => 'required_with:conceptos|string|max:255',
            'conceptos.*.importe' => 'required_with:conceptos|numeric|min:0',
        ]);

        $empresa = Empresa::findOrFail($request->empresa_id);
        $cp = $empresa->codigo_postal ?? '';
        $esCanarias = str_starts_with($cp, '35') || str_starts_with($cp, '38');
        $impNombre = $esCanarias ? 'IGIC' : 'IVA';
        $impPct = $esCanarias ? 7 : 21;

        $precioVenta = (float) $request->precio_venta;
        $descuento = (float) ($request->descuento ?? 0);
        $conceptos = $request->input('conceptos', []);

        $sumExtras = 0;
        $sumDescuentos = 0;
        foreach ($conceptos as $c) {
            if ($c['tipo'] === 'extra') $sumExtras += (float) $c['importe'];
            if ($c['tipo'] === 'descuento') $sumDescuentos += (float) $c['importe'];
        }

        $precioFinal = $precioVenta - $descuento + $sumExtras - $sumDescuentos;
        $subtotal = $precioFinal;
        $impImporte = round($subtotal * $impPct / 100, 2);
        $total = round($subtotal + $impImporte, 2);

        DB::transaction(function () use ($request, $venta, $precioFinal, $subtotal, $impNombre, $impPct, $impImporte, $total, $conceptos, $descuento) {
            $venta->update([
                'vehiculo_id' => $request->vehiculo_id,
                'cliente_id' => $request->cliente_id,
                'empresa_id' => $request->empresa_id,
                'centro_id' => $request->centro_id,
                'marca_id' => $request->marca_id,
                'vendedor_id' => Auth::id(),
                'precio_venta' => $request->precio_venta,
                'descuento' => $descuento,
                'precio_final' => $precioFinal,
                'subtotal' => $subtotal,
                'impuesto_nombre' => $impNombre,
                'impuesto_porcentaje' => $impPct,
                'impuesto_importe' => $impImporte,
                'total' => $total,
                'forma_pago' => $request->forma_pago,
                'estado' => $request->estado,
                'fecha_venta' => $request->fecha_venta,
                'fecha_entrega' => $request->fecha_entrega,
                'observaciones' => $request->observaciones,
            ]);

            // Replace conceptos
            $venta->conceptos()->delete();
            foreach ($conceptos as $c) {
                VentaConcepto::create([
                    'venta_id' => $venta->id,
                    'tipo' => $c['tipo'],
                    'descripcion' => $c['descripcion'],
                    'importe' => $c['importe'],
                ]);
            }
        });

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }

    public function export()
    {
        $fileName = 'ventas_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new VentasExport(), $fileName);
    }

    public function exportPdf()
    {
        $ventas = Venta::with(['vehiculo', 'cliente', 'empresa', 'marca'])->orderByDesc('fecha_venta')->get();
        $pdf = Pdf::loadView('ventas.pdf', compact('ventas'));
        $fileName = 'ventas_' . date('Y-m-d_His') . '.pdf';
        return $pdf->download($fileName);
    }

    private function crearFacturaDesdeVenta(Venta $venta): \Illuminate\Http\RedirectResponse
    {
        $codigoFactura = 'FAC-' . date('Ym') . '-' . str_pad(Factura::whereYear('fecha_factura', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        $factura = Factura::create([
            'codigo_factura' => $codigoFactura,
            'venta_id' => $venta->id,
            'cliente_id' => $venta->cliente_id,
            'empresa_id' => $venta->empresa_id,
            'centro_id' => $venta->centro_id,
            'marca_id' => $venta->marca_id,
            'emisor_id' => Auth::id(),
            'fecha_factura' => now(),
            'fecha_vencimiento' => now()->addDays(30),
            'concepto' => 'Venta vehículo - ' . $venta->codigo_venta,
            'subtotal' => $venta->subtotal,
            'iva_porcentaje' => $venta->impuesto_porcentaje,
            'iva_importe' => $venta->impuesto_importe,
            'total' => $venta->total,
            'estado' => 'emitida',
        ]);

        // Auto-register in Verifactu if module enabled
        $verifactuMsg = '';
        if (Setting::get('modulo_verifactu', true)) {
            try {
                $factura->load(['empresa', 'cliente']);
                $service = new AeatVerifactuService();
                $registro = $service->registrarFactura($factura, 'alta');
                $verifactuMsg = " Registro Verifactu {$registro->codigo_registro} generado.";
            } catch (\Exception $e) {
                $verifactuMsg = ' (Error Verifactu: ' . $e->getMessage() . ')';
            }
        }

        return redirect()->route('facturas.show', $factura)->with('success', 'Venta registrada y factura ' . $factura->codigo_factura . ' creada correctamente.' . $verifactuMsg);
    }
}
