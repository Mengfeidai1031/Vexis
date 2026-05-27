<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsage extends Model
{
    protected $table = 'ai_usage';

    public $timestamps = true;

    protected $fillable = [
        'provider', 'user_id', 'tokens_in', 'tokens_out',
        'http_status', 'ok', 'endpoint', 'error',
    ];

    protected $casts = [
        'tokens_in' => 'integer',
        'tokens_out' => 'integer',
        'http_status' => 'integer',
        'ok' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Resumen rápido para el panel del navbar.
     *
     * @return array{provider:string, today:int, month:int, last_at:?string}[]
     */
    public static function summary(): array
    {
        $now = now();
        $today = $now->copy()->startOfDay();
        $monthStart = $now->copy()->startOfMonth();

        return collect(['chatbot', 'pretasacion'])->map(function (string $p) use ($today, $monthStart) {
            $base = static::where('provider', $p);
            $tok = fn ($q) => (int) (clone $q)->sum('tokens_in') + (int) (clone $q)->sum('tokens_out');

            return [
                'provider' => $p,
                'today' => (clone $base)->where('created_at', '>=', $today)->count(),
                'month' => (clone $base)->where('created_at', '>=', $monthStart)->count(),
                'tokens_today' => $tok((clone $base)->where('created_at', '>=', $today)),
                'tokens_month' => $tok((clone $base)->where('created_at', '>=', $monthStart)),
                'errors_today' => (clone $base)->where('created_at', '>=', $today)->where('ok', false)->count(),
                'last_at' => (clone $base)->latest('created_at')->value('created_at')?->format('d/m/Y H:i'),
            ];
        })->all();
    }
}
