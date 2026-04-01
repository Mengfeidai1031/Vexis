<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'codigo_factura', 'numero_serie', 'venta_id', 'cliente_id', 'empresa_id', 'centro_id',
        'marca_id', 'emisor_id', 'fecha_factura', 'fecha_vencimiento', 'concepto',
        'subtotal', 'iva_porcentaje', 'iva_importe', 'total', 'estado',
        'tipo_factura', 'clave_regimen_iva', 'factura_simplificada',
        'observaciones', 'pdf_path',
    ];

    protected $casts = [
        'fecha_factura' => 'date',
        'fecha_vencimiento' => 'date',
        'subtotal' => 'decimal:2',
        'iva_porcentaje' => 'decimal:2',
        'iva_importe' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public static array $estados = [
        'emitida' => 'Emitida',
        'pagada' => 'Pagada',
        'vencida' => 'Vencida',
        'anulada' => 'Anulada',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Factura $factura) {
            if ($factura->pdf_path && Storage::disk('public')->exists($factura->pdf_path)) {
                Storage::disk('public')->delete($factura->pdf_path);
            }
        });
    }

    public function venta(): BelongsTo { return $this->belongsTo(Venta::class); }
    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class); }
    public function centro(): BelongsTo { return $this->belongsTo(Centro::class); }
    public function marca(): BelongsTo { return $this->belongsTo(Marca::class); }
    public function emisor(): BelongsTo { return $this->belongsTo(User::class, 'emisor_id'); }
}
