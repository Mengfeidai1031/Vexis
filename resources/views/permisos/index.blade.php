@extends('layouts.app')
@section('title', 'Permisos del sistema - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Permisos del sistema</h1>
    <div class="vx-page-actions">
        <a href="{{ route('permisos.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-lg"></i> Nuevo permiso</a>
        <a href="{{ route('gestion.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<p style="color:var(--vx-text-muted);margin-bottom:16px;">Gestión del catálogo de permisos Spatie. Sólo accesible para Super Admin.</p>

<x-filtros-avanzados :action="route('permisos.index')">
    <div class="vx-filtro" data-filtro="id"><label class="vx-filtro-label">ID</label><input type="number" name="id" class="vx-input" value="{{ request('id') }}" placeholder="#"></div>
    <div class="vx-filtro" data-filtro="nombre"><label class="vx-filtro-label">Nombre</label><select name="nombre" class="vx-select"><option value="">Todos</option>@foreach($permissions_all as $n)<option value="{{ $n }}" {{ request('nombre') == $n ? 'selected' : '' }}>{{ $n }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="roles_min"><label class="vx-filtro-label">Roles (mín.)</label><input type="number" name="roles_min" class="vx-input" value="{{ request('roles_min') }}" min="0" placeholder="0"></div>
</x-filtros-avanzados>

<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        <table class="vx-table">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Nombre</th>
                    <th style="width:120px;">Roles</th>
                    <th style="width:140px;text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td><span style="font-family:var(--vx-font-mono);font-size:12.5px;"><i class="bi bi-key" style="color:var(--vx-text-muted);margin-right:4px;"></i>{{ $permission->name }}</span></td>
                    <td><span class="vx-badge vx-badge-info">{{ $permission->roles->count() }}</span></td>
                    <td style="text-align:right;">
                        <form action="{{ route('permisos.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar el permiso {{ $permission->name }}? Se quitará de todos los roles que lo tengan.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="vx-btn vx-btn-danger vx-btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--vx-text-muted);">No hay permisos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="vx-pagination-wrapper">{{ $permissions->links() }}</div>
@endsection
