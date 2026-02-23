<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $fillable = [
        'referencia', 'nombre_pieza', 'descripcion', 'marca_pieza',
        'cantidad', 'stock_minimo', 'precio_unitario', 'ubicacion_almacen',
        'almacen_id', 'empresa_id', 'centro_id', 'activo',
    ];

    protected $casts = ['activo' => 'boolean', 'precio_unitario' => 'decimal:2'];

    public function almacen(): BelongsTo { return $this->belongsTo(Almacen::class); }
    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class); }
    public function centro(): BelongsTo { return $this->belongsTo(Centro::class); }

    public function isBajoStock(): bool { return $this->cantidad <= $this->stock_minimo; }
}
