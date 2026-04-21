@php $codigoDocumento = $datos['numero_contrato']; @endphp
@extends('vehiculos.documentos.pdf._layout')

@section('content')
    @php
        $tipoLabel = [
            'compraventa' => 'COMPRAVENTA',
            'deposito' => 'DEPÓSITO',
            'alquiler' => 'ALQUILER',
            'custodia' => 'CUSTODIA',
            'cesion' => 'CESIÓN',
        ][$datos['tipo_contrato']] ?? $datos['tipo_contrato'];
    @endphp

    <div class="callout callout-blue">
        <div class="callout-title">Contrato de {{ $tipoLabel }}</div>
        Por el presente documento las partes identificadas formalizan las condiciones del contrato indicado relativo al vehículo descrito a continuación.
    </div>

    <table class="pair-row">
        <tr>
            <td class="left">
                <div class="party-box primary">
                    <div class="party-label">Empresa</div>
                    <div class="party-name">{{ $empresa->nombre ?? '—' }}</div>
                    <div class="party-detail">
                        CIF: <strong>{{ $empresa->cif ?? '—' }}</strong><br>
                        {{ $empresa->domicilio ?? '' }}<br>
                        {{ $empresa->codigo_postal ?? '' }} {{ $empresa->localidad ?? '' }}<br>
                        Tel: {{ $empresa->telefono ?? '—' }}
                    </div>
                </div>
            </td>
            <td class="right">
                <div class="party-box accent">
                    <div class="party-label">Cliente / Contraparte</div>
                    <div class="party-name">{{ $cliente->nombre ?? '—' }} {{ $cliente->apellidos ?? '' }}</div>
                    <div class="party-detail">
                        DNI/NIF: <strong>{{ $cliente->dni ?? '—' }}</strong><br>
                        {{ $cliente->domicilio ?? '' }}<br>
                        {{ $cliente->codigo_postal ?? '' }} {{ $cliente->municipio ?? '' }}<br>
                        Tel: {{ $cliente->telefono ?? '—' }}<br>
                        Email: {{ $cliente->email ?? '—' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Vehículo objeto del contrato</div>
    <table class="info-table">
        <tr>
            <td class="label">Marca · Modelo</td><td>{{ ($vehiculo->marca->nombre ?? '—') }} · {{ $vehiculo->modelo }}</td>
            <td class="label">Versión</td><td>{{ $vehiculo->version }}</td>
        </tr>
        <tr>
            <td class="label">Matrícula</td><td class="value-mono">{{ $vehiculo->matricula ?? 'Sin matricular' }}</td>
            <td class="label">Chasis</td><td class="value-mono">{{ $vehiculo->chasis }}</td>
        </tr>
        <tr>
            <td class="label">Color exterior</td><td>{{ $vehiculo->color_externo }}</td>
            <td class="label">Color interior</td><td>{{ $vehiculo->color_interno }}</td>
        </tr>
    </table>

    <div class="section-title">Condiciones económicas y temporales</div>
    <div class="kv-block">
        <table>
            <tr><td class="k">Tipo de contrato</td><td><span class="status-badge badge-info">{{ $tipoLabel }}</span></td></tr>
            <tr><td class="k">Nº Contrato</td><td class="value-mono" style="font-weight:600;">{{ $datos['numero_contrato'] }}</td></tr>
            <tr><td class="k">Fecha contrato</td><td><strong>{{ \Carbon\Carbon::parse($datos['fecha_contrato'])->format('d/m/Y') }}</strong></td></tr>
            @if(!empty($datos['importe']))
            <tr><td class="k">Importe</td><td><strong style="font-size:13px;color:#33AADD;font-family:monospace;">{{ number_format((float) $datos['importe'], 2, ',', '.') }} €</strong></td></tr>
            @endif
            @if(!empty($datos['duracion_meses']))
            <tr><td class="k">Duración</td><td><strong>{{ $datos['duracion_meses'] }} meses</strong></td></tr>
            @endif
            @if(!empty($datos['fecha_vencimiento']))
            <tr><td class="k">Vencimiento</td><td>{{ \Carbon\Carbon::parse($datos['fecha_vencimiento'])->format('d/m/Y') }}</td></tr>
            @endif
        </table>
    </div>

    <div class="section-title">Cláusulas</div>
    <div style="font-size:10px;color:#444;line-height:1.6;text-align:justify;">
        <p style="margin-bottom:6px;"><strong style="color:#33AADD;">PRIMERA.</strong> Las partes identificadas formalizan el contrato de {{ strtolower($tipoLabel) }} sobre el vehículo descrito, libre de cargas y gravámenes conocidos, en el estado físico y técnico actual aceptado por la contraparte.</p>
        <p style="margin-bottom:6px;"><strong style="color:#33AADD;">SEGUNDA.</strong> Las obligaciones económicas y temporales derivadas del presente contrato son las recogidas en el apartado "Condiciones económicas y temporales". Cualquier modificación deberá ser pactada por escrito y firmada por ambas partes.</p>
        <p style="margin-bottom:6px;"><strong style="color:#33AADD;">TERCERA.</strong> La entrega y/o disposición del vehículo queda sujeta al cumplimiento íntegro de las obligaciones acordadas. Los gastos derivados de la formalización se liquidarán conforme a la normativa vigente.</p>
        <p style="margin-bottom:6px;"><strong style="color:#33AADD;">CUARTA.</strong> Para cualquier controversia derivada del presente contrato, las partes se someten a los Juzgados y Tribunales del domicilio de la empresa, con renuncia expresa a cualquier otro fuero.</p>
        @if(!empty($datos['clausulas_adicionales']))
        <p style="margin-bottom:6px;"><strong style="color:#33AADD;">QUINTA.</strong> Cláusulas adicionales: {{ $datos['clausulas_adicionales'] }}</p>
        @endif
    </div>

    @if(!empty($datos['observaciones']))
    <div class="section-title">Observaciones</div>
    <div class="kv-block" style="font-size:10.5px;line-height:1.5;">{{ $datos['observaciones'] }}</div>
    @endif

    <table class="signatures">
        <tr>
            <td><div class="signature-line"></div><div class="signature-label">Firma y sello de la empresa</div></td>
            <td><div class="signature-line"></div><div class="signature-label">Firma del cliente/contraparte</div></td>
        </tr>
    </table>
@endsection
