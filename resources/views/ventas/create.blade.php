@extends('layouts.app')
@section('title', 'Nueva Venta - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Registrar Venta</h1><a href="{{ route('ventas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:900px;"><div class="vx-card"><div class="vx-card-body">
    <form id="ventaForm" action="{{ route('ventas.store') }}" method="POST">@csrf
        {{-- Vehículo y Cliente --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Vehículo <span class="required">*</span></label><select class="vx-select @error('vehiculo_id') is-invalid @enderror" name="vehiculo_id" id="vehiculoSelect" required><option value="">Seleccionar...</option>@foreach($vehiculos as $v)<option value="{{ $v->id }}" data-marca="{{ $v->marca_id }}" data-modelo="{{ $v->modelo }}" data-version="{{ $v->version }}" {{ old('vehiculo_id') == $v->id ? 'selected' : '' }}>{{ $v->matricula ?? 'SIN MAT.' }} — {{ $v->marca?->nombre }} {{ $v->modelo }}</option>@endforeach</select><a href="{{ route('vehiculos.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>@error('vehiculo_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="vx-form-group"><label class="vx-label">Cliente</label><select class="vx-select" name="cliente_id"><option value="">Sin asignar</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach</select><a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>

        {{-- Marca, Empresa, Centro --}}
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id" id="marcaSelect"><option value="">—</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" id="empresaSelect" required>@foreach($empresas as $e)<option value="{{ $e->id }}" data-cp="{{ $e->codigo_postal }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select></div>
        </div>

        {{-- Precio base del vehículo --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Precio Venta (€) <span class="required">*</span></label><input type="number" class="vx-input" id="precioVenta" name="precio_venta" value="{{ old('precio_venta', 0) }}" step="0.01" min="0" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Descuento general (€)</label><input type="number" class="vx-input" id="descuento" name="descuento" value="{{ old('descuento', 0) }}" step="0.01" min="0" style="font-family:var(--vx-font-mono);"></div>
        </div>

        {{-- Extras --}}
        <div class="vx-form-group" style="margin-top:8px;">
            <label class="vx-label" style="display:flex;align-items:center;justify-content:space-between;">
                <span><i class="bi bi-plus-circle" style="color:var(--vx-success);margin-right:4px;"></i> Extras</span>
                <button type="button" class="vx-btn vx-btn-sm" onclick="addConcepto('extra')" style="font-size:11px;padding:2px 10px;background:var(--vx-success);color:#fff;border:0;border-radius:4px;cursor:pointer;">+ Añadir extra</button>
            </label>
            <div id="extrasContainer">
                @if(old('conceptos'))
                    @foreach(old('conceptos') as $idx => $c)
                        @if($c['tipo'] === 'extra')
                        <div class="concepto-row" style="display:grid;grid-template-columns:1fr 150px 32px;gap:8px;margin-bottom:6px;">
                            <input type="text" class="vx-input" name="conceptos[{{ $idx }}][descripcion]" value="{{ $c['descripcion'] }}" placeholder="Descripción del extra" required>
                            <input type="number" class="vx-input concepto-importe" name="conceptos[{{ $idx }}][importe]" value="{{ $c['importe'] }}" step="0.01" min="0" required style="font-family:var(--vx-font-mono);">
                            <input type="hidden" name="conceptos[{{ $idx }}][tipo]" value="extra">
                            <button type="button" onclick="this.closest('.concepto-row').remove();recalc();" style="background:none;border:none;color:var(--vx-danger);cursor:pointer;font-size:16px;"><i class="bi bi-x-circle"></i></button>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Descuentos adicionales --}}
        <div class="vx-form-group">
            <label class="vx-label" style="display:flex;align-items:center;justify-content:space-between;">
                <span><i class="bi bi-dash-circle" style="color:var(--vx-danger);margin-right:4px;"></i> Descuentos adicionales</span>
                <button type="button" class="vx-btn vx-btn-sm" onclick="addConcepto('descuento')" style="font-size:11px;padding:2px 10px;background:var(--vx-danger);color:#fff;border:0;border-radius:4px;cursor:pointer;">+ Añadir descuento</button>
            </label>
            <div id="descuentosContainer">
                @if(old('conceptos'))
                    @foreach(old('conceptos') as $idx => $c)
                        @if($c['tipo'] === 'descuento')
                        <div class="concepto-row" style="display:grid;grid-template-columns:1fr 150px 32px;gap:8px;margin-bottom:6px;">
                            <input type="text" class="vx-input" name="conceptos[{{ $idx }}][descripcion]" value="{{ $c['descripcion'] }}" placeholder="Descripción del descuento" required>
                            <input type="number" class="vx-input concepto-importe" name="conceptos[{{ $idx }}][importe]" value="{{ $c['importe'] }}" step="0.01" min="0" required style="font-family:var(--vx-font-mono);">
                            <input type="hidden" name="conceptos[{{ $idx }}][tipo]" value="descuento">
                            <button type="button" onclick="this.closest('.concepto-row').remove();recalc();" style="background:none;border:none;color:var(--vx-danger);cursor:pointer;font-size:16px;"><i class="bi bi-x-circle"></i></button>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Resumen de precios --}}
        <div style="background:var(--vx-bg);border:1px solid var(--vx-border);border-radius:var(--vx-radius);padding:16px;margin:12px 0;">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;text-align:center;">
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);margin-bottom:2px;">Subtotal</div>
                    <div id="displaySubtotal" style="font-family:var(--vx-font-mono);font-weight:700;font-size:16px;">0,00 €</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);margin-bottom:2px;"><span id="impuestoLabel">IGIC</span> (<span id="impuestoPct">7</span>%)</div>
                    <div id="displayImpuesto" style="font-family:var(--vx-font-mono);font-weight:700;font-size:16px;color:var(--vx-warning);">0,00 €</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);margin-bottom:2px;">Precio Final (sin imp.)</div>
                    <div id="displayPrecioFinal" style="font-family:var(--vx-font-mono);font-weight:600;font-size:14px;">0,00 €</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--vx-text-muted);margin-bottom:2px;">Total</div>
                    <div id="displayTotal" style="font-family:var(--vx-font-mono);font-weight:800;font-size:20px;color:var(--vx-success);">0,00 €</div>
                </div>
            </div>
        </div>

        {{-- Hidden fields for calculated values --}}
        <input type="hidden" name="precio_final" id="precioFinal">
        <input type="hidden" name="subtotal" id="subtotal">
        <input type="hidden" name="impuesto_nombre" id="impuestoNombre">
        <input type="hidden" name="impuesto_porcentaje" id="impuestoPorcentaje">
        <input type="hidden" name="impuesto_importe" id="impuestoImporte">
        <input type="hidden" name="total" id="total">

        {{-- Forma pago, fechas --}}
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Forma de Pago <span class="required">*</span></label><select class="vx-select" name="forma_pago" required>@foreach(\App\Models\Venta::$formasPago as $k => $v)<option value="{{ $k }}" {{ old('forma_pago') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Fecha Venta <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_venta" value="{{ old('fecha_venta', date('Y-m-d')) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Fecha Entrega</label><input type="date" class="vx-input" name="fecha_entrega" value="{{ old('fecha_entrega') }}"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea></div>

        {{-- Action buttons --}}
        <div style="display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('ventas.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
            <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Registrar Venta</button>
            @can('crear facturas')
            <button type="submit" name="crear_factura" value="1" class="vx-btn vx-btn-success"><i class="bi bi-receipt"></i> Registrar y Crear Factura</button>
            @endcan
        </div>
    </form>
</div></div></div>

@push('scripts')
<script>
const preciosCatalogo = @json($preciosCatalogo->mapWithKeys(fn($c, $k) => [$k => (float) $c->precio_base]));
let conceptoIdx = {{ old('conceptos') ? count(old('conceptos')) : 0 }};

function addConcepto(tipo) {
    const container = tipo === 'extra' ? document.getElementById('extrasContainer') : document.getElementById('descuentosContainer');
    const placeholder = tipo === 'extra' ? 'Descripción del extra' : 'Descripción del descuento';
    const html = `<div class="concepto-row" style="display:grid;grid-template-columns:1fr 150px 32px;gap:8px;margin-bottom:6px;">
        <input type="text" class="vx-input" name="conceptos[${conceptoIdx}][descripcion]" placeholder="${placeholder}" required>
        <input type="number" class="vx-input concepto-importe" name="conceptos[${conceptoIdx}][importe]" value="0" step="0.01" min="0" required style="font-family:var(--vx-font-mono);">
        <input type="hidden" name="conceptos[${conceptoIdx}][tipo]" value="${tipo}">
        <button type="button" onclick="this.closest('.concepto-row').remove();recalc();" style="background:none;border:none;color:var(--vx-danger);cursor:pointer;font-size:16px;"><i class="bi bi-x-circle"></i></button>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
    conceptoIdx++;
    // Listen for changes on the new importe input
    container.lastElementChild.querySelector('.concepto-importe').addEventListener('input', recalc);
}

function getImpuestoFromEmpresa() {
    const sel = document.getElementById('empresaSelect');
    const opt = sel.options[sel.selectedIndex];
    const cp = opt ? opt.dataset.cp || '' : '';
    if (cp.startsWith('35') || cp.startsWith('38')) {
        return { nombre: 'IGIC', porcentaje: 7 };
    }
    return { nombre: 'IVA', porcentaje: 21 };
}

function recalc() {
    const precioVenta = parseFloat(document.getElementById('precioVenta').value) || 0;
    const descuentoGral = parseFloat(document.getElementById('descuento').value) || 0;

    let sumExtras = 0, sumDescuentos = 0;
    document.querySelectorAll('#extrasContainer .concepto-importe').forEach(el => { sumExtras += parseFloat(el.value) || 0; });
    document.querySelectorAll('#descuentosContainer .concepto-importe').forEach(el => { sumDescuentos += parseFloat(el.value) || 0; });

    const precioFinal = precioVenta - descuentoGral + sumExtras - sumDescuentos;
    const subtotal = precioFinal;
    const imp = getImpuestoFromEmpresa();
    const impImporte = Math.round(subtotal * imp.porcentaje / 100 * 100) / 100;
    const total = Math.round((subtotal + impImporte) * 100) / 100;

    // Update displays
    document.getElementById('displaySubtotal').textContent = subtotal.toLocaleString('es-ES', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' €';
    document.getElementById('displayPrecioFinal').textContent = precioFinal.toLocaleString('es-ES', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' €';
    document.getElementById('impuestoLabel').textContent = imp.nombre;
    document.getElementById('impuestoPct').textContent = imp.porcentaje;
    document.getElementById('displayImpuesto').textContent = impImporte.toLocaleString('es-ES', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' €';
    document.getElementById('displayTotal').textContent = total.toLocaleString('es-ES', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' €';

    // Update hidden fields
    document.getElementById('precioFinal').value = precioFinal.toFixed(2);
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('impuestoNombre').value = imp.nombre;
    document.getElementById('impuestoPorcentaje').value = imp.porcentaje;
    document.getElementById('impuestoImporte').value = impImporte.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

// Auto-set marca and precio when selecting vehicle
document.getElementById('vehiculoSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (!opt.value) return;
    const marcaId = opt.dataset.marca;
    const modelo = opt.dataset.modelo;
    const version = opt.dataset.version;
    if (marcaId) document.getElementById('marcaSelect').value = marcaId;
    // Auto-fill price from catalog
    const key = marcaId + '|' + modelo + '|' + version;
    if (preciosCatalogo[key]) {
        document.getElementById('precioVenta').value = preciosCatalogo[key].toFixed(2);
        recalc();
    }
});

// Recalc on changes
document.getElementById('precioVenta').addEventListener('input', recalc);
document.getElementById('descuento').addEventListener('input', recalc);
document.getElementById('empresaSelect').addEventListener('change', recalc);

// Listen to existing concepto inputs
document.querySelectorAll('.concepto-importe').forEach(el => el.addEventListener('input', recalc));

// Initial calc
recalc();
</script>
@endpush
@endsection
