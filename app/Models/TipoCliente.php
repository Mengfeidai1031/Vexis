<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TipoCliente extends Model
{
    protected $table = 'tipos_cliente';

    protected $fillable = ['nombre', 'slug', 'descripcion', 'color', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (TipoCliente $t) {
            if (empty($t->slug)) {
                $t->slug = Str::slug($t->nombre);
            }
        });
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }
}
