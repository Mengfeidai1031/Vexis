@extends('layouts.app')
@section('title', 'Dataxis Taller - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-wrench-adjustable" style="color:#9B59B6;"></i> Dataxis — Taller</h1><a href="{{ route('dataxis.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Citas por Estado</h4></div><div class="vx-card-body"><canvas id="chartCitasEstado" height="220"></canvas></div></div>
    <div class="vx-card"><div class="vx-card-header"><h4>Citas por Día de la Semana</h4></div><div class="vx-card-body"><canvas id="chartCitasDia" height="220"></canvas></div></div>
    <div class="vx-card"><div class="vx-card-header"><h4>Carga por Mecánico</h4></div><div class="vx-card-body"><canvas id="chartMecanicos" height="220"></canvas></div></div>
    <div class="vx-card"><div class="vx-card-header"><h4>Tasaciones por Estado</h4></div><div class="vx-card-body"><canvas id="chartTasaciones" height="220"></canvas></div></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
Chart.defaults.color = isDark ? '#9CA3AF' : '#6C757D';
Chart.defaults.borderColor = isDark ? '#374151' : '#E9ECEF';

const estadoColors = { pendiente: '#F39C12', confirmada: '#3498DB', en_curso: '#9B59B6', completada: '#2ECC71', cancelada: '#E74C3C' };
const citasEstado = {!! json_encode($citasEstado) !!};
new Chart(document.getElementById('chartCitasEstado'), {
    type: 'doughnut',
    data: { labels: citasEstado.map(e => e.estado), datasets: [{ data: citasEstado.map(e => e.total), backgroundColor: citasEstado.map(e => estadoColors[e.estado] || '#888'), borderWidth: 2, borderColor: isDark ? '#1F2937' : '#fff' }] },
    options: { plugins: { legend: { position: 'bottom' } } }
});

const dias = ['','Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];
const citasDia = {!! json_encode($citasDia) !!};
new Chart(document.getElementById('chartCitasDia'), {
    type: 'bar',
    data: { labels: citasDia.map(d => dias[d.dia] || d.dia), datasets: [{ label: 'Citas', data: citasDia.map(d => d.total), backgroundColor: 'rgba(51,170,221,0.7)', borderRadius: 6 }] },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

new Chart(document.getElementById('chartMecanicos'), {
    type: 'radar',
    data: {
        labels: {!! json_encode($cargaMecanico->pluck('mecanico')->map(fn($n) => Str::limit($n, 15))) !!},
        datasets: [{ label: 'Citas asignadas', data: {!! json_encode($cargaMecanico->pluck('total')) !!}, backgroundColor: 'rgba(155,89,182,0.2)', borderColor: '#9B59B6', borderWidth: 2, pointBackgroundColor: '#9B59B6' }]
    },
    options: { plugins: { legend: { display: false } }, scales: { r: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

const tasColors = { pendiente: '#F39C12', valorada: '#3498DB', aceptada: '#2ECC71', rechazada: '#E74C3C' };
const tasEstado = {!! json_encode($tasacionesEstado) !!};
new Chart(document.getElementById('chartTasaciones'), {
    type: 'pie',
    data: { labels: tasEstado.map(e => e.estado), datasets: [{ data: tasEstado.map(e => e.total), backgroundColor: tasEstado.map(e => tasColors[e.estado] || '#888'), borderWidth: 2, borderColor: isDark ? '#1F2937' : '#fff' }] },
    options: { plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
@endsection
