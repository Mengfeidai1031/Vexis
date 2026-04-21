<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\VentasExport;
use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use App\Models\CatalogoPrecio;
use App\Models\Centro;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Venta;
use App\Models\VentaConcepto;
use App\Services\FacturaCreationService;
use App\Services\ImpuestoService;
use App\Services\VehiculoEstadoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VentaController extends Controller
{
    public function __construct(
        private readonly ImpuestoService $impuestoService,
        private readonly FacturaCreationService $facturaCreationService,
        private readonly VehiculoEstadoService $vehiculoEstadoService,
    ) {}

    public function index(Request $request)
    {
        $query = Venta::with(['vehiculo', 'cliente', 'empresa', 'marca', 'vendedor']);
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }
        if ($request->filled('forma_pago')) {
            $query->where('forma_pago', $request->forma_pago);
        }
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_venta', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_venta', '<=', $request->fecha_hasta);
        }
        if ($request->filled('vendedor_id')) {
            $query->where('vendedor_id', $request->vendedor_id);
        }
        if ($request->filled('vehiculo_modelo')) {
            $query->whereHas('vehiculo', fn ($q) => $q->where('modelo', $request->vehiculo_modelo));
        }
        if ($request->filled('precio_min')) {
            $query->where('precio_final', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio_final', '<=', $request->precio_max);
        }
        if ($request->filled('codigo_venta')) {
            $query->where('codigo_venta', $request->codigo_venta);
        }

        $sortable = ['id', 'codigo_venta', 'vehiculo_id', 'cliente_id', 'marca_id', 'precio_final', 'forma_pago', 'estado', 'fecha_venta'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $ventas = $query->paginate(15)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $vendedores = User::orderBy('nombre')->get();
        $modelos_vehiculo = Vehiculo::distinct()->orderBy('modelo')->pluck('modelo');
        $codigos_venta = Venta::distinct()->orderBy('codigo_venta')->pluck('codigo_venta');

        return view('ventas.index', compact('ventas', 'marcas', 'clientes', 'empresas', 'vendedores', 'modelos_vehiculo', 'codigos_venta'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::with('marca')->orderBy('modelo')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $centros = Centro::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $preciosCatalogo = CatalogoPrecio::select('marca_id', 'modelo', 'version', 'precio_base')->get()
            ->keyBy(fn ($c) => $c->marca_id.'|'.$c->modelo.'|'.$c->version);

        return view('ventas.create', compact('vehiculos', 'clientes', 'empresas', 'centros', 'marcas', 'preciosCatalogo'));
    }

    public function store(StoreVentaRequest $request): RedirectResponse
    {
        $empresa = Empresa::findOrFail($request->validated('empresa_id'));
        $conceptos = $request->input('conceptos', []);

        $calculo = $this->impuestoService->calcularVenta(
            $empresa,
            (float) $request->validated('precio_venta'),
            (float) ($request->descuento ?? 0),
            $conceptos,
        );

        $codigo = 'VTA-'.date('Ym').'-'.str_pad(
            (string) (Venta::whereYear('fecha_venta', date('Y'))->count() + 1),
            4, '0', STR_PAD_LEFT
        );

        $venta = DB::transaction(function () use ($request, $codigo, $calculo, $conceptos) {
            $venta = Venta::create([
                'codigo_venta' => $codigo,
                'vehiculo_id' => $request->vehiculo_id,
                'cliente_id' => $request->cliente_id,
                'empresa_id' => $request->empresa_id,
                'centro_id' => $request->centro_id,
                'marca_id' => $request->marca_id,
                'vendedor_id' => Auth::id(),
                'precio_venta' => $request->precio_venta,
                'descuento' => $request->descuento ?? 0,
                'precio_final' => $calculo['precio_final'],
                'subtotal' => $calculo['subtotal'],
                'impuesto_nombre' => $calculo['impuesto_nombre'],
                'impuesto_porcentaje' => $calculo['impuesto_porcentaje'],
                'impuesto_importe' => $calculo['impuesto_importe'],
                'total' => $calculo['total'],
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

        $venta->loadMissing('vehiculo');
        if ($venta->vehiculo) {
            $this->vehiculoEstadoService->sincronizarConVenta($venta->vehiculo, $venta->estado);
        }

        if ($request->filled('crear_factura')) {
            $result = $this->facturaCreationService->crearDesdeVenta($venta);

            return redirect()->route('facturas.show', $result['factura'])
                ->with('success', 'Venta registrada y factura '.$result['factura']->codigo_factura.' creada correctamente.'.$result['verifactu_msg']);
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
            ->keyBy(fn ($c) => $c->marca_id.'|'.$c->modelo.'|'.$c->version);

        return view('ventas.edit', compact('venta', 'vehiculos', 'clientes', 'empresas', 'centros', 'marcas', 'preciosCatalogo'));
    }

    public function update(UpdateVentaRequest $request, Venta $venta): RedirectResponse
    {
        $empresa = Empresa::findOrFail($request->validated('empresa_id'));
        $conceptos = $request->input('conceptos', []);

        $calculo = $this->impuestoService->calcularVenta(
            $empresa,
            (float) $request->validated('precio_venta'),
            (float) ($request->descuento ?? 0),
            $conceptos,
        );

        DB::transaction(function () use ($request, $venta, $calculo, $conceptos) {
            $venta->update([
                'vehiculo_id' => $request->vehiculo_id,
                'cliente_id' => $request->cliente_id,
                'empresa_id' => $request->empresa_id,
                'centro_id' => $request->centro_id,
                'marca_id' => $request->marca_id,
                'vendedor_id' => Auth::id(),
                'precio_venta' => $request->precio_venta,
                'descuento' => $request->descuento ?? 0,
                'precio_final' => $calculo['precio_final'],
                'subtotal' => $calculo['subtotal'],
                'impuesto_nombre' => $calculo['impuesto_nombre'],
                'impuesto_porcentaje' => $calculo['impuesto_porcentaje'],
                'impuesto_importe' => $calculo['impuesto_importe'],
                'total' => $calculo['total'],
                'forma_pago' => $request->forma_pago,
                'estado' => $request->estado,
                'fecha_venta' => $request->fecha_venta,
                'fecha_entrega' => $request->fecha_entrega,
                'observaciones' => $request->observaciones,
            ]);

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

        $venta->loadMissing('vehiculo');
        if ($venta->vehiculo) {
            $this->vehiculoEstadoService->sincronizarConVenta($venta->vehiculo, $venta->estado);
        }

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta): RedirectResponse
    {
        $vehiculo = $venta->vehiculo;
        $venta->delete();
        if ($vehiculo) {
            $this->vehiculoEstadoService->cambiarEstado($vehiculo, 'disponible', 'Venta '.$venta->codigo_venta.' eliminada');
        }

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }

    public function export()
    {
        $fileName = 'ventas_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new VentasExport, $fileName);
    }

    public function exportPdf()
    {
        $ventas = Venta::with(['vehiculo', 'cliente', 'empresa', 'marca'])->orderByDesc('fecha_venta')->get();
        $pdf = Pdf::loadView('ventas.pdf', compact('ventas'));

        return $pdf->download('ventas_'.date('Y-m-d_His').'.pdf');
    }

    public function contratoPdf(Venta $venta)
    {
        $venta->load(['vehiculo', 'cliente', 'empresa', 'centro', 'marca', 'vendedor', 'conceptos']);

        $pdf = Pdf::loadView('ventas.contrato-pdf', compact('venta'))->setPaper('a4', 'portrait');

        return $pdf->download('contrato_'.$venta->codigo_venta.'.pdf');
    }
}
