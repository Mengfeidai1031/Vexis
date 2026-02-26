@extends('layouts.app')
@section('title', 'Vacaciones - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Vacaciones</h1>
    <div class="vx-page-actions">
        <a href="{{ route('vacaciones.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Solicitar</a>
    </div>
</div>

{{-- Resumen --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-bottom:20px;">
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:32px;font-weight:800;color:var(--vx-primary);">{{ \App\Models\Vacacion::DIAS_TOTALES }}</div>
        <div style="font-size:12px;color:var(--vx-text-muted);">Días totales</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:32px;font-weight:800;color:var(--vx-danger);">{{ $diasUsados }}</div>
        <div style="font-size:12px;color:var(--vx-text-muted);">Días usados</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:32px;font-weight:800;color:var(--vx-success);">{{ $diasDisponibles }}</div>
        <div style="font-size:12px;color:var(--vx-text-muted);">Días disponibles</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:32px;font-weight:800;color:var(--vx-warning);">{{ $vacaciones->total() }}</div>
        <div style="font-size:12px;color:var(--vx-text-muted);">Solicitudes {{ $anio }}</div>
    </div></div>
</div>

{{-- Barra progreso --}}
<div class="vx-card" style="margin-bottom:20px;">
    <div class="vx-card-body" style="padding:14px 20px;">
        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px;">
            <span style="color:var(--vx-text-muted);">Progreso {{ $anio }}</span>
            <span style="font-weight:700;">{{ $diasUsados }}/{{ \App\Models\Vacacion::DIAS_TOTALES }} días</span>
        </div>
        <div style="height:10px;background:var(--vx-gray-200);border-radius:5px;overflow:hidden;">
            @php $pct = \App\Models\Vacacion::DIAS_TOTALES > 0 ? ($diasUsados / \App\Models\Vacacion::DIAS_TOTALES * 100) : 0; @endphp
            <div style="height:100%;width:{{ $pct }}%;background:{{ $pct > 80 ? 'var(--vx-danger)' : ($pct > 50 ? 'var(--vx-warning)' : 'var(--vx-success)') }};border-radius:5px;transition:width 0.5s;"></div>
        </div>
    </div>
</div>

{{-- Calendario --}}
<div class="vx-card" style="margin-bottom:20px;">
    <div class="vx-card-header"><h4><i class="bi bi-calendar3" style="color:var(--vx-primary);"></i> Calendario {{ $anio }}</h4></div>
    <div class="vx-card-body">
        <div id="calVac" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;"></div>
        <div style="display:flex;gap:16px;margin-top:12px;font-size:11px;">
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#2ecc71;margin-right:4px;"></span>Aprobada</span>
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#f39c12;margin-right:4px;"></span>Pendiente</span>
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#e74c3c;margin-right:4px;"></span>Rechazada</span>
        </div>
    </div>
</div>

{{-- Tabla solicitudes --}}
<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        @if($vacaciones->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead><tr>
                    @if($isSuperAdmin)<th>Empleado</th>@endif
                    <th>Desde</th><th>Hasta</th><th>Días</th><th>Estado</th><th>Motivo</th><th>Acciones</th>
                </tr></thead>
                <tbody>
                    @foreach($vacaciones as $v)
                    <tr>
                        @if($isSuperAdmin)<td style="font-weight:600;">{{ $v->user->nombre_completo }}</td>@endif
                        <td>{{ $v->fecha_inicio->format('d/m/Y') }}</td>
                        <td>{{ $v->fecha_fin->format('d/m/Y') }}</td>
                        <td style="text-align:center;font-weight:700;">{{ $v->dias_solicitados }}</td>
                        <td>
                            @if($v->estado === 'aprobada')<span class="vx-badge vx-badge-success">Aprobada</span>
                            @elseif($v->estado === 'rechazada')<span class="vx-badge vx-badge-danger">Rechazada</span>
                            @else<span class="vx-badge vx-badge-warning">Pendiente</span>@endif
                        </td>
                        <td style="font-size:12px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $v->motivo ?? '—' }}</td>
                        <td>
                            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">@if($isSuperAdmin && $v->estado === 'pendiente')
                                <form action="{{ route('vacaciones.gestionar', $v) }}" method="POST" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="estado" value="aprobada">
                                    <button type="submit" class="vx-btn vx-btn-success vx-btn-sm" title="Aprobar"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form action="{{ route('vacaciones.gestionar', $v) }}" method="POST" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="estado" value="rechazada">
                                    <button type="submit" class="vx-btn vx-btn-danger vx-btn-sm" title="Rechazar"><i class="bi bi-x-lg"></i></button>
                                </form>
                                @endif
                                @if($v->estado === 'pendiente')
                                <form action="{{ route('vacaciones.destroy', $v) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar solicitud?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="vx-btn vx-btn-secondary vx-btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif</div></div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px 20px;">{{ $vacaciones->links('vendor.pagination.vexis') }}</div>
        @else
        <div class="vx-empty"><i class="bi bi-calendar-check"></i><p>No hay solicitudes de vacaciones para {{ $anio }}.</p></div>
        @endif
    </div>
</div>

@push('scripts')
<script>
const eventos = @json($eventos);
const anio = {{ $anio }};
const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
const cal = document.getElementById('calVac');

meses.forEach((mes, mi) => {
    const firstDay = new Date(anio, mi, 1);
    const daysInMonth = new Date(anio, mi + 1, 0).getDate();
    let startDay = firstDay.getDay(); // 0=Sun
    startDay = startDay === 0 ? 6 : startDay - 1; // convert to Mon=0

    let html = `<div style="background:var(--vx-surface);border:1px solid var(--vx-border);border-radius:8px;padding:10px;">`;
    html += `<div style="font-weight:700;font-size:13px;text-align:center;margin-bottom:6px;">${mes}</div>`;
    html += `<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:1px;font-size:10px;text-align:center;">`;
    ['L','M','X','J','V','S','D'].forEach(d => html += `<div style="font-weight:700;color:var(--vx-text-muted);padding:2px;">${d}</div>`);

    for (let i = 0; i < startDay; i++) html += '<div></div>';
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${anio}-${String(mi+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const ev = eventos.find(e => dateStr >= e.start && dateStr < e.end);
        const bg = ev ? ev.color : 'transparent';
        const color = ev ? 'white' : 'var(--vx-text)';
        const isWeekend = ((startDay + d - 1) % 7 >= 5);
        const wkColor = isWeekend && !ev ? 'var(--vx-text-muted)' : color;
        html += `<div style="padding:3px 1px;border-radius:3px;background:${bg};color:${wkColor};font-size:10px;${isWeekend && !ev ? 'opacity:0.5;' : ''}" title="${ev ? ev.estado : ''}">${d}</div>`;
    }
    html += '</div></div>';
    cal.innerHTML += html;
});
</script>
@endpush
@endsection
