<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mecanico extends Model
{
    protected $table = 'mecanicos';
    protected $fillable = ['nombre','apellidos','especialidad','taller_id','activo'];
    protected $casts = ['activo' => 'boolean'];

    public function taller(): BelongsTo { return $this->belongsTo(Taller::class); }
    public function citas(): HasMany { return $this->hasMany(CitaTaller::class); }
    public function getNombreCompletoAttribute(): string { return $this->nombre . ' ' . $this->apellidos; }
}
