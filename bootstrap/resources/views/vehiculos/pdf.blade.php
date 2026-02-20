<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Vehículos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .header .date {
            font-size: 9px;
            color: #666;
        }
        
        .info {
            margin-bottom: 15px;
            font-size: 9px;
            color: #555;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table thead {
            background-color: #2c3e50;
            color: #fff;
        }
        
        table th {
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #1a252f;
        }
        
        table td {
            padding: 6px 5px;
            font-size: 8px;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        table tbody tr:hover {
            background-color: #e9ecef;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #666;
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 5px;
            background-color: #6c757d;
            color: #fff;
            border-radius: 3px;
            font-size: 7px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Listado de Vehículos</h1>
        <div class="date">Generado el: {{ date('d/m/Y H:i:s') }}</div>
    </div>
    
    <div class="info">
        <strong>Total de vehículos:</strong> {{ $vehiculos->count() }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 15%;">Chasis</th>
                <th style="width: 20%;">Modelo</th>
                <th style="width: 20%;">Versión</th>
                <th style="width: 12%;">Color Externo</th>
                <th style="width: 12%;">Color Interno</th>
                <th style="width: 16%;">Empresa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehiculos as $vehiculo)
                <tr>
                    <td class="text-center">{{ $vehiculo->id }}</td>
                    <td>
                        @if($vehiculo->chasis)
                            <span class="badge">{{ $vehiculo->chasis }}</span>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td><strong>{{ $vehiculo->modelo ?? '-' }}</strong></td>
                    <td>{{ $vehiculo->version ?? '-' }}</td>
                    <td>{{ $vehiculo->color_externo ?? '-' }}</td>
                    <td>{{ $vehiculo->color_interno ?? '-' }}</td>
                    <td>{{ $vehiculo->empresa ? $vehiculo->empresa->nombre : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #999;">
                        No hay vehículos registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Sistema de Gestión de Ofertas Comerciales - Página {PAGENO} de {PAGECOUNT}</p>
    </div>
</body>
</html>
