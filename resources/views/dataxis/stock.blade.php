@extends('layouts.app')
@section('title', 'Dataxis Stock - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Dataxis — Stock</h1><div class="vx-page-actions"><a href="{{ route('dataxis.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div></div>

<div class="dx-grid">
    <div class="vx-card dx-chart-sm"><div class="vx-card-header"><h4>Unidades por Almacén</h4></div><div class="vx-card-body"><canvas id="chartStockAlm" height="180"></canvas></div></div>
    <div class="vx-card dx-chart-sm"><div class="vx-card-header"><h4>Valor Stock por Almacén</h4></div><div class="vx-card-body"><canvas id="chartValorAlm" height="180"></canvas></div></div>
    <div class="vx-card dx-chart-sm"><div class="vx-card-header"><h4>Top Piezas por Valor</h4></div><div class="vx-card-body"><canvas id="chartTopValor" height="180"></canvas></div></div>
    <div class="vx-card dx-chart-sm"><div class="vx-card-header"><h4><i class="bi bi-exclamation-triangle" style="color:var(--vx-danger);"></i> Alertas Bajo Stock</h4></div><div class="vx-card-body" style="padding:0;">
        @if($bajoStock->count() > 0)
        <div class="vx-table-wrapper"><table class="vx-table"><thead><tr><th>Ref.</th><th>Pieza</th><th>Actual</th><th>Mínimo</th></tr></thead><tbody>
            @foreach($bajoStock as $s)
            <tr><td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $s->referencia }}</td><td style="font-size:12px;">{{ $s->nombre_pieza }}</td><td style="color:var(--vx-danger);font-weight:700;">{{ $s->cantidad }}</td><td>{{ $s->stock_minimo }}</td></tr>
            @endforeach
        </tbody></table></div>
        @else
        <div style="padding:24px;text-align:center;color:var(--vx-text-muted);"><i class="bi bi-check-circle" style="font-size:24px;color:var(--vx-success);"></i><p style="margin-top:6px;">Todo el stock está por encima del mínimo</p></div>
        @endif
    </div></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
Chart.defaults.color = isDark ? '#9CA3AF' : '#6C757D';
Chart.defaults.borderColor = isDark ? '#374151' : '#E9ECEF';
const colors = ['#33AADD','#2ECC71','#F39C12','#E74C3C','#9B59B6','#1ABC9C','#E67E22','#3498DB'];

new Chart(document.getElementById('chartStockAlm'), {
    type: 'doughnut',
    data: { labels: {!! json_encode($stockAlmacen->pluck('nombre')) !!}, datasets: [{ data: {!! json_encode($stockAlmacen->pluck('total')) !!}, backgroundColor: colors, borderWidth: 2, borderColor: isDark ? '#1F2937' : '#fff' }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('chartValorAlm'), {
    type: 'bar',
    data: { labels: {!! json_encode($valorStock->pluck('nombre')) !!}, datasets: [{ label: 'Valor (€)', data: {!! json_encode($valorStock->pluck('valor')->map(fn($v) => round($v, 2))) !!}, backgroundColor: colors.map(c => c + '99'), borderColor: colors, borderWidth: 2, borderRadius: 6 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() + '€' } } } }
});

new Chart(document.getElementById('chartTopValor'), {
    type: 'bar',
    data: { labels: {!! json_encode($topValor->pluck('nombre_pieza')->map(fn($n) => Str::limit($n, 20))) !!}, datasets: [{ label: 'Valor', data: {!! json_encode($topValor->pluck('valor')->map(fn($v) => round($v, 2))) !!}, backgroundColor: 'rgba(243,156,18,0.7)', borderRadius: 6 }] },
    options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { ticks: { callback: v => v.toLocaleString() + '€' } } } }
});
</script>
@endpush
@endsection
