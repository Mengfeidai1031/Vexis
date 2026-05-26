@extends('layouts.app')
@section('title', 'Crear Vehículo - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nuevo Vehículo</h1>
    <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width: 800px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('vehiculos.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="chasis">Chasis (VIN) <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('chasis') is-invalid @enderror" id="chasis" name="chasis" value="{{ old('chasis') }}" maxlength="17" placeholder="WVWZZZ1JZXW123456" required style="text-transform: uppercase; font-family: var(--vx-font-mono);">
                    @error('chasis')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    <div class="vx-form-hint">Exactamente 17 caracteres</div>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="matricula">Matrícula</label>
                    <div style="display:flex;gap:8px;">
                        <input type="text" class="vx-input @error('matricula') is-invalid @enderror" id="matricula" name="matricula" value="{{ old('matricula') }}" maxlength="10" placeholder="1234 BCD" style="text-transform: uppercase; font-family: var(--vx-font-mono);flex:1;">
                        <button type="button" id="btnGenerarMatricula" class="vx-btn vx-btn-primary" style="white-space:nowrap;padding:8px 12px;" title="Generar siguiente matrícula disponible"><i class="bi bi-plus-circle"></i> Nueva</button>
                    </div>
                    @error('matricula')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    <div class="vx-form-hint">Dejar vacío si aún no está matriculado</div>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="marca_id">Marca <span class="required">*</span></label>
                    <select class="vx-select @error('marca_id') is-invalid @enderror" id="marca_id" name="marca_id" required>
                        <option value="">Seleccione una marca</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                    @error('marca_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="modelo">Modelo <span class="required">*</span></label>
                    <div style="display:flex;gap:8px;">
                        <select class="vx-select @error('modelo') is-invalid @enderror" id="modelo" name="modelo" required style="flex:1;">
                            <option value="">Seleccione marca primero</option>
                        </select>
                        <a href="{{ route('catalogo-precios.create') }}" class="vx-btn vx-btn-secondary" style="white-space:nowrap;padding:8px 12px;" target="_blank" title="Crear modelo en catálogo"><i class="bi bi-plus-circle"></i></a>
                    </div>
                    @error('modelo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="version">Versión <span class="required">*</span></label>
                    <select class="vx-select @error('version') is-invalid @enderror" id="version" name="version" required>
                        <option value="">Seleccione modelo primero</option>
                    </select>
                    @error('version')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="empresa_id">Empresa <span class="required">*</span></label>
                    <select class="vx-select @error('empresa_id') is-invalid @enderror" id="empresa_id" name="empresa_id" required>
                        <option value="">Seleccione una empresa</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                        @endforeach
                    </select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva</a>
                    @error('empresa_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="color_externo">Color Externo <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('color_externo') is-invalid @enderror" id="color_externo" name="color_externo" value="{{ old('color_externo') }}" placeholder="Ej: Blanco Perlado" required>
                    @error('color_externo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="color_interno">Color Interno <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('color_interno') is-invalid @enderror" id="color_interno" name="color_interno" value="{{ old('color_interno') }}" placeholder="Ej: Negro Titanio" required>
                    @error('color_interno')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="estado">Estado <span class="required">*</span></label>
                    <select class="vx-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                        @foreach(\App\Models\Vehiculo::$estados as $k => $v)
                            <option value="{{ $k }}" {{ old('estado', 'disponible') === $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('estado')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="responsable_id">Responsable</label>
                    <select class="vx-select" id="responsable_id" name="responsable_id">
                        <option value="">— Sin asignar —</option>
                        @foreach(\App\Models\User::orderBy('nombre')->get() as $u)
                            <option value="{{ $u->id }}" {{ old('responsable_id') == $u->id ? 'selected' : '' }}>{{ $u->nombre }} {{ $u->apellidos }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>
const catalogoModelos = @json($catalogoModelos);
const oldModelo = @json(old('modelo', ''));
const oldVersion = @json(old('version', ''));
const marcaSelect = document.getElementById('marca_id');
const modeloSelect = document.getElementById('modelo');
const versionSelect = document.getElementById('version');

marcaSelect.addEventListener('change', function() {
    updateModelos(this.value);
    versionSelect.innerHTML = '<option value="">Seleccione modelo primero</option>';
});

modeloSelect.addEventListener('change', function() {
    updateVersiones(marcaSelect.value, this.value);
});

function updateModelos(marcaId) {
    modeloSelect.innerHTML = '<option value="">Seleccione un modelo</option>';
    if (!marcaId || !catalogoModelos[marcaId]) return;
    const modelos = [...new Set(catalogoModelos[marcaId].map(c => c.modelo))];
    modelos.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m;
        opt.textContent = m;
        if (m === oldModelo) opt.selected = true;
        modeloSelect.appendChild(opt);
    });
}

function updateVersiones(marcaId, modelo) {
    versionSelect.innerHTML = '<option value="">Seleccione una versión</option>';
    if (!marcaId || !modelo || !catalogoModelos[marcaId]) return;
    const versiones = catalogoModelos[marcaId].filter(c => c.modelo === modelo).map(c => c.version).filter(Boolean);
    const unique = [...new Set(versiones)];
    unique.forEach(v => {
        const opt = document.createElement('option');
        opt.value = v;
        opt.textContent = v;
        if (v === oldVersion) opt.selected = true;
        versionSelect.appendChild(opt);
    });
}

// Init on page load (for old() values)
if (marcaSelect.value) {
    updateModelos(marcaSelect.value);
    if (oldModelo) {
        modeloSelect.value = oldModelo;
        updateVersiones(marcaSelect.value, oldModelo);
        if (oldVersion) versionSelect.value = oldVersion;
    }
}

document.getElementById('chasis').addEventListener('input',function(e){e.target.value=e.target.value.toUpperCase();});
document.getElementById('matricula').addEventListener('input',function(e){e.target.value=e.target.value.toUpperCase();});
document.getElementById('btnGenerarMatricula').addEventListener('click', function() {
    const btn = this;
    const input = document.getElementById('matricula');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    fetch('{{ route("vehiculos.generarMatricula") }}', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        input.value = data.matricula;
        input.style.borderColor = 'var(--vx-success)';
        setTimeout(() => input.style.borderColor = '', 1500);
    })
    .catch(() => alert('Error al generar matrícula'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-plus-circle"></i> Nueva';
    });
});
</script>
@endpush
