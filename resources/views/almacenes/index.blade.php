@extends('layouts.app')
@section('title', 'Almacenes - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-boxes" style="color:var(--vx-primary);margin-right:6px;"></i>Almacenes</h1>
    <div class="vx-page-actions">
        @can('crear almacenes')
            <a href="{{ route('almacenes.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Almacén</a>
        @endcan
    </div>
</div>
<form action="{{ route('almacenes.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre, código, domicilio..." value="{{ request('search') }}" style="flex:1;">
    <select name="isla" class="vx-select" style="width:auto;">
        <option value="">Todas las islas</option>
        @foreach(\App\Models\Almacen::$islas as $isla)
            <option value="{{ $isla }}" {{ request('isla') == $isla ? 'selected' : '' }}>{{ $isla }}</option>
        @endforeach
    </select>
    <select name="empresa_id" class="vx-select" style="width:auto;">
        <option value="">Todas las empresas</option>
        @foreach($empresas as $e)
            <option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
        @endforeach
    </select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','isla','empresa_id']))<a href="{{ route('almacenes.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        @if($almacenes->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead><tr><th>Código</th><th>Nombre</th><th>Localidad</th><th>Isla</th><th>Empresa</th><th>Centro</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach($almacenes as $almacen)
                    <tr>
                        <td><span class="vx-badge vx-badge-primary" style="font-family:var(--vx-font-mono);">{{ $almacen->codigo }}</span></td>
                        <td style="font-weight:600;">{{ $almacen->nombre }}</td>
                        <td style="font-size:12px;">{{ $almacen->localidad ?? '—' }}</td>
                        <td><span class="vx-badge vx-badge-info">{{ $almacen->isla ?? '—' }}</span></td>
                        <td style="font-size:12px;">{{ $almacen->empresa->abreviatura ?? '—' }}</td>
                        <td style="font-size:12px;">{{ $almacen->centro->nombre ?? '—' }}</td>
                        <td>@if($almacen->activo)<span class="vx-badge vx-badge-success">Activo</span>@else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif</td>
                        <td>
                            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                @can('ver almacenes')<a href="{{ route('almacenes.show', $almacen) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>@endcan
                                @can('editar almacenes')<a href="{{ route('almacenes.edit', $almacen) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                                @can('eliminar almacenes')
                                <form action="{{ route('almacenes.destroy', $almacen) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
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
        <div style="padding:16px 20px;">{{ $almacenes->links('vendor.pagination.vexis') }}</div>
        @else
        <div class="vx-empty"><i class="bi bi-boxes"></i><p>No se encontraron almacenes.</p></div>
        @endif
    </div>
</div>
@endsection
