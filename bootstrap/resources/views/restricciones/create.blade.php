@extends('layouts.app')
@section('title', 'Crear Restricción - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nueva Restricción</h1>
    <a href="{{ route('restricciones.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width: 700px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('restricciones.store') }}" method="POST">
            @csrf
            <div class="vx-form-group">
                <label class="vx-label" for="user_id">Usuario <span class="required">*</span></label>
                <select class="vx-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                    <option value="">Seleccione un usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nombre_completo }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="restriction_type">Tipo de Restricción <span class="required">*</span></label>
                <select class="vx-select @error('restriction_type') is-invalid @enderror" id="restriction_type" name="restriction_type" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="empresa" {{ old('restriction_type') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                    <option value="cliente" {{ old('restriction_type') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="vehiculo" {{ old('restriction_type') == 'vehiculo' ? 'selected' : '' }}>Vehículo</option>
                    <option value="centro" {{ old('restriction_type') == 'centro' ? 'selected' : '' }}>Centro</option>
                    <option value="departamento" {{ old('restriction_type') == 'departamento' ? 'selected' : '' }}>Departamento</option>
                </select>
                @error('restriction_type')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="restrictable_id">Entidad <span class="required">*</span></label>
                <select class="vx-select @error('restrictable_id') is-invalid @enderror" id="restrictable_id" name="restrictable_id" required>
                    <option value="">Seleccione primero un tipo</option>
                </select>
                @error('restrictable_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <a href="{{ route('restricciones.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>
const availableRestrictions = @json($availableRestrictions);
const typeMap = {empresa:'empresas',cliente:'clientes',vehiculo:'vehiculos',centro:'centros',departamento:'departamentos'};
document.getElementById('restriction_type').addEventListener('change', function() {
    const type = this.value, select = document.getElementById('restrictable_id');
    select.innerHTML = '<option value="">Seleccione una opción</option>';
    if (type && availableRestrictions[typeMap[type]]) {
        availableRestrictions[typeMap[type]].forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            let label = '';
            if (type === 'empresa') label = item.nombre + (item.cif ? ' (' + item.cif + ')' : '');
            else if (type === 'cliente') label = (item.nombre_completo || item.nombre + ' ' + (item.apellidos||'')) + (item.empresa?.nombre ? ' - ' + item.empresa.nombre : '');
            else if (type === 'vehiculo') label = item.modelo + ' ' + item.version + (item.empresa?.nombre ? ' - ' + item.empresa.nombre : '');
            else if (type === 'centro') label = item.nombre + (item.empresa?.nombre ? ' - ' + item.empresa.nombre : '');
            else if (type === 'departamento') label = item.nombre + (item.abreviatura ? ' (' + item.abreviatura + ')' : '');
            opt.textContent = label;
            select.appendChild(opt);
        });
    }
});
</script>
@endpush
