<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservaSustitucion extends Model
{
    protected $table = 'reservas_sustitucion';

    protected $fillable = ['coche_id', 'cliente_id', 'cliente_nombre', 'fecha_inicio', 'fecha_fin', 'estado', 'observaciones'];

    protected $casts = ['fecha_inicio' => 'date', 'fecha_fin' => 'date'];

    public function coche(): BelongsTo
    {
        return $this->belongsTo(CocheSustitucion::class, 'coche_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function getClienteDisplayAttribute(): string
    {
        return $this->cliente?->nombre_completo ?? $this->cliente_nombre ?? '—';
    }

    public static $estados = ['reservado' => 'Reservado', 'entregado' => 'Entregado', 'devuelto' => 'Devuelto', 'cancelado' => 'Cancelado'];
}
