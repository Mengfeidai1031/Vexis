<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiculoDocumento extends Model
{
    protected $table = 'vehiculo_documentos';

    protected $fillable = [
        'vehiculo_id', 'user_id', 'tipo', 'nombre_original',
        'ruta', 'mime', 'tamano_bytes', 'fecha_vencimiento', 'observaciones',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'tamano_bytes' => 'integer',
    ];

    public static array $tipos = [
        'ficha_tecnica' => 'Ficha Técnica',
        'itv' => 'ITV',
        'permiso_circulacion' => 'Permiso de Circulación',
        'seguro' => 'Seguro',
        'contrato' => 'Contrato',
        'otro' => 'Otro',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTipoEtiquetaAttribute(): string
    {
        return self::$tipos[$this->tipo] ?? $this->tipo;
    }
}
