@extends('layouts.app')
@section('title', 'Vehículos - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Vehículos</h1>
    <div class="vx-page-actions">
        @can('ver vehículos')
            <a href="{{ route('vehiculos.export') }}" class="vx-btn vx-btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a>
            <a href="{{ route('vehiculos.exportPdf') }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
        @endcan
        @can('crear vehículos')
            <a href="{{ route('vehiculos.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Vehículo</a>
        @endcan
    </div>
</div>

<form action="{{ route('vehiculos.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por chasis, modelo, versión, colores o empresa..." value="{{ request('search') }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
    @if(request('search'))
        <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>
    @endif
</form>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($vehiculos->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Chasis</th>
                            <th>Modelo</th>
                            <th>Versión</th>
                            <th>Color Ext.</th>
                            <th>Color Int.</th>
                            <th>Empresa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehiculos as $vehiculo)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $vehiculo->id }}</td>
                                <td><span class="vx-badge vx-badge-gray" style="font-family: var(--vx-font-mono); font-size: 10px;">{{ $vehiculo->chasis }}</span></td>
                                <td style="font-weight: 600;">{{ $vehiculo->modelo }}</td>
                                <td style="font-size: 12px;">{{ $vehiculo->version }}</td>
                                <td>{{ $vehiculo->color_externo }}</td>
                                <td>{{ $vehiculo->color_interno }}</td>
                                <td>{{ $vehiculo->empresa->nombre }}</td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                        @can('view', $vehiculo)
                                            <a href="{{ route('vehiculos.show', $vehiculo) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                                        @endcan
                                        @can('update', $vehiculo)
                                            <a href="{{ route('vehiculos.edit', $vehiculo) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>
                                        @endcan
                                        @can('delete', $vehiculo)
                                            <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este vehículo?');">
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
            <div style="padding: 16px 20px;">{{ $vehiculos->links('vendor.pagination.vexis') }}</div>
        @else
            <div class="vx-empty"><i class="bi bi-truck"></i><p>No se encontraron vehículos.</p></div>
        @endif
    </div>
</div>
@endsection
