<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tasaciones - VEXIS</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        h1 { font-size: 18px; color: #33AADD; margin-bottom: 4px; }
        .subtitle { font-size: 11px; color: #888; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #33AADD; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <table style="width:100%;border:none;margin-bottom:12px;border-bottom:2px solid #33AADD;padding-bottom:8px;">
        <tr><td style="border:none;padding:0;"><img src="{{ public_path('img/vexis-logo.png') }}" style="height:36px;"></td><td style="border:none;padding:0;text-align:right;vertical-align:bottom;"><span style="font-size:10px;color:#888;">Generado: {{ date('d/m/Y H:i') }} — VEXIS Grupo DAI</span></td></tr>
    </table>
    <h1 style="margin-top:0;">Listado de Tasaciones</h1>
    <table>
        <thead><tr><th>Código</th><th>Vehículo</th><th>Matrícula</th><th>Km</th><th>V. Estimado</th><th>V. Final</th><th>Estado</th><th>Cliente</th><th>Fecha</th></tr></thead>
        <tbody>
            @foreach($tasaciones as $t)
            <tr>
                <td style="font-family:monospace;">{{ $t->codigo_tasacion }}</td>
                <td>{{ $t->vehiculo_marca }} {{ $t->vehiculo_modelo }} ({{ $t->vehiculo_anio }})</td>
                <td>{{ $t->matricula ?? '—' }}</td>
                <td style="text-align:right;">{{ number_format($t->kilometraje) }}</td>
                <td style="text-align:right;">{{ $t->valor_estimado ? number_format($t->valor_estimado, 2) . '€' : '—' }}</td>
                <td style="text-align:right;">{{ $t->valor_final ? number_format($t->valor_final, 2) . '€' : '—' }}</td>
                <td>{{ \App\Models\Tasacion::$estados[$t->estado] ?? $t->estado }}</td>
                <td>{{ $t->cliente ? $t->cliente->nombre . ' ' . $t->cliente->apellidos : '—' }}</td>
                <td>{{ $t->fecha_tasacion?->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
