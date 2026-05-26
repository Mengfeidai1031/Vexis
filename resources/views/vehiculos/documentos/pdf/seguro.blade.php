@php $codigoDocumento = $datos['numero_poliza']; @endphp
@extends('vehiculos.documentos.pdf._layout')

@section('content')
    @php
        $coberturaLabel = [
            'terceros' => 'TERCEROS',
            'terceros_ampliado' => 'TERCEROS AMPLIADO',
            'todo_riesgo' => 'TODO RIESGO',
            'todo_riesgo_franquicia' => 'TODO RIESGO CON FRANQUICIA',
        ][$datos['tipo_cobertura']] ?? $datos['tipo_cobertura'];
    @endphp

    <div class="callout callout-blue">
        <div class="callout-title">Certificado de Seguro</div>
        Acreditación de la cobertura aseguradora contratada para el vehículo identificado, conforme a la Ley de Responsabilidad Civil y Seguro en la Circulación de Vehículos a Motor.
    </div>

    <table class="pair-row">
        <tr>
            <td class="left">
                <div class="party-box primary">
                    <div class="party-label">Aseguradora</div>
                    <div class="party-name">{{ $datos['aseguradora'] }}</div>
                    <div class="party-detail">Póliza Nº <strong class="value-mono">{{ $datos['numero_poliza'] }}</strong></div>
                </div>
            </td>
            <td class="right">
                <div class="party-box accent">
                    <div class="party-label">Tomador</div>
                    <div class="party-name">{{ $cliente->nombre ?? '—' }} {{ $cliente->apellidos ?? '' }}</div>
                    <div class="party-detail">
                        DNI/NIF: <strong>{{ $cliente->dni ?? '—' }}</strong><br>
                        {{ $cliente->domicilio ?? '' }}<br>
                        {{ $cliente->codigo_postal ?? '' }} {{ $cliente->municipio ?? '' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Vehículo asegurado</div>
    <table class="info-table">
        <tr>
            <td class="label">Matrícula</td><td class="value-mono"><span class="status-badge badge-info" style="font-size:12px;letter-spacing:1.2px;">{{ $vehiculo->matricula ?? 'SIN MATRICULAR' }}</span></td>
            <td class="label">Chasis</td><td class="value-mono">{{ $vehiculo->chasis }}</td>
        </tr>
        <tr>
            <td class="label">Marca · Modelo</td><td>{{ ($vehiculo->marca->nombre ?? '—') }} · {{ $vehiculo->modelo }}</td>
            <td class="label">Versión</td><td>{{ $vehiculo->version }}</td>
        </tr>
    </table>

    <div class="section-title">Cobertura contratada</div>
    <div class="kv-block">
        <table>
            <tr><td class="k">Tipo de cobertura</td><td><span class="status-badge badge-info">{{ $coberturaLabel }}</span></td></tr>
            <tr><td class="k">Periodo de vigencia</td><td><strong>{{ \Carbon\Carbon::parse($datos['fecha_inicio'])->format('d/m/Y') }}</strong> — <strong>{{ \Carbon\Carbon::parse($datos['fecha_fin'])->format('d/m/Y') }}</strong></td></tr>
            <tr><td class="k">Prima anual</td><td><strong style="font-size:13px;color:#33AADD;font-family:monospace;">{{ number_format((float) $datos['prima_anual'], 2, ',', '.') }} €</strong></td></tr>
            @if(!empty($datos['franquicia']))
            <tr><td class="k">Franquicia</td><td class="value-mono">{{ number_format((float) $datos['franquicia'], 2, ',', '.') }} €</td></tr>
            @endif
        </table>
    </div>

    <div class="callout callout-green">
        <div class="callout-title">Coberturas incluidas (resumen)</div>
        <ul style="margin:6px 0 0 16px;padding:0;line-height:1.7;">
            <li>Responsabilidad Civil obligatoria y voluntaria hasta los límites legales.</li>
            <li>Defensa jurídica y reclamación de daños.</li>
            @if($datos['tipo_cobertura'] !== 'terceros')
            <li>Lunas, incendio y robo.</li>
            @endif
            @if(in_array($datos['tipo_cobertura'], ['todo_riesgo', 'todo_riesgo_franquicia']))
            <li>Daños propios por colisión o vuelco.</li>
            <li>Asistencia en viaje 24 h.</li>
            @endif
        </ul>
    </div>

    @if(!empty($datos['observaciones']))
    <div class="section-title">Observaciones</div>
    <div class="kv-block" style="font-size:10.5px;line-height:1.5;">{{ $datos['observaciones'] }}</div>
    @endif

    <table class="signatures">
        <tr>
            <td><div class="signature-line"></div><div class="signature-label">Firma del tomador</div></td>
            <td><div class="signature-line"></div><div class="signature-label">Sello de la aseguradora</div></td>
        </tr>
    </table>

    <div class="callout callout-amber" style="margin-top:16px;">
        <div class="callout-title">Aviso legal</div>
        El documento definitivo de cobertura es la póliza emitida por la compañía aseguradora. Este resumen se genera desde VEXIS con fines de gestión interna y entrega al cliente.
    </div>
@endsection
