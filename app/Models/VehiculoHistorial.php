<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiculoHistorial extends Model
{
    protected $table = 'vehiculo_historial';

    protected $fillable = [
        'vehiculo_id', 'user_id', 'accion', 'campo',
        'valor_anterior', 'valor_nuevo', 'observaciones',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
