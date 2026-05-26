<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculo extends Model
{
    use SoftDeletes;

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
        'estado',
        'responsable_id',
    ];

    public static array $estados = [
        'disponible' => 'Disponible',
        'reservado' => 'Reservado',
        'vendido' => 'Vendido',
        'taller' => 'En Taller',
        'baja' => 'Baja',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(VehiculoHistorial::class)->orderByDesc('created_at');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(VehiculoDocumento::class)->orderByDesc('created_at');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function getDescripcionCompletaAttribute(): string
    {
        return "{$this->modelo} {$this->version}";
    }

    public function getEstadoEtiquetaAttribute(): string
    {
        return self::$estados[$this->estado] ?? $this->estado;
    }

    public function scopeDisponible($query)
    {
        return $query->where('estado', 'disponible');
    }
}
