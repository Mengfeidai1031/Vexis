@extends('layouts.app')
@section('title', 'Tipos de Cliente - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-tags"></i> Tipos de Cliente</h1>
    <div class="vx-page-actions">
        @can('crear tipos-cliente')
        <a href="{{ route('tipos-cliente.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Tipo</a>
        @endcan
    </div>
</div>

@if(session('success'))<div class="vx-alert vx-alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="vx-alert vx-alert-danger">{{ session('error') }}</div>@endif

<div class="vx-card vx-filter-card">
    <div class="vx-card-body">
        <form method="GET" action="{{ route('tipos-cliente.index') }}" style="display:grid;grid-template-columns:repeat(3,1fr) auto;gap:12px;align-items:end;">
            <div class="vx-form-group" style="margin:0;"><label class="vx-label">Nombre</label><input type="text" name="nombre" class="vx-input" value="{{ request('nombre') }}"></div>
            <div class="vx-form-group" style="margin:0;"><label class="vx-label">Estado</label>
                <select name="activo" class="vx-select">
                    <option value="">Todos</option>
                    <option value="1" @selected(request('activo')==='1')>Activos</option>
                    <option value="0" @selected(request('activo')==='0')>Inactivos</option>
                </select>
            </div>
            <div></div>
            <div style="display:flex;gap:6px;">
                <button class="vx-btn vx-btn-primary" type="submit"><i class="bi bi-search"></i></button>
                <a href="{{ route('tipos-cliente.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        <table class="vx-table">
            <thead>
                <tr>
                    <th>Color</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Clientes</th>
                    <th>Estado</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $tipo)
                <tr>
                    <td><span style="display:inline-block;width:20px;height:20px;border-radius:6px;background:{{ $tipo->color }};border:1px solid var(--vx-border);"></span></td>
                    <td><strong>{{ $tipo->nombre }}</strong></td>
                    <td style="color:var(--vx-text-muted);">{{ $tipo->descripcion ?: '—' }}</td>
                    <td>{{ $tipo->clientes_count }}</td>
                    <td>
                        @if($tipo->activo)<span class="vx-badge vx-badge-success">Activo</span>
                        @else<span class="vx-badge vx-badge-secondary">Inactivo</span>@endif
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        @can('editar tipos-cliente')
                        <a href="{{ route('tipos-cliente.edit', $tipo) }}" class="vx-btn vx-btn-sm vx-btn-secondary"><i class="bi bi-pencil"></i></a>
                        @endcan
                        @can('eliminar tipos-cliente')
                        <form action="{{ route('tipos-cliente.destroy', $tipo) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este tipo?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="vx-btn vx-btn-sm vx-btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--vx-text-muted);">Sin tipos de cliente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px;">{{ $tipos->links() }}</div>
@endsection
