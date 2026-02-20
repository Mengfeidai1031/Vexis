@extends('layouts.app')
@section('title', 'Oferta #' . $oferta->id . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Oferta Comercial #{{ $oferta->id }}</h1>
    <div class="vx-page-actions">
        @if($oferta->pdf_path)
            <a href="{{ asset('storage/' . $oferta->pdf_path) }}" class="vx-btn vx-btn-secondary" target="_blank"><i class="bi bi-file-pdf"></i> Ver PDF</a>
        @endif
        <a href="{{ route('ofertas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

@if(!$oferta->vehiculo_id)
<div class="vx-alert vx-alert-warning" style="margin-bottom: 16px;">
    <i class="bi bi-info-circle-fill"></i>
    <span><strong>Documento Informativo</strong> — Sin número de bastidor/chasis. No se ha registrado ningún vehículo.</span>
</div>
@endif

{{-- Fila 1: Cliente + Empresa --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-person" style="color: var(--vx-primary);"></i> Cliente</h4></div>
        <div class="vx-card-body">
            @if($oferta->cliente)
            <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value" style="font-weight:600;">{{ $oferta->cliente->nombre_completo }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">DNI</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray" style="font-family:var(--vx-font-mono);">{{ $oferta->cliente->dni ?? '—' }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Email</div><div class="vx-info-value" style="font-size:12px;">{{ $oferta->cliente->email }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Teléfono</div><div class="vx-info-value">{{ $oferta->cliente->telefono }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Domicilio</div><div class="vx-info-value" style="font-size:12px;">{{ $oferta->cliente->domicilio }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">CP</div><div class="vx-info-value">{{ $oferta->cliente->codigo_postal }}</div></div>
            <div style="margin-top: 10px; display: flex; gap: 6px;">
                @can('view', $oferta->cliente)<a href="{{ route('clientes.show', $oferta->cliente) }}" class="vx-btn vx-btn-info vx-btn-sm"><i class="bi bi-eye"></i> Ver</a>@endcan
                @can('update', $oferta->cliente)<a href="{{ route('clientes.edit', $oferta->cliente) }}" class="vx-btn vx-btn-warning vx-btn-sm"><i class="bi bi-pencil"></i> Editar</a>@endcan
            </div>
            @else
            <p style="color: var(--vx-text-muted);">Cliente no encontrado</p>
            @endif
        </div>
    </div>

    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-building" style="color: var(--vx-success);"></i> Empresa</h4></div>
        <div class="vx-card-body">
            @if($oferta->cliente && $oferta->cliente->empresa)
                @php $empresa = $oferta->cliente->empresa; @endphp
            <div class="vx-info-row"><div class="vx-info-label">Nombre</div><div class="vx-info-value" style="font-weight:600;">{{ $empresa->nombre }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Abreviatura</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray">{{ $empresa->abreviatura }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">CIF</div><div class="vx-info-value"><span class="vx-badge vx-badge-gray" style="font-family:var(--vx-font-mono);">{{ $empresa->cif }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Domicilio</div><div class="vx-info-value" style="font-size:12px;">{{ $empresa->domicilio }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Teléfono</div><div class="vx-info-value">{{ $empresa->telefono }}</div></div>
            @else
            <p style="color: var(--vx-text-muted);">Empresa no encontrada</p>
            @endif
        </div>
    </div>
</div>

{{-- Fila 2: Vehículo + Oferta Cabecera --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-truck" style="color: var(--vx-info);"></i> Vehículo</h4></div>
        <div class="vx-card-body">
            @if($oferta->vehiculo)
            <div class="vx-info-row"><div class="vx-info-label">Chasis</div><div class="vx-info-value"><span style="font-family:var(--vx-font-mono);font-size:12px;background:var(--vx-bg);padding:2px 6px;border-radius:4px;">{{ $oferta->vehiculo->chasis }}</span></div></div>
            <div class="vx-info-row"><div class="vx-info-label">Modelo</div><div class="vx-info-value" style="font-weight:600;">{{ $oferta->vehiculo->modelo }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Versión</div><div class="vx-info-value">{{ $oferta->vehiculo->version }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Color Ext.</div><div class="vx-info-value">{{ $oferta->vehiculo->color_externo }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Color Int.</div><div class="vx-info-value">{{ $oferta->vehiculo->color_interno }}</div></div>
            <div style="margin-top: 10px; display: flex; gap: 6px;">
                @can('view', $oferta->vehiculo)<a href="{{ route('vehiculos.show', $oferta->vehiculo) }}" class="vx-btn vx-btn-info vx-btn-sm"><i class="bi bi-eye"></i> Ver</a>@endcan
                @can('update', $oferta->vehiculo)<a href="{{ route('vehiculos.edit', $oferta->vehiculo) }}" class="vx-btn vx-btn-warning vx-btn-sm"><i class="bi bi-pencil"></i> Editar</a>@endcan
            </div>
            @else
            <div class="vx-alert vx-alert-gray" style="margin:0;"><i class="bi bi-info-circle"></i><span>Sin vehículo — documento informativo</span></div>
            @endif
        </div>
    </div>

    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-file-earmark-text" style="color: var(--vx-warning);"></i> Cabecera</h4></div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">ID Oferta</div><div class="vx-info-value">{{ $oferta->id }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Fecha</div><div class="vx-info-value" style="font-weight:600;">{{ $oferta->fecha->format('d/m/Y') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">PDF</div><div class="vx-info-value" style="font-size:12px;font-family:var(--vx-font-mono);">{{ basename($oferta->pdf_path) }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Creado</div><div class="vx-info-value">{{ $oferta->created_at->format('d/m/Y H:i') }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Actualizado</div><div class="vx-info-value">{{ $oferta->updated_at->format('d/m/Y H:i') }}</div></div>
        </div>
    </div>
</div>

{{-- Líneas de Oferta --}}
<div class="vx-card" style="margin-bottom: 16px;">
    <div class="vx-card-header"><h4>Líneas de Oferta <span class="vx-badge vx-badge-gray">{{ $oferta->lineas->count() }}</span></h4></div>
    <div class="vx-card-body" style="padding: 0;">
        @if($oferta->lineas->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead>
                    <tr>
                        <th style="width:5%;">ID</th>
                        <th style="width:18%;">Tipo</th>
                        <th style="width:57%;">Descripción</th>
                        <th style="width:20%;text-align:right;">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($oferta->lineas as $linea)
                    <tr>
                        <td style="color:var(--vx-text-muted);">{{ $linea->id }}</td>
                        <td>
                            @php
                                $t = strtolower($linea->tipo);
                                $bc = 'gray';
                                if (str_contains($t, 'modelo')) $bc = 'primary';
                                elseif (str_contains($t, 'promocion') || str_contains($t, 'oferta')) $bc = 'danger';
                                elseif (str_contains($t, 'igic') || str_contains($t, 'impuesto')) $bc = 'warning';
                                elseif (str_contains($t, 'gastos')) $bc = 'info';
                                elseif (str_contains($t, 'color') || str_contains($t, 'pintura') || str_contains($t, 'tapicería')) $bc = 'success';
                            @endphp
                            <span class="vx-badge vx-badge-{{ $bc }}">{{ $linea->tipo }}</span>
                        </td>
                        <td style="font-size: 13px;">{{ $linea->descripcion }}</td>
                        <td style="text-align:right;font-weight:700;color:{{ $linea->precio < 0 ? 'var(--vx-danger)' : 'var(--vx-success)' }};">{{ number_format($linea->precio, 2, ',', '.') }} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="vx-empty"><i class="bi bi-list-ul"></i><p>Sin líneas de detalle.</p></div>
        @endif
    </div>
</div>

{{-- Debug --}}
<details class="vx-card" style="margin-bottom: 16px;">
    <summary class="vx-card-header" style="cursor: pointer; user-select: none;">
        <h4><i class="bi bi-code-slash"></i> Datos Extraídos del PDF (Debug)</h4>
    </summary>
    <div class="vx-card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <div class="vx-info-row"><div class="vx-info-label">cliente_nombre_pdf</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:12px;">{{ $oferta->cliente_nombre_pdf ?? 'NULL' }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">cliente_dni_pdf</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:12px;">{{ $oferta->cliente_dni_pdf ?? 'NULL' }}</div></div>
            </div>
            <div>
                <div class="vx-info-row"><div class="vx-info-label">vehiculo_modelo_pdf</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:12px;">{{ $oferta->vehiculo_modelo_pdf ?? 'NULL' }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">vehiculo_chasis_pdf</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:12px;">{{ $oferta->vehiculo_chasis_pdf ?? 'NULL' }}</div></div>
            </div>
        </div>
    </div>
</details>

{{-- Footer actions --}}
<div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
    @can('delete', $oferta)
        <form action="{{ route('ofertas.destroy', $oferta) }}" method="POST" onsubmit="return confirm('¿Eliminar esta oferta?');">
            @csrf @method('DELETE')
            <button type="submit" class="vx-btn vx-btn-danger"><i class="bi bi-trash"></i> Eliminar Oferta</button>
        </form>
    @endcan
</div>
@endsection
