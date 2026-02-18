<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nombre',
        'apellidos',
        'empresa_id',
        'departamento_id',
        'centro_id',
        'email',
        'telefono',
        'extension',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: Un usuario pertenece a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relación: Un usuario pertenece a un departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Relación: Un usuario pertenece a un centro
     */
    public function centro(): BelongsTo
    {
        return $this->belongsTo(Centro::class);
    }

    /**
     * Relación: Un usuario tiene muchas restricciones
     */
    public function restrictions(): HasMany
    {
        return $this->hasMany(UserRestriction::class);
    }

    /**
     * Obtener restricciones de un tipo específico (polimórfico)
     */
    public function restrictionsOfType(string $modelClass): HasMany
    {
        return $this->hasMany(UserRestriction::class)
            ->where('restrictable_type', $modelClass);
    }

    /**
     * Obtener el nombre completo del usuario
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}