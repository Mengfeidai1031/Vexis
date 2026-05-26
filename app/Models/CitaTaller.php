<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitaTaller extends Model
{
    protected $table = 'citas_taller';

    protected $fillable = ['mecanico_id', 'taller_id', 'marca_id', 'empresa_id', 'cliente_id', 'vehiculo_id', 'cliente_nombre', 'vehiculo_info', 'fecha', 'hora_inicio', 'hora_fin', 'descripcion', 'estado'];

    protected $casts = ['fecha' => 'date'];

    public function mecanico(): BelongsTo
    {
        return $this->belongsTo(Mecanico::class);
    }

    public function taller(): BelongsTo
    {
        return $this->belongsTo(Taller::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function getClienteDisplayAttribute(): string
    {
        return $this->cliente?->nombre_completo ?? $this->cliente_nombre ?? '—';
    }

    public function getVehiculoDisplayAttribute(): string
    {
        if ($this->vehiculo) {
            return trim(($this->vehiculo->matricula ?? '').' '.($this->vehiculo->modelo ?? ''));
        }

        return $this->vehiculo_info ?? '—';
    }

    public static $estados = ['pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'en_curso' => 'En Curso', 'completada' => 'Completada', 'cancelada' => 'Cancelada'];
}
