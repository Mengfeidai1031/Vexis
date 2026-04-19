<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';

    protected $fillable = [
        'nombre',
        'slug',
        'color',
        'logo_url',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];
}
