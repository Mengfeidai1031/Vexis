@extends('layouts.app')
@section('title', 'Pretasación IA - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-calculator" style="color:#9B59B6;"></i> Pretasación con IA</h1>
    <div class="vx-page-actions">
        <a href="{{ route('cliente.tasacion') }}" class="vx-btn vx-btn-warning"><i class="bi bi-clipboard-check"></i> Solicitar tasación formal</a>
        <a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    {{-- Formulario --}}
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-car-front" style="color:#9B59B6;"></i> Datos del vehículo</h4></div>
        <div class="vx-card-body">
            <div class="vx-form-group"><label class="vx-label">Marca <span class="required">*</span></label><input type="text" id="ptMarca" class="vx-input" placeholder="Ej: Nissan, Renault, Seat..." required></div>
            <div class="vx-form-group"><label class="vx-label">Modelo <span class="required">*</span></label><input type="text" id="ptModelo" class="vx-input" placeholder="Ej: Qashqai, Clio, León..." required></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 12px;">
                <div class="vx-form-group"><label class="vx-label">Año <span class="required">*</span></label><input type="number" id="ptAnio" class="vx-input" value="{{ date('Y') - 3 }}" min="1990" max="2030" required></div>
                <div class="vx-form-group"><label class="vx-label">Kilometraje <span class="required">*</span></label><input type="number" id="ptKm" class="vx-input" placeholder="60000" min="0" required style="font-family:var(--vx-font-mono);"></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 12px;">
                <div class="vx-form-group"><label class="vx-label">Combustible</label><select id="ptCombustible" class="vx-select"><option value="">—</option><option>Gasolina</option><option>Diésel</option><option>Híbrido</option><option>Eléctrico</option><option>GLP</option></select></div>
                <div class="vx-form-group"><label class="vx-label">Estado general</label><select id="ptEstado" class="vx-select"><option value="">—</option><option>Excelente</option><option>Bueno</option><option>Regular</option><option>Malo</option></select></div>
            </div>
            <button id="ptSubmit" class="vx-btn vx-btn-primary" style="width:100%;margin-top:4px;"><i class="bi bi-calculator"></i> Obtener Pretasación</button>
        </div>
    </div>

    {{-- Resultado --}}
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-robot" style="color:var(--vx-primary);"></i> Valoración IA</h4></div>
        <div class="vx-card-body" id="ptResult" style="min-height:300px;display:flex;align-items:center;justify-content:center;">
            <div style="text-align:center;color:var(--vx-text-muted);">
                <i class="bi bi-arrow-left-circle" style="font-size:40px;opacity:0.3;"></i>
                <p style="margin-top:8px;font-size:13px;">Completa los datos del vehículo y pulsa "Obtener Pretasación" para recibir una valoración orientativa.</p>
            </div>
        </div>
    </div>
</div>

<div class="vx-card" style="max-width:900px;margin:16px auto 0;background:rgba(243,156,18,0.05);border-color:rgba(243,156,18,0.2);">
    <div class="vx-card-body" style="padding:12px 16px;display:flex;align-items:center;gap:8px;font-size:12px;color:var(--vx-text-muted);">
        <i class="bi bi-info-circle" style="color:var(--vx-warning);font-size:16px;flex-shrink:0;"></i>
        Esta pretasación es <strong>orientativa</strong> y se genera con IA a partir de los datos que introduces en el formulario. Si quieres una valoración oficial, crea una <strong>solicitud de tasación formal</strong> desde el botón superior.
    </div>
</div>

@push('scripts')
<script>
function escapeHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.getElementById('ptSubmit').addEventListener('click', async function() {
    const marca = document.getElementById('ptMarca').value;
    const modelo = document.getElementById('ptModelo').value;
    const anio = document.getElementById('ptAnio').value;
    const km = document.getElementById('ptKm').value;
    if (!marca || !modelo || !anio || !km) { alert('Completa los campos obligatorios.'); return; }

    const result = document.getElementById('ptResult');
    result.innerHTML = '<div style="text-align:center;"><div class="chat-typing" style="display:inline-flex;gap:6px;"><span></span><span></span><span></span></div><p style="font-size:12px;color:var(--vx-text-muted);margin-top:8px;">Analizando datos del vehículo...</p></div>';
    this.disabled = true;

    try {
        const res = await fetch('{{ route("cliente.pretasacion.query") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ marca, modelo, anio: parseInt(anio), kilometraje: parseInt(km), combustible: document.getElementById('ptCombustible').value, estado: document.getElementById('ptEstado').value })
        });
        const data = await res.json();
        const safeText = escapeHtml(data.respuesta || 'Sin respuesta.');
        const formatted = safeText
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
        result.innerHTML = `<div style="font-size:13px;line-height:1.7;">${formatted}</div>`;
    } catch (e) {
        result.innerHTML = '<div style="text-align:center;color:var(--vx-danger);"><i class="bi bi-exclamation-triangle" style="font-size:32px;"></i><p>Error al conectar con el servicio.</p></div>';
    }
    this.disabled = false;
});
</script>
@endpush
@endsection
