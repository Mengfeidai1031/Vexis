<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto Oferta #{{ $oferta->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; padding: 40px; }
        .header { width: 100%; margin-bottom: 20px; }
        .header td { vertical-align: top; border: none; padding: 0; }
        .logo-cell { width: 50%; }
        .info-cell { width: 50%; text-align: right; }
        .vexis-logo { height: 30px; }
        .title { font-size: 24px; font-weight: 800; color: #33AADD; letter-spacing: 1px; }
        .code { font-size: 13px; color: #666; font-family: monospace; margin-top: 4px; }
        .divider { height: 3px; background: linear-gradient(to right, #33AADD, #33AADD80, transparent); margin: 16px 0 20px; }
        .parties { width: 100%; margin-bottom: 20px; }
        .parties td { vertical-align: top; border: none; padding: 0; width: 50%; }
        .party-box { padding: 12px; border-radius: 6px; }
        .party-box.emisor { background: #f0f9ff; border-left: 3px solid #33AADD; margin-right: 8px; }
        .party-box.cliente { background: #f9f9f9; border-left: 3px solid #999; margin-left: 8px; }
        .party-label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #999; font-weight: 700; margin-bottom: 6px; }
        .party-name { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
        .party-detail { font-size: 10px; color: #666; line-height: 1.55; }
        .section-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #33AADD; font-weight: 700; margin: 18px 0 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-table td { padding: 6px 10px; font-size: 10.5px; border-bottom: 1px solid #eee; vertical-align: top; }
        .info-table td.label { width: 35%; font-weight: 600; color: #555; background: #fafafa; }
        .lineas { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .lineas th { background: #33AADD; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .lineas td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 10.5px; }
        .lineas tr:nth-child(even) td { background: #fafafa; }
        .lineas td.r { text-align: right; font-family: monospace; }
        .totals { width: 300px; margin-left: auto; margin-top: 12px; border-collapse: collapse; }
        .totals td { padding: 6px 12px; border: none; }
        .totals .label { font-weight: 600; color: #555; }
        .totals .value { text-align: right; font-family: monospace; font-size: 12px; }
        .totals .total-row td { border-top: 2px solid #33AADD; padding-top: 10px; }
        .totals .total-label { font-size: 13px; font-weight: 800; color: #333; }
        .totals .total-value { font-size: 16px; font-weight: 800; color: #33AADD; text-align: right; font-family: monospace; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 9px; color: #999; }
        .notice { margin-top: 14px; padding: 10px 12px; background: #fff3e0; border-left: 3px solid #f39c12; font-size: 10px; color: #8a5a00; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="logo-cell"><img src="{{ public_path('img/vexis-logo.png') }}" class="vexis-logo" alt="VEXIS"></td>
            <td class="info-cell">
                <div class="title">PRESUPUESTO</div>
                <div class="code">OF-{{ str_pad((string) $oferta->id, 5, '0', STR_PAD_LEFT) }}</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="parties">
        <tr>
            <td>
                <div class="party-box emisor">
                    <div class="party-label">Emisor</div>
                    @php $emp = $oferta->cliente->empresa ?? null; @endphp
                    <div class="party-name">{{ $emp->nombre ?? 'VEXIS — Grupo DAI' }}</div>
                    <div class="party-detail">
                        @if($emp)
                            CIF: {{ $emp->cif }}<br>
                            {{ $emp->domicilio }}<br>
                            Tel: {{ $emp->telefono }}
                        @else
                            Grupo DAI
                        @endif
                    </div>
                </div>
            </td>
            <td>
                <div class="party-box cliente">
                    <div class="party-label">Cliente</div>
                    @if($oferta->cliente)
                    <div class="party-name">{{ $oferta->cliente->nombre }} {{ $oferta->cliente->apellidos }}</div>
                    <div class="party-detail">
                        DNI/NIF: {{ $oferta->cliente->dni ?? '—' }}<br>
                        {{ $oferta->cliente->domicilio ?? '' }}<br>
                        Tel: {{ $oferta->cliente->telefono ?? '—' }}
                    </div>
                    @else
                    <div class="party-detail">{{ $oferta->cliente_nombre_pdf ?? 'Sin cliente asociado' }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Vehículo</div>
    <table class="info-table">
        @if($oferta->vehiculo)
        <tr><td class="label">Marca</td><td>{{ $oferta->vehiculo->marca->nombre ?? '—' }}</td><td class="label">Modelo</td><td>{{ $oferta->vehiculo->modelo }}</td></tr>
        <tr><td class="label">Versión</td><td>{{ $oferta->vehiculo->version }}</td><td class="label">Chasis</td><td style="font-family:monospace;">{{ $oferta->vehiculo->chasis }}</td></tr>
        <tr><td class="label">Color Ext.</td><td>{{ $oferta->vehiculo->color_externo }}</td><td class="label">Color Int.</td><td>{{ $oferta->vehiculo->color_interno }}</td></tr>
        @else
        <tr><td colspan="4" style="color:#999;">{{ $oferta->vehiculo_modelo_pdf ?? 'Sin vehículo asociado' }}</td></tr>
        @endif
        <tr><td class="label">Fecha</td><td colspan="3">{{ $oferta->fecha?->format('d/m/Y') }}</td></tr>
    </table>

    @if($oferta->lineas->count())
    <div class="section-title">Detalle</div>
    <table class="lineas">
        <thead>
            <tr><th>Tipo</th><th>Descripción</th><th style="text-align:right;">Importe</th></tr>
        </thead>
        <tbody>
            @foreach($oferta->lineas as $l)
            <tr>
                <td style="text-transform:capitalize;">{{ $l->tipo }}</td>
                <td>{{ $l->descripcion }}</td>
                <td class="r">{{ number_format((float) $l->precio, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <table class="totals">
        <tr><td class="label">Base imponible</td><td class="value">{{ number_format((float) $oferta->base_imponible, 2, ',', '.') }} €</td></tr>
        <tr><td class="label">Impuestos</td><td class="value">{{ number_format((float) $oferta->impuestos, 2, ',', '.') }} €</td></tr>
        <tr class="total-row"><td class="total-label">TOTAL</td><td class="total-value">{{ number_format((float) $oferta->total_con_impuestos, 2, ',', '.') }} €</td></tr>
    </table>

    <div class="notice">
        Presupuesto sin valor contractual. Los importes son estimativos y están sujetos a validación, confirmación de stock y disponibilidad en el momento del pedido.
    </div>

    <div class="footer">Documento generado el {{ now()->format('d/m/Y H:i') }} · VEXIS — Grupo DAI</div>
</body>
</html>
