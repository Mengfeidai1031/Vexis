@extends('layouts.app')
@section('title', 'Editar ' . $vehiculo->modelo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar: {{ $vehiculo->descripcion_completa }}</h1>
    <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width: 800px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST">
            @csrf @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="chasis">Chasis (VIN) <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('chasis') is-invalid @enderror" id="chasis" name="chasis" value="{{ old('chasis', $vehiculo->chasis) }}" maxlength="17" required style="text-transform: uppercase; font-family: var(--vx-font-mono);">
                    @error('chasis')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    <div class="vx-form-hint">Exactamente 17 caracteres</div>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="matricula">Matrícula</label>
                    <div style="display:flex;gap:8px;">
                        <input type="text" class="vx-input @error('matricula') is-invalid @enderror" id="matricula" name="matricula" value="{{ old('matricula', $vehiculo->matricula) }}" maxlength="10" placeholder="1234 BCD" style="text-transform: uppercase; font-family: var(--vx-font-mono);flex:1;">
                        @if(!$vehiculo->matricula)
                        <button type="button" id="btnGenerarMatricula" class="vx-btn vx-btn-primary" style="white-space:nowrap;padding:8px 12px;" title="Generar siguiente matrícula disponible"><i class="bi bi-plus-circle"></i> Nueva</button>
                        @endif
                    </div>
                    @error('matricula')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="marca_id">Marca <span class="required">*</span></label>
                    <select class="vx-select @error('marca_id') is-invalid @enderror" id="marca_id" name="marca_id" required>
                        <option value="">Seleccione una marca</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}" {{ old('marca_id', $vehiculo->marca_id) == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
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
                        <option value="">Seleccione</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id', $vehiculo->empresa_id) == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                        @endforeach
                    </select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva</a>
                    @error('empresa_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="color_externo">Color Externo <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('color_externo') is-invalid @enderror" id="color_externo" name="color_externo" value="{{ old('color_externo', $vehiculo->color_externo) }}" required>
                    @error('color_externo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="color_interno">Color Interno <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('color_interno') is-invalid @enderror" id="color_interno" name="color_interno" value="{{ old('color_interno', $vehiculo->color_interno) }}" required>
                    @error('color_interno')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <a href="{{ route('vehiculos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>
const catalogoModelos = @json($catalogoModelos);
const currentModelo = @json(old('modelo', $vehiculo->modelo));
const currentVersion = @json(old('version', $vehiculo->version));
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
        if (m === currentModelo) opt.selected = true;
        modeloSelect.appendChild(opt);
    });
    // If current modelo not in catalog, add it as option
    if (currentModelo && !modelos.includes(currentModelo)) {
        const opt = document.createElement('option');
        opt.value = currentModelo;
        opt.textContent = currentModelo + ' (no en catálogo)';
        opt.selected = true;
        modeloSelect.appendChild(opt);
    }
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
        if (v === currentVersion) opt.selected = true;
        versionSelect.appendChild(opt);
    });
    // If current version not in catalog, add it
    if (currentVersion && !unique.includes(currentVersion)) {
        const opt = document.createElement('option');
        opt.value = currentVersion;
        opt.textContent = currentVersion + ' (no en catálogo)';
        opt.selected = true;
        versionSelect.appendChild(opt);
    }
}

// Init on page load
if (marcaSelect.value) {
    updateModelos(marcaSelect.value);
    if (currentModelo) {
        modeloSelect.value = currentModelo;
        updateVersiones(marcaSelect.value, currentModelo);
        if (currentVersion) versionSelect.value = currentVersion;
    }
}

document.getElementById('chasis').addEventListener('input',function(e){e.target.value=e.target.value.toUpperCase();});
document.getElementById('matricula').addEventListener('input',function(e){e.target.value=e.target.value.toUpperCase();});
var btnGen = document.getElementById('btnGenerarMatricula');
if (btnGen) {
    btnGen.addEventListener('click', function() {
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
}
</script>
@endpush
