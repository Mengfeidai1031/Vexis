@extends('layouts.app')
@section('title', 'Documentos · ' . ($vehiculo->matricula ?? $vehiculo->chasis) . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Documentos del Vehículo</h1>
    <div class="vx-page-actions">
        <a href="{{ route('vehiculos.show', $vehiculo) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-eye"></i> Ver vehículo</a>
        <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width: 950px;">
    {{-- Cabecera vehículo --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-body" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            @if($vehiculo->marca)
                <span class="vx-badge" style="background:{{ $vehiculo->marca->color }};color:white;">{{ $vehiculo->marca->nombre }}</span>
            @endif
            <strong>{{ $vehiculo->modelo }} {{ $vehiculo->version }}</strong>
            @if($vehiculo->matricula)
                <span class="vx-badge vx-badge-info" style="font-family:var(--vx-font-mono);letter-spacing:1px;">{{ $vehiculo->matricula }}</span>
            @endif
            <span class="vx-badge vx-badge-gray" style="font-family:var(--vx-font-mono);font-size:10px;">{{ $vehiculo->chasis }}</span>
            <span style="margin-left:auto;color:var(--vx-text-muted);font-size:12px;">{{ $vehiculo->empresa->nombre ?? '' }}</span>
        </div>
    </div>

    {{-- Subir documento --}}
    @can('subir documentos vehiculos')
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><i class="bi bi-upload" style="color:var(--vx-primary);margin-right:6px;"></i>Subir nuevo documento</h4></div>
        <div class="vx-card-body">
            <form action="{{ route('vehiculos.documentos.store', $vehiculo) }}" method="POST" enctype="multipart/form-data">
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
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-upload"></i> Subir documento</button>
                </div>
            </form>
        </div>
    </div>
    @endcan

    {{-- Listado documentos --}}
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-folder2-open" style="color:var(--vx-info);margin-right:6px;"></i>Documentos almacenados ({{ $vehiculo->documentos->count() }})</h4></div>
        <div class="vx-card-body" style="padding:0;">
            @if($vehiculo->documentos->count())
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead><tr><th>Tipo</th><th>Archivo</th><th>Tamaño</th><th>Vence</th><th>Subido por</th><th style="text-align:right;">Acciones</th></tr></thead>
                    <tbody>
                    @foreach($vehiculo->documentos as $doc)
                        <tr>
                            <td><span class="vx-badge vx-badge-info">{{ $doc->tipo_etiqueta }}</span></td>
                            <td style="font-size:12.5px;"><i class="bi bi-file-earmark"></i> {{ $doc->nombre_original }}</td>
                            <td style="font-size:12px;color:var(--vx-text-muted);">{{ number_format($doc->tamano_bytes / 1024, 1) }} KB</td>
                            <td style="font-size:12px;">{{ $doc->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                            <td style="font-size:12px;color:var(--vx-text-muted);">{{ $doc->user?->nombre ?? '—' }} · {{ $doc->created_at->format('d/m/Y') }}</td>
                            <td style="text-align:right;">
                                <a href="{{ route('vehiculos.documentos.download', $doc) }}" class="vx-btn vx-btn-secondary vx-btn-sm" title="Descargar"><i class="bi bi-download"></i></a>
                                @can('eliminar documentos vehiculos')
                                <form action="{{ route('vehiculos.documentos.destroy', $doc) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar documento?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="vx-btn vx-btn-danger vx-btn-sm" aria-label="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="vx-empty" style="padding:32px;"><i class="bi bi-folder2"></i><p>Sin documentos adjuntos.</p></div>
            @endif
        </div>
    </div>
</div>
@endsection
