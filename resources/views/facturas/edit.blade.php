@extends('layouts.app')
@section('title', 'Editar Factura - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $factura->codigo_factura }}</h1><a href="{{ route('facturas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:800px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('facturas.update', $factura) }}" method="POST">@csrf @method('PUT')
        {{-- Venta vinculada --}}
        <div class="vx-form-group">
            <label class="vx-label">Venta vinculada <span class="required">*</span></label>
            <select class="vx-select" name="venta_id" id="ventaSelect" required>
                <option value="">Seleccionar venta...</option>
                @foreach($ventas as $v)
                <option value="{{ $v->id }}"
                    data-cliente="{{ $v->cliente_id }}"
                    data-empresa="{{ $v->empresa_id }}"
                    data-centro="{{ $v->centro_id }}"
                    data-marca="{{ $v->marca_id }}"
                    data-subtotal="{{ $v->subtotal ?? $v->precio_final }}"
                    data-impuesto-nombre="{{ $v->impuesto_nombre ?? 'IGIC' }}"
                    data-impuesto-pct="{{ $v->impuesto_porcentaje ?? 7 }}"
                    data-impuesto-importe="{{ $v->impuesto_importe ?? 0 }}"
                    data-total="{{ $v->total ?? $v->precio_final }}"
                    {{ old('venta_id', $factura->venta_id) == $v->id ? 'selected' : '' }}>
                    {{ $v->codigo_venta }} — {{ $v->vehiculo?->matricula ?? '' }} {{ $v->vehiculo?->modelo ?? '' }} ({{ number_format($v->total ?? $v->precio_final, 2, ',', '.') }} €)
                </option>
                @endforeach
            </select>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Cliente</label><select class="vx-select" name="cliente_id" id="clienteSelect"><option value="">Sin asignar</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id', $factura->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id" id="marcaSelect"><option value="">—</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id', $factura->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" id="empresaSelect" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $factura->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" id="centroSelect" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id', $factura->centro_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select></div>
        </div>

        <div class="vx-form-group"><label class="vx-label">Concepto</label><textarea class="vx-input" name="concepto" rows="2">{{ old('concepto', $factura->concepto) }}</textarea></div>

        {{-- Importes display --}}
        <div style="background:var(--vx-bg);border:1px solid var(--vx-border);border-radius:var(--vx-radius);padding:16px;margin:12px 0;">
            <div style="font-size:11px;color:var(--vx-text-muted);margin-bottom:8px;font-weight:600;">Importes de la venta</div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;text-align:center;">
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);">Subtotal</div>
                    <div id="displaySubtotal" style="font-family:var(--vx-font-mono);font-weight:700;font-size:16px;">{{ number_format($factura->subtotal, 2, ',', '.') }} €</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);"><span id="displayImpNombre">{{ $factura->venta?->impuesto_nombre ?? 'IVA' }}</span> (<span id="displayImpPct">{{ number_format($factura->iva_porcentaje, 0) }}</span>%)</div>
                    <div id="displayImpImporte" style="font-family:var(--vx-font-mono);font-weight:700;font-size:16px;color:var(--vx-warning);">{{ number_format($factura->iva_importe, 2, ',', '.') }} €</div>
                </div>
                <div></div>
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);">Total</div>
                    <div id="displayTotal" style="font-family:var(--vx-font-mono);font-weight:800;font-size:20px;color:var(--vx-success);">{{ number_format($factura->total, 2, ',', '.') }} €</div>
                </div>
            </div>
        </div>

        <input type="hidden" name="subtotal" id="subtotal" value="{{ old('subtotal', $factura->subtotal) }}">
        <input type="hidden" name="iva_porcentaje" id="ivaPct" value="{{ old('iva_porcentaje', $factura->iva_porcentaje) }}">

        {{-- Estado, Fechas --}}
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
function fmt(n) { return parseFloat(n).toLocaleString('es-ES', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' €'; }

document.getElementById('ventaSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (!opt || !opt.value) return;

    document.getElementById('clienteSelect').value = opt.dataset.cliente || '';
    document.getElementById('empresaSelect').value = opt.dataset.empresa || '';
    document.getElementById('centroSelect').value = opt.dataset.centro || '';
    document.getElementById('marcaSelect').value = opt.dataset.marca || '';

    const subtotal = parseFloat(opt.dataset.subtotal) || 0;
    const impNombre = opt.dataset.impuestoNombre || 'IGIC';
    const impPct = parseFloat(opt.dataset.impuestoPct) || 7;
    const impImporte = parseFloat(opt.dataset.impuestoImporte) || 0;
    const total = parseFloat(opt.dataset.total) || 0;

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('ivaPct').value = impPct.toFixed(2);

    document.getElementById('displaySubtotal').textContent = fmt(subtotal);
    document.getElementById('displayImpNombre').textContent = impNombre;
    document.getElementById('displayImpPct').textContent = impPct;
    document.getElementById('displayImpImporte').textContent = fmt(impImporte);
    document.getElementById('displayTotal').textContent = fmt(total);
});
</script>
@endpush
@endsection
