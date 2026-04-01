@extends('layouts.app')
@section('title', 'Nueva Factura - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Registrar Factura</h1><a href="{{ route('facturas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:800px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('facturas.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Venta vinculada</label><select class="vx-select" name="venta_id" id="ventaSelect"><option value="">Sin venta asociada</option>@foreach($ventas as $v)<option value="{{ $v->id }}" {{ old('venta_id', $ventaPreseleccionada?->id) == $v->id ? 'selected' : '' }} data-cliente="{{ $v->cliente_id }}" data-empresa="{{ $v->empresa_id }}" data-centro="{{ $v->centro_id }}" data-marca="{{ $v->marca_id }}" data-precio="{{ $v->precio_final }}">{{ $v->codigo_venta }} — {{ $v->vehiculo?->modelo ?? '' }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Cliente</label><select class="vx-select" name="cliente_id" id="clienteSelect"><option value="">Sin asignar</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id', $ventaPreseleccionada?->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach</select><a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id" id="marcaSelect"><option value="">—</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id', $ventaPreseleccionada?->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select><a href="{{ route('gestion.marcas') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Gestionar marcas</a></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" id="empresaSelect" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $ventaPreseleccionada?->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" id="centroSelect" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id', $ventaPreseleccionada?->centro_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Concepto</label><textarea class="vx-input" name="concepto" rows="2" placeholder="Descripción del servicio o producto facturado...">{{ old('concepto') }}</textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Subtotal (€) <span class="required">*</span></label><input type="number" class="vx-input" id="subtotal" name="subtotal" value="{{ old('subtotal', $ventaPreseleccionada?->precio_final ?? 0) }}" step="0.01" min="0" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">IVA (%)</label><input type="number" class="vx-input" id="ivaPct" name="iva_porcentaje" value="{{ old('iva_porcentaje', 21) }}" step="0.01" min="0" max="100" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">IVA (€)</label><input type="number" class="vx-input" id="ivaImporte" value="0" step="0.01" readonly style="font-family:var(--vx-font-mono);background:var(--vx-bg);"></div>
            <div class="vx-form-group"><label class="vx-label">Total (€)</label><input type="number" class="vx-input" id="total" value="0" step="0.01" readonly style="font-family:var(--vx-font-mono);font-weight:700;background:var(--vx-bg);"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Fecha Factura <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_factura" value="{{ old('fecha_factura', date('Y-m-d')) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Fecha Vencimiento</label><input type="date" class="vx-input" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('facturas.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Registrar Factura</button></div>
    </form>
</div></div></div>
@push('scripts')
<script>
function calcTotals() {
    const sub = parseFloat(document.getElementById('subtotal').value) || 0;
    const pct = parseFloat(document.getElementById('ivaPct').value) || 0;
    const iva = (sub * pct / 100);
    document.getElementById('ivaImporte').value = iva.toFixed(2);
    document.getElementById('total').value = (sub + iva).toFixed(2);
}
document.getElementById('subtotal').addEventListener('input', calcTotals);
document.getElementById('ivaPct').addEventListener('input', calcTotals);
calcTotals();

document.getElementById('ventaSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (opt.value) {
        document.getElementById('clienteSelect').value = opt.dataset.cliente || '';
        document.getElementById('empresaSelect').value = opt.dataset.empresa || '';
        document.getElementById('centroSelect').value = opt.dataset.centro || '';
        document.getElementById('marcaSelect').value = opt.dataset.marca || '';
        document.getElementById('subtotal').value = opt.dataset.precio || 0;
        calcTotals();
    }
});
</script>
@endpush
@endsection
