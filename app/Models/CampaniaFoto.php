<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaniaFoto extends Model
{
    protected $table = 'campania_fotos';

    protected $fillable = [
        'campania_id', 'ruta', 'nombre_original', 'orden',
    ];

    public function campania(): BelongsTo
    {
        return $this->belongsTo(Campania::class);
    }
}
