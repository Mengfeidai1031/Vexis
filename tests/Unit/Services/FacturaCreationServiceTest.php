<?php

namespace Tests\Unit\Services;

use App\Models\Centro;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\Setting;
use App\Models\Vehiculo;
use App\Models\Venta;
use App\Models\Verifactu;
use App\Services\FacturaCreationService;
use Tests\TestCase;

class FacturaCreationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_generar_codigo_format_includes_serie_and_yearmonth(): void
    {
        $svc = app(FacturaCreationService::class);
        $codigo = $svc->generarCodigo();
        $serie = setting('factura_serie_actual', 'A');
        $this->assertMatchesRegularExpression('/^FAC-'.preg_quote($serie).'-\d{6}-\d{4}$/', $codigo);
    }

    public function test_generar_codigo_uses_setting_serie(): void
    {
        Setting::set('factura_serie_actual', 'TEST');
        $codigo = app(FacturaCreationService::class)->generarCodigo();
        $this->assertStringStartsWith('FAC-TEST-', $codigo);
        Setting::set('factura_serie_actual', 'A');
    }

    public function test_crear_desde_venta_creates_factura_and_verifactu(): void
    {
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();
        $cliente = Cliente::first();
        $empresa = Empresa::first();
        $centro = Centro::where('empresa_id', $empresa->id)->first();

        $venta = Venta::create([
            'codigo_venta' => 'TEST-UNIT-'.uniqid(),
            'vehiculo_id' => $vehiculo->id, 'cliente_id' => $cliente->id,
            'empresa_id' => $empresa->id, 'centro_id' => $centro->id,
            'vendedor_id' => auth()->id(),
            'fecha_venta' => now(),
            'precio_venta' => 5000, 'precio_final' => 5000,
            'subtotal' => 5000, 'impuesto_nombre' => 'IGIC',
            'impuesto_porcentaje' => 7, 'impuesto_importe' => 350,
            'total' => 5350, 'estado' => 'reservada', 'forma_pago' => 'contado',
        ]);

        $res = app(FacturaCreationService::class)->crearDesdeVenta($venta);

        $this->assertInstanceOf(Factura::class, $res['factura']);
        $this->assertEquals($venta->id, $res['factura']->venta_id);
        $this->assertEquals(5350, $res['factura']->total);

        $verifactu = Verifactu::where('factura_id', $res['factura']->id)->first();
        $this->assertNotNull($verifactu);
        $this->assertNotEmpty($verifactu->hash_registro);
        $this->assertStringStartsWith('VRF-', $verifactu->codigo_registro);
    }
}
