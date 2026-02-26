@extends('layouts.app')
@section('title', 'Clientes - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Clientes</h1>
    <div class="vx-page-actions">
        @can('crear clientes')
            <a href="{{ route('clientes.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Cliente</a>
        @endcan
    </div>
</div>

<form action="{{ route('clientes.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre, apellidos, DNI, domicilio, CP o empresa..." value="{{ request('search') }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
    @if(request('search'))
        <a href="{{ route('clientes.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>
    @endif
</form>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($clientes->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Empresa</th>
                            <th>Domicilio</th>
                            <th>CP</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $cliente->id }}</td>
                                <td style="font-weight: 600;">{{ $cliente->nombre_completo }}</td>
                                <td><span class="vx-badge vx-badge-gray" style="font-family: var(--vx-font-mono);">{{ $cliente->dni }}</span></td>
                                <td>{{ $cliente->empresa->nombre }}</td>
                                <td style="font-size: 12px;">{{ $cliente->domicilio }}</td>
                                <td>{{ $cliente->codigo_postal }}</td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                        @can('view', $cliente)
                                            <a href="{{ route('clientes.show', $cliente) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                                        @endcan
                                        @can('update', $cliente)
                                            <a href="{{ route('clientes.edit', $cliente) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>
                                        @endcan
                                        @can('delete', $cliente)
                                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este cliente?');">
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
            <div style="padding: 16px 20px;">{{ $clientes->links('vendor.pagination.vexis') }}</div>
        @else
            <div class="vx-empty"><i class="bi bi-person-lines-fill"></i><p>No se encontraron clientes.</p></div>
        @endif
    </div>
</div>
@endsection
