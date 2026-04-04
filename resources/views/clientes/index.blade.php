@extends('layouts.app')
@section('title', 'Clientes - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Clientes</h1>
    <div class="vx-page-actions">
        <a href="{{ route('clientes.export') }}" class="vx-btn vx-btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a>
        <a href="{{ route('clientes.exportPdf') }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
        @can('crear clientes')
            <a href="{{ route('clientes.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Cliente</a>
        @endcan
    </div>
</div>

<x-filtros-avanzados :action="route('clientes.index')">
    <div class="vx-filtro" data-filtro="nombre"><label class="vx-filtro-label">Nombre</label><select name="nombre" class="vx-select"><option value="">Todos</option>@foreach($clientes_all as $c)<option value="{{ $c->nombre_completo }}" {{ request('nombre') == $c->nombre_completo ? 'selected' : '' }}>{{ $c->nombre_completo }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="dni"><label class="vx-filtro-label">DNI</label><select name="dni" class="vx-select"><option value="">Todos</option>@foreach($clientes_all as $c)<option value="{{ $c->dni }}" {{ request('dni') == $c->dni ? 'selected' : '' }}>{{ $c->dni }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="cp"><label class="vx-filtro-label">Código Postal</label><select name="codigo_postal" class="vx-select"><option value="">Todos</option>@foreach($codigos_postales as $cp)<option value="{{ $cp }}" {{ request('codigo_postal') == $cp ? 'selected' : '' }}>{{ $cp }}</option>@endforeach</select></div>
</x-filtros-avanzados>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($clientes->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <x-columna-ordenable campo="id" label="ID" />
                            <x-columna-ordenable campo="nombre" label="Nombre" />
                            <x-columna-ordenable campo="dni" label="DNI" />
                            <x-columna-ordenable campo="empresa_id" label="Empresa" />
                            <x-columna-ordenable campo="domicilio" label="Domicilio" />
                            <x-columna-ordenable campo="codigo_postal" label="CP" />
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
