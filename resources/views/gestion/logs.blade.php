@extends('layouts.app')

@section('title', 'Visor de Logs - VEXIS')

@push('styles')
<style>
    .vx-log-layout { display: grid; grid-template-columns: 260px 1fr; gap: 16px; }
    .vx-log-sidebar { background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius); padding: 12px; max-height: 75vh; overflow-y: auto; }
    .vx-log-file { display: flex; justify-content: space-between; padding: 8px 10px; border-radius: 6px; cursor: pointer; color: var(--vx-text-secondary); font-size: 13px; text-decoration: none; border: 1px solid transparent; }
    .vx-log-file:hover { background: var(--vx-surface-hover); }
    .vx-log-file.active { background: rgba(51,170,221,0.12); color: var(--vx-primary); border-color: var(--vx-primary); }
    .vx-log-size { font-size: 11px; color: var(--vx-text-muted); }
    .vx-log-main { background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius); padding: 16px; }
    .vx-log-toolbar { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; align-items: center; }
    .vx-log-entries { max-height: 65vh; overflow-y: auto; border: 1px solid var(--vx-border); border-radius: 6px; font-family: var(--vx-font-mono); font-size: 12px; }
    .vx-log-entry { padding: 8px 10px; border-bottom: 1px solid var(--vx-border); }
    .vx-log-entry:last-child { border-bottom: none; }
    .vx-log-level { display: inline-block; padding: 2px 7px; border-radius: 4px; font-weight: 700; font-size: 11px; text-transform: uppercase; margin-right: 6px; }
    .vx-log-level.emergency, .vx-log-level.alert, .vx-log-level.critical { background: #4a0000; color: #ffcccc; }
    .vx-log-level.error { background: rgba(231,76,60,0.18); color: #c0392b; }
    .vx-log-level.warning { background: rgba(243,156,18,0.18); color: #b8750f; }
    .vx-log-level.notice, .vx-log-level.info { background: rgba(52,152,219,0.15); color: #2874a6; }
    .vx-log-level.debug { background: rgba(108,117,125,0.15); color: #495057; }
    [data-theme="dark"] .vx-log-level.error { color: #ff7466; }
    [data-theme="dark"] .vx-log-level.warning { color: #f0b15c; }
    [data-theme="dark"] .vx-log-level.info { color: #7fbde8; }
    .vx-log-time { color: var(--vx-text-muted); margin-right: 8px; font-weight: 500; }
    .vx-log-channel { color: var(--vx-primary); margin-right: 6px; }
    .vx-log-message { color: var(--vx-text); }
    .vx-log-context { margin-top: 4px; padding: 6px 8px; background: var(--vx-gray-50); border-radius: 4px; font-size: 11px; color: var(--vx-text-secondary); white-space: pre-wrap; }
    [data-theme="dark"] .vx-log-context { background: var(--vx-gray-100); }
    .vx-log-empty { padding: 30px; text-align: center; color: var(--vx-text-muted); }
    @media (max-width: 900px) { .vx-log-layout { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div style="padding: 24px;">
    <div class="vx-page-header" style="margin-bottom: 20px;">
        <div>
            <h1 style="font-size: 22px; font-weight: 800; margin: 0;"><i class="bi bi-journal-text"></i> Visor de Logs</h1>
            <p style="color: var(--vx-text-muted); margin: 4px 0 0; font-size: 13px;">Diagnóstico de errores e incidentes en tiempo real. Solo Super Admin.</p>
        </div>
        <div style="display:flex;gap:8px;">
            @if($currentFile)
            <a href="{{ route('logs.download', ['file' => $currentFile['name']]) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-download"></i> Descargar</a>
            <form method="POST" action="{{ route('logs.clear') }}" onsubmit="return confirm('¿Limpiar este archivo de log?');" style="display:inline;">
                @csrf
                <input type="hidden" name="file" value="{{ $currentFile['name'] }}">
                <button type="submit" class="vx-btn vx-btn-danger"><i class="bi bi-trash"></i> Limpiar</button>
            </form>
            @endif
        </div>
    </div>

    <div class="vx-log-layout">
        <aside class="vx-log-sidebar">
            <div style="font-size: 12px; font-weight: 700; color: var(--vx-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px;">Archivos ({{ count($files) }})</div>
            @forelse($files as $f)
                <a href="{{ route('logs.index', ['file' => $f['name']]) }}" class="vx-log-file {{ $currentFile && $currentFile['name'] === $f['name'] ? 'active' : '' }}">
                    <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $f['name'] }}</span>
                    <span class="vx-log-size">{{ number_format($f['size']/1024, 1) }} KB</span>
                </a>
            @empty
                <div class="vx-log-empty">Sin archivos de log.</div>
            @endforelse
        </aside>

        <section class="vx-log-main">
            <form method="GET" action="{{ route('logs.index') }}" class="vx-log-toolbar">
                <input type="hidden" name="file" value="{{ $currentFile['name'] ?? '' }}">
                <select name="level" class="vx-input" style="max-width: 160px;">
                    <option value="">Todos los niveles</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl }}" @selected($levelFilter === $lvl)>{{ strtoupper($lvl) }}</option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ $search }}" placeholder="Buscar mensaje, canal..." class="vx-input" style="max-width: 300px;">
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-funnel"></i> Filtrar</button>
                <label class="vx-checkbox" style="margin-left: auto;">
                    <input type="checkbox" id="autoRefresh"> <span>Auto-recarga 10 s</span>
                </label>
            </form>

            @if(!$currentFile)
                <div class="vx-log-empty"><i class="bi bi-inbox" style="font-size:42px;"></i><br>No hay logs para mostrar.</div>
            @else
                <div style="font-size:12px;color:var(--vx-text-muted);margin-bottom:8px;">Archivo: <strong>{{ $currentFile['name'] }}</strong> · Entradas: <strong id="entryCount">{{ count($entries) }}</strong></div>
                <div class="vx-log-entries" id="logEntries">
                    @forelse($entries as $e)
                        <div class="vx-log-entry">
                            <span class="vx-log-time">{{ $e['timestamp'] }}</span>
                            <span class="vx-log-level {{ $e['level'] }}">{{ $e['level'] }}</span>
                            <span class="vx-log-channel">{{ $e['channel'] }}:</span>
                            <span class="vx-log-message">{{ $e['message'] }}</span>
                            @if(!empty(trim($e['context'])))
                                <div class="vx-log-context">{{ $e['context'] }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="vx-log-empty">Sin entradas que coincidan con los filtros.</div>
                    @endforelse
                </div>
            @endif
        </section>
    </div>
</div>

@if($currentFile)
<script>
(function(){
    const chk = document.getElementById('autoRefresh');
    const entriesBox = document.getElementById('logEntries');
    const countBox = document.getElementById('entryCount');
    const params = new URLSearchParams(window.location.search);
    let timer = null;

    function render(entries){
        if (!entries.length) {
            entriesBox.innerHTML = '<div class="vx-log-empty">Sin entradas que coincidan con los filtros.</div>';
            countBox.textContent = '0';
            return;
        }
        entriesBox.innerHTML = entries.map(e => {
            const ctx = (e.context || '').trim();
            const ctxHtml = ctx ? `<div class="vx-log-context">${escape(ctx)}</div>` : '';
            return `<div class="vx-log-entry">
                <span class="vx-log-time">${escape(e.timestamp)}</span>
                <span class="vx-log-level ${escape(e.level)}">${escape(e.level)}</span>
                <span class="vx-log-channel">${escape(e.channel)}:</span>
                <span class="vx-log-message">${escape(e.message)}</span>
                ${ctxHtml}
            </div>`;
        }).join('');
        countBox.textContent = String(entries.length);
    }
    function escape(s){ return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
    function refresh(){
        fetch("{{ route('logs.stream') }}?" + params.toString(), { headers: { 'Accept': 'application/json' }})
            .then(r => r.json()).then(d => render(d.entries || [])).catch(() => {});
    }
    chk.addEventListener('change', () => {
        if (chk.checked) { timer = setInterval(refresh, 10000); } else { clearInterval(timer); timer = null; }
    });
})();
</script>
@endif
@endsection
