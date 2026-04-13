@extends('layouts.app')
@section('title', 'Dataxis Facturas - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Dataxis — Facturas</h1><div class="vx-page-actions"><a href="{{ route('dataxis.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div></div>

{{-- KPIs --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-bottom:24px;">
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(51,170,221,0.1);color:var(--vx-primary);"><i class="bi bi-receipt"></i></div><div><div class="dx-kpi-val">{{ $totalFacturas }}</div><div class="dx-kpi-lbl">Total Facturas</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(46,204,113,0.1);color:var(--vx-success);"><i class="bi bi-currency-euro"></i></div><div><div class="dx-kpi-val">{{ number_format($totalFacturado, 0, ',', '.') }}€</div><div class="dx-kpi-lbl">Total Facturado</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(243,156,18,0.1);color:var(--vx-warning);"><i class="bi bi-percent"></i></div><div><div class="dx-kpi-val">{{ number_format($totalIva, 0, ',', '.') }}€</div><div class="dx-kpi-lbl">IVA/IGIC Recaudado</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(155,89,182,0.1);color:#9B59B6;"><i class="bi bi-check-circle"></i></div><div><div class="dx-kpi-val">{{ $facturasPagadas }}</div><div class="dx-kpi-lbl">Pagadas</div></div></div>
</div>

{{-- Gráficas --}}
<div class="dx-grid">
    <div class="vx-card dx-grid-full dx-chart-lg"><div class="vx-card-header"><h4>Facturación Mensual</h4></div><div class="vx-card-body"><canvas id="chartFactMes" height="120"></canvas></div></div>
    <div class="vx-card dx-chart-sm"><div class="vx-card-header"><h4>Facturas por Estado</h4></div><div class="vx-card-body"><canvas id="chartFactEstado" height="180"></canvas></div></div>
    <div class="vx-card dx-chart-sm"><div class="vx-card-header"><h4>Facturación por Marca</h4></div><div class="vx-card-body"><canvas id="chartFactMarca" height="180"></canvas></div></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
Chart.defaults.color = isDark ? '#9CA3AF' : '#6C757D';
Chart.defaults.borderColor = isDark ? '#374151' : '#E9ECEF';

new Chart(document.getElementById('chartFactMes'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($facturasMes->pluck('mes')) !!},
        datasets: [
            { label: 'Nº Facturas', data: {!! json_encode($facturasMes->pluck('total')) !!}, backgroundColor: 'rgba(231,76,60,0.7)', borderRadius: 6, yAxisID: 'y' },
            { label: 'Importe (€)', data: {!! json_encode($facturasMes->pluck('importe')) !!}, type: 'line', borderColor: '#2ECC71', backgroundColor: 'rgba(46,204,113,0.1)', fill: true, tension: 0.4, yAxisID: 'y1', pointRadius: 5 }
        ]
    },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, position: 'left' }, y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { callback: v => (v/1000).toFixed(0)+'k€' } } } }
});

new Chart(document.getElementById('chartFactEstado'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($facturasEstado->pluck('estado')) !!},
        datasets: [{ data: {!! json_encode($facturasEstado->pluck('total')) !!}, backgroundColor: ['#3498DB','#2ECC71','#E74C3C','#95A5A6'], borderWidth: 2, borderColor: isDark ? '#1F2937' : '#fff' }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('chartFactMarca'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($facturasMarca->pluck('nombre')) !!},
        datasets: [{ label: 'Importe (€)', data: {!! json_encode($facturasMarca->pluck('importe')) !!}, backgroundColor: {!! json_encode($facturasMarca->pluck('color')->map(fn($c) => $c . '99')) !!}, borderColor: {!! json_encode($facturasMarca->pluck('color')) !!}, borderWidth: 2, borderRadius: 6 }]
    },
    options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { ticks: { callback: v => (v/1000).toFixed(0)+'k€' } } } }
});
</script>
@endpush
@endsection
