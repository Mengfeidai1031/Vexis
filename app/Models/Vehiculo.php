<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = [
        'chasis',
        'modelo',
        'version',
        'color_externo',
        'color_interno',
        'empresa_id',
    ];

    /**
     * Relación: Un vehículo pertenece a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Obtener descripción completa del vehículo
     */
    public function getDescripcionCompletaAttribute(): string
    {
        return "{$this->modelo} {$this->version}";
    }
}