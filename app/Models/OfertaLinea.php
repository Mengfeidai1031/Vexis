<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfertaLinea extends Model
{
    protected $table = 'oferta_lineas';

    protected $fillable = [
        'oferta_cabecera_id',
        'tipo',
        'descripcion',
        'precio',
    ];

    protected $casts = [
        'precio' => 'float',
    ];

    /**
     * Relación: Una línea pertenece a una oferta cabecera
     */
    public function ofertaCabecera(): BelongsTo
    {
        return $this->belongsTo(OfertaCabecera::class);
    }
}