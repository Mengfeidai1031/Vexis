@extends('layouts.app')
@section('title', 'Política de Seguridad - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Política de Seguridad</h1>
    <a href="{{ route('gestion.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Gestión</a>
</div>
<div style="max-width:800px;">
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-shield-check" style="color:var(--vx-primary);"></i> Acceso al Sistema</h4></div>
        <div class="vx-card-body" style="font-size:14px;line-height:1.7;color:var(--vx-text-secondary);">
            <p>El acceso a VEXIS está protegido mediante autenticación por credenciales (email y contraseña). Cada usuario tiene asignado un rol que determina sus permisos dentro del sistema. Los roles disponibles son: Super Admin, Administrador, Gerente, Vendedor y Consultor.</p>
            <p>Las sesiones se invalidan al cerrar sesión y los tokens CSRF protegen todas las operaciones de escritura.</p>
        </div>
    </div>
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-key" style="color:var(--vx-warning);"></i> Contraseñas</h4></div>
        <div class="vx-card-body" style="font-size:14px;line-height:1.7;color:var(--vx-text-secondary);">
            <p>Las contraseñas se almacenan con hash bcrypt y nunca en texto plano. Se recomienda un mínimo de 6 caracteres. Los usuarios pueden cambiar su contraseña desde su perfil verificando la contraseña actual.</p>
        </div>
    </div>
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-lock" style="color:var(--vx-danger);"></i> Restricciones de Datos</h4></div>
        <div class="vx-card-body" style="font-size:14px;line-height:1.7;color:var(--vx-text-secondary);">
            <p>El sistema implementa restricciones polimórficas que permiten limitar el acceso de usuarios específicos a entidades concretas (empresas, clientes, vehículos, centros, departamentos). Estas restricciones son gestionadas por los administradores desde el módulo de Seguridad.</p>
        </div>
    </div>
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-database-lock" style="color:var(--vx-success);"></i> Protección de Datos</h4></div>
        <div class="vx-card-body" style="font-size:14px;line-height:1.7;color:var(--vx-text-secondary);">
            <p>Todas las comunicaciones se realizan a través de conexiones seguras. Los datos personales de clientes se gestionan conforme a la normativa vigente de protección de datos (RGPD/LOPDGDD). Los archivos PDF subidos se almacenan en el servidor con acceso controlado.</p>
        </div>
    </div>
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-clipboard-check" style="color:var(--vx-info);"></i> Auditoría</h4></div>
        <div class="vx-card-body" style="font-size:14px;line-height:1.7;color:var(--vx-text-secondary);">
            <p>Todas las operaciones de creación, edición y eliminación generan notificaciones en tiempo real. El sistema mantiene marcas temporales (created_at, updated_at) en todos los registros para trazabilidad.</p>
        </div>
    </div>
</div>
@endsection
