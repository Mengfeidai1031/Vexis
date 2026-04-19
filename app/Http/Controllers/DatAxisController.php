<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CatalogoPrecio;
use App\Models\CitaTaller;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Incidencia;
use App\Models\Marca;
use App\Models\Stock;
use App\Models\Tasacion;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class DatAxisController extends Controller
{
    public function inicio()
    {
        return view('dataxis.inicio');
    }

    public function ventas()
    {
        // Ventas por mes (últimos 6 meses)
        $ventasMes = Venta::select(
            DB::raw("DATE_FORMAT(fecha_venta, '%Y-%m') as mes"),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(precio_final) as importe')
        )->where('fecha_venta', '>=', now()->subMonths(6))
            ->groupBy('mes')->orderBy('mes')->get();

        // Ventas por estado
        $ventasEstado = Venta::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')->get();

        // Ventas por forma de pago
        $ventasPago = Venta::select('forma_pago', DB::raw('COUNT(*) as total'))
            ->groupBy('forma_pago')->get();

        // Ventas por marca
        $ventasMarca = Venta::join('marcas', 'ventas.marca_id', '=', 'marcas.id')
            ->select('marcas.nombre', 'marcas.color', DB::raw('COUNT(*) as total'), DB::raw('SUM(precio_final) as importe'))
            ->groupBy('marcas.nombre', 'marcas.color')->get();

        // Top vendedores
        $topVendedores = Venta::join('users', 'ventas.vendedor_id', '=', 'users.id')
            ->select('users.nombre', DB::raw('COUNT(*) as total'), DB::raw('SUM(ventas.precio_final) as importe'))
            ->groupBy('users.nombre')->orderByDesc('total')->limit(5)->get();

        return view('dataxis.ventas', compact('ventasMes', 'ventasEstado', 'ventasPago', 'ventasMarca', 'topVendedores'));
    }

    public function stock()
    {
        // Stock por almacén
        $stockAlmacen = Stock::join('almacenes', 'stocks.almacen_id', '=', 'almacenes.id')
            ->select('almacenes.nombre', DB::raw('SUM(stocks.cantidad) as total'), DB::raw('COUNT(*) as referencias'))
            ->groupBy('almacenes.nombre')->get();

        // Valor stock por almacén
        $valorStock = Stock::join('almacenes', 'stocks.almacen_id', '=', 'almacenes.id')
            ->select('almacenes.nombre', DB::raw('SUM(stocks.cantidad * stocks.precio_unitario) as valor'))
            ->groupBy('almacenes.nombre')->get();

        // Piezas bajo stock
        $bajoStock = Stock::whereColumn('cantidad', '<=', 'stock_minimo')
            ->select('nombre_pieza', 'cantidad', 'stock_minimo', 'referencia')
            ->orderBy('cantidad')->limit(10)->get();

        // Top piezas por valor
        $topValor = Stock::select('nombre_pieza', DB::raw('(cantidad * precio_unitario) as valor'), 'cantidad')
            ->orderByDesc(DB::raw('cantidad * precio_unitario'))->limit(8)->get();

        return view('dataxis.stock', compact('stockAlmacen', 'valorStock', 'bajoStock', 'topValor'));
    }

    public function taller()
    {
        // Citas por estado
        $citasEstado = CitaTaller::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')->get();

        // Citas por día de la semana
        $citasDia = CitaTaller::select(DB::raw('DAYOFWEEK(fecha) as dia'), DB::raw('COUNT(*) as total'))
            ->groupBy('dia')->orderBy('dia')->get();

        // Carga por mecánico
        $cargaMecanico = CitaTaller::join('mecanicos', 'citas_taller.mecanico_id', '=', 'mecanicos.id')
            ->select(DB::raw("CONCAT(mecanicos.nombre, ' ', mecanicos.apellidos) as mecanico"), DB::raw('COUNT(*) as total'))
            ->groupBy('mecanico')->orderByDesc('total')->limit(8)->get();

        // Tasaciones por estado
        $tasacionesEstado = Tasacion::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')->get();

        return view('dataxis.taller', compact('citasEstado', 'citasDia', 'cargaMecanico', 'tasacionesEstado'));
    }

    public function facturas()
    {
        // Facturación mensual (últimos 6 meses)
        $facturasMes = Factura::select(
            DB::raw("DATE_FORMAT(fecha_factura, '%Y-%m') as mes"),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(total) as importe')
        )->where('fecha_factura', '>=', now()->subMonths(6))
            ->groupBy('mes')->orderBy('mes')->get();

        // Facturas por estado
        $facturasEstado = Factura::select('estado', DB::raw('COUNT(*) as total'), DB::raw('SUM(total) as importe'))
            ->groupBy('estado')->get();

        // Importe total facturado
        $totalFacturado = Factura::where('estado', '!=', 'anulada')->sum('total');
        $totalIva = Factura::where('estado', '!=', 'anulada')->sum('iva_importe');
        $totalFacturas = Factura::count();
        $facturasPagadas = Factura::where('estado', 'pagada')->count();

        // Facturación por marca
        $facturasMarca = Factura::join('marcas', 'facturas.marca_id', '=', 'marcas.id')
            ->select('marcas.nombre', 'marcas.color', DB::raw('COUNT(*) as total'), DB::raw('SUM(facturas.total) as importe'))
            ->where('facturas.estado', '!=', 'anulada')
            ->groupBy('marcas.nombre', 'marcas.color')->get();

        return view('dataxis.facturas', compact(
            'facturasMes', 'facturasEstado', 'totalFacturado', 'totalIva',
            'totalFacturas', 'facturasPagadas', 'facturasMarca'
        ));
    }

    public function incidencias()
    {
        // Incidencias por estado
        $incidenciasEstado = Incidencia::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')->get();

        // Incidencias por prioridad
        $incidenciasPrioridad = Incidencia::select('prioridad', DB::raw('COUNT(*) as total'))
            ->groupBy('prioridad')->get();

        // Tiempo medio de resolución (días) — solo resueltas/cerradas con fecha_cierre
        $tiempoMedio = Incidencia::whereNotNull('fecha_cierre')
            ->select(DB::raw('AVG(DATEDIFF(fecha_cierre, fecha_apertura)) as dias'))
            ->value('dias');

        // Carga por técnico
        $cargaTecnico = Incidencia::join('users', 'incidencias.tecnico_id', '=', 'users.id')
            ->select(DB::raw("CONCAT(users.nombre, ' ', users.apellidos) as tecnico"), DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN incidencias.estado IN ('resuelta','cerrada') THEN 1 ELSE 0 END) as resueltas"))
            ->groupBy('tecnico')->orderByDesc('total')->limit(8)->get();

        // Incidencias por mes (últimos 6 meses)
        $incidenciasMes = Incidencia::select(
            DB::raw("DATE_FORMAT(fecha_apertura, '%Y-%m') as mes"),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN estado IN ('resuelta','cerrada') THEN 1 ELSE 0 END) as cerradas")
        )->where('fecha_apertura', '>=', now()->subMonths(6))
            ->groupBy('mes')->orderBy('mes')->get();

        // KPIs
        $totalIncidencias = Incidencia::count();
        $abiertas = Incidencia::whereIn('estado', ['abierta', 'en_progreso'])->count();

        return view('dataxis.incidencias', compact(
            'incidenciasEstado', 'incidenciasPrioridad', 'tiempoMedio',
            'cargaTecnico', 'incidenciasMes', 'totalIncidencias', 'abiertas'
        ));
    }

    public function general()
    {
        // KPIs
        $totalVentas = Venta::count();
        $importeVentas = Venta::sum('precio_final');
        $totalClientes = Cliente::count();
        $totalVehiculos = Vehiculo::count();
        $totalStock = Stock::sum('cantidad');
        $totalUsuarios = User::count();
        $totalFacturado = Factura::where('estado', '!=', 'anulada')->sum('total');
        $incidenciasAbiertas = Incidencia::whereIn('estado', ['abierta', 'en_progreso'])->count();

        // Catálogo por marca
        $catalogoMarca = CatalogoPrecio::join('marcas', 'catalogo_precios.marca_id', '=', 'marcas.id')
            ->select('marcas.nombre', 'marcas.color', DB::raw('COUNT(*) as modelos'), DB::raw('AVG(catalogo_precios.precio_base) as precio_medio'))
            ->where('catalogo_precios.disponible', true)
            ->groupBy('marcas.nombre', 'marcas.color')->get();

        // Clientes últimos 6 meses
        $clientesMes = Cliente::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"),
            DB::raw('COUNT(*) as total')
        )->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')->orderBy('mes')->get();

        return view('dataxis.general', compact(
            'totalVentas', 'importeVentas', 'totalClientes', 'totalVehiculos',
            'totalStock', 'totalUsuarios', 'totalFacturado', 'incidenciasAbiertas',
            'catalogoMarca', 'clientesMes'
        ));
    }
}
