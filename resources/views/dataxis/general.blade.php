@extends('layouts.app')
@section('title', 'Dataxis General - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-speedometer2" style="color:var(--vx-primary);"></i> Dataxis — General</h1><a href="{{ route('dataxis.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

{{-- KPIs --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;margin-bottom:24px;">
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(46,204,113,0.1);color:var(--vx-success);"><i class="bi bi-cart-check"></i></div><div><div class="dx-kpi-val">{{ $totalVentas }}</div><div class="dx-kpi-lbl">Ventas</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(51,170,221,0.1);color:var(--vx-primary);"><i class="bi bi-currency-euro"></i></div><div><div class="dx-kpi-val">{{ number_format($importeVentas, 0, ',', '.') }}€</div><div class="dx-kpi-lbl">Facturado</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(155,89,182,0.1);color:#9B59B6;"><i class="bi bi-people"></i></div><div><div class="dx-kpi-val">{{ $totalClientes }}</div><div class="dx-kpi-lbl">Clientes</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(231,76,60,0.1);color:var(--vx-danger);"><i class="bi bi-car-front"></i></div><div><div class="dx-kpi-val">{{ $totalVehiculos }}</div><div class="dx-kpi-lbl">Vehículos</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(243,156,18,0.1);color:var(--vx-warning);"><i class="bi bi-box2"></i></div><div><div class="dx-kpi-val">{{ number_format($totalStock, 0, ',', '.') }}</div><div class="dx-kpi-lbl">Uds. Stock</div></div></div>
    <div class="dx-kpi"><div class="dx-kpi-icon" style="background:rgba(52,73,94,0.1);color:#34495E;"><i class="bi bi-person-badge"></i></div><div><div class="dx-kpi-val">{{ $totalUsuarios }}</div><div class="dx-kpi-lbl">Usuarios</div></div></div>
</div>

{{-- Gráficas --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Modelos por Marca</h4></div><div class="vx-card-body"><canvas id="chartCatalogo" height="220"></canvas></div></div>
    <div class="vx-card"><div class="vx-card-header"><h4>Precio Medio por Marca</h4></div><div class="vx-card-body"><canvas id="chartPrecioMedio" height="220"></canvas></div></div>
    <div class="vx-card" style="grid-column:span 2;"><div class="vx-card-header"><h4>Nuevos Clientes por Mes</h4></div><div class="vx-card-body"><canvas id="chartClientes" height="140"></canvas></div></div>
</div>

@push('styles')
<style>
.dx-kpi{display:flex;align-items:center;gap:12px;padding:16px;background:var(--vx-surface);border:1px solid var(--vx-border);border-radius:var(--vx-radius-lg);}
.dx-kpi-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
.dx-kpi-val{font-size:20px;font-weight:800;font-family:var(--vx-font-mono);}
.dx-kpi-lbl{font-size:11px;color:var(--vx-text-muted);}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
Chart.defaults.color = isDark ? '#9CA3AF' : '#6C757D';
Chart.defaults.borderColor = isDark ? '#374151' : '#E9ECEF';

new Chart(document.getElementById('chartCatalogo'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($catalogoMarca->pluck('nombre')) !!},
        datasets: [{ data: {!! json_encode($catalogoMarca->pluck('modelos')) !!}, backgroundColor: {!! json_encode($catalogoMarca->pluck('color')) !!}, borderWidth: 2, borderColor: isDark ? '#1F2937' : '#fff' }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('chartPrecioMedio'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($catalogoMarca->pluck('nombre')) !!},
        datasets: [{ label: 'Precio medio (€)', data: {!! json_encode($catalogoMarca->pluck('precio_medio')->map(fn($v) => round($v))) !!}, backgroundColor: {!! json_encode($catalogoMarca->pluck('color')->map(fn($c) => $c . '99')) !!}, borderColor: {!! json_encode($catalogoMarca->pluck('color')) !!}, borderWidth: 2, borderRadius: 6 }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() + '€' } } } }
});

new Chart(document.getElementById('chartClientes'), {
    type: 'line',
    data: {
        labels: {!! json_encode($clientesMes->pluck('mes')) !!},
        datasets: [{ label: 'Nuevos clientes', data: {!! json_encode($clientesMes->pluck('total')) !!}, borderColor: '#9B59B6', backgroundColor: 'rgba(155,89,182,0.1)', fill: true, tension: 0.4, pointRadius: 5, pointBackgroundColor: '#9B59B6' }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endpush
@endsection
