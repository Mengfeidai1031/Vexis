<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Factura;
use App\Models\Setting;
use App\Models\Verifactu;

/**
 * Envuelve el registro de una factura en Verifactu respetando el flag `modulo_verifactu`
 * y devolviendo un mensaje flash listo para concatenar a la respuesta.
 */
class VerifactuRegistrationService
{
    public function __construct(private readonly AeatVerifactuService $aeat) {}

    /**
     * Registra una factura en Verifactu si el módulo está activado.
     * Devuelve el texto a añadir al mensaje flash (vacío si no se hace nada).
     */
    public function registrar(Factura $factura, string $tipo = 'alta'): string
    {
        if (! Setting::get('modulo_verifactu', true)) {
            return '';
        }

        try {
            $factura->loadMissing(['empresa', 'cliente']);
            $registro = $this->aeat->registrarFactura($factura, $tipo);

            return match ($tipo) {
                'anulacion' => " Registro de anulación Verifactu {$registro->codigo_registro} generado.",
                default => " Registro Verifactu {$registro->codigo_registro} generado automáticamente.",
            };
        } catch (\Exception $e) {
            return ' (Error Verifactu: '.$e->getMessage().')';
        }
    }

    public function registrarAnulacion(Factura $factura): string
    {
        return $this->registrar($factura, 'anulacion');
    }

    /**
     * Devuelve el registro activo (no anulado/rechazado) de la factura, si existe.
     */
    public function registroActivoDeFactura(Factura $factura): ?Verifactu
    {
        return Verifactu::where('factura_id', $factura->id)
            ->whereNotIn('estado', ['anulado', 'rechazado'])
            ->first();
    }
}
