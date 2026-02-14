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
</script>
@endpush