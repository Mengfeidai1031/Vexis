<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tasacion extends Model
{
    protected $table = 'tasaciones';
    protected $fillable = ['codigo_tasacion','cliente_id','empresa_id','marca_id','tasador_id','vehiculo_marca','vehiculo_modelo','vehiculo_anio','kilometraje','matricula','combustible','estado_vehiculo','valor_estimado','valor_final','estado','observaciones','fecha_tasacion'];
    protected $casts = ['fecha_tasacion' => 'date', 'valor_estimado' => 'decimal:2', 'valor_final' => 'decimal:2'];

    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class); }
    public function marca(): BelongsTo { return $this->belongsTo(Marca::class); }
    public function tasador(): BelongsTo { return $this->belongsTo(User::class, 'tasador_id'); }

    public static $estados = ['pendiente'=>'Pendiente','valorada'=>'Valorada','aceptada'=>'Aceptada','rechazada'=>'Rechazada'];
    public static $estadosVehiculo = ['excelente'=>'Excelente','bueno'=>'Bueno','regular'=>'Regular','malo'=>'Malo'];
    public static $combustibles = ['Gasolina','Diésel','Híbrido','Eléctrico','GLP'];
}
