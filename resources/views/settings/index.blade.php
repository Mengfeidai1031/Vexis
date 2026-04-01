@extends('layouts.app')
@section('title', 'Configuración - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-gear" style="margin-right:6px;"></i> Configuración del Sistema</h1>
</div>

<form action="{{ route('settings.update') }}" method="POST">@csrf @method('PUT')
<div style="max-width:900px;">

    @php
        $groupLabels = [
            'modulos' => ['Módulos', 'bi-grid-3x3-gap', 'Activar o desactivar módulos del sistema. Los módulos desactivados no aparecerán en la navegación.'],
            'verifactu' => ['Verifactu', 'bi-shield-check', 'Configuración del sistema de facturación electrónica Verifactu (RD 1007/2023).'],
            'sistema' => ['Sistema', 'bi-pc-display', 'Configuración general del sistema.'],
            'seguridad' => ['Seguridad', 'bi-lock', 'Opciones de seguridad y control de acceso.'],
        ];
    @endphp

    @foreach($groupLabels as $groupKey => $groupInfo)
    @if(isset($settings[$groupKey]))
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header">
            <h4><i class="bi {{ $groupInfo[1] }}"></i> {{ $groupInfo[0] }}</h4>
        </div>
        <div class="vx-card-body">
            <p style="font-size:12px;color:var(--vx-text-muted);margin:0 0 16px;">{{ $groupInfo[2] }}</p>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($settings[$groupKey] as $setting)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--vx-bg);border-radius:8px;">
                    <div style="flex:1;">
                        <div style="font-size:13px;font-weight:600;">{{ $setting['description'] }}</div>
                        <div style="font-size:10px;color:var(--vx-text-muted);font-family:var(--vx-font-mono);">{{ $setting['key'] }}</div>
                    </div>
                    <div style="margin-left:16px;min-width:200px;">
                        @if($setting['type'] === 'boolean')
                        <label class="vx-toggle" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="hidden" name="settings.{{ $setting['key'] }}" value="0">
                            <input type="checkbox" name="settings.{{ $setting['key'] }}" value="1" {{ $setting['value'] ? 'checked' : '' }} style="display:none;" class="toggle-input">
                            <span class="toggle-track" style="display:inline-block;width:44px;height:24px;border-radius:12px;background:{{ $setting['value'] ? 'var(--vx-success, #4caf50)' : '#ccc' }};position:relative;transition:background 0.2s;">
                                <span class="toggle-thumb" style="display:block;width:20px;height:20px;border-radius:50%;background:white;position:absolute;top:2px;{{ $setting['value'] ? 'left:22px' : 'left:2px' }};transition:left 0.2s;box-shadow:0 1px 3px rgba(0,0,0,0.2);"></span>
                            </span>
                            <span class="toggle-label" style="font-size:12px;font-weight:600;color:{{ $setting['value'] ? 'var(--vx-success, #4caf50)' : 'var(--vx-text-muted)' }};">{{ $setting['value'] ? 'Activado' : 'Desactivado' }}</span>
                        </label>
                        @elseif($setting['type'] === 'integer')
                        <input type="number" class="vx-input" name="settings.{{ $setting['key'] }}" value="{{ $setting['value'] }}" style="width:100%;font-family:var(--vx-font-mono);">
                        @else
                        <input type="text" class="vx-input" name="settings.{{ $setting['key'] }}" value="{{ $setting['value'] }}" style="width:100%;">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    @endforeach

    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:8px;">
        <button type="submit" class="vx-btn vx-btn-primary" style="padding:10px 24px;"><i class="bi bi-check-lg"></i> Guardar Configuración</button>
    </div>
</div>
</form>

@push('scripts')
<script>
document.querySelectorAll('.vx-toggle').forEach(label => {
    const input = label.querySelector('.toggle-input');
    const track = label.querySelector('.toggle-track');
    const thumb = label.querySelector('.toggle-thumb');
    const text = label.querySelector('.toggle-label');
    label.addEventListener('click', function(e) {
        if (e.target === input) return;
        e.preventDefault();
        input.checked = !input.checked;
        updateToggle(track, thumb, text, input.checked);
    });
});
function updateToggle(track, thumb, text, checked) {
    track.style.background = checked ? 'var(--vx-success, #4caf50)' : '#ccc';
    thumb.style.left = checked ? '22px' : '2px';
    text.textContent = checked ? 'Activado' : 'Desactivado';
    text.style.color = checked ? 'var(--vx-success, #4caf50)' : 'var(--vx-text-muted)';
}
</script>
@endpush
@endsection
