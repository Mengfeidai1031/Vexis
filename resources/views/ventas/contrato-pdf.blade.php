<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato Compraventa {{ $venta->codigo_venta }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; padding: 40px; }
        .header { width: 100%; margin-bottom: 20px; }
        .header td { vertical-align: top; border: none; padding: 0; }
        .logo-cell { width: 50%; }
        .info-cell { width: 50%; text-align: right; }
        .vexis-logo { height: 30px; }
        .contract-title { font-size: 24px; font-weight: 800; color: #33AADD; letter-spacing: 1px; }
        .contract-code { font-size: 13px; color: #666; font-family: monospace; margin-top: 4px; }
        .divider { height: 3px; background: linear-gradient(to right, #33AADD, #33AADD80, transparent); margin: 16px 0 20px; }
        .parties { width: 100%; margin-bottom: 20px; }
        .parties td { vertical-align: top; border: none; padding: 0; width: 50%; }
        .party-box { padding: 12px; border-radius: 6px; margin-right: 8px; }
        .party-box.cliente { margin-right: 0; margin-left: 8px; }
        .party-box.vendedor { background: #f0f9ff; border-left: 3px solid #33AADD; }
        .party-box.cliente { background: #f9f9f9; border-left: 3px solid #999; }
        .party-label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #999; font-weight: 700; margin-bottom: 6px; }
        .party-name { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
        .party-detail { font-size: 10px; color: #666; line-height: 1.55; }
        .section-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #33AADD; font-weight: 700; margin: 18px 0 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-table td { padding: 6px 10px; font-size: 10.5px; border-bottom: 1px solid #eee; vertical-align: top; }
        .info-table td.label { width: 35%; font-weight: 600; color: #555; background: #fafafa; }
        .totals { width: 300px; margin-left: auto; margin-top: 12px; border-collapse: collapse; }
        .totals td { padding: 6px 12px; border: none; }
        .totals .label { font-weight: 600; color: #555; }
        .totals .value { text-align: right; font-family: monospace; font-size: 12px; }
        .totals .total-row td { border-top: 2px solid #33AADD; padding-top: 10px; }
        .totals .total-label { font-size: 13px; font-weight: 800; color: #333; }
        .totals .total-value { font-size: 16px; font-weight: 800; color: #33AADD; text-align: right; font-family: monospace; }
        .clauses { font-size: 10px; color: #444; line-height: 1.55; text-align: justify; margin-top: 12px; }
        .clauses p { margin-bottom: 6px; }
        .clause-num { font-weight: 700; color: #33AADD; }
        .signatures { width: 100%; margin-top: 40px; }
        .signatures td { width: 50%; vertical-align: top; padding: 0 10px; text-align: center; }
        .signature-line { border-top: 1px solid #333; margin: 36px 40px 6px; }
        .signature-label { font-size: 10px; color: #666; }
        .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('img/vexis-logo.png') }}" class="vexis-logo" alt="VEXIS">
            </td>
            <td class="info-cell">
                <div class="contract-title">CONTRATO DE COMPRAVENTA</div>
                <div class="contract-code">{{ $venta->codigo_venta }}</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="parties">
        <tr>
            <td>
                <div class="party-box vendedor">
                    <div class="party-label">Parte Vendedora</div>
                    <div class="party-name">{{ $venta->empresa->nombre ?? '—' }}</div>
                    <div class="party-detail">
                        CIF: {{ $venta->empresa->cif ?? '—' }}<br>
                        {{ $venta->empresa->domicilio ?? '' }}<br>
                        {{ $venta->empresa->codigo_postal ?? '' }} {{ $venta->empresa->localidad ?? '' }}<br>
                        Tel: {{ $venta->empresa->telefono ?? '—' }}
                        @if($venta->vendedor)
                            <br><br>Vendedor: <strong>{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</strong>
                        @endif
                    </div>
                </div>
            </td>
            <td>
                <div class="party-box cliente">
                    <div class="party-label">Parte Compradora</div>
                    @if($venta->cliente)
                    <div class="party-name">{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</div>
                    <div class="party-detail">
                        DNI/NIF: {{ $venta->cliente->dni ?? '—' }}<br>
                        {{ $venta->cliente->domicilio ?? '' }}<br>
                        {{ $venta->cliente->codigo_postal ?? '' }} {{ $venta->cliente->municipio ?? '' }}<br>
                        Tel: {{ $venta->cliente->telefono ?? '—' }}<br>
                        Email: {{ $venta->cliente->email ?? '—' }}
                    </div>
                    @else
                    <div class="party-detail">Sin cliente asociado</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Vehículo Objeto del Contrato</div>
    <table class="info-table">
        <tr><td class="label">Marca</td><td>{{ $venta->marca->nombre ?? '—' }}</td><td class="label">Modelo</td><td>{{ $venta->vehiculo->modelo ?? '—' }}</td></tr>
        <tr><td class="label">Versión</td><td>{{ $venta->vehiculo->version ?? '—' }}</td><td class="label">Matrícula</td><td>{{ $venta->vehiculo->matricula ?? 'Sin matricular' }}</td></tr>
        <tr><td class="label">Chasis (VIN)</td><td colspan="3" style="font-family:monospace;">{{ $venta->vehiculo->chasis ?? '—' }}</td></tr>
        <tr><td class="label">Color Externo</td><td>{{ $venta->vehiculo->color_externo ?? '—' }}</td><td class="label">Color Interno</td><td>{{ $venta->vehiculo->color_interno ?? '—' }}</td></tr>
    </table>

    <div class="section-title">Condiciones Económicas</div>
    <table class="info-table">
        <tr><td class="label">Forma de pago</td><td>{{ \App\Models\Venta::$formasPago[$venta->forma_pago] ?? $venta->forma_pago }}</td><td class="label">Fecha de venta</td><td>{{ $venta->fecha_venta?->format('d/m/Y') }}</td></tr>
        @if($venta->fecha_entrega)
        <tr><td class="label">Fecha de entrega prevista</td><td colspan="3">{{ $venta->fecha_entrega->format('d/m/Y') }}</td></tr>
        @endif
    </table>

    <table class="totals">
        <tr><td class="label">Precio base</td><td class="value">{{ number_format((float) $venta->precio_venta, 2, ',', '.') }} €</td></tr>
        @if((float) $venta->descuento > 0)
        <tr><td class="label">Descuento</td><td class="value">- {{ number_format((float) $venta->descuento, 2, ',', '.') }} €</td></tr>
        @endif
        @foreach($venta->conceptos as $c)
        <tr><td class="label">{{ $c->tipo === 'extra' ? 'Extra' : 'Descuento' }}: {{ $c->descripcion }}</td><td class="value">{{ $c->tipo === 'descuento' ? '- ' : '' }}{{ number_format((float) $c->importe, 2, ',', '.') }} €</td></tr>
        @endforeach
        <tr><td class="label">Subtotal</td><td class="value">{{ number_format((float) $venta->subtotal, 2, ',', '.') }} €</td></tr>
        <tr><td class="label">{{ $venta->impuesto_nombre }} ({{ rtrim(rtrim((string) $venta->impuesto_porcentaje, '0'), '.') }}%)</td><td class="value">{{ number_format((float) $venta->impuesto_importe, 2, ',', '.') }} €</td></tr>
        <tr class="total-row"><td class="total-label">TOTAL</td><td class="total-value">{{ number_format((float) $venta->total, 2, ',', '.') }} €</td></tr>
    </table>

    <div class="section-title">Cláusulas</div>
    <div class="clauses">
        <p><span class="clause-num">PRIMERA.</span> La parte vendedora transmite a la parte compradora la plena propiedad del vehículo identificado, libre de cargas y gravámenes, en el estado físico y técnico actual, conocido y aceptado por la parte compradora.</p>
        <p><span class="clause-num">SEGUNDA.</span> El precio total del vehículo es el indicado en el apartado de condiciones económicas. Los impuestos aplicables ({{ $venta->impuesto_nombre }}) se liquidarán conforme a la normativa vigente en el territorio fiscal correspondiente.</p>
        <p><span class="clause-num">TERCERA.</span> La entrega material del vehículo queda supeditada al cumplimiento íntegro del pago acordado. La parte vendedora conserva el dominio hasta la efectiva liquidación del precio.</p>
        <p><span class="clause-num">CUARTA.</span> La garantía aplicable será la establecida legalmente y, en su caso, la ofrecida por el fabricante del vehículo. Cualquier ampliación de garantía deberá documentarse por separado.</p>
        <p><span class="clause-num">QUINTA.</span> Para la resolución de cualquier controversia derivada del presente contrato, las partes se someten expresamente a los Juzgados y Tribunales del domicilio de la parte vendedora, con renuncia a cualquier otro fuero que pudiera corresponderles.</p>
        @if($venta->observaciones)
        <p><span class="clause-num">SEXTA.</span> Observaciones adicionales: {{ $venta->observaciones }}</p>
        @endif
    </div>

    <table class="signatures">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div class="signature-label">Firma parte vendedora</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div class="signature-label">Firma parte compradora</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} · VEXIS — Grupo DAI
    </div>
</body>
</html>
