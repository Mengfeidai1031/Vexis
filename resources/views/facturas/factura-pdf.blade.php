<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $factura->codigo_factura }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; padding: 40px; }
        .header { width: 100%; margin-bottom: 30px; }
        .header td { vertical-align: top; border: none; padding: 0; }
        .logo-cell { width: 50%; }
        .info-cell { width: 50%; text-align: right; }
        .brand-logo { height: 50px; margin-bottom: 8px; }
        .vexis-logo { height: 30px; }
        .invoice-title { font-size: 28px; font-weight: 800; color: #33AADD; letter-spacing: 1px; }
        .invoice-code { font-size: 14px; color: #666; font-family: monospace; margin-top: 4px; }
        .divider { height: 3px; background: linear-gradient(to right, #33AADD, #33AADD80, transparent); margin: 20px 0; }
        .parties { width: 100%; margin-bottom: 24px; }
        .parties td { vertical-align: top; border: none; padding: 0; width: 50%; }
        .party-box { padding: 16px; border-radius: 6px; }
        .party-box.emisor { background: #f0f9ff; border-left: 3px solid #33AADD; }
        .party-box.cliente { background: #f9f9f9; border-left: 3px solid #999; }
        .party-label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #999; font-weight: 700; margin-bottom: 6px; }
        .party-name { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .party-detail { font-size: 10px; color: #666; line-height: 1.6; }
        .detail-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .detail-table th { background: #33AADD; color: white; padding: 8px 12px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .detail-table td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 11px; }
        .detail-table tr:nth-child(even) { background: #fafafa; }
        .totals { width: 300px; margin-left: auto; margin-top: 16px; }
        .totals td { padding: 6px 12px; border: none; }
        .totals .label { font-weight: 600; color: #555; }
        .totals .value { text-align: right; font-family: monospace; font-size: 12px; }
        .totals .total-row td { border-top: 2px solid #33AADD; padding-top: 10px; }
        .totals .total-label { font-size: 14px; font-weight: 800; color: #333; }
        .totals .total-value { font-size: 18px; font-weight: 800; color: #33AADD; text-align: right; font-family: monospace; }
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { border: none; padding: 4px 16px 4px 0; font-size: 10px; }
        .meta-label { color: #999; font-weight: 600; text-transform: uppercase; font-size: 9px; }
        .meta-value { font-weight: 600; }
        .footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid #ddd; text-align: center; font-size: 9px; color: #999; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .status-emitida { background: #e3f2fd; color: #1565c0; }
        .status-pagada { background: #e8f5e9; color: #2e7d32; }
        .status-vencida { background: #fff3e0; color: #e65100; }
        .status-anulada { background: #fce4ec; color: #c62828; }
        .verifactu-box { margin-top: 24px; padding: 12px 16px; border: 1px solid #33AADD; border-radius: 6px; background: #f0f9ff; }
        .verifactu-box td { border: none; vertical-align: top; padding: 0; }
        .verifactu-label { font-size: 9px; text-transform: uppercase; color: #999; font-weight: 700; }
        .verifactu-value { font-size: 10px; font-family: monospace; color: #333; word-break: break-all; }
    </style>
</head>
<body>
    {{-- Header --}}
    <table class="header">
        <tr>
            <td class="logo-cell">
                @if($logoMarca)
                <img src="{{ $logoMarca }}" class="brand-logo" alt="Logo marca"><br>
                @endif
                <img src="{{ public_path('img/vexis-logo.png') }}" class="vexis-logo" alt="VEXIS">
            </td>
            <td class="info-cell">
                <div class="invoice-title">FACTURA</div>
                <div class="invoice-code">{{ $factura->codigo_factura }}</div>
                <div style="margin-top:8px;">
                    <span class="status-badge status-{{ $factura->estado }}">{{ \App\Models\Factura::$estados[$factura->estado] ?? $factura->estado }}</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- Dates & Meta --}}
    <table class="meta-table">
        <tr>
            <td><span class="meta-label">Fecha emisión</span><br><span class="meta-value">{{ $factura->fecha_factura->format('d/m/Y') }}</span></td>
            <td><span class="meta-label">Fecha vencimiento</span><br><span class="meta-value">{{ $factura->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</span></td>
            <td><span class="meta-label">Venta asociada</span><br><span class="meta-value">{{ $factura->venta?->codigo_venta ?? '—' }}</span></td>
            <td><span class="meta-label">Emitida por</span><br><span class="meta-value">{{ $factura->emisor?->name ?? '—' }}</span></td>
        </tr>
        <tr>
            <td><span class="meta-label">Tipo factura</span><br><span class="meta-value">{{ $factura->tipo_factura ?? 'F1' }}</span></td>
            <td><span class="meta-label">Clave régimen IVA</span><br><span class="meta-value">{{ $factura->clave_regimen_iva ?? '01' }}</span></td>
            <td colspan="2"><span class="meta-label">Factura simplificada</span><br><span class="meta-value">{{ $factura->factura_simplificada ? 'Sí' : 'No' }}</span></td>
        </tr>
    </table>

    {{-- Parties --}}
    <table class="parties">
        <tr>
            <td style="padding-right:10px;">
                <div class="party-box emisor">
                    <div class="party-label">Empresa emisora</div>
                    <div class="party-name">{{ $factura->empresa?->nombre ?? 'VEXIS' }}</div>
                    <div class="party-detail">
                        {{ $factura->centro?->nombre ?? '' }}<br>
                        CIF: {{ $factura->empresa?->cif ?? '—' }}
                    </div>
                </div>
            </td>
            <td style="padding-left:10px;">
                <div class="party-box cliente">
                    <div class="party-label">Cliente</div>
                    @if($factura->cliente)
                    <div class="party-name">{{ $factura->cliente->nombre }} {{ $factura->cliente->apellidos }}</div>
                    <div class="party-detail">
                        {{ $factura->cliente->dni ?? '' }}<br>
                        {{ $factura->cliente->email ?? '' }}<br>
                        {{ $factura->cliente->telefono ?? '' }}
                    </div>
                    @else
                    <div class="party-name">—</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Detail --}}
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width:60%;">Concepto</th>
                <th style="width:20%;text-align:center;">Tipo</th>
                <th style="width:20%;text-align:right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $factura->venta?->vehiculo ? 'Vehículo ' . $factura->venta->vehiculo->modelo . ($factura->venta->vehiculo->version ? ' — ' . $factura->venta->vehiculo->version : '') : ($factura->concepto ?? 'Venta de vehículo') }}</td>
                <td style="text-align:center;">Precio base</td>
                <td style="text-align:right;font-family:monospace;">{{ number_format($factura->venta?->precio_venta ?? $factura->subtotal, 2, ',', '.') }} €</td>
            </tr>
            @if($factura->venta && $factura->venta->descuento > 0)
            <tr>
                <td style="color:#c62828;">Descuento general</td>
                <td style="text-align:center;color:#c62828;">Descuento</td>
                <td style="text-align:right;font-family:monospace;color:#c62828;">-{{ number_format($factura->venta->descuento, 2, ',', '.') }} €</td>
            </tr>
            @endif
            @if($factura->venta && $factura->venta->conceptos)
                @foreach($factura->venta->conceptos->where('tipo', 'extra') as $extra)
                <tr>
                    <td style="color:#2e7d32;">{{ $extra->descripcion }}</td>
                    <td style="text-align:center;color:#2e7d32;">Extra</td>
                    <td style="text-align:right;font-family:monospace;color:#2e7d32;">+{{ number_format($extra->importe, 2, ',', '.') }} €</td>
                </tr>
                @endforeach
                @foreach($factura->venta->conceptos->where('tipo', 'descuento') as $desc)
                <tr>
                    <td style="color:#c62828;">{{ $desc->descripcion }}</td>
                    <td style="text-align:center;color:#c62828;">Descuento</td>
                    <td style="text-align:right;font-family:monospace;color:#c62828;">-{{ number_format($desc->importe, 2, ',', '.') }} €</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">{{ number_format($factura->subtotal, 2, ',', '.') }} €</td>
        </tr>
        <tr>
            <td class="label">{{ $factura->venta?->impuesto_nombre ?? 'IVA' }} ({{ number_format($factura->iva_porcentaje, 0) }}%)</td>
            <td class="value">{{ number_format($factura->iva_importe, 2, ',', '.') }} €</td>
        </tr>
        <tr class="total-row">
            <td class="total-label">TOTAL</td>
            <td class="total-value">{{ number_format($factura->total, 2, ',', '.') }} €</td>
        </tr>
    </table>

    @if($factura->observaciones)
    <div style="margin-top:24px;padding:12px;background:#f9f9f9;border-radius:6px;">
        <div style="font-size:9px;text-transform:uppercase;color:#999;font-weight:700;margin-bottom:4px;">Observaciones</div>
        <div style="font-size:10px;color:#555;">{{ $factura->observaciones }}</div>
    </div>
    @endif

    {{-- Verifactu QR Code --}}
    @if(isset($qrBase64) && $qrBase64)
    <div class="verifactu-box">
        <table style="width:100%;">
            <tr>
                <td style="width:100px;padding-right:12px;">
                    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Verifactu" style="width:90px;height:90px;">
                </td>
                <td>
                    <div style="font-size:11px;font-weight:700;color:#33AADD;margin-bottom:6px;">VERI*FACTU — RD 1007/2023</div>
                    <div class="verifactu-label">Código registro</div>
                    <div class="verifactu-value" style="margin-bottom:4px;">{{ $verifactuRegistro->codigo_registro ?? '—' }}</div>
                    <div class="verifactu-label">Huella SHA-256</div>
                    <div class="verifactu-value" style="font-size:8px;">{{ $verifactuRegistro->hash_registro ?? '—' }}</div>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        VEXIS — Sistema de Gestión &bull; Factura generada el {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
