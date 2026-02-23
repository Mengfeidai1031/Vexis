@extends('layouts.app')
@section('title', 'Solicitar Vacaciones - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Solicitar Vacaciones</h1>
    <a href="{{ route('vacaciones.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:600px;">
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-body" style="display:flex;gap:24px;justify-content:center;">
            <div style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--vx-success);">{{ $diasDisponibles }}</div>
                <div style="font-size:12px;color:var(--vx-text-muted);">Días disponibles</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:28px;font-weight:800;color:var(--vx-danger);">{{ $diasUsados }}</div>
                <div style="font-size:12px;color:var(--vx-text-muted);">Días usados</div>
            </div>
        </div>
    </div>
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('vacaciones.store') }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="fecha_inicio">Fecha Inicio <span class="required">*</span></label>
                    <input type="date" class="vx-input @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required min="{{ date('Y-m-d') }}">
                    @error('fecha_inicio')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="fecha_fin">Fecha Fin <span class="required">*</span></label>
                    <input type="date" class="vx-input @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                    @error('fecha_fin')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div id="diasCalc" style="padding:8px 0;font-size:14px;font-weight:600;color:var(--vx-primary);display:none;">
                <i class="bi bi-info-circle"></i> <span id="diasNum"></span> días laborables seleccionados
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="motivo">Motivo (opcional)</label>
                <textarea class="vx-input" id="motivo" name="motivo" rows="3" placeholder="Ej: Vacaciones de verano...">{{ old('motivo') }}</textarea>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('vacaciones.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-send"></i> Enviar Solicitud</button>
            </div>
        </form>
    </div></div>
</div>
@push('scripts')
<script>
const fi = document.getElementById('fecha_inicio');
const ff = document.getElementById('fecha_fin');
function calcDias() {
    if (fi.value && ff.value) {
        const start = new Date(fi.value), end = new Date(ff.value);
        if (end >= start) {
            let count = 0, d = new Date(start);
            while (d <= end) { const day = d.getDay(); if (day !== 0 && day !== 6) count++; d.setDate(d.getDate()+1); }
            document.getElementById('diasNum').textContent = count;
            document.getElementById('diasCalc').style.display = '';
        }
    }
}
fi.addEventListener('change', () => { if (!ff.value || ff.value < fi.value) ff.value = fi.value; calcDias(); });
ff.addEventListener('change', calcDias);
</script>
@endpush
@endsection
