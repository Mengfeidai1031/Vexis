<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas - VEXIS</title>
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
    <h1>Listado de Ventas</h1>
    <div class="subtitle">Generado: {{ date('d/m/Y H:i') }} — VEXIS Grupo ARI</div>
    <table>
        <thead><tr><th>Código</th><th>Vehículo</th><th>Cliente</th><th>Marca</th><th>Precio</th><th>Pago</th><th>Estado</th><th>Fecha</th></tr></thead>
        <tbody>
            @foreach($ventas as $v)
            <tr>
                <td style="font-family:monospace;">{{ $v->codigo_venta }}</td>
                <td>{{ $v->vehiculo?->modelo ?? '—' }}</td>
                <td>{{ $v->cliente ? $v->cliente->nombre . ' ' . $v->cliente->apellidos : '—' }}</td>
                <td>{{ $v->marca?->nombre ?? '—' }}</td>
                <td style="text-align:right;">{{ number_format($v->precio_final, 2) }}€</td>
                <td>{{ \App\Models\Venta::$formasPago[$v->forma_pago] ?? $v->forma_pago }}</td>
                <td>{{ \App\Models\Venta::$estados[$v->estado] ?? $v->estado }}</td>
                <td>{{ $v->fecha_venta?->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
