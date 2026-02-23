<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogoPrecio extends Model
{
    protected $table = 'catalogo_precios';
    protected $fillable = ['marca_id','modelo','version','combustible','potencia_cv','precio_base','precio_oferta','disponible','imagen_url','anio_modelo'];
    protected $casts = ['disponible' => 'boolean', 'precio_base' => 'decimal:2', 'precio_oferta' => 'decimal:2'];

    public function marca(): BelongsTo { return $this->belongsTo(Marca::class); }

    public static $combustibles = ['Gasolina','Diésel','Híbrido','Eléctrico','GLP'];
}
