@extends('layouts.app')
@section('title', 'Centros - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Centros</h1>
    <div class="vx-page-actions">
        @can('crear centros')
            <a href="{{ route('centros.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Centro</a>
        @endcan
    </div>
</div>

<form action="{{ route('centros.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre, dirección, provincia, municipio o empresa..." value="{{ request('search') }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
    @if(request('search'))
        <a href="{{ route('centros.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>
    @endif
</form>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($centros->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Empresa</th>
                            <th>Dirección</th>
                            <th>Municipio</th>
                            <th>Provincia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($centros as $centro)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $centro->id }}</td>
                                <td style="font-weight: 600;">{{ $centro->nombre }}</td>
                                <td><span class="vx-badge vx-badge-primary">{{ $centro->empresa->abreviatura ?? $centro->empresa->nombre }}</span></td>
                                <td style="font-size: 12px;">{{ $centro->direccion }}</td>
                                <td>{{ $centro->municipio }}</td>
                                <td>{{ $centro->provincia }}</td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">@can('view', $centro)
                                            <a href="{{ route('centros.show', $centro) }}" class="vx-btn vx-btn-info vx-btn-sm" title="Ver"><i class="bi bi-eye"></i></a>
                                        @endcan
                                        @can('update', $centro)
                                            <a href="{{ route('centros.edit', $centro) }}" class="vx-btn vx-btn-warning vx-btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $centro)
                                            <form action="{{ route('centros.destroy', $centro) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este centro?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="vx-btn vx-btn-danger vx-btn-sm" title="Eliminar"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endcan</div></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 16px 20px;">{{ $centros->links('vendor.pagination.vexis') }}</div>
        @else
            <div class="vx-empty"><i class="bi bi-geo-alt"></i><p>No se encontraron centros.</p></div>
        @endif
    </div>
</div>
@endsection
