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
        .meta-row { display: flex; gap: 30px; margin-bottom: 20px; }
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
    </table>

    {{-- Parties --}}
    <table class="parties">
        <tr>
            <td style="padding-right:10px;">
                <div class="party-box emisor">
                    <div class="party-label">Empresa emisora</div>
                    <div class="party-name">{{ $factura->empresa?->nombre ?? 'VEXIS Grupo ARI' }}</div>
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
                <th style="width:20%;text-align:center;">Vehículo</th>
                <th style="width:20%;text-align:right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $factura->concepto ?? 'Venta de vehículo' }}</td>
                <td style="text-align:center;">{{ $factura->venta?->vehiculo?->modelo ?? '—' }}</td>
                <td style="text-align:right;font-family:monospace;">{{ number_format($factura->subtotal, 2) }} €</td>
            </tr>
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">{{ number_format($factura->subtotal, 2) }} €</td>
        </tr>
        <tr>
            <td class="label">IVA ({{ number_format($factura->iva_porcentaje, 0) }}%)</td>
            <td class="value">{{ number_format($factura->iva_importe, 2) }} €</td>
        </tr>
        <tr class="total-row">
            <td class="total-label">TOTAL</td>
            <td class="total-value">{{ number_format($factura->total, 2) }} €</td>
        </tr>
    </table>

    @if($factura->observaciones)
    <div style="margin-top:24px;padding:12px;background:#f9f9f9;border-radius:6px;">
        <div style="font-size:9px;text-transform:uppercase;color:#999;font-weight:700;margin-bottom:4px;">Observaciones</div>
        <div style="font-size:10px;color:#555;">{{ $factura->observaciones }}</div>
    </div>
    @endif

    <div class="footer">
        VEXIS — Sistema de Gestión de Grupo ARI &bull; Factura generada el {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
