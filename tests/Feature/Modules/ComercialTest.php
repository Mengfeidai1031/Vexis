<?php

namespace Tests\Feature\Modules;

use App\Models\Centro;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\Vehiculo;
use App\Models\Venta;
use App\Models\Verifactu;
use App\Services\FacturaCreationService;
use App\Services\MatriculaService;
use Tests\TestCase;

class ComercialTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_comercial_index_routes(): void
    {
        foreach ([
            '/comercial', '/clientes', '/vehiculos', '/ofertas', '/ventas',
            '/facturas', '/verifactu', '/catalogo-precios', '/tasaciones',
            '/coches-sustitucion',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_comercial_create_routes(): void
    {
        foreach ([
            '/clientes/create', '/vehiculos/create', '/ofertas/create',
            '/ventas/create', '/facturas/create', '/catalogo-precios/create',
            '/tasaciones/create', '/coches-sustitucion/create',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_cliente_crud(): void
    {
        $this->post('/clientes', [
            'nombre' => 'TEST', 'apellidos' => 'Cliente',
            'email' => 'test.crud@grupo-dai.com',
            'telefono' => '600000001', 'domicilio' => 'Calle Test',
            'codigo_postal' => '35500', 'empresa_id' => 1,
            'tipo_cliente_id' => 1, 'dni' => 'X9999999Z',
        ])->assertRedirect();
        $c = Cliente::where('email', 'test.crud@grupo-dai.com')->first();
        $this->assertNotNull($c);
    }

    public function test_vehiculo_crud_starts_disponible(): void
    {
        $this->post('/vehiculos', [
            'chasis' => 'TESTCRUDVIN12345A',
            'marca_id' => 1, 'modelo' => 'TestCRUD', 'version' => 'V1',
            'color_externo' => 'Blanco', 'color_interno' => 'Gris',
            'empresa_id' => 1,
        ])->assertRedirect();
        $v = Vehiculo::where('chasis', 'TESTCRUDVIN12345A')->first();
        $this->assertNotNull($v);
        $this->assertEquals('disponible', $v->estado);
    }

    public function test_matricula_autogenerator_endpoint(): void
    {
        $resp = $this->get('/vehiculos/generar-matricula');
        $resp->assertOk()->assertJsonStructure(['matricula']);
        $this->assertMatchesRegularExpression('/^\d{4} [BCDFGHJKLMNPRSTVWXYZ]{3}$/', $resp->json('matricula'));
    }

    public function test_matricula_service_returns_string(): void
    {
        $svc = new MatriculaService;
        $this->assertIsString($svc->generarSiguiente());
    }

    public function test_modelos_por_marca_endpoint(): void
    {
        $this->get('/vehiculos/modelos-por-marca/1')
            ->assertOk()
            ->assertJsonStructure(['modelos', 'versiones']);
    }

    public function test_venta_factura_verifactu_chain(): void
    {
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();
        $cliente = Cliente::first();
        $empresa = Empresa::first();
        $centro = Centro::where('empresa_id', $empresa->id)->first();

        $venta = Venta::create([
            'codigo_venta' => 'TEST-CHAIN-'.uniqid(),
            'vehiculo_id' => $vehiculo->id,
            'cliente_id' => $cliente->id,
            'empresa_id' => $empresa->id,
            'centro_id' => $centro->id,
            'vendedor_id' => auth()->id(),
            'fecha_venta' => now(),
            'precio_venta' => 12000, 'precio_final' => 12000,
            'subtotal' => 12000, 'impuesto_nombre' => 'IGIC',
            'impuesto_porcentaje' => 7, 'impuesto_importe' => 840,
            'total' => 12840, 'estado' => 'reservada', 'forma_pago' => 'contado',
        ]);

        $svc = app(FacturaCreationService::class);
        $res = $svc->crearDesdeVenta($venta);

        $this->assertInstanceOf(Factura::class, $res['factura']);
        $this->assertMatchesRegularExpression('/^FAC-[A-Z]-\d{6}-\d{4}$/', $res['factura']->codigo_factura);

        $verifactu = Verifactu::where('factura_id', $res['factura']->id)->first();
        $this->assertNotNull($verifactu);
        $this->assertEquals('aceptado', $verifactu->estado);
        $this->assertStringContainsString('prewww2.aeat.es', $verifactu->url_qr);
    }

    public function test_factura_pdf_generation(): void
    {
        $factura = Factura::first();
        $resp = $this->get("/facturas/{$factura->id}/generate-pdf");
        $resp->assertOk();
        $this->assertEquals('application/pdf', $resp->headers->get('Content-Type'));
    }

    public function test_tasacion_pdf(): void
    {
        $this->get('/tasaciones/1/pdf')->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_venta_contrato_pdf(): void
    {
        $this->get('/ventas/1/contrato-pdf')->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_vehiculo_doc_generators(): void
    {
        foreach (['ficha_tecnica', 'itv', 'permiso_circulacion', 'seguro', 'contrato'] as $tipo) {
            $this->get("/vehiculos/1/documentos/generar/{$tipo}")->assertOk();
        }
        $this->get('/vehiculos/documentos/generar')->assertOk();
    }

    public function test_exports(): void
    {
        foreach ([
            '/clientes/export/excel', '/clientes/export/pdf',
            '/vehiculos/export/excel', '/vehiculos/export/pdf',
            '/ventas/export/excel', '/ventas/export/pdf',
            '/facturas/export/excel', '/facturas/export/pdf',
            '/tasaciones/export/excel', '/tasaciones/export/pdf',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }
}
