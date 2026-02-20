@extends('layouts.app')
@section('title', $role->name . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle del Rol</h1>
    <div class="vx-page-actions">
        @can('editar roles')
            <a href="{{ route('roles.edit', $role->id) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('roles.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width: 900px;">
    <div class="vx-card" style="margin-bottom: 20px;">
        <div class="vx-card-header">
            <h3><i class="bi bi-shield-lock" style="color: var(--vx-primary); margin-right: 8px;"></i>{{ $role->name }}</h3>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $role->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value" style="font-weight: 600;">{{ $role->name }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Permisos</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $role->permissions->count() }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Usuarios</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray">{{ $role->users->count() }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $role->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $role->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>

    {{-- Permisos agrupados --}}
    @if($role->permissions->count() > 0)
    <div class="vx-card" style="margin-bottom: 20px;">
        <div class="vx-card-header"><h4>Permisos Asignados</h4></div>
        <div class="vx-card-body">
            @php
                $groupedPermissions = $role->permissions->groupBy(function($permission) {
                    return explode(' ', $permission->name)[1] ?? 'otros';
                });
            @endphp
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                @foreach($groupedPermissions as $module => $permissions)
                    <div class="vx-section">
                        <div class="vx-section-header" style="text-transform: capitalize;">{{ ucfirst($module) }}</div>
                        <div class="vx-section-body">
                            @foreach($permissions as $permission)
                                <div style="display: flex; align-items: center; gap: 6px; padding: 3px 0; font-size: 12px;">
                                    <i class="bi bi-check-circle-fill" style="color: var(--vx-success); font-size: 14px;"></i>
                                    {{ $permission->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="vx-alert vx-alert-warning" style="margin-bottom: 20px;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>Este rol no tiene permisos asignados.</span>
    </div>
    @endif

    {{-- Usuarios con este rol --}}
    @if($role->users->count() > 0)
    <div class="vx-card">
        <div class="vx-card-header"><h4>Usuarios con este Rol</h4></div>
        <div class="vx-card-body" style="padding: 0;">
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead><tr><th>Nombre</th><th>Email</th><th>Departamento</th><th></th></tr></thead>
                    <tbody>
                        @foreach($role->users as $user)
                        <tr>
                            <td style="font-weight: 600;">{{ $user->nombre_completo }}</td>
                            <td style="font-size: 12px; color: var(--vx-text-secondary);">{{ $user->email }}</td>
                            <td>{{ $user->departamento->nombre }}</td>
                            <td>
                                <a href="{{ route('users.show', $user->id) }}" class="vx-btn vx-btn-info vx-btn-sm"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
