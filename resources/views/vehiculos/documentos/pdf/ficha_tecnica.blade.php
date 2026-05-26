@php $codigoDocumento = $datos['numero_homologacion']; @endphp
@extends('vehiculos.documentos.pdf._layout')

@section('content')
    <div class="callout callout-blue">
        <div class="callout-title">Ficha Técnica · Reducida</div>
        Resumen técnico del vehículo emitido por el sistema VEXIS conforme a los datos registrados en la base de datos homologada.
    </div>

    <div class="section-title">Identificación del vehículo</div>
    <table class="info-table">
        <tr>
            <td class="label">Marca</td><td>{{ $vehiculo->marca->nombre ?? '—' }}</td>
            <td class="label">Modelo</td><td>{{ $vehiculo->modelo }}</td>
        </tr>
        <tr>
            <td class="label">Versión</td><td>{{ $vehiculo->version }}</td>
            <td class="label">Categoría</td><td><span class="status-badge badge-info">{{ $datos['categoria'] }}</span></td>
        </tr>
        <tr>
            <td class="label">Chasis (VIN)</td><td class="value-mono" colspan="3">{{ $vehiculo->chasis }}</td>
        </tr>
        <tr>
            <td class="label">Matrícula</td><td class="value-mono">{{ $vehiculo->matricula ?? 'Sin matricular' }}</td>
            <td class="label">Fecha emisión</td><td>{{ \Carbon\Carbon::parse($datos['fecha_emision'])->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="section-title">Características técnicas</div>
    <table class="info-table">
        <tr>
            <td class="label">Combustible</td><td>{{ $datos['combustible'] }}</td>
            <td class="label">Transmisión</td><td>{{ $datos['transmision'] }}</td>
        </tr>
        <tr>
            <td class="label">Cilindrada</td><td>{{ $datos['cilindrada_cc'] ? number_format((int) $datos['cilindrada_cc'], 0, ',', '.').' cc' : '—' }}</td>
            <td class="label">Potencia</td><td><strong>{{ $datos['potencia_cv'] }} CV</strong></td>
        </tr>
        <tr>
            <td class="label">Plazas</td><td>{{ $datos['plazas'] }}</td>
            <td class="label">Emisiones CO₂</td><td>{{ !empty($datos['emisiones_co2']) ? $datos['emisiones_co2'].' g/km' : '—' }}</td>
        </tr>
        <tr>
            <td class="label">Peso en vacío</td><td>{{ !empty($datos['peso_vacio_kg']) ? number_format((int) $datos['peso_vacio_kg'], 0, ',', '.').' kg' : '—' }}</td>
            <td class="label">MMA</td><td>{{ !empty($datos['peso_maximo_kg']) ? number_format((int) $datos['peso_maximo_kg'], 0, ',', '.').' kg' : '—' }}</td>
        </tr>
        <tr>
            <td class="label">Color exterior</td><td>{{ $vehiculo->color_externo }}</td>
            <td class="label">Color interior</td><td>{{ $vehiculo->color_interno }}</td>
        </tr>
    </table>

    @if(!empty($datos['observaciones']))
    <div class="section-title">Observaciones</div>
    <div class="kv-block" style="font-size:10.5px;line-height:1.5;">{{ $datos['observaciones'] }}</div>
    @endif

    <div class="callout callout-amber" style="margin-top:20px;">
        <div class="callout-title">Aviso</div>
        Este documento reproduce datos administrativos y técnicos con fines internos y de información al cliente. No sustituye la Tarjeta de Inspección Técnica oficial emitida por la Jefatura Provincial de Tráfico.
    </div>
@endsection
