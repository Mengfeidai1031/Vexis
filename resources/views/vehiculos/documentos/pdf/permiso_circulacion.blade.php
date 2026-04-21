@php $codigoDocumento = $datos['numero_permiso']; @endphp
@extends('vehiculos.documentos.pdf._layout')

@section('content')
    <div class="callout callout-blue">
        <div class="callout-title">Permiso de circulación</div>
        Documento autorizativo que acredita la titularidad administrativa del vehículo y su aptitud para circular por las vías públicas.
    </div>

    <table class="pair-row">
        <tr>
            <td class="left">
                <div class="party-box primary">
                    <div class="party-label">Titular</div>
                    <div class="party-name">{{ $cliente->nombre ?? '—' }} {{ $cliente->apellidos ?? '' }}</div>
                    <div class="party-detail">
                        DNI/NIF: <strong>{{ $cliente->dni ?? '—' }}</strong><br>
                        {{ $cliente->domicilio ?? '' }}<br>
                        {{ $cliente->codigo_postal ?? '' }} {{ $cliente->municipio ?? '' }}<br>
                        Tel: {{ $cliente->telefono ?? '—' }}
                    </div>
                </div>
            </td>
            <td class="right">
                <div class="party-box accent">
                    <div class="party-label">Empresa responsable</div>
                    <div class="party-name">{{ $empresa->nombre ?? '—' }}</div>
                    <div class="party-detail">
                        CIF: {{ $empresa->cif ?? '—' }}<br>
                        {{ $empresa->domicilio ?? '' }}<br>
                        {{ $empresa->codigo_postal ?? '' }} {{ $empresa->localidad ?? '' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Datos del vehículo</div>
    <table class="info-table">
        <tr>
            <td class="label">Matrícula</td><td class="value-mono"><span class="status-badge badge-info" style="font-size:12px;letter-spacing:1.2px;">{{ $vehiculo->matricula ?? 'PENDIENTE' }}</span></td>
            <td class="label">Marca</td><td>{{ $vehiculo->marca->nombre ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Modelo</td><td>{{ $vehiculo->modelo }}</td>
            <td class="label">Versión</td><td>{{ $vehiculo->version }}</td>
        </tr>
        <tr>
            <td class="label">Chasis (VIN)</td><td class="value-mono" colspan="3">{{ $vehiculo->chasis }}</td>
        </tr>
        <tr>
            <td class="label">Color exterior</td><td>{{ $vehiculo->color_externo }}</td>
            <td class="label">Color interior</td><td>{{ $vehiculo->color_interno }}</td>
        </tr>
    </table>

    <div class="section-title">Datos administrativos</div>
    <table class="info-table">
        <tr>
            <td class="label">Nº Permiso</td><td class="value-mono">{{ $datos['numero_permiso'] }}</td>
            <td class="label">Uso</td><td>{{ ucfirst(str_replace('_', ' ', $datos['uso'])) }}</td>
        </tr>
        <tr>
            <td class="label">Fecha matriculación</td><td>{{ \Carbon\Carbon::parse($datos['fecha_matriculacion'])->format('d/m/Y') }}</td>
            <td class="label">Jefatura Tráfico</td><td>{{ $datos['jefatura_trafico'] }}</td>
        </tr>
        @if(!empty($datos['fecha_vencimiento']))
        <tr>
            <td class="label">Válido hasta</td><td colspan="3"><strong style="color:#33AADD;">{{ \Carbon\Carbon::parse($datos['fecha_vencimiento'])->format('d/m/Y') }}</strong></td>
        </tr>
        @endif
    </table>

    @if(!empty($datos['observaciones']))
    <div class="section-title">Observaciones</div>
    <div class="kv-block" style="font-size:10.5px;line-height:1.5;">{{ $datos['observaciones'] }}</div>
    @endif

    <table class="signatures">
        <tr>
            <td><div class="signature-line"></div><div class="signature-label">Firma del titular</div></td>
            <td><div class="signature-line"></div><div class="signature-label">Sello empresa responsable</div></td>
        </tr>
    </table>

    <div class="callout callout-amber" style="margin-top:20px;">
        <div class="callout-title">Aviso legal</div>
        Este documento tiene carácter informativo y de registro interno. El Permiso de Circulación oficial es el emitido por la Dirección General de Tráfico, que deberá portarse en el vehículo junto con la Tarjeta ITV y el seguro vigente.
    </div>
@endsection
