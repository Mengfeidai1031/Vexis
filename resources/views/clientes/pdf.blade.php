<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes - VEXIS</title>
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
    <h1 style="margin-top:0;">Listado de Clientes</h1>
    <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>DNI</th><th>Email</th><th>Teléfono</th><th>Domicilio</th><th>CP</th><th>Empresa</th></tr></thead>
        <tbody>
            @foreach($clientes as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->nombre }}</td>
                <td>{{ $c->apellidos }}</td>
                <td>{{ $c->dni ?? '—' }}</td>
                <td>{{ $c->email ?? '—' }}</td>
                <td>{{ $c->telefono ?? '—' }}</td>
                <td>{{ $c->domicilio ?? '—' }}</td>
                <td>{{ $c->codigo_postal ?? '—' }}</td>
                <td>{{ $c->empresa?->nombre ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
