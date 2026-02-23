<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacacion extends Model
{
    protected $table = 'vacaciones';

    protected $fillable = [
        'user_id', 'fecha_inicio', 'fecha_fin', 'dias_solicitados',
        'estado', 'motivo', 'respuesta', 'aprobado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public static $estados = [
        'pendiente' => 'Pendiente',
        'aprobada' => 'Aprobada',
        'rechazada' => 'Rechazada',
    ];

    public const DIAS_TOTALES = 30;

    public static function diasUsados(int $userId, ?int $anio = null): int
    {
        $anio = $anio ?? now()->year;
        return static::where('user_id', $userId)
            ->where('estado', 'aprobada')
            ->whereYear('fecha_inicio', $anio)
            ->sum('dias_solicitados');
    }
}
