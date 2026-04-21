@extends('layouts.app')
@section('title', $vehiculo->descripcion_completa . ' - VEXIS')
@section('content')
@php
    $estadoColor = match($vehiculo->estado) {
        'disponible' => 'success',
        'reservado' => 'warning',
        'vendido' => 'info',
        'taller' => 'warning',
        'baja' => 'danger',
        default => 'gray',
    };
    $vehiculo->load(['historial.user', 'documentos.user']);
@endphp
<div class="vx-page-header">
    <h1 class="vx-page-title">Detalle del Vehículo</h1>
    <div class="vx-page-actions">
        @can('update', $vehiculo)
            <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>
        @endcan
        <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width: 950px;">
    <div class="vx-card">
        <div class="vx-card-header">
            <h3><i class="bi bi-truck" style="color: var(--vx-primary); margin-right: 8px;"></i>{{ $vehiculo->descripcion_completa }} <span class="vx-badge vx-badge-{{ $estadoColor }}" style="margin-left:8px;">{{ $vehiculo->estado_etiqueta }}</span></h3>
        </div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID</div><div class="vx-info-value">{{ $vehiculo->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Chasis (VIN)</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray" style="font-family: var(--vx-font-mono); letter-spacing: 0.5px;">{{ $vehiculo->chasis }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Matrícula</div><div class="vx-info-value">@if($vehiculo->matricula)<span class="vx-badge vx-badge-info" style="font-family: var(--vx-font-mono); letter-spacing: 1px; font-size: 13px;">{{ $vehiculo->matricula }}</span>@else<span style="color:var(--vx-text-muted);">Sin matricular</span>@endif</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Marca</div><div class="vx-info-value">@if($vehiculo->marca)<span class="vx-badge" style="background:{{ $vehiculo->marca->color }}; color:white;">{{ $vehiculo->marca->nombre }}</span>@else<span style="color:var(--vx-text-muted);">—</span>@endif</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Modelo</div><div class="vx-info-value" style="font-weight: 600;">{{ $vehiculo->modelo }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Versión</div><div class="vx-info-value">{{ $vehiculo->version }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Color Externo</div><div class="vx-info-value">{{ $vehiculo->color_externo }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Color Interno</div><div class="vx-info-value">{{ $vehiculo->color_interno }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $vehiculo->empresa->nombre }} <span class="vx-badge vx-badge-gray">{{ $vehiculo->empresa->abreviatura }}</span></div></div>
            @if($vehiculo->responsable)
            <div class="vx-info-row"><div class="vx-info-label">Responsable</div><div class="vx-info-value">{{ $vehiculo->responsable->nombre }} {{ $vehiculo->responsable->apellidos }}</div></div>
            @endif
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $vehiculo->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $vehiculo->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>

    {{-- Documentos --}}
    <div class="vx-card" style="margin-top:16px;">
        <div class="vx-card-header">
            <h4><i class="bi bi-file-earmark-text" style="color: var(--vx-info); margin-right:6px;"></i>Documentación</h4>
        </div>
        <div class="vx-card-body">
            @can('update', $vehiculo)
            <form action="{{ route('vehiculos.documentos.store', $vehiculo) }}" method="POST" enctype="multipart/form-data" style="margin-bottom:16px;">
                @csrf
                <div class="vx-form-grid vx-form-grid-3">
                    <div class="vx-form-group">
                        <label class="vx-label" for="tipo">Tipo <span class="required">*</span></label>
                        <select name="tipo" id="tipo" class="vx-select" required>
                            @foreach(\App\Models\VehiculoDocumento::$tipos as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="archivo">Archivo (PDF/JPG/PNG) <span class="required">*</span></label>
                        <input type="file" name="archivo" id="archivo" class="vx-input" accept="application/pdf,image/jpeg,image/png" required>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="fecha_vencimiento">Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="vx-input">
                    </div>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="observaciones">Observaciones</label>
                    <input type="text" name="observaciones" id="observaciones" class="vx-input" maxlength="500">
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-upload"></i> Subir</button>
                </div>
            </form>
            @endcan

            @if($vehiculo->documentos->count())
                <table class="vx-table">
                    <thead><tr><th>Tipo</th><th>Archivo</th><th>Vence</th><th>Subido por</th><th style="text-align:right;">Acciones</th></tr></thead>
                    <tbody>
                    @foreach($vehiculo->documentos as $doc)
                        <tr>
                            <td><span class="vx-badge vx-badge-info">{{ $doc->tipo_etiqueta }}</span></td>
                            <td style="font-size:12.5px;"><i class="bi bi-file-earmark"></i> {{ $doc->nombre_original }}</td>
                            <td>{{ $doc->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                            <td style="font-size:12px;color:var(--vx-text-muted);">{{ $doc->user?->nombre }} · {{ $doc->created_at->format('d/m/Y') }}</td>
                            <td style="text-align:right;">
                                <a href="{{ route('vehiculos.documentos.download', $doc) }}" class="vx-btn vx-btn-secondary vx-btn-sm"><i class="bi bi-download"></i></a>
                                @can('update', $vehiculo)
                                <form action="{{ route('vehiculos.documentos.destroy', $doc) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar documento?');">
                                    @csrf @method('DELETE')
                                    <button class="vx-btn vx-btn-danger vx-btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:var(--vx-text-muted);margin:0;">Sin documentos adjuntos.</p>
            @endif
        </div>
    </div>

    {{-- Historial --}}
    <div class="vx-card" style="margin-top:16px;">
        <div class="vx-card-header">
            <h4><i class="bi bi-clock-history" style="color: var(--vx-warning); margin-right:6px;"></i>Historial de cambios</h4>
        </div>
        <div class="vx-card-body" style="padding:0;">
            @if($vehiculo->historial->count())
                <table class="vx-table">
                    <thead><tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Cambio</th><th>Observaciones</th></tr></thead>
                    <tbody>
                    @foreach($vehiculo->historial as $h)
                        <tr>
                            <td style="font-size:12px;">{{ $h->created_at->format('d/m/Y H:i') }}</td>
                            <td style="font-size:12.5px;">{{ $h->user?->nombre ?? '—' }}</td>
                            <td><span class="vx-badge vx-badge-gray">{{ $h->accion }}</span></td>
                            <td style="font-size:12px;">
                                @if($h->campo)
                                    <code>{{ $h->campo }}</code>: {{ $h->valor_anterior ?? '—' }} → <strong>{{ $h->valor_nuevo ?? '—' }}</strong>
                                @else
                                    —
                                @endif
                            </td>
                            <td style="font-size:12px;color:var(--vx-text-muted);">{{ $h->observaciones }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:var(--vx-text-muted);margin:16px;">Sin eventos registrados.</p>
            @endif
        </div>
    </div>
</div>
@endsection
