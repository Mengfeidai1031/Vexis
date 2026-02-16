@extends('layouts.app')

@section('title', 'Cargar Oferta Comercial')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Cargar Nueva Oferta Comercial (PDF)</h2>
            <a href="{{ route('ofertas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<!-- Mensajes de error -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8 offset-md-2">
        <!-- Información importante -->
        <div class="alert alert-info">
            <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Información Importante</h5>
            <p>El sistema procesará automáticamente el PDF de la oferta comercial y extraerá:</p>
            <ul class="mb-0">
                <li>Fecha de la oferta</li>
                <li>Líneas de detalle (opciones, descuentos, accesorios)</li>
                <li>Precios de cada línea</li>
            </ul>
            <hr>
            <p class="mb-0"><strong>Nota:</strong> El formato del PDF puede variar. Si el sistema no extrae correctamente la información, puedes revisar y ajustar el servicio de procesamiento.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('ofertas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Cliente -->
                    <div class="mb-3">
                        <label for="cliente_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('cliente_id') is-invalid @enderror" 
                            id="cliente_id" 
                            name="cliente_id" 
                            required
                        >
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre_completo }} - {{ $cliente->dni }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vehículo -->
                    <div class="mb-3">
                        <label for="vehiculo_id" class="form-label">Vehículo <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('vehiculo_id') is-invalid @enderror" 
                            id="vehiculo_id" 
                            name="vehiculo_id" 
                            required
                        >
                            <option value="">Seleccione un vehículo</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}" {{ old('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                    {{ $vehiculo->modelo }} - {{ $vehiculo->version }} ({{ $vehiculo->chasis }})
                                </option>
                            @endforeach
                        </select>
                        @error('vehiculo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Archivo PDF -->
                    <div class="mb-3">
                        <label for="pdf_file" class="form-label">Archivo PDF de la Oferta <span class="text-danger">*</span></label>
                        <input 
                            type="file" 
                            class="form-control @error('pdf_file') is-invalid @enderror" 
                            id="pdf_file" 
                            name="pdf_file" 
                            accept="application/pdf"
                            required
                        >
                        @error('pdf_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tamaño máximo: 10MB. Solo archivos PDF.</small>
                    </div>

                    <!-- Vista previa del nombre del archivo -->
                    <div id="file-info" class="mb-3 d-none">
                        <div class="alert alert-secondary">
                            <strong>Archivo seleccionado:</strong> <span id="file-name"></span><br>
                            <strong>Tamaño:</strong> <span id="file-size"></span>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('ofertas.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Procesar PDF y Guardar Oferta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar información del archivo seleccionado
    document.getElementById('pdf_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('file-name').textContent = file.name;
            document.getElementById('file-size').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            document.getElementById('file-info').classList.remove('d-none');
        } else {
            document.getElementById('file-info').classList.add('d-none');
        }
    });
</script>
@endpush