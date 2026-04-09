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

<x-filtros-avanzados :action="route('centros.index')">
    <div class="vx-filtro" data-filtro="nombre"><label class="vx-filtro-label">Nombre</label><select name="nombre" class="vx-select"><option value="">Todos</option>@foreach($nombres_centros as $n)<option value="{{ $n }}" {{ request('nombre') == $n ? 'selected' : '' }}>{{ $n }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="direccion"><label class="vx-filtro-label">Dirección</label><select name="direccion" class="vx-select"><option value="">Todas</option>@foreach($direcciones_centros as $d)<option value="{{ $d }}" {{ request('direccion') == $d ? 'selected' : '' }}>{{ $d }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="municipio"><label class="vx-filtro-label">Municipio</label><select name="municipio" class="vx-select"><option value="">Todos</option>@foreach($municipios as $m)<option value="{{ $m }}" {{ request('municipio') == $m ? 'selected' : '' }}>{{ $m }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="provincia"><label class="vx-filtro-label">Provincia</label><select name="provincia" class="vx-select"><option value="">Todas</option>@foreach($provincias as $p)<option value="{{ $p }}" {{ request('provincia') == $p ? 'selected' : '' }}>{{ $p }}</option>@endforeach</select></div>
</x-filtros-avanzados>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($centros->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <x-columna-ordenable campo="id" label="ID" />
                            <x-columna-ordenable campo="nombre" label="Nombre" />
                            <x-columna-ordenable campo="empresa_id" label="Empresa" />
                            <x-columna-ordenable campo="direccion" label="Dirección" />
                            <x-columna-ordenable campo="municipio" label="Municipio" />
                            <x-columna-ordenable campo="provincia" label="Provincia" />
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
