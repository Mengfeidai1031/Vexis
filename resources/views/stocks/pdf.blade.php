<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Stock - VEXIS</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        h1 { font-size: 18px; color: #33AADD; margin-bottom: 4px; }
        .subtitle { font-size: 11px; color: #888; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #33AADD; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .bajo { color: #E74C3C; font-weight: bold; }
        .ok { color: #2ECC71; }
    </style>
</head>
<body>
    <h1>Inventario de Stock</h1>
    <div class="subtitle">Generado: {{ date('d/m/Y H:i') }} — VEXIS Grupo ARI</div>
    <table>
        <thead><tr><th>Ref.</th><th>Pieza</th><th>Cant.</th><th>Mín.</th><th>Precio</th><th>Almacén</th><th>Empresa</th><th>Estado</th></tr></thead>
        <tbody>
            @foreach($stocks as $s)
            <tr>
                <td style="font-family:monospace;">{{ $s->referencia }}</td>
                <td>{{ $s->nombre_pieza }}</td>
                <td style="text-align:center;">{{ $s->cantidad }}</td>
                <td style="text-align:center;">{{ $s->stock_minimo }}</td>
                <td style="text-align:right;">{{ number_format($s->precio_unitario, 2) }}€</td>
                <td>{{ $s->almacen?->nombre ?? '—' }}</td>
                <td>{{ $s->empresa?->nombre ?? '—' }}</td>
                <td class="{{ $s->cantidad <= $s->stock_minimo ? 'bajo' : 'ok' }}">{{ $s->cantidad <= $s->stock_minimo ? 'BAJO' : 'OK' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
