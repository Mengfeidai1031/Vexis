<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = [
        'chasis',
        'matricula',
        'modelo',
        'version',
        'color_externo',
        'color_interno',
        'empresa_id',
        'marca_id',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * Obtener descripción completa del vehículo
     */
    public function getDescripcionCompletaAttribute(): string
    {
        return "{$this->modelo} {$this->version}";
    }
}