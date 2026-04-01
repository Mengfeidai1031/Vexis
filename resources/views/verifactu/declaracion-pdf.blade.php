<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Declaración Responsable Verifactu</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; padding: 50px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 40px; }
        .header img { height: 40px; margin-bottom: 8px; }
        .title { font-size: 22px; font-weight: 800; color: #333; margin-bottom: 4px; }
        .subtitle { font-size: 13px; color: #666; margin-bottom: 24px; }
        .divider { height: 3px; background: linear-gradient(to right, #33AADD, #33AADD80, transparent); margin: 24px 0; }
        .section { margin-bottom: 24px; }
        .section-title { font-size: 14px; font-weight: 700; color: #33AADD; border-bottom: 1px solid #33AADD; padding-bottom: 4px; margin-bottom: 12px; }
        .field { margin-bottom: 8px; }
        .field-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #999; font-weight: 700; }
        .field-value { font-size: 13px; font-weight: 600; }
        .declaration-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; margin: 24px 0; }
        .declaration-text { font-size: 12px; line-height: 1.8; text-align: justify; }
        .stats-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .stats-table th { background: #33AADD; color: white; padding: 8px 12px; text-align: left; font-size: 11px; }
        .stats-table td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 12px; }
        .signature-area { margin-top: 60px; display: flex; justify-content: space-between; }
        .signature-box { width: 45%; text-align: center; }
        .signature-line { border-top: 1px solid #333; margin-top: 60px; padding-top: 8px; font-size: 11px; }
        .footer { margin-top: 40px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 12px; }
        .legal-ref { font-size: 10px; color: #666; margin-top: 16px; font-style: italic; }
        .logo-verifactu { height: 30px; margin-left: 8px; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/vexis-logo.png') }}" alt="VEXIS">
        @if(file_exists(storage_path('app/public/logos/verifactu.png')))
        <img src="{{ storage_path('app/public/logos/verifactu.png') }}" class="logo-verifactu" alt="Verifactu">
        @endif
        <div class="title">DECLARACIÓN RESPONSABLE</div>
        <div class="subtitle">Sistema de Verificación de Facturas (VERI*FACTU)</div>
    </div>

    <div class="divider"></div>

    <div class="section">
        <div class="section-title">1. Datos del Declarante</div>
        <table style="width:100%;">
            <tr>
                <td style="width:50%;padding:4px 0;">
                    <div class="field">
                        <div class="field-label">Nombre completo</div>
                        <div class="field-value">Meng Fei Dai</div>
                    </div>
                </td>
                <td style="width:50%;padding:4px 0;">
                    <div class="field">
                        <div class="field-label">Cargo</div>
                        <div class="field-value">Responsable del Sistema Informático</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding:4px 0;">
                    <div class="field">
                        <div class="field-label">Empresa</div>
                        <div class="field-value">VEXIS — Grupo DAI</div>
                    </div>
                </td>
                <td style="padding:4px 0;">
                    <div class="field">
                        <div class="field-label">Fecha de declaración</div>
                        <div class="field-value">{{ date('d/m/Y') }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">2. Declaración</div>
        <div class="declaration-box">
            <div class="declaration-text">
                <strong>D./Dña. Meng Fei Dai</strong>, en calidad de Responsable del Sistema Informático de facturación
                <strong>VEXIS</strong>, utilizado por la empresa <strong>Grupo DAI</strong>, DECLARA RESPONSABLEMENTE que:
                <br><br>
                <strong>PRIMERO.</strong> — El sistema informático de facturación VEXIS cumple con los requisitos establecidos en el
                <strong>Real Decreto 1007/2023, de 5 de diciembre</strong>, por el que se aprueba el Reglamento que establece
                los requisitos que deben adoptar los sistemas y programas informáticos o electrónicos que soporten los
                procesos de facturación de empresarios y profesionales, y la estandarización de formatos de los registros de facturación.
                <br><br>
                <strong>SEGUNDO.</strong> — El sistema VEXIS garantiza la <strong>integridad, conservación, accesibilidad, legibilidad,
                trazabilidad e inalterabilidad</strong> de los registros de facturación, mediante el uso de un sistema de
                hashes encadenados SHA-256 que asegura que cualquier modificación de un registro invalidaría toda la
                cadena posterior.
                <br><br>
                <strong>TERCERO.</strong> — El sistema genera un registro de facturación por cada factura emitida, que incluye:
                código de registro único, hash del registro actual, hash del registro anterior (cadena), datos del emisor
                (NIF y nombre), importe total, fecha y hora del registro, y tipo de operación (emisión, anulación o rectificación).
                <br><br>
                <strong>CUARTO.</strong> — El sistema permite la generación de los ficheros XML en el formato establecido por la
                Agencia Estatal de Administración Tributaria (AEAT) para el envío de los registros de facturación,
                pudiendo realizarse de forma inmediata o en los plazos establecidos reglamentariamente.
                <br><br>
                <strong>QUINTO.</strong> — El sistema no permite la eliminación ni manipulación de los registros de facturación
                una vez generados, garantizando así su inalterabilidad conforme a lo exigido por la normativa vigente.
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">3. Datos del Sistema</div>
        <table class="stats-table">
            <thead><tr><th>Concepto</th><th>Valor</th></tr></thead>
            <tbody>
                <tr><td>Nombre del sistema</td><td><strong>VEXIS</strong></td></tr>
                <tr><td>Versión</td><td>1.0.0</td></tr>
                <tr><td>Algoritmo de hash</td><td>SHA-256 (encadenado)</td></tr>
                <tr><td>Total de registros generados</td><td>{{ $stats['total'] }}</td></tr>
                <tr><td>Registros aceptados por AEAT</td><td>{{ $stats['aceptados'] }}</td></tr>
                <tr><td>Base imponible total</td><td>{{ number_format($stats['base_imponible_total'], 2) }} €</td></tr>
                <tr><td>Cuota tributaria total</td><td>{{ number_format($stats['cuota_total'], 2) }} €</td></tr>
                <tr><td>Importe total registrado</td><td>{{ number_format($stats['importe_total'], 2) }} €</td></tr>
                <tr><td>Primer registro</td><td>{{ $stats['primer_registro']?->format('d/m/Y H:i') ?? '—' }}</td></tr>
                <tr><td>Último registro</td><td>{{ $stats['ultimo_registro']?->format('d/m/Y H:i') ?? '—' }}</td></tr>
            </tbody>
        </table>
    </div>

    <div class="legal-ref">
        Referencia legal: Real Decreto 1007/2023, de 5 de diciembre (BOE núm. 293, de 7 de diciembre de 2023).
        Orden HAC/1177/2024 por la que se desarrollan las especificaciones técnicas del sistema VERI*FACTU.
    </div>

    <table style="width:100%;margin-top:50px;">
        <tr>
            <td style="width:50%;text-align:center;border:none;padding:0;">
                <div style="border-top:1px solid #333;width:200px;margin:60px auto 0;padding-top:8px;font-size:11px;">
                    Meng Fei Dai<br>
                    <span style="font-size:10px;color:#666;">Responsable del Sistema</span>
                </div>
            </td>
            <td style="width:50%;text-align:center;border:none;padding:0;">
                <div style="border-top:1px solid #333;width:200px;margin:60px auto 0;padding-top:8px;font-size:11px;">
                    Sello de la empresa<br>
                    <span style="font-size:10px;color:#666;">Grupo DAI</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Documento generado automáticamente por VEXIS el {{ date('d/m/Y H:i') }} — Este documento tiene carácter de declaración responsable conforme al artículo 69 de la Ley 39/2015
    </div>
</body>
</html>
