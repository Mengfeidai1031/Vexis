<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verifactu extends Model
{
    protected $table = 'verifactus';

    protected $fillable = [
        'codigo_registro', 'factura_id', 'hash_registro', 'hash_anterior',
        'fecha_registro', 'estado', 'tipo_operacion', 'nif_emisor',
        'nombre_emisor', 'importe_total', 'respuesta_aeat', 'observaciones',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'importe_total' => 'decimal:2',
        'respuesta_aeat' => 'array',
    ];

    public static array $estados = [
        'registrado' => 'Registrado',
        'enviado' => 'Enviado',
        'validado' => 'Validado',
        'rechazado' => 'Rechazado',
        'anulado' => 'Anulado',
    ];

    public static array $tiposOperacion = [
        'emision' => 'Emisión',
        'anulacion' => 'Anulación',
        'rectificacion' => 'Rectificación',
    ];

    public function factura(): BelongsTo { return $this->belongsTo(Factura::class); }

    /**
     * Generate SHA-256 chained hash for Verifactu compliance.
     */
    public static function generateHash(Factura $factura, ?string $hashAnterior = null): string
    {
        $data = implode('|', [
            $factura->codigo_factura,
            $factura->fecha_factura->format('Y-m-d'),
            $factura->total,
            $factura->empresa?->cif ?? '',
            $factura->cliente?->dni ?? '',
            $hashAnterior ?? 'GENESIS',
        ]);

        return hash('sha256', $data);
    }
}
