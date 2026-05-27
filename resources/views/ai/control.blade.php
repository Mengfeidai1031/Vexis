@extends('layouts.app')
@section('title', 'Control de IA - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-cpu" style="color:var(--vx-primary);"></i> Control de IA</h1></div>
<div style="max-width:900px;">
    <div class="vx-card" style="margin-bottom:16px;background:rgba(51,170,221,0.06);border-color:rgba(51,170,221,0.25);">
        <div class="vx-card-body" style="padding:12px 16px;font-size:12.5px;display:flex;gap:10px;align-items:flex-start;color:var(--vx-text-muted);">
            <i class="bi bi-info-circle" style="color:var(--vx-info);font-size:18px;flex-shrink:0;margin-top:1px;"></i>
            <span style="flex:1;line-height:1.6;">VEXIS usa dos APIs de Google Gemini separadas. Las claves se almacenan en <code>.env</code> y nunca se muestran completas. La cuota mensual se reinicia el <strong>{{ $reset_at }}</strong>.</span>
        </div>
    </div>

    @foreach($summary as $s)
        @php $api = $apis[$s['provider']]; @endphp
        <div class="vx-card" style="margin-bottom:14px;">
            <div class="vx-card-header" style="display:flex;justify-content:space-between;align-items:center;">
                <h4><i class="bi bi-{{ $s['provider'] === 'chatbot' ? 'chat-dots' : 'graph-up' }}" style="color:var(--vx-primary);margin-right:6px;"></i>{{ $api['label'] }}</h4>
                <span class="vx-badge vx-badge-info">{{ $s['provider'] }}</span>
            </div>
            <div class="vx-card-body">
                <div class="vx-info-row"><div class="vx-info-label">API Key</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:12px;">{{ $api['key_masked'] }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Proyecto Google</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:12px;">{{ $api['project'] ?? '—' }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Consumo hoy</div><div class="vx-info-value"><strong>{{ $s['today'] }}</strong> peticiones</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Consumo mes</div><div class="vx-info-value"><strong>{{ $s['month'] }}</strong> peticiones</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Último uso</div><div class="vx-info-value">{{ $s['last_at'] ?? 'Nunca' }}</div></div>
            </div>
        </div>
    @endforeach
</div>
@endsection
