@extends('layouts.app')
@section('title', 'Editar Festivo - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar: {{ $festivo->nombre }}</h1>
    <a href="{{ route('festivos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:600px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('festivos.update', $festivo) }}" method="POST">
            @csrf @method('PUT')
            <div class="vx-form-group">
                <label class="vx-label" for="nombre">Nombre del Festivo <span class="required">*</span></label>
                <input type="text" class="vx-input @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $festivo->nombre) }}" required>
                @error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="fecha">Fecha <span class="required">*</span></label>
                    <input type="date" class="vx-input @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $festivo->fecha->format('Y-m-d')) }}" required>
                    @error('fecha')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="ambito">Ámbito <span class="required">*</span></label>
                    <select class="vx-select" id="ambito" name="ambito" required>
                        @foreach(\App\Models\Festivo::$ambitos as $k => $v)
                            <option value="{{ $k }}" {{ old('ambito', $festivo->ambito) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="vx-form-group" id="municipioGroup">
                <label class="vx-label" for="municipio">Municipio</label>
                <input type="text" class="vx-input" id="municipio" name="municipio" value="{{ old('municipio', $festivo->municipio) }}">
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('festivos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
            </div>
        </form>
    </div></div>
</div>
@push('scripts')
<script>
document.getElementById('ambito').addEventListener('change', function() {
    document.getElementById('municipioGroup').style.display = this.value === 'local' ? '' : 'none';
    if (this.value !== 'local') document.getElementById('municipio').value = '';
});
if (document.getElementById('ambito').value !== 'local') document.getElementById('municipioGroup').style.display = 'none';
</script>
@endpush
@endsection
