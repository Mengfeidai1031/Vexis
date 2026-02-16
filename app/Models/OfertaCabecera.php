<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfertaCabecera extends Model
{
    protected $table = 'oferta_cabeceras';

    protected $fillable = [
        'cliente_id',
        'vehiculo_id',
        'fecha',
        'pdf_path',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * Relación: Una oferta pertenece a un cliente
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación: Una oferta pertenece a un vehículo
     */
    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Relación: Una oferta tiene muchas líneas
     */
    public function lineas(): HasMany
    {
        return $this->hasMany(OfertaLinea::class);
    }

    /**
     * Calcular el precio total de la oferta
     */
    public function getPrecioTotalAttribute(): float
    {
        return $this->lineas->sum('precio');
    }
}