<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NamingPc extends Model
{
    protected $table = 'naming_pcs';

    protected $fillable = [
        'nombre_equipo', 'tipo', 'ubicacion', 'centro_id', 'empresa_id',
        'usuario_asignado', 'direccion_ip', 'direccion_mac',
        'sistema_operativo', 'observaciones', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function centro(): BelongsTo
    {
        return $this->belongsTo(Centro::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public static $tipos = ['PC', 'Portátil', 'Servidor', 'Impresora', 'Otro'];
}
