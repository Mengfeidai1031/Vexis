<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Incidencia extends Model
{
    protected $fillable = [
        'codigo_incidencia', 'titulo', 'descripcion',
        'usuario_id', 'tecnico_id', 'prioridad', 'estado',
        'comentario_tecnico', 'fecha_apertura', 'fecha_cierre',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    public static array $estados = [
        'abierta' => 'Abierta',
        'en_progreso' => 'En Progreso',
        'resuelta' => 'Resuelta',
        'cerrada' => 'Cerrada',
    ];

    public static array $prioridades = [
        'baja' => 'Baja',
        'media' => 'Media',
        'alta' => 'Alta',
        'critica' => 'Crítica',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Incidencia $incidencia) {
            foreach ($incidencia->archivos as $archivo) {
                if (Storage::disk('public')->exists($archivo->ruta)) {
                    Storage::disk('public')->delete($archivo->ruta);
                }
            }
        });
    }

    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'usuario_id'); }
    public function tecnico(): BelongsTo { return $this->belongsTo(User::class, 'tecnico_id'); }
    public function archivos(): HasMany { return $this->hasMany(IncidenciaArchivo::class); }
    public function archivosUsuario(): HasMany { return $this->hasMany(IncidenciaArchivo::class)->where('tipo', 'usuario'); }
    public function archivosTecnico(): HasMany { return $this->hasMany(IncidenciaArchivo::class)->where('tipo', 'tecnico'); }
}
