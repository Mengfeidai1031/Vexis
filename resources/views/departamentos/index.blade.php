@extends('layouts.app')
@section('title', 'Departamentos - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Departamentos</h1>
    <div class="vx-page-actions">
        @can('crear departamentos')
            <a href="{{ route('departamentos.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Departamento</a>
        @endcan
    </div>
</div>

<form action="{{ route('departamentos.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre o abreviatura..." value="{{ request('search') }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
    @if(request('search'))
        <a href="{{ route('departamentos.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>
    @endif
</form>

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($departamentos->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Abreviatura</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departamentos as $departamento)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $departamento->id }}</td>
                                <td style="font-weight: 600;">{{ $departamento->nombre }}</td>
                                <td><span class="vx-badge vx-badge-gray">{{ $departamento->abreviatura }}</span></td>
                                <td>{{ $departamento->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">@can('view', $departamento)
                                            <a href="{{ route('departamentos.show', $departamento) }}" class="vx-btn vx-btn-info vx-btn-sm" title="Ver"><i class="bi bi-eye"></i></a>
                                        @endcan
                                        @can('update', $departamento)
                                            <a href="{{ route('departamentos.edit', $departamento) }}" class="vx-btn vx-btn-warning vx-btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $departamento)
                                            <form action="{{ route('departamentos.destroy', $departamento) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este departamento?');">
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
            <div style="padding: 16px 20px;">{{ $departamentos->links('vendor.pagination.vexis') }}</div>
        @else
            <div class="vx-empty"><i class="bi bi-diagram-3"></i><p>No se encontraron departamentos.</p></div>
        @endif
    </div>
</div>
@endsection
