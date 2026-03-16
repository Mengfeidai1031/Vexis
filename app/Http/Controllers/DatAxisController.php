<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Tasacion;
use App\Models\Stock;
use App\Models\CitaTaller;
use App\Models\CatalogoPrecio;
use App\Models\Marca;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\User;
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
        $citasDia = CitaTaller::select(DB::raw("DAYOFWEEK(fecha) as dia"), DB::raw('COUNT(*) as total'))
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

    public function general()
    {
        // KPIs
        $totalVentas = Venta::count();
        $importeVentas = Venta::sum('precio_final');
        $totalClientes = Cliente::count();
        $totalVehiculos = Vehiculo::count();
        $totalStock = Stock::sum('cantidad');
        $totalUsuarios = User::count();

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
            'totalStock', 'totalUsuarios', 'catalogoMarca', 'clientesMes'
        ));
    }
}
