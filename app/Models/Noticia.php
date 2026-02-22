<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Noticia extends Model
{
    protected $table = 'noticias';

    protected $fillable = [
        'titulo', 'contenido', 'imagen_url', 'categoria',
        'destacada', 'publicada', 'autor_id', 'fecha_publicacion',
    ];

    protected $casts = [
        'destacada' => 'boolean',
        'publicada' => 'boolean',
        'fecha_publicacion' => 'datetime',
    ];

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public static $categorias = [
        'general' => 'General',
        'empresa' => 'Empresa',
        'comercial' => 'Comercial',
        'rrhh' => 'Recursos Humanos',
        'tecnologia' => 'Tecnología',
    ];
}
