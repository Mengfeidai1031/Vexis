<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Centro extends Model
{
    protected $table = 'centros';

    protected $fillable = [
        'nombre',
        'empresa_id',
        'direccion',
        'provincia',
        'municipio',
    ];

    /**
     * Relación: Un centro pertenece a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relación: Un centro tiene muchos usuarios
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
