<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CocheSustitucion extends Model
{
    protected $table = 'coches_sustitucion';
    protected $fillable = ['matricula','modelo','marca_id','taller_id','empresa_id','disponible','color','anio','observaciones'];
    protected $casts = ['disponible' => 'boolean'];

    public function marca(): BelongsTo { return $this->belongsTo(Marca::class); }
    public function taller(): BelongsTo { return $this->belongsTo(Taller::class); }
    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class); }
    public function reservas(): HasMany { return $this->hasMany(ReservaSustitucion::class, 'coche_id'); }
}
