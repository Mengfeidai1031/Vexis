<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $fillable = ['codigo_venta','vehiculo_id','cliente_id','empresa_id','centro_id','marca_id','vendedor_id','precio_venta','descuento','precio_final','subtotal','impuesto_nombre','impuesto_porcentaje','impuesto_importe','total','forma_pago','estado','fecha_venta','fecha_entrega','observaciones'];
    protected $casts = ['fecha_venta' => 'date', 'fecha_entrega' => 'date', 'precio_venta' => 'decimal:2', 'descuento' => 'decimal:2', 'precio_final' => 'decimal:2', 'subtotal' => 'decimal:2', 'impuesto_porcentaje' => 'decimal:2', 'impuesto_importe' => 'decimal:2', 'total' => 'decimal:2'];

    public function vehiculo(): BelongsTo { return $this->belongsTo(Vehiculo::class); }
    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class); }
    public function centro(): BelongsTo { return $this->belongsTo(Centro::class); }
    public function marca(): BelongsTo { return $this->belongsTo(Marca::class); }
    public function vendedor(): BelongsTo { return $this->belongsTo(User::class, 'vendedor_id'); }
    public function conceptos(): HasMany { return $this->hasMany(VentaConcepto::class); }
    public function extras(): HasMany { return $this->hasMany(VentaConcepto::class)->where('tipo', 'extra'); }
    public function descuentos(): HasMany { return $this->hasMany(VentaConcepto::class)->where('tipo', 'descuento'); }

    public static $formasPago = ['contado'=>'Contado','financiado'=>'Financiado','leasing'=>'Leasing','renting'=>'Renting'];
    public static $estados = ['reservada'=>'Reservada','pendiente_entrega'=>'Pte. Entrega','entregada'=>'Entregada','cancelada'=>'Cancelada'];
}
