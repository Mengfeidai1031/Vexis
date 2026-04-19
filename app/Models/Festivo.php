<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festivo extends Model
{
    protected $table = 'festivos';

    protected $fillable = [
        'nombre', 'fecha', 'municipio', 'ambito', 'anio',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public static $ambitos = [
        'nacional' => 'Nacional',
        'autonomico' => 'Autonómico (Canarias)',
        'local' => 'Local',
    ];
}
