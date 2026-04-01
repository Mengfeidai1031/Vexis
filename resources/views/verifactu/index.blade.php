@extends('layouts.app')
@section('title', 'Verifactu - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><img src="{{ asset('storage/logos/verifactu.png') }}" alt="Verifactu" style="height:24px;vertical-align:middle;margin-right:6px;"> Verifactu</h1>
    <div class="vx-page-actions">
        <a href="{{ route('verifactu.declaracion') }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> Declaración Responsable</a>
        <button class="vx-btn vx-btn-secondary" id="btnVerificar"><i class="bi bi-shield-check"></i> Verificar Cadena</button>
        @can('crear verifactu')<a href="{{ route('verifactu.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Registro</a>@endcan
    </div>
</div>

{{-- Dashboard Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:var(--vx-primary);font-family:var(--vx-font-mono);">{{ $stats['total'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">Total Registros</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:var(--vx-success);font-family:var(--vx-font-mono);">{{ $stats['validados'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">Validados AEAT</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:var(--vx-warning);font-family:var(--vx-font-mono);">{{ $stats['pendientes'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">Pendientes</div>
    </div></div>
    <div class="vx-card"><div class="vx-card-body" style="text-align:center;padding:16px;">
        <div style="font-size:28px;font-weight:800;color:var(--vx-danger);font-family:var(--vx-font-mono);">{{ $stats['rechazados'] }}</div>
        <div style="font-size:11px;color:var(--vx-text-muted);">Rechazados</div>
    </div></div>
</div>

{{-- Verification result banner --}}
<div id="verifyResult" style="display:none;margin-bottom:16px;padding:12px 16px;border-radius:8px;font-size:13px;"></div>

{{-- Filters --}}
<form action="{{ route('verifactu.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por código registro o factura..." value="{{ request('search') }}" style="flex:1;">
    <select name="estado" class="vx-select" style="width:auto;"><option value="">Todos los estados</option>@foreach(\App\Models\Verifactu::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select>
    <select name="tipo_operacion" class="vx-select" style="width:auto;"><option value="">Todas las operaciones</option>@foreach(\App\Models\Verifactu::$tiposOperacion as $k => $v)<option value="{{ $k }}" {{ request('tipo_operacion') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','estado','tipo_operacion']))<a href="{{ route('verifactu.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>

<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($registros->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Código</th><th>Factura</th><th>Tipo</th><th>Emisor</th><th>Importe</th><th>Hash</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>@foreach($registros as $r)
        <tr>
            <td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $r->codigo_registro }}</td>
            <td><a href="{{ route('facturas.show', $r->factura_id) }}" style="color:var(--vx-primary);font-size:12px;">{{ $r->factura?->codigo_factura ?? '—' }}</a></td>
            <td>@switch($r->tipo_operacion) @case('emision')<span class="vx-badge vx-badge-info">Emisión</span>@break @case('anulacion')<span class="vx-badge vx-badge-danger">Anulación</span>@break @case('rectificacion')<span class="vx-badge vx-badge-warning">Rectificación</span>@break @endswitch</td>
            <td style="font-size:12px;">{{ $r->nombre_emisor ?? '—' }}</td>
            <td style="font-family:var(--vx-font-mono);font-weight:700;">{{ number_format($r->importe_total, 2) }}€</td>
            <td style="font-family:var(--vx-font-mono);font-size:9px;color:var(--vx-text-muted);" title="{{ $r->hash_registro }}">{{ substr($r->hash_registro, 0, 12) }}...</td>
            <td>@switch($r->estado) @case('registrado')<span class="vx-badge" style="background:#e3f2fd;color:#1565c0;">Registrado</span>@break @case('enviado')<span class="vx-badge vx-badge-info">Enviado</span>@break @case('validado')<span class="vx-badge vx-badge-success">Validado</span>@break @case('rechazado')<span class="vx-badge vx-badge-danger">Rechazado</span>@break @case('anulado')<span class="vx-badge" style="background:#eee;color:#666;">Anulado</span>@break @endswitch</td>
            <td style="font-size:12px;">{{ $r->fecha_registro->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('verifactu.show', $r) }}" class="vx-btn vx-btn-primary" style="padding:4px 10px;font-size:11px;"><i class="bi bi-eye"></i></a></td>
        </tr>@endforeach</tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $registros->links('vendor.pagination.vexis') }}</div>
    @else<div class="vx-empty"><i class="bi bi-shield-check"></i><p>No se encontraron registros Verifactu.</p></div>@endif
</div></div>

@push('scripts')
<script>
document.getElementById('btnVerificar').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Verificando...';
    fetch('{{ route("verifactu.verificarCadena") }}')
        .then(r => r.json())
        .then(data => {
            const el = document.getElementById('verifyResult');
            if (data.cadena_valida) {
                el.style.background = 'var(--vx-success-bg, #e8f5e9)';
                el.style.color = 'var(--vx-success, #2e7d32)';
                el.style.border = '1px solid var(--vx-success, #2e7d32)';
                el.innerHTML = '<i class="bi bi-check-circle"></i> Cadena de hashes válida. ' + data.total_registros + ' registros verificados.';
            } else {
                el.style.background = '#fce4ec';
                el.style.color = '#c62828';
                el.style.border = '1px solid #c62828';
                el.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Se encontraron ' + data.errores.length + ' errores en la cadena de hashes.';
            }
            el.style.display = 'block';
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-shield-check"></i> Verificar Cadena';
        });
});
</script>
@endpush
@endsection
