@extends('layouts.app')
@section('title', 'Ofertas Comerciales - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Ofertas Comerciales</h1>
    <div class="vx-page-actions">
        @can('crear ofertas')
            <a href="{{ route('ofertas.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-file-earmark-pdf"></i> Nueva Oferta (PDF)</a>
        @endcan
    </div>
</div>

{{-- Búsqueda y Filtros --}}
<div class="vx-card" style="margin-bottom: 16px;">
    <div class="vx-card-body">
        <form action="{{ route('ofertas.index') }}" method="GET">
            <div class="vx-search-box" style="margin-bottom: 0;">
                <input type="text" name="search" class="vx-input" placeholder="Buscar por descripción, cliente, vehículo..." value="{{ $filters['search'] ?? '' }}">
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
                <button type="button" class="vx-btn vx-btn-secondary" onclick="document.getElementById('filtrosAvanzados').classList.toggle('vx-hidden')"><i class="bi bi-funnel"></i> Filtros</button>
                @if(!empty(array_filter($filters ?? [], function($v) { return $v !== null && $v !== ''; })))
                    <a href="{{ route('ofertas.index') }}" class="vx-btn vx-btn-ghost">Limpiar</a>
                @endif
            </div>

            <div id="filtrosAvanzados" class="{{ !empty(array_filter(array_diff_key($filters ?? [], ['search' => '']), function($v) { return $v !== null && $v !== ''; })) ? '' : 'vx-hidden' }}" style="margin-top: 12px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px 12px;">
                    <div class="vx-form-group" style="margin-bottom: 0;">
                        <label class="vx-label">Desde</label>
                        <input type="date" name="fecha_desde" class="vx-input" value="{{ $filters['fecha_desde'] ?? '' }}">
                    </div>
                    <div class="vx-form-group" style="margin-bottom: 0;">
                        <label class="vx-label">Hasta</label>
                        <input type="date" name="fecha_hasta" class="vx-input" value="{{ $filters['fecha_hasta'] ?? '' }}">
                    </div>
                    <div class="vx-form-group" style="margin-bottom: 0;">
                        <label class="vx-label">Cliente</label>
                        <select name="cliente_id" class="vx-select">
                            <option value="">Todos</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ ($filters['cliente_id'] ?? '') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vx-form-group" style="margin-bottom: 0;">
                        <label class="vx-label">Vehículo</label>
                        <select name="vehiculo_id" class="vx-select">
                            <option value="">Todos</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}" {{ ($filters['vehiculo_id'] ?? '') == $vehiculo->id ? 'selected' : '' }}>{{ $vehiculo->modelo }} - {{ $vehiculo->version }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vx-form-group" style="margin-bottom: 0;">
                        <label class="vx-label">Empresa</label>
                        <select name="empresa_id" class="vx-select">
                            <option value="">Todas</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ ($filters['empresa_id'] ?? '') == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <button type="submit" class="vx-btn vx-btn-primary vx-btn-sm"><i class="bi bi-funnel-fill"></i> Aplicar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Filtros activos --}}
@php $activeFilters = array_filter($filters ?? [], function($v) { return $v !== null && $v !== ''; }); @endphp
@if(!empty($activeFilters))
<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
    <span style="font-size: 12px; color: var(--vx-text-muted);"><i class="bi bi-funnel-fill"></i> Filtros:</span>
    @if(!empty($filters['search']))<span class="vx-badge vx-badge-primary">Búsqueda: "{{ $filters['search'] }}"</span>@endif
    @if(!empty($filters['fecha_desde']))<span class="vx-badge vx-badge-info">Desde: {{ \Carbon\Carbon::parse($filters['fecha_desde'])->format('d/m/Y') }}</span>@endif
    @if(!empty($filters['fecha_hasta']))<span class="vx-badge vx-badge-info">Hasta: {{ \Carbon\Carbon::parse($filters['fecha_hasta'])->format('d/m/Y') }}</span>@endif
    @if(!empty($filters['cliente_id']))<span class="vx-badge vx-badge-info">Cliente</span>@endif
    @if(!empty($filters['vehiculo_id']))<span class="vx-badge vx-badge-info">Vehículo</span>@endif
    @if(!empty($filters['empresa_id']))<span class="vx-badge vx-badge-info">Empresa</span>@endif
    <a href="{{ route('ofertas.index') }}" style="font-size: 12px; color: var(--vx-danger);">Limpiar todo</a>
</div>
@endif

{{-- Tabla --}}
<div class="vx-card">
    <div class="vx-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <span>Ofertas <span class="vx-badge vx-badge-gray">{{ $ofertas->total() }}</span></span>
    </div>
    <div class="vx-card-body" style="padding: 0;">
        @if($ofertas->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Empresa</th>
                            <th>Vehículo</th>
                            <th>Líneas</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ofertas as $oferta)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $oferta->id }}</td>
                                <td>{{ $oferta->fecha->format('d/m/Y') }}</td>
                                <td>
                                    <div style="font-weight: 600;">{{ $oferta->cliente->nombre_completo }}</div>
                                    <div style="font-size: 11px; color: var(--vx-text-muted);">{{ $oferta->cliente->dni }}</div>
                                </td>
                                <td>
                                    @if($oferta->cliente->empresa)
                                        <span class="vx-badge vx-badge-primary">{{ $oferta->cliente->empresa->abreviatura }}</span>
                                    @else
                                        <span style="color: var(--vx-text-muted);">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($oferta->vehiculo)
                                        <div style="font-weight: 600;">{{ $oferta->vehiculo->modelo }}</div>
                                        <div style="font-size: 11px; color: var(--vx-text-muted);">{{ $oferta->vehiculo->version }}</div>
                                    @else
                                        <span class="vx-badge vx-badge-warning">Doc. Informativo</span>
                                    @endif
                                </td>
                                <td><span class="vx-badge vx-badge-info">{{ $oferta->lineas->count() }}</span></td>
                                <td>
                                    @php
                                        $ultimoTotal = $oferta->lineas()->where('tipo', 'Total')->orderBy('id', 'desc')->first();
                                        $precioMostrar = $ultimoTotal ? $ultimoTotal->precio : 0;
                                    @endphp
                                    <span style="font-weight: 700; color: var(--vx-success);">{{ number_format($precioMostrar, 2, ',', '.') }} €</span>
                                </td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                        @can('view', $oferta)
                                            <a href="{{ route('ofertas.show', $oferta->id) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                                        @endcan
                                        @if($oferta->pdf_path)
                                            <a href="{{ asset('storage/' . $oferta->pdf_path) }}" class="vx-btn vx-btn-secondary vx-btn-sm" target="_blank" title="PDF"><i class="bi bi-file-pdf"></i></a>
                                        @endif
                                        @can('delete', $oferta)
                                            <form action="{{ route('ofertas.destroy', $oferta) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta oferta?');">
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
            <div style="padding: 16px 20px;">{{ $ofertas->links('vendor.pagination.vexis') }}</div>
        @else
            <div class="vx-empty">
                <i class="bi bi-file-earmark-text"></i>
                <p>No se encontraron ofertas.
                @can('crear ofertas') <a href="{{ route('ofertas.create') }}">Sube tu primera oferta en PDF</a>@endcan</p>
            </div>
        @endif
    </div>
</div>
@endsection
