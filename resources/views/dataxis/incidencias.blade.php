@extends('layouts.app')
@section('title', 'Dataxis Incidencias - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Dataxis — Incidencias</h1><div class="vx-page-actions"><a href="{{ route('dataxis.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div></div>

{{-- KPIs --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;margin-bottom:24px;">
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(243,156,18,0.1);color:var(--vx-warning);"><i class="bi bi-exclamation-triangle"></i></div><div><div class="dx-kpi-val">{{ $totalIncidencias }}</div><div class="dx-kpi-lbl">Total Incidencias</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(231,76,60,0.1);color:var(--vx-danger);"><i class="bi bi-clock-history"></i></div><div><div class="dx-kpi-val">{{ $abiertas }}</div><div class="dx-kpi-lbl">Abiertas / En Progreso</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(46,204,113,0.1);color:var(--vx-success);"><i class="bi bi-hourglass-split"></i></div><div><div class="dx-kpi-val">{{ $tiempoMedio !== null ? number_format($tiempoMedio, 1, ',', '.') . 'd' : '—' }}</div><div class="dx-kpi-lbl">Tiempo Medio Resolución</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(51,170,221,0.1);color:var(--vx-primary);"><i class="bi bi-check2-all"></i></div><div><div class="dx-kpi-val">{{ $totalIncidencias > 0 ? round(($totalIncidencias - $abiertas) / $totalIncidencias * 100) . '%' : '—' }}</div><div class="dx-kpi-lbl">Tasa Resolución</div></div></div>
</div>

{{-- Gráficas --}}
<div class="dx-grid">
    <div class="vx-card dx-grid-full dx-chart-lg"><div class="vx-card-header"><h4>Incidencias por Mes</h4></div><div class="vx-card-body"><canvas id="chartIncMes" height="120"></canvas></div></div>
    <div class="vx-card dx-chart-sm"><div class="vx-card-header"><h4>Por Estado</h4></div><div class="vx-card-body"><canvas id="chartIncEstado" height="180"></canvas></div></div>
    <div class="vx-card dx-chart-sm"><div class="vx-card-header"><h4>Por Prioridad</h4></div><div class="vx-card-body"><canvas id="chartIncPrioridad" height="180"></canvas></div></div>
    <div class="vx-card dx-grid-full dx-chart-lg"><div class="vx-card-header"><h4>Carga por Técnico</h4></div><div class="vx-card-body"><canvas id="chartIncTecnico" height="140"></canvas></div></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
Chart.defaults.color = isDark ? '#9CA3AF' : '#6C757D';
Chart.defaults.borderColor = isDark ? '#374151' : '#E9ECEF';

new Chart(document.getElementById('chartIncMes'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($incidenciasMes->pluck('mes')) !!},
        datasets: [
            { label: 'Abiertas', data: {!! json_encode($incidenciasMes->map(fn($m) => $m->total - $m->cerradas)) !!}, backgroundColor: 'rgba(231,76,60,0.7)', borderRadius: 6, stack: 'a' },
            { label: 'Cerradas', data: {!! json_encode($incidenciasMes->pluck('cerradas')) !!}, backgroundColor: 'rgba(46,204,113,0.7)', borderRadius: 6, stack: 'a' }
        ]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } } } }
});

new Chart(document.getElementById('chartIncEstado'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($incidenciasEstado->pluck('estado')) !!},
        datasets: [{ data: {!! json_encode($incidenciasEstado->pluck('total')) !!}, backgroundColor: ['#E65100','#3498DB','#2ECC71','#95A5A6'], borderWidth: 2, borderColor: isDark ? '#1F2937' : '#fff' }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

const prioridadColors = { baja: '#2ECC71', media: '#3498DB', alta: '#F39C12', critica: '#E74C3C' };
new Chart(document.getElementById('chartIncPrioridad'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($incidenciasPrioridad->pluck('prioridad')) !!},
        datasets: [{ data: {!! json_encode($incidenciasPrioridad->pluck('total')) !!}, backgroundColor: {!! json_encode($incidenciasPrioridad->pluck('prioridad')->map(fn($p) => $prioridadColors[$p] ?? '#95A5A6')) !!}, borderWidth: 2, borderColor: isDark ? '#1F2937' : '#fff' }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('chartIncTecnico'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($cargaTecnico->pluck('tecnico')) !!},
        datasets: [
            { label: 'Total asignadas', data: {!! json_encode($cargaTecnico->pluck('total')) !!}, backgroundColor: 'rgba(51,170,221,0.7)', borderRadius: 6 },
            { label: 'Resueltas', data: {!! json_encode($cargaTecnico->pluck('resueltas')) !!}, backgroundColor: 'rgba(46,204,113,0.7)', borderRadius: 6 }
        ]
    },
    options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { position: 'top' } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endpush
@endsection
