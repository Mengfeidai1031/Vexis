<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'empresas';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'nombre',
        'abreviatura',
        'cif',
        'domicilio',
        'codigo_postal',
        'telefono',
    ];

    /**
     * Relación: Una empresa tiene muchos centros
     */
    public function centros(): HasMany
    {
        return $this->hasMany(Centro::class);
    }

    /**
     * Relación: Una empresa tiene muchos usuarios
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function talleres(): HasMany
    {
        return $this->hasMany(\App\Models\Taller::class);
    }

    public function marcas()
    {
        return \App\Models\Marca::whereIn('id',
            \App\Models\Taller::where('empresa_id', $this->id)->whereNotNull('marca_id')->distinct()->pluck('marca_id')
        )->get();
    }
}