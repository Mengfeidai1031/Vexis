@extends('layouts.app')

@section('title', $user->nombre_completo . ' - VEXIS')

@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle del Usuario</h1>
    <div class="vx-page-actions">
        @can('update', $user)
            <a href="{{ route('users.edit', $user->id) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('users.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width: 800px;">
    <div class="vx-card">
        <div class="vx-card-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="vx-avatar" style="width: 40px; height: 40px; font-size: 15px; cursor: default;">
                    {{ strtoupper(substr($user->nombre, 0, 1)) }}{{ strtoupper(substr($user->apellidos, 0, 1)) }}
                </div>
                <div>
                    <h3 style="margin: 0;">{{ $user->nombre_completo }}</h3>
                    <span style="font-size: 12px; color: var(--vx-text-muted); font-weight: 400;">ID: {{ $user->id }}</span>
                </div>
            </div>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row">
                <div class="vx-info-label">Nombre</div>
                <div class="vx-info-value">{{ $user->nombre }}</div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Apellidos</div>
                <div class="vx-info-value">{{ $user->apellidos }}</div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Email</div>
                <div class="vx-info-value" style="font-family: var(--vx-font-mono); font-size: 13px;">{{ $user->email }}</div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Teléfono</div>
                <div class="vx-info-value">{{ $user->telefono ?? 'No especificado' }}</div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Extensión</div>
                <div class="vx-info-value">{{ $user->extension ?? 'No especificado' }}</div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Empresa</div>
                <div class="vx-info-value">{{ $user->empresa->nombre }} <span class="vx-badge vx-badge-gray">{{ $user->empresa->abreviatura }}</span></div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Departamento</div>
                <div class="vx-info-value">{{ $user->departamento->nombre }} <span class="vx-badge vx-badge-gray">{{ $user->departamento->abreviatura }}</span></div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Centro</div>
                <div class="vx-info-value">
                    {{ $user->centro->nombre }}<br>
                    <span style="font-size: 12px; color: var(--vx-text-muted);">{{ $user->centro->direccion }}, {{ $user->centro->municipio }}, {{ $user->centro->provincia }}</span>
                </div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Roles</div>
                <div class="vx-info-value">
                    @if($user->roles->count() > 0)
                        @foreach($user->roles as $role)
                            <span class="vx-badge vx-badge-primary">{{ $role->name }}</span>
                        @endforeach
                    @else
                        <span style="color: var(--vx-text-muted);">Sin roles asignados</span>
                    @endif
                </div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Restricciones</div>
                <div class="vx-info-value">
                    @php $restrictionsCount = $user->restrictions->count(); @endphp
                    @if($restrictionsCount > 0)
                        <span class="vx-badge vx-badge-warning">{{ $restrictionsCount }} restricciones</span>
                    @else
                        <span class="vx-badge vx-badge-success">Sin restricciones</span>
                    @endif
                    @can('editar restricciones')
                        <a href="{{ route('restricciones.edit', $user->id) }}" class="vx-btn vx-btn-warning vx-btn-sm" style="margin-left: 8px;">Gestionar</a>
                    @endcan
                </div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Creado</div>
                <div class="vx-info-value">{{ $user->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Actualizado</div>
                <div class="vx-info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
