<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taller extends Model
{
    protected $table = 'talleres';
    protected $fillable = ['nombre','codigo','domicilio','codigo_postal','localidad','isla','telefono','empresa_id','centro_id','marca_id','capacidad_diaria','activo','observaciones'];
    protected $casts = ['activo' => 'boolean'];

    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class); }
    public function centro(): BelongsTo { return $this->belongsTo(Centro::class); }
    public function marca(): BelongsTo { return $this->belongsTo(Marca::class); }
    public function mecanicos(): HasMany { return $this->hasMany(Mecanico::class); }
    public function citas(): HasMany { return $this->hasMany(CitaTaller::class); }
    public function cochesSustitucion(): HasMany { return $this->hasMany(CocheSustitucion::class); }

    public static $islas = ['Gran Canaria','Tenerife','Lanzarote','Fuerteventura','La Palma','La Gomera','El Hierro'];
}
