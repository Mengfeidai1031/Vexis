@extends('layouts.app')

@section('title', 'Editar Restricción')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Editar Restricción</h2>
            <a href="{{ route('restricciones.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('restricciones.update', $restriccion->id) }}" method="POST" id="restrictionForm">
                    @csrf
                    @method('PUT')

                    <!-- Usuario -->
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('user_id') is-invalid @enderror" 
                            id="user_id" 
                            name="user_id" 
                            required
                        >
                            <option value="">Seleccione un usuario</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $restriccion->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->nombre_completo }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tipo de Restricción -->
                    <div class="mb-3">
                        <label for="restriction_type" class="form-label">Tipo de Restricción <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('restriction_type') is-invalid @enderror" 
                            id="restriction_type" 
                            name="restriction_type" 
                            required
                        >
                            <option value="">Seleccione un tipo</option>
                            @php
                                $currentType = match($restriccion->restrictable_type) {
                                    'App\Models\Empresa' => 'empresa',
                                    'App\Models\Cliente' => 'cliente',
                                    'App\Models\Vehiculo' => 'vehiculo',
                                    'App\Models\Centro' => 'centro',
                                    'App\Models\Departamento' => 'departamento',
                                    default => '',
                                };
                            @endphp
                            <option value="empresa" {{ old('restriction_type', $currentType) == 'empresa' ? 'selected' : '' }}>Empresa</option>
                            <option value="cliente" {{ old('restriction_type', $currentType) == 'cliente' ? 'selected' : '' }}>Cliente</option>
                            <option value="vehiculo" {{ old('restriction_type', $currentType) == 'vehiculo' ? 'selected' : '' }}>Vehículo</option>
                            <option value="centro" {{ old('restriction_type', $currentType) == 'centro' ? 'selected' : '' }}>Centro</option>
                            <option value="departamento" {{ old('restriction_type', $currentType) == 'departamento' ? 'selected' : '' }}>Departamento</option>
                        </select>
                        @error('restriction_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Entidad Restringida -->
                    <div class="mb-3">
                        <label for="restrictable_id" class="form-label">Entidad <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('restrictable_id') is-invalid @enderror" 
                            id="restrictable_id" 
                            name="restrictable_id" 
                            required
                        >
                            <option value="">Seleccione primero un tipo de restricción</option>
                        </select>
                        @error('restrictable_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('restricciones.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Restricción</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const availableRestrictions = @json($availableRestrictions);
    const currentRestrictableId = {{ $restriccion->restrictable_id }};
    
    function loadRestrictions() {
        const type = document.getElementById('restriction_type').value;
        const select = document.getElementById('restrictable_id');
        select.innerHTML = '<option value="">Seleccione una opción</option>';
        
        // Mapeo de tipos a claves del array
        const typeMap = {
            'empresa': 'empresas',
            'cliente': 'clientes',
            'vehiculo': 'vehiculos',
            'centro': 'centros',
            'departamento': 'departamentos'
        };
        
        const arrayKey = typeMap[type];
        
        if (type && availableRestrictions[arrayKey]) {
            const items = availableRestrictions[arrayKey];
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.selected = (item.id == currentRestrictableId);
                
                let label = '';
                if (type === 'empresa') {
                    label = item.nombre + (item.cif ? ' (' + item.cif + ')' : '');
                } else if (type === 'cliente') {
                    // Construir nombre completo desde nombre y apellidos
                    const nombreCompleto = item.nombre_completo || (item.nombre + ' ' + (item.apellidos || ''));
                    label = nombreCompleto;
                    if (item.empresa && item.empresa.nombre) {
                        label += ' - ' + item.empresa.nombre;
                    }
                } else if (type === 'vehiculo') {
                    label = item.modelo + ' ' + item.version;
                    if (item.empresa && item.empresa.nombre) {
                        label += ' - ' + item.empresa.nombre;
                    }
                } else if (type === 'centro') {
                    label = item.nombre;
                    if (item.empresa && item.empresa.nombre) {
                        label += ' - ' + item.empresa.nombre;
                    }
                } else if (type === 'departamento') {
                    label = item.nombre;
                    if (item.abreviatura) {
                        label += ' (' + item.abreviatura + ')';
                    }
                }
                
                option.textContent = label;
                select.appendChild(option);
            });
        }
    }
    
    document.getElementById('restriction_type').addEventListener('change', loadRestrictions);
    
    // Cargar al inicio
    document.addEventListener('DOMContentLoaded', function() {
        loadRestrictions();
    });
</script>
@endpush
