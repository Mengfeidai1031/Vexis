@extends('layouts.app')
@section('title', 'Festivos - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Festivos {{ $anio }}</h1>
    <div class="vx-page-actions">
        @can('crear festivos')
            <a href="{{ route('festivos.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Festivo</a>
        @endcan
    </div>
</div>

{{-- Calendario --}}
<div class="vx-card" style="margin-bottom:20px;">
    <div class="vx-card-header"><h4><i class="bi bi-calendar-event" style="color:var(--vx-primary);"></i> Calendario {{ $anio }}</h4></div>
    <div class="vx-card-body">
        <div id="calFest" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;"></div>
        <div style="display:flex;gap:16px;margin-top:12px;font-size:11px;flex-wrap:wrap;">
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#e74c3c;margin-right:4px;"></span>Nacional</span>
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#3498db;margin-right:4px;"></span>Autonómico</span>
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#2ecc71;margin-right:4px;"></span>Local</span>
        </div>
    </div>
</div>

{{-- Filtros --}}
<form action="{{ route('festivos.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar festivo o municipio..." value="{{ request('search') }}" style="flex:1;">
    <select name="ambito" class="vx-select" style="width:auto;">
        <option value="">Todos los ámbitos</option>
        @foreach(\App\Models\Festivo::$ambitos as $k => $v)
            <option value="{{ $k }}" {{ request('ambito') == $k ? 'selected' : '' }}>{{ $v }}</option>
        @endforeach
    </select>
    <select name="municipio" class="vx-select" style="width:auto;">
        <option value="">Todos los municipios</option>
        @foreach($municipios as $m)
            <option value="{{ $m }}" {{ request('municipio') == $m ? 'selected' : '' }}>{{ $m }}</option>
        @endforeach
    </select>
    <input type="hidden" name="anio" value="{{ $anio }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','ambito','municipio']))<a href="{{ route('festivos.index', ['anio' => $anio]) }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>

{{-- Tabla --}}
<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        @if($festivos->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead><tr><th>Fecha</th><th>Nombre</th><th>Ámbito</th><th>Municipio</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach($festivos as $f)
                    <tr>
                        <td style="font-family:var(--vx-font-mono);font-size:13px;white-space:nowrap;">{{ $f->fecha->format('d/m/Y') }} <span style="color:var(--vx-text-muted);font-size:11px;">{{ $f->fecha->translatedFormat('l') }}</span></td>
                        <td style="font-weight:600;">{{ $f->nombre }}</td>
                        <td>
                            @if($f->ambito === 'nacional')<span class="vx-badge vx-badge-danger">Nacional</span>
                            @elseif($f->ambito === 'autonomico')<span class="vx-badge vx-badge-info">Autonómico</span>
                            @else<span class="vx-badge vx-badge-success">Local</span>@endif
                        </td>
                        <td style="font-size:12px;">{{ $f->municipio ?? 'Todos' }}</td>
                        <td>
                            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                @can('editar festivos')<a href="{{ route('festivos.edit', $f) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                                @can('eliminar festivos')
                                <form action="{{ route('festivos.destroy', $f) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
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
        <div style="padding:16px 20px;">{{ $festivos->links('vendor.pagination.vexis') }}</div>
        @else
        <div class="vx-empty"><i class="bi bi-calendar-event"></i><p>No se encontraron festivos.</p></div>
        @endif
    </div>
</div>

@push('scripts')
<script>
const eventos = @json($eventos);
const anio = {{ $anio }};
const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
const cal = document.getElementById('calFest');

meses.forEach((mes, mi) => {
    const firstDay = new Date(anio, mi, 1);
    const daysInMonth = new Date(anio, mi + 1, 0).getDate();
    let startDay = firstDay.getDay();
    startDay = startDay === 0 ? 6 : startDay - 1;

    let html = `<div style="background:var(--vx-surface);border:1px solid var(--vx-border);border-radius:8px;padding:10px;">`;
    html += `<div style="font-weight:700;font-size:13px;text-align:center;margin-bottom:6px;">${mes}</div>`;
    html += `<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:1px;font-size:10px;text-align:center;">`;
    ['L','M','X','J','V','S','D'].forEach(d => html += `<div style="font-weight:700;color:var(--vx-text-muted);padding:2px;">${d}</div>`);

    for (let i = 0; i < startDay; i++) html += '<div></div>';
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${anio}-${String(mi+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const evs = eventos.filter(e => e.start === dateStr);
        const ev = evs.length > 0 ? evs[0] : null;
        const bg = ev ? ev.color : 'transparent';
        const color = ev ? 'white' : 'var(--vx-text)';
        const isWeekend = ((startDay + d - 1) % 7 >= 5);
        const wkColor = isWeekend && !ev ? 'var(--vx-text-muted)' : color;
        const title = evs.map(e => e.title + (e.municipio !== 'Todos' ? ' (' + e.municipio + ')' : '')).join(', ');
        html += `<div style="padding:3px 1px;border-radius:3px;background:${bg};color:${wkColor};font-size:10px;cursor:${ev?'help':'default'};${isWeekend && !ev ? 'opacity:0.5;' : ''}" title="${title}">${d}</div>`;
    }
    html += '</div></div>';
    cal.innerHTML += html;
});
</script>
@endpush
@endsection
