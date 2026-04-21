@extends('layouts.app')
@section('title', 'Vehículos - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Vehículos</h1>
    <div class="vx-page-actions">
        @can('ver vehículos')
            <a href="{{ route('vehiculos.export') }}" class="vx-btn vx-btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a>
            <a href="{{ route('vehiculos.exportPdf') }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
        @endcan
        @can('subir documentos vehiculos')
            <a href="{{ route('vehiculos.documentos.hub') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-file-earmark-text"></i> Generar documentos</a>
        @endcan
        @can('crear vehículos')
            <a href="{{ route('vehiculos.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Vehículo</a>
        @endcan
    </div>
</div>

<x-filtros-avanzados :action="route('vehiculos.index')">
    <div class="vx-filtro" data-filtro="chasis"><label class="vx-filtro-label">Chasis</label><select name="chasis" class="vx-select"><option value="">Todos</option>@foreach($chasis_list as $ch)<option value="{{ $ch }}" {{ request('chasis') == $ch ? 'selected' : '' }}>{{ $ch }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="matricula"><label class="vx-filtro-label">Matrícula</label><select name="matricula" class="vx-select"><option value="">Todas</option>@foreach($matriculas as $m)<option value="{{ $m }}" {{ request('matricula') == $m ? 'selected' : '' }}>{{ $m }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="marca"><label class="vx-filtro-label">Marca</label><select name="marca_id" class="vx-select"><option value="">Todas</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ request('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="modelo"><label class="vx-filtro-label">Modelo</label><select name="modelo" class="vx-select"><option value="">Todos</option>@foreach($modelos as $mod)<option value="{{ $mod }}" {{ request('modelo') == $mod ? 'selected' : '' }}>{{ $mod }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="version"><label class="vx-filtro-label">Versión</label><select name="version" class="vx-select"><option value="">Todas</option>@foreach($versiones as $ver)<option value="{{ $ver }}" {{ request('version') == $ver ? 'selected' : '' }}>{{ $ver }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="color_ext"><label class="vx-filtro-label">Color Ext.</label><select name="color_externo" class="vx-select"><option value="">Todos</option>@foreach($colores_ext as $col)<option value="{{ $col }}" {{ request('color_externo') == $col ? 'selected' : '' }}>{{ $col }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="color_int"><label class="vx-filtro-label">Color Int.</label><select name="color_interno" class="vx-select"><option value="">Todos</option>@foreach($colores_int as $col)<option value="{{ $col }}" {{ request('color_interno') == $col ? 'selected' : '' }}>{{ $col }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="estado"><label class="vx-filtro-label">Estado</label><select name="estado" class="vx-select"><option value="">Todos</option>@foreach(\App\Models\Vehiculo::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="responsable"><label class="vx-filtro-label">Responsable</label><select name="responsable_id" class="vx-select"><option value="">Todos</option>@foreach($responsables as $r)<option value="{{ $r->id }}" {{ request('responsable_id') == $r->id ? 'selected' : '' }}>{{ $r->nombre }} {{ $r->apellidos }}</option>@endforeach</select></div>
</x-filtros-avanzados>

@php
    $estadoColors = [
        'disponible' => 'success',
        'reservado' => 'warning',
        'vendido' => 'info',
        'taller' => 'warning',
        'baja' => 'danger',
    ];
@endphp

<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($vehiculos->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <x-columna-ordenable campo="id" label="ID" />
                            <x-columna-ordenable campo="chasis" label="Chasis" />
                            <x-columna-ordenable campo="matricula" label="Matrícula" />
                            <x-columna-ordenable campo="marca_id" label="Marca" />
                            <x-columna-ordenable campo="modelo" label="Modelo" />
                            <x-columna-ordenable campo="version" label="Versión" />
                            <x-columna-ordenable campo="color_externo" label="Color Ext." />
                            <x-columna-ordenable campo="color_interno" label="Color Int." />
                            <x-columna-ordenable campo="empresa_id" label="Empresa" />
                            <x-columna-ordenable campo="estado" label="Estado" />
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehiculos as $vehiculo)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $vehiculo->id }}</td>
                                <td><span class="vx-badge vx-badge-gray" style="font-family: var(--vx-font-mono); font-size: 10px;">{{ $vehiculo->chasis }}</span></td>
                                <td>@if($vehiculo->matricula)<span class="vx-badge vx-badge-info" style="font-family:var(--vx-font-mono);font-size:11px;letter-spacing:1px;">{{ $vehiculo->matricula }}</span>@else<span style="color:var(--vx-text-muted);font-size:11px;">—</span>@endif</td>
                                <td>@if($vehiculo->marca)<span class="vx-badge" style="background:{{ $vehiculo->marca->color }};color:white;font-size:10px;">{{ $vehiculo->marca->nombre }}</span>@else<span style="color:var(--vx-text-muted);font-size:11px;">—</span>@endif</td>
                                <td style="font-weight: 600;">{{ $vehiculo->modelo }}</td>
                                <td style="font-size: 12px;">{{ $vehiculo->version }}</td>
                                <td>{{ $vehiculo->color_externo }}</td>
                                <td>{{ $vehiculo->color_interno }}</td>
                                <td>{{ $vehiculo->empresa->nombre }}</td>
                                <td><span class="vx-badge vx-badge-{{ $estadoColors[$vehiculo->estado] ?? 'gray' }}">{{ $vehiculo->estado_etiqueta }}</span></td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                        @can('view', $vehiculo)
                                            <a href="{{ route('vehiculos.show', $vehiculo) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                                            <a href="{{ route('vehiculos.show', $vehiculo) }}#documentos"><i class="bi bi-folder2-open" style="color:var(--vx-info);"></i> Ver documentos</a>
                                        @endcan
                                        @can('update', $vehiculo)
                                            <a href="{{ route('vehiculos.edit', $vehiculo) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>
                                            <a href="#" class="vx-action-upload-doc" data-vehiculo-label="{{ $vehiculo->matricula ?? $vehiculo->chasis }}" data-action="{{ route('vehiculos.documentos.store', $vehiculo) }}"><i class="bi bi-upload" style="color:var(--vx-primary);"></i> Subir documento</a>
                                            <div class="vx-sub-trigger" data-sub-target="sub-gen-{{ $vehiculo->id }}">
                                                <span><i class="bi bi-file-earmark-pdf" style="color:var(--vx-danger);"></i> Generar documento</span>
                                                <i class="bi bi-chevron-down vx-sub-chevron"></i>
                                            </div>
                                            <div class="vx-sub-menu" id="sub-gen-{{ $vehiculo->id }}" style="display:none;">
                                                @foreach(\App\Models\VehiculoDocumento::$tipos as $k => $v)
                                                    @if($k !== 'otro')
                                                        <a href="{{ route('vehiculos.documentos.generar.form', ['vehiculo' => $vehiculo, 'tipo' => $k]) }}"><i class="bi bi-dot"></i> {{ $v }}</a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endcan
                                        @can('delete', $vehiculo)
                                            <div style="border-top:1px solid var(--vx-border);margin:4px 0;"></div>
                                            <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este vehículo?');">
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
            <div style="padding: 16px 20px;">{{ $vehiculos->links('vendor.pagination.vexis') }}</div>
        @else
            <div class="vx-empty"><i class="bi bi-truck"></i><p>No se encontraron vehículos.</p></div>
        @endif
    </div>
</div>

{{-- Modal: subir documento desde /index --}}
<div id="vxDocUploadModal" class="vx-modal-overlay" style="display:none;">
    <div class="vx-modal">
        <div class="vx-modal-header">
            <h4><i class="bi bi-upload" style="color:var(--vx-primary);"></i> Subir documento — <span id="vxDocUploadLabel"></span></h4>
            <button type="button" class="vx-modal-close" aria-label="Cerrar">&times;</button>
        </div>
        <form id="vxDocUploadForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="vx-modal-body">
                <div class="vx-form-grid vx-form-grid-3">
                    <div class="vx-form-group">
                        <label class="vx-label" for="modal_tipo">Tipo <span class="required">*</span></label>
                        <select name="tipo" id="modal_tipo" class="vx-select" required>
                            @foreach(\App\Models\VehiculoDocumento::$tipos as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="modal_archivo">Archivo (PDF/JPG/PNG) <span class="required">*</span></label>
                        <input type="file" name="archivo" id="modal_archivo" class="vx-input" accept="application/pdf,image/jpeg,image/png" required>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="modal_fecha_vencimiento">Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="modal_fecha_vencimiento" class="vx-input">
                    </div>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="modal_observaciones">Observaciones</label>
                    <input type="text" name="observaciones" id="modal_observaciones" class="vx-input" maxlength="500">
                </div>
            </div>
            <div class="vx-modal-footer">
                <button type="button" class="vx-btn vx-btn-secondary vx-modal-close">Cancelar</button>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-upload"></i> Subir</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.vx-modal-overlay { position:fixed;inset:0;background:rgba(15,17,23,0.55);z-index:2000;display:flex;align-items:center;justify-content:center;padding:16px; }
.vx-modal { background:var(--vx-surface);border-radius:var(--vx-radius-lg);box-shadow:var(--vx-shadow-lg);max-width:720px;width:100%;border:1px solid var(--vx-border);overflow:hidden; }
.vx-modal-header { display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--vx-border); }
.vx-modal-header h4 { font-size:15px;font-weight:700;color:var(--vx-text);display:flex;align-items:center;gap:8px; }
.vx-modal-close { background:none;border:none;font-size:22px;line-height:1;color:var(--vx-text-muted);cursor:pointer;padding:4px 8px; }
.vx-modal-close:hover { color:var(--vx-danger); }
.vx-modal-body { padding:18px; }
.vx-modal-footer { display:flex;justify-content:flex-end;gap:8px;padding:12px 18px;border-top:1px solid var(--vx-border);background:var(--vx-gray-50); }

/* Submenú desplegable en acciones */
.vx-sub-trigger { display:flex;align-items:center;justify-content:space-between;gap:8px;padding:6px 10px;font-size:13px;color:var(--vx-text);cursor:pointer;border-radius:4px;user-select:none; }
.vx-sub-trigger:hover { background:var(--vx-surface-hover); }
.vx-sub-trigger.open .vx-sub-chevron { transform:rotate(180deg); }
.vx-sub-chevron { font-size:10px;color:var(--vx-text-muted);transition:transform 0.15s ease; }
.vx-sub-menu { padding-left:10px;border-left:2px solid var(--vx-primary);margin:2px 4px 4px 10px; }
.vx-sub-menu a { display:flex;align-items:center;gap:6px;font-size:12.5px;padding:5px 8px;color:var(--vx-text);border-radius:4px; }
.vx-sub-menu a:hover { background:var(--vx-surface-hover);color:var(--vx-primary); }
</style>
@endpush

@push('scripts')
<script>
(function() {
    const overlay = document.getElementById('vxDocUploadModal');
    const form = document.getElementById('vxDocUploadForm');
    const label = document.getElementById('vxDocUploadLabel');

    function openModal(action, vehLabel) {
        form.action = action;
        label.textContent = vehLabel;
        overlay.style.display = 'flex';
    }
    function closeModal() {
        overlay.style.display = 'none';
        form.reset();
    }

    document.querySelectorAll('.vx-action-upload-doc').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(this.dataset.action, this.dataset.vehiculoLabel);
        });
    });
    overlay.querySelectorAll('.vx-modal-close').forEach(function(b) { b.addEventListener('click', closeModal); });
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && overlay.style.display === 'flex') closeModal(); });

    // Submenú desplegable
    document.querySelectorAll('.vx-sub-trigger').forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const target = document.getElementById(this.dataset.subTarget);
            if (!target) return;
            const isOpen = target.style.display === 'block';
            target.style.display = isOpen ? 'none' : 'block';
            this.classList.toggle('open', !isOpen);
        });
    });
})();
</script>
@endpush
@endsection
