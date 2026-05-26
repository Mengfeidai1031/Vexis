<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tasación {{ $tasacion->codigo_tasacion }}</title>
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
        .section-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #33AADD; font-weight: 700; margin: 18px 0 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-table td { padding: 6px 10px; font-size: 10.5px; border-bottom: 1px solid #eee; vertical-align: top; }
        .info-table td.label { width: 35%; font-weight: 600; color: #555; background: #fafafa; }
        .valor-box { margin: 20px auto; padding: 20px; background: #f0f9ff; border: 2px solid #33AADD; border-radius: 8px; text-align: center; width: 60%; }
        .valor-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #33AADD; font-weight: 700; margin-bottom: 8px; }
        .valor-amount { font-size: 28px; font-weight: 800; color: #33AADD; font-family: monospace; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .status-pendiente { background: #fff3e0; color: #e65100; }
        .status-valorada { background: #e3f2fd; color: #1565c0; }
        .status-aceptada { background: #e8f5e9; color: #2e7d32; }
        .status-rechazada { background: #fce4ec; color: #c62828; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 9px; color: #999; }
        .notes { margin-top: 12px; padding: 10px; background: #fafafa; border-left: 3px solid #33AADD; font-size: 10px; color: #555; line-height: 1.5; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="logo-cell"><img src="{{ public_path('img/vexis-logo.png') }}" class="vexis-logo" alt="VEXIS"></td>
            <td class="info-cell">
                <div class="title">TASACIÓN</div>
                <div class="code">{{ $tasacion->codigo_tasacion }}</div>
                <div style="margin-top:8px;">
                    <span class="status-badge status-{{ $tasacion->estado }}">{{ ucfirst($tasacion->estado) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="section-title">Datos del cliente</div>
    <table class="info-table">
        @if($tasacion->cliente)
        <tr><td class="label">Cliente</td><td>{{ $tasacion->cliente->nombre }} {{ $tasacion->cliente->apellidos }}</td><td class="label">DNI/NIF</td><td>{{ $tasacion->cliente->dni }}</td></tr>
        <tr><td class="label">Teléfono</td><td>{{ $tasacion->cliente->telefono }}</td><td class="label">Email</td><td>{{ $tasacion->cliente->email }}</td></tr>
        @else
        <tr><td colspan="4" style="color:#999;">Sin cliente asociado</td></tr>
        @endif
        <tr><td class="label">Empresa</td><td>{{ $tasacion->empresa->nombre ?? '—' }}</td><td class="label">Fecha</td><td>{{ $tasacion->fecha_tasacion?->format('d/m/Y') }}</td></tr>
        @if($tasacion->tasador)
        <tr><td class="label">Tasador</td><td colspan="3">{{ $tasacion->tasador->nombre }} {{ $tasacion->tasador->apellidos }}</td></tr>
        @endif
    </table>

    <div class="section-title">Vehículo tasado</div>
    <table class="info-table">
        <tr><td class="label">Marca</td><td>{{ $tasacion->vehiculo_marca }}</td><td class="label">Modelo</td><td>{{ $tasacion->vehiculo_modelo }}</td></tr>
        <tr><td class="label">Año</td><td>{{ $tasacion->vehiculo_anio }}</td><td class="label">Kilometraje</td><td>{{ number_format($tasacion->kilometraje, 0, ',', '.') }} km</td></tr>
        <tr><td class="label">Matrícula</td><td>{{ $tasacion->matricula ?? '—' }}</td><td class="label">Combustible</td><td>{{ $tasacion->combustible ?? '—' }}</td></tr>
        <tr><td class="label">Estado del vehículo</td><td colspan="3">{{ ucfirst($tasacion->estado_vehiculo) }}</td></tr>
    </table>

    <div class="valor-box">
        <div class="valor-label">Valor {{ $tasacion->valor_final ? 'Final' : 'Estimado' }}</div>
        <div class="valor-amount">{{ number_format((float) ($tasacion->valor_final ?? $tasacion->valor_estimado ?? 0), 2, ',', '.') }} €</div>
        @if($tasacion->valor_final && $tasacion->valor_estimado && (float) $tasacion->valor_final !== (float) $tasacion->valor_estimado)
            <div style="font-size:10px;color:#666;margin-top:6px;">Valor estimado inicial: {{ number_format((float) $tasacion->valor_estimado, 2, ',', '.') }} €</div>
        @endif
    </div>

    @if($tasacion->observaciones)
    <div class="section-title">Observaciones</div>
    <div class="notes">{{ $tasacion->observaciones }}</div>
    @endif

    <div class="footer">Documento generado el {{ now()->format('d/m/Y H:i') }} · VEXIS — Grupo DAI</div>
</body>
</html>
