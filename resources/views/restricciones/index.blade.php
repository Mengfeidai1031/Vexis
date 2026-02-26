@extends('layouts.app')
@section('title', 'Restricciones - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Restricciones</h1>
    <div class="vx-page-actions">
        @can('crear restricciones')
            <a href="{{ route('restricciones.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Restricción</a>
        @endcan
    </div>
</div>

<form action="{{ route('restricciones.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por usuario o entidad restringida..." value="{{ request('search') }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
    @if(request('search'))
        <a href="{{ route('restricciones.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>
    @endif
</form>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($restricciones->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Entidad</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($restricciones as $restriccion)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $restriccion->id }}</td>
                                <td>
                                    <div style="font-weight: 600;">{{ $restriccion->user->nombre_completo }}</div>
                                    <div style="font-size: 11px; color: var(--vx-text-muted);">{{ $restriccion->user->email }}</div>
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    @if($restriccion->restrictable)
                                        @if($restriccion->restrictable_type === 'App\Models\Vehiculo')
                                            {{ $restriccion->restrictable->modelo }} {{ $restriccion->restrictable->version }}
                                        @elseif($restriccion->restrictable_type === 'App\Models\Cliente')
                                            {{ $restriccion->restrictable->nombre_completo }}
                                        @else
                                            {{ $restriccion->restrictable->nombre }}
                                        @endif
                                    @else
                                        <span style="color: var(--vx-text-muted);">Entidad eliminada</span>
                                    @endif
                                </td>
                                <td style="font-size: 12px;">{{ $restriccion->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                        @can('view', $restriccion)
                                            <a href="{{ route('restricciones.show', $restriccion) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                                        @endcan
                                        @can('update', $restriccion)
                                            <a href="{{ route('restricciones.edit', $restriccion) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>
                                        @endcan
                                        @can('delete', $restriccion)
                                            <form action="{{ route('restricciones.destroy', $restriccion) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta restricción?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                            </form>
                                        @endcan
                                    </div></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 16px 20px;">{{ $restricciones->links('vendor.pagination.vexis') }}</div>
        @else
            <div class="vx-empty"><i class="bi bi-shield-lock"></i><p>No se encontraron restricciones.</p></div>
        @endif
    </div>
</div>
@endsection
