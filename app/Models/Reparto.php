<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reparto extends Model
{
    protected $table = 'repartos';

    protected $fillable = [
        'codigo_reparto', 'stock_id', 'almacen_origen_id', 'almacen_destino_id',
        'empresa_id', 'centro_id', 'cantidad', 'estado',
        'fecha_solicitud', 'fecha_entrega', 'solicitado_por', 'observaciones',
    ];

    protected $casts = ['fecha_solicitud' => 'date', 'fecha_entrega' => 'date'];

    public function stock(): BelongsTo { return $this->belongsTo(Stock::class); }
    public function almacenOrigen(): BelongsTo { return $this->belongsTo(Almacen::class, 'almacen_origen_id'); }
    public function almacenDestino(): BelongsTo { return $this->belongsTo(Almacen::class, 'almacen_destino_id'); }
    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class); }
    public function centro(): BelongsTo { return $this->belongsTo(Centro::class); }

    public static $estados = [
        'pendiente' => 'Pendiente',
        'en_transito' => 'En Tránsito',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado',
    ];
}
