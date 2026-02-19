<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserRestriction extends Model
{
    protected $fillable = [
        'user_id',
        'restrictable_type',
        'restrictable_id',
    ];

    /**
     * Relación: Una restricción pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación polimórfica: La restricción puede ser de cualquier modelo
     */
    public function restrictable(): MorphTo
    {
        return $this->morphTo();
    }
}
