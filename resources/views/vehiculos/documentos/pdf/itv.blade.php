@php $codigoDocumento = $datos['numero_informe']; @endphp
@extends('vehiculos.documentos.pdf._layout')

@section('content')
    @php
        $resultadoColor = match($datos['resultado']) {
            'favorable' => 'callout-green',
            'favorable_con_defectos_leves' => 'callout-amber',
            'desfavorable', 'negativa' => 'callout-red',
            default => 'callout-blue',
        };
        $resultadoLabel = [
            'favorable' => 'FAVORABLE',
            'favorable_con_defectos_leves' => 'FAVORABLE con defectos leves',
            'desfavorable' => 'DESFAVORABLE',
            'negativa' => 'NEGATIVA',
        ][$datos['resultado']] ?? $datos['resultado'];
        $badgeClass = match($datos['resultado']) {
            'favorable' => 'badge-ok',
            'favorable_con_defectos_leves' => 'badge-warn',
            default => 'badge-danger',
        };
    @endphp

    <div class="callout {{ $resultadoColor }}">
        <div class="callout-title">Resultado de la inspección</div>
        <div style="font-size:14px;font-weight:800;letter-spacing:0.5px;">{{ $resultadoLabel }}</div>
    </div>

    <div class="section-title">Identificación del vehículo</div>
    <table class="info-table">
        <tr>
            <td class="label">Matrícula</td><td class="value-mono">{{ $vehiculo->matricula ?? 'Sin matricular' }}</td>
            <td class="label">Chasis (VIN)</td><td class="value-mono">{{ $vehiculo->chasis }}</td>
        </tr>
        <tr>
            <td class="label">Marca · Modelo</td><td>{{ ($vehiculo->marca->nombre ?? '—') }} · {{ $vehiculo->modelo }}</td>
            <td class="label">Versión</td><td>{{ $vehiculo->version }}</td>
        </tr>
    </table>

    <div class="section-title">Detalles de la inspección</div>
    <table class="info-table">
        <tr>
            <td class="label">Nº Informe</td><td class="value-mono">{{ $datos['numero_informe'] }}</td>
            <td class="label">Resultado</td><td><span class="status-badge {{ $badgeClass }}">{{ str_replace('_', ' ', $datos['resultado']) }}</span></td>
        </tr>
        <tr>
            <td class="label">Fecha inspección</td><td>{{ \Carbon\Carbon::parse($datos['fecha_inspeccion'])->format('d/m/Y') }}</td>
            <td class="label">Próxima revisión</td><td><strong style="color:#33AADD;">{{ \Carbon\Carbon::parse($datos['proxima_revision'])->format('d/m/Y') }}</strong></td>
        </tr>
        <tr>
            <td class="label">Estación ITV</td><td colspan="3">{{ $datos['estacion_itv'] }}</td>
        </tr>
        <tr>
            <td class="label">Kilometraje</td><td colspan="3">{{ number_format((int) $datos['kilometraje'], 0, ',', '.') }} km</td>
        </tr>
    </table>

    @if(!empty($datos['defectos']))
    <div class="section-title">Defectos detectados</div>
    <div class="kv-block" style="font-size:10.5px;line-height:1.5;white-space:pre-wrap;">{{ $datos['defectos'] }}</div>
    @endif

    @if(!empty($datos['observaciones']))
    <div class="section-title">Observaciones</div>
    <div class="kv-block" style="font-size:10.5px;line-height:1.5;">{{ $datos['observaciones'] }}</div>
    @endif

    <table class="signatures">
        <tr>
            <td><div class="signature-line"></div><div class="signature-label">Técnico responsable</div></td>
            <td><div class="signature-line"></div><div class="signature-label">Sello estación ITV</div></td>
        </tr>
    </table>

    <div class="callout callout-amber" style="margin-top:20px;">
        <div class="callout-title">Aviso legal</div>
        Documento informativo interno. El certificado ITV oficial es el emitido por la estación autorizada. Conserve la tarjeta amarilla/roja adhesiva original como prueba válida frente a la DGT.
    </div>
@endsection
