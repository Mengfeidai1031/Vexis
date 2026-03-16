<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NamingPc extends Model
{
    protected $table = 'naming_pcs';

    protected $fillable = [
        'nombre_equipo', 'tipo', 'ubicacion', 'centro_id', 'empresa_id',
        'direccion_ip', 'direccion_mac',
        'sistema_operativo', 'version_so', 'observaciones', 'activo',
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

    public static $tipos = ['Portátil', 'Sobremesa'];

    public static $sistemasOperativos = [
        'Windows 11', 'Windows 10', 'Windows Server 2025', 'Windows Server 2022',
        'macOS Sonoma', 'macOS Sequoia', 'macOS Ventura',
        'Ubuntu 24.04 LTS', 'Ubuntu 22.04 LTS', 'Debian 12', 'Fedora 41',
        'Linux Mint 22', 'Red Hat Enterprise Linux 9', 'Chrome OS',
    ];

    public static $versionesSo = ['PRO', 'HOME'];
}
