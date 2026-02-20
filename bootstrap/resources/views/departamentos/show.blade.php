@extends('layouts.app')
@section('title', $departamento->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle del Departamento</h1>
    <div class="vx-page-actions">
        @can('update', $departamento)
            <a href="{{ route('departamentos.edit', $departamento) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('departamentos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width: 700px;">
    <div class="vx-card" style="margin-bottom: 20px;">
        <div class="vx-card-header">
            <h3><i class="bi bi-diagram-3" style="color: var(--vx-primary); margin-right: 8px;"></i>{{ $departamento->nombre }}</h3>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $departamento->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value">{{ $departamento->nombre }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Abreviatura</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray">{{ $departamento->abreviatura }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Usuarios</div><div class="vx-info-value"><span class="vx-badge vx-badge-info">{{ $departamento->users->count() }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $departamento->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $departamento->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>

    @if($departamento->users->count() > 0)
    <div class="vx-card">
        <div class="vx-card-header"><h4>Usuarios en este Departamento</h4></div>
        <div class="vx-card-body" style="padding: 0;">
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead><tr><th>Nombre</th><th>Email</th><th></th></tr></thead>
                    <tbody>
                        @foreach($departamento->users as $user)
                        <tr>
                            <td style="font-weight: 600;">{{ $user->nombre_completo }}</td>
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
