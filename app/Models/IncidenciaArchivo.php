<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class IncidenciaArchivo extends Model
{
    protected $table = 'incidencia_archivos';

    protected $fillable = [
        'incidencia_id', 'user_id', 'ruta', 'nombre_original', 'tipo',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (IncidenciaArchivo $archivo) {
            if (Storage::disk('public')->exists($archivo->ruta)) {
                Storage::disk('public')->delete($archivo->ruta);
            }
        });
    }

    public function incidencia(): BelongsTo
    {
        return $this->belongsTo(Incidencia::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
