@extends('layouts.app')
@section('title', 'Generar documentos de vehículo - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Generar documento de vehículo</h1>
    <div class="vx-page-actions">
        <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver a Vehículos</a>
    </div>
</div>

<div class="vx-alert vx-alert-info" style="margin-bottom:16px;">
    <i class="bi bi-info-circle-fill"></i>
    <span>Selecciona el tipo de documento que quieres generar y el vehículo asociado. En la siguiente pantalla rellenarás los datos específicos. Podrás <strong>generar sólo el PDF</strong> o <strong>generarlo y subirlo automáticamente</strong> a la documentación del vehículo.</span>
</div>

<div style="max-width: 900px;">
    <form id="vxDocHubForm" method="GET">
        @csrf

        <div class="vx-card">
            <div class="vx-card-header"><h4><i class="bi bi-collection" style="color:var(--vx-primary);"></i> Paso 1 — Tipo de documento</h4></div>
            <div class="vx-card-body">
                <div class="vx-tipos-grid">
                    @foreach($tipos as $k => $v)
                        @php
                            $icons = [
                                'ficha_tecnica' => 'bi-file-earmark-ruled',
                                'itv' => 'bi-patch-check',
                                'permiso_circulacion' => 'bi-card-text',
                                'seguro' => 'bi-shield-check',
                                'contrato' => 'bi-file-earmark-text',
                            ];
                            $descs = [
                                'ficha_tecnica' => 'Resumen técnico del vehículo: categoría, combustible, potencia, dimensiones.',
                                'itv' => 'Informe de Inspección Técnica con resultado, estación y próxima revisión.',
                                'permiso_circulacion' => 'Documento administrativo de titularidad y aptitud para circular.',
                                'seguro' => 'Certificado de póliza con aseguradora, cobertura y vigencia.',
                                'contrato' => 'Contrato de compraventa, alquiler, depósito, custodia o cesión.',
                            ];
                        @endphp
                        <label class="vx-tipo-card" data-tipo="{{ $k }}">
                            <input type="radio" name="tipo" value="{{ $k }}" required>
                            <div class="vx-tipo-icon"><i class="bi {{ $icons[$k] ?? 'bi-file-earmark' }}"></i></div>
                            <div class="vx-tipo-body">
                                <div class="vx-tipo-name">{{ $v }}</div>
                                <div class="vx-tipo-desc">{{ $descs[$k] ?? '' }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="vx-card" style="margin-top:16px;">
            <div class="vx-card-header"><h4><i class="bi bi-truck" style="color:var(--vx-primary);"></i> Paso 2 — Vehículo</h4></div>
            <div class="vx-card-body">
                <div class="vx-form-group">
                    <label class="vx-label" for="vehiculo_id">Vehículo <span class="required">*</span></label>
                    <select class="vx-select" id="vehiculo_id" name="vehiculo_id" required>
                        <option value="">— Selecciona un vehículo —</option>
                        @foreach($vehiculos as $v)
                            <option value="{{ $v->id }}">{{ $v->matricula ?? 'Sin matrícula' }} · {{ $v->marca->nombre ?? '—' }} {{ $v->modelo }} {{ $v->version }} · {{ $v->chasis }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('vehiculos.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo vehículo</a>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
            <button type="submit" class="vx-btn vx-btn-primary" id="vxHubContinuar" disabled><i class="bi bi-arrow-right-circle"></i> Continuar al formulario</button>
        </div>
    </form>
</div>

@push('styles')
<style>
.vx-tipos-grid { display:grid;grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));gap:12px; }
.vx-tipo-card { position:relative;display:flex;gap:12px;align-items:flex-start;padding:14px;border:2px solid var(--vx-border);border-radius:var(--vx-radius);cursor:pointer;background:var(--vx-surface);transition:all 0.15s ease; }
.vx-tipo-card:hover { border-color:var(--vx-primary);background:var(--vx-surface-hover); }
.vx-tipo-card input[type="radio"] { position:absolute;opacity:0;pointer-events:none; }
.vx-tipo-card.selected { border-color:var(--vx-primary);background:rgba(51,170,221,0.08);box-shadow:0 4px 12px rgba(51,170,221,0.15); }
.vx-tipo-card.selected .vx-tipo-icon { background:var(--vx-primary);color:#fff; }
.vx-tipo-icon { flex-shrink:0;width:42px;height:42px;border-radius:8px;background:var(--vx-gray-100);color:var(--vx-primary);display:flex;align-items:center;justify-content:center;font-size:20px;transition:all 0.15s; }
.vx-tipo-body { flex:1;min-width:0; }
.vx-tipo-name { font-size:14px;font-weight:700;color:var(--vx-text);margin-bottom:4px; }
.vx-tipo-desc { font-size:12px;color:var(--vx-text-muted);line-height:1.4; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    const form = document.getElementById('vxDocHubForm');
    const btn = document.getElementById('vxHubContinuar');
    const vehSel = document.getElementById('vehiculo_id');
    const cards = document.querySelectorAll('.vx-tipo-card');
    let tipoSel = null;

    function refresh() {
        btn.disabled = !(tipoSel && vehSel.value);
    }

    cards.forEach(function(card) {
        card.addEventListener('click', function() {
            cards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type=radio]').checked = true;
            tipoSel = this.dataset.tipo;
            refresh();
        });
    });

    vehSel.addEventListener('change', refresh);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!tipoSel || !vehSel.value) return;
        const url = '{{ url("/vehiculos") }}/' + vehSel.value + '/documentos/generar/' + tipoSel;
        window.location.href = url;
    });
})();
</script>
@endpush
@endsection
