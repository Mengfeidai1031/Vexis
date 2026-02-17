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
        'cliente_nombre_pdf',
        'cliente_dni_pdf',
        'vehiculo_modelo_pdf',
        'vehiculo_chasis_pdf',
        'base_imponible',
        'impuestos',
        'total_sin_impuestos',
        'total_con_impuestos',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'base_imponible' => 'float',
        'impuestos' => 'float',
        'total_sin_impuestos' => 'float',
        'total_con_impuestos' => 'float',
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

    /**
     * Obtener subtotal de opciones
     */
    public function getSubtotalOpcionesAttribute(): float
    {
        return $this->lineas()->where('tipo', 'opciones')->sum('precio');
    }

    /**
     * Obtener subtotal de descuentos
     */
    public function getSubtotalDescuentosAttribute(): float
    {
        return $this->lineas()->where('tipo', 'descuento')->sum('precio');
    }

    /**
     * Obtener subtotal de accesorios
     */
    public function getSubtotalAccesoriosAttribute(): float
    {
        return $this->lineas()->where('tipo', 'accesorios')->sum('precio');
    }
}