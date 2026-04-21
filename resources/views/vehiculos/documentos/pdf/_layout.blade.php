<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }} — {{ $vehiculo->matricula ?? $vehiculo->chasis }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #2c3e50; padding: 40px; }
        .header { width: 100%; margin-bottom: 18px; }
        .header td { vertical-align: top; border: none; padding: 0; }
        .logo-cell { width: 50%; }
        .info-cell { width: 50%; text-align: right; }
        .vexis-logo { height: 30px; }
        .doc-title { font-size: 22px; font-weight: 800; color: #33AADD; letter-spacing: 1.2px; text-transform: uppercase; }
        .doc-subtitle { font-size: 11px; color: #6B7580; font-weight: 500; margin-top: 2px; letter-spacing: 0.5px; }
        .doc-code { font-size: 13px; color: #2c3e50; font-family: monospace; margin-top: 6px; background: #f0f9ff; padding: 4px 10px; border-radius: 4px; display: inline-block; border: 1px solid #33AADD; }
        .divider { height: 3px; background: linear-gradient(to right, #33AADD, #33AADD80, transparent); margin: 14px 0 20px; }

        .section-title { font-size: 11px; text-transform: uppercase; letter-spacing: 1.2px; color: #33AADD; font-weight: 700; margin: 18px 0 6px; border-bottom: 2px solid #33AADD; padding-bottom: 4px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-table td { padding: 7px 10px; font-size: 10.5px; border-bottom: 1px solid #eaecef; vertical-align: top; }
        .info-table td.label { width: 28%; font-weight: 600; color: #495057; background: #f8f9fa; text-transform: uppercase; font-size: 9.5px; letter-spacing: 0.3px; }
        .info-table td.value-mono { font-family: monospace; color: #2c3e50; font-weight: 600; }

        .party-box { padding: 12px 14px; border-radius: 6px; font-size: 10.5px; line-height: 1.55; }
        .party-box.primary { background: #f0f9ff; border-left: 3px solid #33AADD; }
        .party-box.accent { background: #f9f9f9; border-left: 3px solid #9BA4AE; }
        .party-label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #6B7580; font-weight: 700; margin-bottom: 4px; }
        .party-name { font-size: 12.5px; font-weight: 700; color: #2c3e50; margin-bottom: 3px; }
        .party-detail { color: #6c757d; }

        .pair-row { width: 100%; margin-bottom: 16px; }
        .pair-row td { vertical-align: top; border: none; padding: 0; width: 50%; }
        .pair-row td.left { padding-right: 6px; }
        .pair-row td.right { padding-left: 6px; }

        .callout { margin: 16px 0; padding: 12px 14px; border-radius: 6px; font-size: 10.5px; line-height: 1.55; }
        .callout-green { background: #e8f5e9; border-left: 3px solid #2ecc71; color: #1a3a1a; }
        .callout-amber { background: #fff8e1; border-left: 3px solid #f39c12; color: #6b4a00; }
        .callout-red { background: #fdecea; border-left: 3px solid #e74c3c; color: #7a1d14; }
        .callout-blue { background: #e3f2fd; border-left: 3px solid #3498db; color: #11456b; }
        .callout .callout-title { font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 4px; }

        .kv-block { margin: 14px 0; padding: 14px; background: #f8f9fa; border-radius: 6px; }
        .kv-block table { width: 100%; border-collapse: collapse; }
        .kv-block td { padding: 4px 6px; font-size: 10.5px; }
        .kv-block td.k { font-weight: 600; color: #6B7580; width: 38%; text-transform: uppercase; font-size: 9.5px; letter-spacing: 0.3px; }

        .signatures { width: 100%; margin-top: 36px; }
        .signatures td { width: 50%; vertical-align: top; padding: 0 10px; text-align: center; border: none; }
        .signature-line { border-top: 1px solid #333; margin: 30px 30px 4px; }
        .signature-label { font-size: 10px; color: #6B7580; font-weight: 600; }

        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 10px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-ok { background: #d4edda; color: #155724; }
        .badge-warn { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }

        .footer { margin-top: 28px; padding-top: 12px; border-top: 2px solid #eaecef; text-align: center; font-size: 9px; color: #9BA4AE; }
        .footer .footer-meta { font-family: monospace; margin-top: 2px; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('img/vexis-logo.png') }}" class="vexis-logo" alt="VEXIS">
                <div style="font-size:9.5px;color:#6B7580;margin-top:4px;letter-spacing:0.4px;">VEXIS — Sistema de Gestión · Grupo DAI</div>
            </td>
            <td class="info-cell">
                <div class="doc-title">{{ $titulo }}</div>
                <div class="doc-subtitle">{{ $vehiculo->matricula ?? 'SIN MATRICULAR' }} · {{ $vehiculo->descripcion_completa }}</div>
                @isset($codigoDocumento)
                <div class="doc-code">{{ $codigoDocumento }}</div>
                @endisset
            </td>
        </tr>
    </table>
    <div class="divider"></div>

    @yield('content')

    <div class="footer">
        Documento generado automáticamente desde VEXIS · {{ now()->format('d/m/Y H:i') }}
        <div class="footer-meta">
            Vehículo #{{ $vehiculo->id }} · VIN {{ $vehiculo->chasis }}
            @isset($emisor) · Emisor: {{ $emisor->nombre }} {{ $emisor->apellidos }}@endisset
        </div>
    </div>
</body>
</html>
