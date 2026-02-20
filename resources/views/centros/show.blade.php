@extends('layouts.app')
@section('title', $centro->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle del Centro</h1>
    <div class="vx-page-actions">
        @can('update', $centro)
            <a href="{{ route('centros.edit', $centro) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('centros.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width: 800px;">
    <div class="vx-card" style="margin-bottom: 20px;">
        <div class="vx-card-header">
            <h3><i class="bi bi-geo-alt" style="color: var(--vx-primary); margin-right: 8px;"></i>{{ $centro->nombre }}</h3>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $centro->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value">{{ $centro->nombre }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $centro->empresa->nombre }} <span class="vx-badge vx-badge-gray">{{ $centro->empresa->abreviatura }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Dirección</div><div class="vx-info-value">{{ $centro->direccion }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Municipio</div><div class="vx-info-value">{{ $centro->municipio }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Provincia</div><div class="vx-info-value">{{ $centro->provincia }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Usuarios</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $centro->users->count() }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $centro->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $centro->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>

    @if($centro->users->count() > 0)
    <div class="vx-card">
        <div class="vx-card-header"><h4>Usuarios en este Centro</h4></div>
        <div class="vx-card-body" style="padding: 0;">
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead><tr><th>Nombre</th><th>Departamento</th><th>Email</th><th></th></tr></thead>
                    <tbody>
                        @foreach($centro->users as $user)
                        <tr>
                            <td style="font-weight: 600;">{{ $user->nombre_completo }}</td>
                            <td>{{ $user->departamento->nombre }}</td>
                            <td style="font-size: 12px; color: var(--vx-text-secondary);">{{ $user->email }}</td>
                            <td>
                                @can('view', $user)
                                    <a href="{{ route('users.show', $user) }}" class="vx-btn vx-btn-info vx-btn-sm"><i class="bi bi-eye"></i></a>
                                @endcan
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
