<?php

namespace Tests\Feature\Regression;

use App\Models\Centro;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Vehiculo;
use App\Models\Venta;
use App\Services\FacturaCreationService;
use App\Services\MatriculaService;
use Tests\TestCase;

/**
 * REGRESSION #003 — `str_pad(int)` rompía en PHP 8.2+ strict.
 *
 * Bug histórico (Fase 1.6 y 2.2):
 *   Tres puntos del código pasaban int directamente a str_pad():
 *     - AeatVerifactuService.php:47 → generación código Verifactu (rompía cadena venta→factura→verifactu)
 *     - AeatVerifactuService.php:178 → simulación respuesta AEAT
 *     - MatriculaService.php:178 → autogeneración matrícula (botón "Nueva" daba undefined)
 *
 *   PHP 8.2 endureció firmas: str_pad() exige string, no int.
 *
 * Fix: cast explícito (string) antes de cada str_pad.
 *
 * @see App\Services\AeatVerifactuService::registrarFactura
 * @see App\Services\MatriculaService::calcularSiguiente
 */
class Issue003StrPadIntCastTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_matricula_generator_returns_valid_string_without_typeerror(): void
    {
        $svc = new MatriculaService;
        $matricula = $svc->generarSiguiente();
        $this->assertIsString($matricula);
        $this->assertNotEquals('undefined', $matricula);
        $this->assertMatchesRegularExpression('/^\d{4} [BCDFGHJKLMNPRSTVWXYZ]{3}$/', $matricula);
    }

    public function test_matricula_ajax_endpoint_returns_json_with_string(): void
    {
        $resp = $this->get('/vehiculos/generar-matricula');
        $resp->assertOk()->assertJsonStructure(['matricula']);
        $this->assertIsString($resp->json('matricula'));
    }

    public function test_venta_factura_verifactu_chain_completes_without_typeerror(): void
    {
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();
        $cliente = Cliente::first();
        $empresa = Empresa::first();
        $centro = Centro::where('empresa_id', $empresa->id)->first();

        $venta = Venta::create([
            'codigo_venta' => 'REG-003-'.uniqid(),
            'vehiculo_id' => $vehiculo->id, 'cliente_id' => $cliente->id,
            'empresa_id' => $empresa->id, 'centro_id' => $centro->id,
            'vendedor_id' => auth()->id(),
            'fecha_venta' => now(),
            'precio_venta' => 8000, 'precio_final' => 8000,
            'subtotal' => 8000, 'impuesto_nombre' => 'IGIC',
            'impuesto_porcentaje' => 7, 'impuesto_importe' => 560,
            'total' => 8560, 'estado' => 'reservada', 'forma_pago' => 'contado',
        ]);

        $res = app(FacturaCreationService::class)->crearDesdeVenta($venta);

        // Si str_pad fallara con TypeError, esto explotaría:
        $this->assertNotNull($res['factura']);
        $this->assertStringContainsString('FAC-', $res['factura']->codigo_factura);
        $this->assertStringContainsString('Verifactu', $res['verifactu_msg']);
    }
}
