<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellidos',
        'empresa_id',
        'tipo_cliente_id',
        'dni',
        'email',
        'telefono',
        'domicilio',
        'codigo_postal',
    ];

    /**
     * Relación: Un cliente pertenece a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function tipoCliente(): BelongsTo
    {
        return $this->belongsTo(TipoCliente::class, 'tipo_cliente_id');
    }

    /**
     * Obtener el nombre completo del cliente
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}