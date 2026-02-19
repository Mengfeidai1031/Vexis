@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Editar Usuario: {{ $user->nombre_completo }}</h2>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" 
                            name="nombre" 
                            value="{{ old('nombre', $user->nombre) }}" 
                            required
                        >
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Apellidos -->
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('apellidos') is-invalid @enderror" 
                            id="apellidos" 
                            name="apellidos" 
                            value="{{ old('apellidos', $user->apellidos) }}" 
                            required
                        >
                        @error('apellidos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Empresa -->
                    <div class="mb-3">
                        <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('empresa_id') is-invalid @enderror" 
                            id="empresa_id" 
                            name="empresa_id" 
                            required
                        >
                            <option value="">Seleccione una empresa</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ old('empresa_id', $user->empresa_id) == $empresa->id ? 'selected' : '' }}>
                                    {{ $empresa->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('empresa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Departamento -->
                    <div class="mb-3">
                        <label for="departamento_id" class="form-label">Departamento <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('departamento_id') is-invalid @enderror" 
                            id="departamento_id" 
                            name="departamento_id" 
                            required
                        >
                            <option value="">Seleccione un departamento</option>
                            @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id }}" {{ old('departamento_id', $user->departamento_id) == $departamento->id ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('departamento_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Centro -->
                    <div class="mb-3">
                        <label for="centro_id" class="form-label">Centro <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('centro_id') is-invalid @enderror" 
                            id="centro_id" 
                            name="centro_id" 
                            required
                        >
                            <option value="">Seleccione un centro</option>
                            @foreach($centros as $centro)
                                <option value="{{ $centro->id }}" data-empresa="{{ $centro->empresa_id }}" {{ old('centro_id', $user->centro_id) == $centro->id ? 'selected' : '' }}>
                                    {{ $centro->nombre }} ({{ $centro->empresa->nombre }})
                                </option>
                            @endforeach
                        </select>
                        @error('centro_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}" 
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input 
                            type="text" 
                            class="form-control @error('telefono') is-invalid @enderror" 
                            id="telefono" 
                            name="telefono" 
                            value="{{ old('telefono', $user->telefono) }}"
                            maxlength="12"
                        >
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Extensión -->
                    <div class="mb-3">
                        <label for="extension" class="form-label">Extensión</label>
                        <input 
                            type="text" 
                            class="form-control @error('extension') is-invalid @enderror" 
                            id="extension" 
                            name="extension" 
                            value="{{ old('extension', $user->extension) }}"
                            maxlength="10"
                        >
                        @error('extension')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Roles -->
                    <div class="mb-3">
                        <label class="form-label">Roles</label>
                        <p class="text-muted small">Seleccione uno o varios roles para el usuario</p>
                        @php
                            $userRoleIds = $user->roles->pluck('id')->toArray();
                        @endphp
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="roles[]" 
                                    value="{{ $role->id }}" 
                                    id="role-{{ $role->id }}"
                                    {{ in_array($role->id, old('roles', $userRoleIds)) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="role-{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                        @error('roles')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Restricciones -->
                    @can('editar restricciones')
                    <div class="mb-4">
                        <label class="form-label">Restricciones</label>
                        <p class="text-muted small">Selecciona las restricciones para este usuario. Si no seleccionas ninguna restricción de un tipo, el usuario podrá ver todo de ese tipo.</p>
                        
                        <!-- Empresas -->
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <input type="checkbox" class="form-check-input me-2 select-all-type" data-type="empresas" id="select-all-empresas">
                                        <label for="select-all-empresas" class="mb-0">Empresas</label>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($availableRestrictions['empresas'] as $empresa)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input restriction-checkbox type-empresas" type="checkbox" name="restrictions[empresas][]" value="{{ $empresa->id }}" id="empresa-{{ $empresa->id }}" {{ in_array($empresa->id, old('restrictions.empresas', $userRestrictions['empresas'] ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="empresa-{{ $empresa->id }}">{{ $empresa->nombre }}@if($empresa->cif) <small class="text-muted">({{ $empresa->cif }})</small>@endif</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Clientes -->
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <input type="checkbox" class="form-check-input me-2 select-all-type" data-type="clientes" id="select-all-clientes">
                                        <label for="select-all-clientes" class="mb-0">Clientes</label>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($availableRestrictions['clientes'] as $cliente)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input restriction-checkbox type-clientes" type="checkbox" name="restrictions[clientes][]" value="{{ $cliente->id }}" id="cliente-{{ $cliente->id }}" {{ in_array($cliente->id, old('restrictions.clientes', $userRestrictions['clientes'] ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="cliente-{{ $cliente->id }}">{{ $cliente->nombre_completo }} <small class="text-muted">({{ $cliente->empresa->nombre }})</small></label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehículos -->
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <input type="checkbox" class="form-check-input me-2 select-all-type" data-type="vehiculos" id="select-all-vehiculos">
                                        <label for="select-all-vehiculos" class="mb-0">Vehículos</label>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($availableRestrictions['vehiculos'] as $vehiculo)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input restriction-checkbox type-vehiculos" type="checkbox" name="restrictions[vehiculos][]" value="{{ $vehiculo->id }}" id="vehiculo-{{ $vehiculo->id }}" {{ in_array($vehiculo->id, old('restrictions.vehiculos', $userRestrictions['vehiculos'] ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="vehiculo-{{ $vehiculo->id }}">{{ $vehiculo->modelo }} {{ $vehiculo->version }} <small class="text-muted">({{ $vehiculo->empresa->nombre }})</small></label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Centros -->
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <input type="checkbox" class="form-check-input me-2 select-all-type" data-type="centros" id="select-all-centros">
                                        <label for="select-all-centros" class="mb-0">Centros</label>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($availableRestrictions['centros'] as $centro)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input restriction-checkbox type-centros" type="checkbox" name="restrictions[centros][]" value="{{ $centro->id }}" id="centro-{{ $centro->id }}" {{ in_array($centro->id, old('restrictions.centros', $userRestrictions['centros'] ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="centro-{{ $centro->id }}">{{ $centro->nombre }} <small class="text-muted">({{ $centro->empresa->nombre }})</small></label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Departamentos -->
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <input type="checkbox" class="form-check-input me-2 select-all-type" data-type="departamentos" id="select-all-departamentos">
                                        <label for="select-all-departamentos" class="mb-0">Departamentos</label>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($availableRestrictions['departamentos'] as $departamento)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input restriction-checkbox type-departamentos" type="checkbox" name="restrictions[departamentos][]" value="{{ $departamento->id }}" id="departamento-{{ $departamento->id }}" {{ in_array($departamento->id, old('restrictions.departamentos', $userRestrictions['departamentos'] ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="departamento-{{ $departamento->id }}">{{ $departamento->nombre }}@if($departamento->abreviatura) <small class="text-muted">({{ $departamento->abreviatura }})</small>@endif</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan

                    <!-- Password (opcional) -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="password" 
                            name="password"
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Mínimo 6 caracteres</small>
                    </div>

                    <!-- Confirmar Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password_confirmation" 
                            name="password_confirmation"
                        >
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filtrar centros por empresa seleccionada
    document.getElementById('empresa_id').addEventListener('change', function() {
        const empresaId = this.value;
        const centroSelect = document.getElementById('centro_id');
        const options = centroSelect.querySelectorAll('option');
        
        // Resetear el select de centros
        centroSelect.value = '';
        
        // Mostrar/ocultar opciones según la empresa
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
            } else {
                const optionEmpresa = option.getAttribute('data-empresa');
                option.style.display = (optionEmpresa === empresaId) ? 'block' : 'none';
            }
        });
    });

    // Ejecutar al cargar para mostrar centros correctos
    document.addEventListener('DOMContentLoaded', function() {
        const empresaSelect = document.getElementById('empresa_id');
        if (empresaSelect.value) {
            empresaSelect.dispatchEvent(new Event('change'));
        }
    });

    // Seleccionar/deseleccionar todos de un tipo de restricción
    document.querySelectorAll('.select-all-type').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const type = this.dataset.type;
            const typeCheckboxes = document.querySelectorAll('.type-' + type);
            
            typeCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });

    // Actualizar el checkbox del tipo si se seleccionan/deseleccionan individuales
    document.querySelectorAll('.restriction-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const classes = Array.from(this.classList);
            const typeClass = classes.find(c => c.startsWith('type-'));
            
            if (typeClass) {
                const type = typeClass.replace('type-', '');
                const typeCheckboxes = document.querySelectorAll('.type-' + type);
                const typeSelectAll = document.querySelector(`.select-all-type[data-type="${type}"]`);
                
                const allChecked = Array.from(typeCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(typeCheckboxes).some(cb => cb.checked);
                
                if (typeSelectAll) {
                    typeSelectAll.checked = allChecked;
                    typeSelectAll.indeterminate = someChecked && !allChecked;
                }
            }
        });
    });

    // Establecer estados iniciales de checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.select-all-type').forEach(checkbox => {
            const type = checkbox.dataset.type;
            const typeCheckboxes = document.querySelectorAll('.type-' + type);
            const allChecked = Array.from(typeCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(typeCheckboxes).some(cb => cb.checked);
            
            if (someChecked && !allChecked) {
                checkbox.indeterminate = true;
            }
        });
    });
</script>
@endpush