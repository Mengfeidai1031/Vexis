<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Almacen extends Model
{
    protected $table = 'almacenes';

    protected $fillable = [
        'nombre', 'codigo', 'domicilio', 'codigo_postal', 'localidad',
        'isla', 'telefono', 'empresa_id', 'centro_id', 'activo', 'observaciones',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function centro(): BelongsTo
    {
        return $this->belongsTo(Centro::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public static $islas = [
        'Gran Canaria', 'Tenerife', 'Lanzarote', 'Fuerteventura',
        'La Palma', 'La Gomera', 'El Hierro',
    ];
}
