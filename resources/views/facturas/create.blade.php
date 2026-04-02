@extends('layouts.app')
@section('title', 'Nueva Factura - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Registrar Factura</h1><a href="{{ route('facturas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:800px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('facturas.store') }}" method="POST">@csrf
        {{-- Venta vinculada (requerida) --}}
        <div class="vx-form-group">
            <label class="vx-label">Venta vinculada <span class="required">*</span></label>
            <select class="vx-select @error('venta_id') is-invalid @enderror" name="venta_id" id="ventaSelect" required>
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
                    data-vehiculo="{{ $v->vehiculo?->modelo ?? '' }}"
                    {{ old('venta_id', $ventaPreseleccionada?->id) == $v->id ? 'selected' : '' }}>
                    {{ $v->codigo_venta }} — {{ $v->vehiculo?->matricula ?? '' }} {{ $v->vehiculo?->modelo ?? '' }} ({{ number_format($v->total ?? $v->precio_final, 2, ',', '.') }} €)
                </option>
                @endforeach
            </select>
            <a href="{{ route('ventas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva venta</a>
            @error('venta_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Auto-filled from venta (read-only display) --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Cliente</label><select class="vx-select" name="cliente_id" id="clienteSelect"><option value="">Sin asignar</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id', $ventaPreseleccionada?->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id" id="marcaSelect"><option value="">—</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id', $ventaPreseleccionada?->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" id="empresaSelect" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $ventaPreseleccionada?->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" id="centroSelect" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id', $ventaPreseleccionada?->centro_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select></div>
        </div>

        {{-- Concepto (auto-generated from venta) --}}
        <div class="vx-form-group"><label class="vx-label">Concepto</label><textarea class="vx-input" name="concepto" id="conceptoField" rows="2" placeholder="Se genera automáticamente al seleccionar venta">{{ old('concepto') }}</textarea></div>

        {{-- Importes (from venta, read-only) --}}
        <div style="background:var(--vx-bg);border:1px solid var(--vx-border);border-radius:var(--vx-radius);padding:16px;margin:12px 0;">
            <div style="font-size:11px;color:var(--vx-text-muted);margin-bottom:8px;font-weight:600;">Importes de la venta (no editables)</div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;text-align:center;">
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);">Subtotal</div>
                    <div id="displaySubtotal" style="font-family:var(--vx-font-mono);font-weight:700;font-size:16px;">0,00 €</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);"><span id="displayImpNombre">IGIC</span> (<span id="displayImpPct">7</span>%)</div>
                    <div id="displayImpImporte" style="font-family:var(--vx-font-mono);font-weight:700;font-size:16px;color:var(--vx-warning);">0,00 €</div>
                </div>
                <div></div>
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);">Total</div>
                    <div id="displayTotal" style="font-family:var(--vx-font-mono);font-weight:800;font-size:20px;color:var(--vx-success);">0,00 €</div>
                </div>
            </div>
        </div>

        {{-- Hidden fields for amounts --}}
        <input type="hidden" name="subtotal" id="subtotal" value="{{ old('subtotal', $ventaPreseleccionada?->subtotal ?? 0) }}">
        <input type="hidden" name="iva_porcentaje" id="ivaPct" value="{{ old('iva_porcentaje', $ventaPreseleccionada?->impuesto_porcentaje ?? 7) }}">

        {{-- Dates --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Fecha Factura <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_factura" value="{{ old('fecha_factura', date('Y-m-d')) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Fecha Vencimiento</label><input type="date" class="vx-input" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"></div>
        </div>

        {{-- Verifactu fields --}}
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Tipo Factura</label><select class="vx-select" name="tipo_factura">@foreach(\App\Models\Verifactu::$tiposFactura as $k => $v)<option value="{{ $k }}" {{ old('tipo_factura', 'F1') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Clave Régimen IVA</label><select class="vx-select" name="clave_regimen_iva">@foreach(\App\Models\Verifactu::$clavesRegimen as $k => $v)<option value="{{ $k }}" {{ old('clave_regimen_iva', '01') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Factura Simplificada</label><select class="vx-select" name="factura_simplificada"><option value="0" {{ old('factura_simplificada') == '0' ? 'selected' : '' }}>No</option><option value="1" {{ old('factura_simplificada') == '1' ? 'selected' : '' }}>Sí</option></select></div>
        </div>

        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('facturas.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Registrar Factura</button></div>
    </form>
</div></div></div>

@push('scripts')
<script>
function fmt(n) { return parseFloat(n).toLocaleString('es-ES', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' €'; }

function updateDisplayFromVenta() {
    const sel = document.getElementById('ventaSelect');
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) {
        document.getElementById('displaySubtotal').textContent = '0,00 €';
        document.getElementById('displayImpImporte').textContent = '0,00 €';
        document.getElementById('displayTotal').textContent = '0,00 €';
        return;
    }

    // Auto-fill selects
    document.getElementById('clienteSelect').value = opt.dataset.cliente || '';
    document.getElementById('empresaSelect').value = opt.dataset.empresa || '';
    document.getElementById('centroSelect').value = opt.dataset.centro || '';
    document.getElementById('marcaSelect').value = opt.dataset.marca || '';

    // Auto-fill concept
    const concepto = document.getElementById('conceptoField');
    if (!concepto.value) {
        concepto.value = 'Venta vehículo - ' + opt.textContent.trim().split(' — ')[0];
    }

    // Set amounts
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
}

document.getElementById('ventaSelect').addEventListener('change', updateDisplayFromVenta);
updateDisplayFromVenta();
</script>
@endpush
@endsection
