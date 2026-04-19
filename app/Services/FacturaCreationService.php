<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Factura;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;

/**
 * Encapsula la creación de facturas (incluyendo generación de código y registro Verifactu).
 */
class FacturaCreationService
{
    public function __construct(
        private readonly VerifactuRegistrationService $verifactuRegistration,
    ) {}

    /**
     * Genera el siguiente código FAC-YYYYmm-XXXX según la cantidad de facturas del año actual.
     */
    public function generarCodigo(): string
    {
        $siguiente = Factura::whereYear('fecha_factura', date('Y'))->count() + 1;

        return 'FAC-'.date('Ym').'-'.str_pad((string) $siguiente, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Crea una factura a partir de una venta ya registrada.
     * Devuelve la factura + el mensaje (posiblemente vacío) del registro Verifactu.
     *
     * @return array{factura: Factura, verifactu_msg: string}
     */
    public function crearDesdeVenta(Venta $venta): array
    {
        $factura = Factura::create([
            'codigo_factura' => $this->generarCodigo(),
            'venta_id' => $venta->id,
            'cliente_id' => $venta->cliente_id,
            'empresa_id' => $venta->empresa_id,
            'centro_id' => $venta->centro_id,
            'marca_id' => $venta->marca_id,
            'emisor_id' => Auth::id(),
            'fecha_factura' => now(),
            'fecha_vencimiento' => now()->addDays(30),
            'concepto' => 'Venta vehículo - '.$venta->codigo_venta,
            'subtotal' => $venta->subtotal,
            'iva_porcentaje' => $venta->impuesto_porcentaje,
            'iva_importe' => $venta->impuesto_importe,
            'total' => $venta->total,
            'estado' => 'emitida',
        ]);

        return [
            'factura' => $factura,
            'verifactu_msg' => $this->verifactuRegistration->registrar($factura, 'alta'),
        ];
    }

    /**
     * Crea una factura a partir de los datos ya validados + una venta asociada.
     *
     * @param  array<string, mixed>  $data  Campos already validated (venta_id, cliente_id, etc.).
     * @return array{factura: Factura, verifactu_msg: string}
     */
    public function crearDesdeDatos(array $data): array
    {
        $factura = Factura::create([...$data,
            'codigo_factura' => $this->generarCodigo(),
            'emisor_id' => Auth::id(),
        ]);

        return [
            'factura' => $factura,
            'verifactu_msg' => $this->verifactuRegistration->registrar($factura, 'alta'),
        ];
    }
}
