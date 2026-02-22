<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campania extends Model
{
    protected $table = 'campanias';

    protected $fillable = [
        'nombre', 'descripcion', 'marca_id',
        'fecha_inicio', 'fecha_fin', 'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(CampaniaFoto::class, 'campania_id')->orderBy('orden');
    }
}
