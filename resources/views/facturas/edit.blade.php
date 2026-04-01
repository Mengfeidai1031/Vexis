@extends('layouts.app')
@section('title', 'Editar Factura - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $factura->codigo_factura }}</h1><a href="{{ route('facturas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:800px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('facturas.update', $factura) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Venta vinculada</label><select class="vx-select" name="venta_id"><option value="">Sin venta asociada</option>@foreach($ventas as $v)<option value="{{ $v->id }}" {{ old('venta_id', $factura->venta_id) == $v->id ? 'selected' : '' }}>{{ $v->codigo_venta }} — {{ $v->vehiculo?->modelo ?? '' }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Cliente</label><select class="vx-select" name="cliente_id"><option value="">Sin asignar</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id', $factura->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach</select><a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id"><option value="">—</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id', $factura->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select><a href="{{ route('gestion.marcas') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Gestionar marcas</a></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $factura->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id', $factura->centro_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Concepto</label><textarea class="vx-input" name="concepto" rows="2">{{ old('concepto', $factura->concepto) }}</textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Subtotal (€) <span class="required">*</span></label><input type="number" class="vx-input" id="subtotal" name="subtotal" value="{{ old('subtotal', $factura->subtotal) }}" step="0.01" min="0" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">IVA (%)</label><input type="number" class="vx-input" id="ivaPct" name="iva_porcentaje" value="{{ old('iva_porcentaje', $factura->iva_porcentaje) }}" step="0.01" min="0" max="100" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">IVA (€)</label><input type="number" class="vx-input" id="ivaImporte" value="{{ $factura->iva_importe }}" step="0.01" readonly style="font-family:var(--vx-font-mono);background:var(--vx-bg);"></div>
            <div class="vx-form-group"><label class="vx-label">Total (€)</label><input type="number" class="vx-input" id="total" value="{{ $factura->total }}" step="0.01" readonly style="font-family:var(--vx-font-mono);font-weight:700;background:var(--vx-bg);"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Estado</label><select class="vx-select" name="estado" required>@foreach(\App\Models\Factura::$estados as $k => $v)<option value="{{ $k }}" {{ old('estado', $factura->estado) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Fecha Factura <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_factura" value="{{ old('fecha_factura', $factura->fecha_factura->format('Y-m-d')) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Fecha Vencimiento</label><input type="date" class="vx-input" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $factura->fecha_vencimiento?->format('Y-m-d')) }}"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Tipo Factura</label><select class="vx-select" name="tipo_factura">@foreach(\App\Models\Verifactu::$tiposFactura as $k => $v)<option value="{{ $k }}" {{ old('tipo_factura', $factura->tipo_factura ?? 'F1') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Clave Régimen IVA</label><select class="vx-select" name="clave_regimen_iva">@foreach(\App\Models\Verifactu::$clavesRegimen as $k => $v)<option value="{{ $k }}" {{ old('clave_regimen_iva', $factura->clave_regimen_iva ?? '01') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Factura Simplificada</label><select class="vx-select" name="factura_simplificada"><option value="0" {{ old('factura_simplificada', $factura->factura_simplificada) == '0' ? 'selected' : '' }}>No</option><option value="1" {{ old('factura_simplificada', $factura->factura_simplificada) == '1' ? 'selected' : '' }}>Sí</option></select></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones', $factura->observaciones) }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('facturas.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button></div>
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
</script>
@endpush
@endsection
