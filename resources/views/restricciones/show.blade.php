@extends('layouts.app')
@section('title', 'Restricción #' . $restriccion->id . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle de la Restricción</h1>
    <div class="vx-page-actions">
        @can('update', $restriccion)
            <a href="{{ route('restricciones.edit', $restriccion) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('restricciones.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width: 700px;">
    <div class="vx-card">
        <div class="vx-card-header"><h3><i class="bi bi-shield-excl" style="color: var(--vx-warning); margin-right: 8px;"></i>Restricción #{{ $restriccion->id }}</h3></div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $restriccion->id }}</div></div>
            <div class="vx-info-row">
                <div class="vx-info-label">Usuario</div>
                <div class="vx-info-value">
                    <div style="font-weight: 600;">{{ $restriccion->user->nombre_completo }}</div>
                    <div style="font-size: 12px; color: var(--vx-text-muted);">{{ $restriccion->user->email }}</div>
                </div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Tipo</div>
                <div class="vx-info-value">
                    @php
                        $typeName = match($restriccion->restrictable_type) {
                            'App\Models\Empresa' => 'Empresa',
                            'App\Models\Cliente' => 'Cliente',
                            'App\Models\Vehiculo' => 'Vehículo',
                            'App\Models\Centro' => 'Centro',
                            'App\Models\Departamento' => 'Departamento',
                            default => 'Desconocido',
                        };
                    @endphp
                    <span class="vx-badge vx-badge-info">{{ $typeName }}</span>
                </div>
            </div>
            <div class="vx-info-row">
                <div class="vx-info-label">Entidad</div>
                <div class="vx-info-value">
                    @if($restriccion->restrictable)
                        @if($restriccion->restrictable_type === 'App\Models\Empresa')
                            <strong>{{ $restriccion->restrictable->nombre }}</strong>
                            @if($restriccion->restrictable->cif)<br><span style="font-size:12px;color:var(--vx-text-muted);">CIF: {{ $restriccion->restrictable->cif }}</span>@endif
                        @elseif($restriccion->restrictable_type === 'App\Models\Cliente')
                            <strong>{{ $restriccion->restrictable->nombre_completo }}</strong><br>
                            <span style="font-size:12px;color:var(--vx-text-muted);">{{ $restriccion->restrictable->email }} · {{ $restriccion->restrictable->empresa->nombre }}</span>
                        @elseif($restriccion->restrictable_type === 'App\Models\Vehiculo')
                            <strong>{{ $restriccion->restrictable->modelo }} {{ $restriccion->restrictable->version }}</strong><br>
                            <span style="font-size:12px;color:var(--vx-text-muted);">{{ $restriccion->restrictable->empresa->nombre }}</span>
                        @elseif($restriccion->restrictable_type === 'App\Models\Centro')
                            <strong>{{ $restriccion->restrictable->nombre }}</strong><br>
                            <span style="font-size:12px;color:var(--vx-text-muted);">{{ $restriccion->restrictable->empresa->nombre }}</span>
                        @elseif($restriccion->restrictable_type === 'App\Models\Departamento')
                            <strong>{{ $restriccion->restrictable->nombre }}</strong>
                            @if($restriccion->restrictable->abreviatura) <span class="vx-badge vx-badge-gray">{{ $restriccion->restrictable->abreviatura }}</span>@endif
                        @endif
                    @else
                        <span style="color: var(--vx-danger);">Entidad eliminada</span>
                    @endif
                </div>
            </div>
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $restriccion->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $restriccion->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>
@endsection
