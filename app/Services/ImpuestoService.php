<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Empresa;

/**
 * Servicio de cálculo de impuestos (IVA / IGIC) según el código postal de la empresa.
 *
 * Canarias (códigos postales 35xxx y 38xxx) → IGIC al 7 %.
 * Resto → IVA al 21 %.
 */
class ImpuestoService
{
    public const IVA_DEFAULT_PCT = 21.0;

    public const IGIC_DEFAULT_PCT = 7.0;

    /**
     * Resolver tipo (nombre + porcentaje) a partir de una empresa.
     *
     * @return array{nombre: string, porcentaje: float}
     */
    public function resolverParaEmpresa(Empresa $empresa): array
    {
        return $this->resolverPorCodigoPostal($empresa->codigo_postal ?? '');
    }

    /**
     * @return array{nombre: string, porcentaje: float}
     */
    public function resolverPorCodigoPostal(string $codigoPostal): array
    {
        $esCanarias = str_starts_with($codigoPostal, '35') || str_starts_with($codigoPostal, '38');

        return [
            'nombre' => $esCanarias ? 'IGIC' : 'IVA',
            'porcentaje' => $esCanarias ? self::IGIC_DEFAULT_PCT : self::IVA_DEFAULT_PCT,
        ];
    }

    /**
     * Calcula el desglose de una venta: subtotal, importe de impuesto y total.
     *
     * @param  array<int, array{tipo: string, importe: float|int|string}>  $conceptos
     * @return array{subtotal: float, impuesto_importe: float, total: float, precio_final: float,
     *               sum_extras: float, sum_descuentos: float, impuesto_nombre: string, impuesto_porcentaje: float}
     */
    public function calcularVenta(
        Empresa $empresa,
        float $precioVenta,
        float $descuento,
        array $conceptos
    ): array {
        $impuesto = $this->resolverParaEmpresa($empresa);

        $sumExtras = 0.0;
        $sumDescuentos = 0.0;
        foreach ($conceptos as $concepto) {
            $importe = (float) ($concepto['importe'] ?? 0);
            if (($concepto['tipo'] ?? '') === 'extra') {
                $sumExtras += $importe;
            } elseif (($concepto['tipo'] ?? '') === 'descuento') {
                $sumDescuentos += $importe;
            }
        }

        $precioFinal = $precioVenta - $descuento + $sumExtras - $sumDescuentos;
        $subtotal = $precioFinal;
        $impuestoImporte = round($subtotal * $impuesto['porcentaje'] / 100, 2);
        $total = round($subtotal + $impuestoImporte, 2);

        return [
            'impuesto_nombre' => $impuesto['nombre'],
            'impuesto_porcentaje' => $impuesto['porcentaje'],
            'precio_final' => $precioFinal,
            'subtotal' => $subtotal,
            'impuesto_importe' => $impuestoImporte,
            'total' => $total,
            'sum_extras' => $sumExtras,
            'sum_descuentos' => $sumDescuentos,
        ];
    }

    /**
     * Recalcula importe de IVA y total sobre un subtotal conocido.
     *
     * @return array{iva_importe: float, total: float}
     */
    public function recalcularFactura(float $subtotal, float $ivaPorcentaje): array
    {
        $ivaImporte = round($subtotal * $ivaPorcentaje / 100, 2);
        $total = round($subtotal + $ivaImporte, 2);

        return [
            'iva_importe' => $ivaImporte,
            'total' => $total,
        ];
    }
}
