@extends('layouts.app')
@section('title', 'Empresas - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Empresas</h1>
    <div class="vx-page-actions">
        @can('crear empresas')
            <a href="{{ route('empresas.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Empresa</a>
        @endcan
    </div>
</div>
<form action="{{ route('empresas.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre, abreviatura, CIF o domicilio..." value="{{ request('search') }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
    @if(request('search'))<a href="{{ route('empresas.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($empresas->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead><tr><th>ID</th><th>Nombre</th><th>Abrev.</th><th>CIF</th><th>Domicilio</th><th>CP</th><th>Teléfono</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach($empresas as $empresa)
                    <tr>
                        <td style="color:var(--vx-text-muted);">{{ $empresa->id }}</td>
                        <td style="font-weight:600;">{{ $empresa->nombre }}</td>
                        <td><span class="vx-badge vx-badge-primary">{{ $empresa->abreviatura }}</span></td>
                        <td><span class="vx-badge vx-badge-gray" style="font-family:var(--vx-font-mono);">{{ $empresa->cif }}</span></td>
                        <td style="font-size:12px;">{{ $empresa->domicilio }}</td>
                        <td>{{ $empresa->codigo_postal }}</td>
                        <td>{{ $empresa->telefono }}</td>
                        <td>
                            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                @can('ver empresas')<a href="{{ route('empresas.show', $empresa) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>@endcan
                                @can('editar empresas')<a href="{{ route('empresas.edit', $empresa) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                                @can('eliminar empresas')
                                <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
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
        <div style="padding:16px 20px;">{{ $empresas->links('vendor.pagination.vexis') }}</div>
        @else
        <div class="vx-empty"><i class="bi bi-building"></i><p>No se encontraron empresas.</p></div>
        @endif
    </div>
</div>
@endsection
