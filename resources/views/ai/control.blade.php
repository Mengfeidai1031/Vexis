@extends('layouts.app')
@section('title', 'Control de IA - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-cpu" style="color:var(--vx-primary);"></i> Control de IA</h1></div>
<div style="max-width:900px;">
    <div class="vx-card" style="margin-bottom:16px;background:rgba(51,170,221,0.06);border-color:rgba(51,170,221,0.25);">
        <div class="vx-card-body" style="padding:12px 16px;font-size:12.5px;display:flex;gap:10px;align-items:flex-start;color:var(--vx-text-muted);">
            <i class="bi bi-info-circle" style="color:var(--vx-info);font-size:18px;flex-shrink:0;margin-top:1px;"></i>
            <span style="flex:1;line-height:1.6;">VEXIS usa dos APIs de Google Gemini separadas y aprovecha el plan gratuito al máximo (sin límite interno). Google no expone el consumo restante en tiempo real para claves del plan gratuito, así que estos contadores son los que VEXIS registra en cada llamada, comparados con los límites publicados por Google. <strong>Coste: {{ $limits['cost'] }}</strong>.</span>
        </div>
    </div>

    @foreach($summary as $s)
        @php
            $api = $apis[$s['provider']];
            $rpd = max(1, (int) $limits['rpd']);
            $pct = min(100, round($s['today'] / $rpd * 100, 1));
            $restante = max(0, $rpd - $s['today']);
            $barColor = $pct < 70 ? 'var(--vx-success)' : ($pct < 90 ? 'var(--vx-warning)' : 'var(--vx-danger)');
        @endphp
        <div class="vx-card" style="margin-bottom:14px;">
            <div class="vx-card-header" style="display:flex;justify-content:space-between;align-items:center;">
                <h4><i class="bi bi-{{ $s['provider'] === 'chatbot' ? 'chat-dots' : 'graph-up' }}" style="color:var(--vx-primary);margin-right:6px;"></i>{{ $api['label'] }}</h4>
                <span class="vx-badge vx-badge-info">{{ $s['provider'] }}</span>
            </div>
            <div class="vx-card-body">
                {{-- Barra: peticiones de HOY frente al límite diario de Google --}}
                <div style="margin-bottom:14px;">
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px;">
                        <span style="color:var(--vx-text-muted);">Peticiones hoy vs. límite diario de Google (aprox.)</span>
                        <span><strong>{{ $s['today'] }}</strong> / {{ number_format($rpd, 0, ',', '.') }} · quedan <strong>{{ number_format($restante, 0, ',', '.') }}</strong></span>
                    </div>
                    <div style="height:9px;background:var(--vx-surface-hover);border-radius:6px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $barColor }};transition:width .3s;"></div>
                    </div>
                </div>

                <div class="vx-info-row"><div class="vx-info-label">API Key</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:12px;">{{ $api['key_masked'] }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Proyecto Google</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-size:12px;">{{ $api['project'] ?? '—' }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Peticiones hoy</div><div class="vx-info-value"><strong>{{ $s['today'] }}</strong></div></div>
                <div class="vx-info-row"><div class="vx-info-label">Peticiones este mes</div><div class="vx-info-value"><strong>{{ $s['month'] }}</strong></div></div>
                <div class="vx-info-row"><div class="vx-info-label">Tokens hoy</div><div class="vx-info-value">{{ number_format($s['tokens_today'] ?? 0, 0, ',', '.') }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Tokens este mes</div><div class="vx-info-value">{{ number_format($s['tokens_month'] ?? 0, 0, ',', '.') }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Errores hoy</div><div class="vx-info-value">{{ $s['errors_today'] ?? 0 ? '⚠️ '.$s['errors_today'] : '0' }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Último uso</div><div class="vx-info-value">{{ $s['last_at'] ?? 'Nunca' }}</div></div>
            </div>
        </div>
    @endforeach

    {{-- Referencia de límites del plan gratuito de Google --}}
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-speedometer2" style="color:var(--vx-primary);margin-right:6px;"></i> Límites del plan gratuito de Google (referencia)</h4></div>
        <div class="vx-card-body">
            <div class="vx-info-row"><div class="vx-info-label">Peticiones / minuto</div><div class="vx-info-value">~{{ number_format($limits['rpm'], 0, ',', '.') }} RPM</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Peticiones / día</div><div class="vx-info-value">~{{ number_format($limits['rpd'], 0, ',', '.') }} RPD</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Tokens / minuto</div><div class="vx-info-value">~{{ number_format($limits['tpm'], 0, ',', '.') }} TPM</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Reinicio</div><div class="vx-info-value">{{ $limits['reset'] }}</div></div>
            <div class="vx-info-row"><div class="vx-info-label">Coste</div><div class="vx-info-value"><strong style="color:var(--vx-success);">{{ $limits['cost'] }}</strong></div></div>
            <p style="font-size:11.5px;color:var(--vx-text-muted);margin:10px 0 0;line-height:1.6;">
                Valores orientativos; Google los ajusta. Confírmalos en
                <a href="https://aistudio.google.com" target="_blank" rel="noopener" style="color:var(--vx-primary);">Google AI Studio → Rate limits</a>.
                Si superas el límite, la petición devuelve error 429 y no se cobra nada.
            </p>
        </div>
    </div>
</div>
@endsection
