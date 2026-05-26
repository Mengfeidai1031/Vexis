<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Cumplimiento Técnico VERI*FACTU</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; padding: 45px; line-height: 1.55; }
        .header { text-align: center; margin-bottom: 24px; }
        .header img { height: 38px; }
        .title { font-size: 20px; font-weight: 800; color: #33AADD; margin-top: 10px; letter-spacing: 0.5px; }
        .subtitle { font-size: 12px; color: #666; margin-top: 2px; }
        .divider { height: 3px; background: linear-gradient(to right, #33AADD, #33AADD80, transparent); margin: 18px 0; }
        h2 { font-size: 13px; color: #33AADD; border-bottom: 1px solid #33AADD; padding-bottom: 4px; margin: 20px 0 10px; }
        h3 { font-size: 11px; color: #222; margin: 10px 0 4px; }
        p { text-align: justify; margin-bottom: 6px; }
        ul { margin: 4px 0 8px 20px; }
        li { margin-bottom: 3px; text-align: justify; }
        .req-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .req-table th { background: #33AADD; color: #fff; padding: 6px 8px; font-size: 10px; text-align: left; }
        .req-table td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 10px; vertical-align: top; }
        .req-table tr:nth-child(even) { background: #fafafa; }
        .ok { color: #2e7d32; font-weight: 700; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 700; background: #e8f5e9; color: #2e7d32; text-transform: uppercase; }
        .info-box { background: #f0f9ff; border-left: 3px solid #33AADD; padding: 10px 14px; margin: 10px 0; border-radius: 4px; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        code { background: #f4f4f4; padding: 1px 4px; border-radius: 3px; font-family: monospace; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/vexis-logo.png') }}" alt="VEXIS">
        <div class="title">INFORME DE CUMPLIMIENTO TÉCNICO</div>
        <div class="subtitle">Sistema VERI*FACTU — Real Decreto 1007/2023 y Orden HAC/1177/2024</div>
    </div>

    <div class="divider"></div>

    <div class="info-box">
        <strong>Propósito del documento:</strong> Desarrollar y evidenciar de forma exhaustiva que el sistema informático de facturación <strong>VEXIS</strong> cumple íntegramente con los requisitos técnicos, funcionales y de seguridad exigidos por la normativa VERI*FACTU vigente. Este informe complementa la Declaración Responsable y detalla la arquitectura, controles y evidencias operativas del sistema.
    </div>

    <h2>1. Marco normativo aplicable</h2>
    <ul>
        <li><strong>Ley 11/2021</strong>, de 9 de julio, de medidas de prevención y lucha contra el fraude fiscal (art. 29.2.j LGT).</li>
        <li><strong>Real Decreto 1007/2023</strong>, de 5 de diciembre — Reglamento de requisitos de los sistemas informáticos de facturación (SIF).</li>
        <li><strong>Orden HAC/1177/2024</strong>, de 17 de octubre — Especificaciones técnicas, funcionales y de contenido del sistema VERI*FACTU.</li>
        <li><strong>Real Decreto 1619/2012</strong> — Reglamento de facturación.</li>
        <li><strong>RGPD (UE) 2016/679</strong> y <strong>LOPDGDD 3/2018</strong>.</li>
    </ul>

    <h2>2. Requisitos técnicos RD 1007/2023 — Matriz de cumplimiento</h2>
    <table class="req-table">
        <thead><tr><th style="width:35%;">Requisito</th><th style="width:15%;">Estado</th><th>Evidencia técnica en VEXIS</th></tr></thead>
        <tbody>
            <tr>
                <td><strong>Integridad</strong> de los registros</td>
                <td><span class="badge">Cumple</span></td>
                <td>Cada registro incorpora un hash <code>SHA-256</code> calculado sobre los campos críticos (NIF emisor, serie, número, fecha, importe total, base, cuota y hash anterior). Cualquier alteración invalidaría la huella y toda la cadena posterior.</td>
            </tr>
            <tr>
                <td><strong>Conservación</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Registros almacenados en MySQL 8 con copias de seguridad automatizadas. La tabla <code>verifactus</code> no permite DELETE desde la aplicación; las anulaciones se representan como nuevos registros de tipo <code>anulacion</code>.</td>
            </tr>
            <tr>
                <td><strong>Accesibilidad</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Interfaz web autenticada con RBAC (Spatie Permission) que permite consulta íntegra de los registros a usuarios autorizados, exportación XML individual y descarga de informes.</td>
            </tr>
            <tr>
                <td><strong>Legibilidad</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Los registros se presentan en formato humano legible (vistas Blade) y en formato máquina (XML AEAT) mediante <code>Verifactu::buildAeatXml()</code>.</td>
            </tr>
            <tr>
                <td><strong>Trazabilidad</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Cadena de hashes encadenados (campo <code>hash_anterior</code> apunta al <code>hash_registro</code> del registro inmediatamente previo), timestamp <code>fecha_registro</code> inmutable y usuario emisor registrado. Endpoint <code>verifactu.verificarCadena</code> valida la integridad global.</td>
            </tr>
            <tr>
                <td><strong>Inalterabilidad</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Ausencia total de rutas/controladores de edición destructiva. Los registros solo admiten transiciones de estado controladas (<code>registrado → enviado → aceptado/rechazado</code>).</td>
            </tr>
            <tr>
                <td><strong>Remisión a la AEAT</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Servicio <code>AeatVerifactuService</code> capaz de generar y enviar los XML firmados al endpoint SOAP de la AEAT. Estados y CSV de respuesta registrados.</td>
            </tr>
            <tr>
                <td><strong>Generación automática</strong> de registros</td>
                <td><span class="badge">Cumple</span></td>
                <td>Al emitir/anular una factura se invoca el servicio dentro de una transacción de BBDD, garantizando atomicidad entre factura y registro Verifactu.</td>
            </tr>
            <tr>
                <td><strong>Identificación del sistema</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Campos <code>nombre_sistema</code>, <code>version_sistema</code>, <code>id_sistema_informatico</code> poblados en cada registro conforme al esquema XSD de AEAT.</td>
            </tr>
            <tr>
                <td><strong>Huella o firma electrónica</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Algoritmo SHA-256, codificación hexadecimal en minúsculas, 64 caracteres. Cumple el art. 10 RD 1007/2023 y Anexo I de la Orden HAC/1177/2024.</td>
            </tr>
            <tr>
                <td><strong>Código QR tributario</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Cada factura PDF incluye código QR con URL de verificación AEAT, NIF emisor, número de factura, fecha e importe, conforme a Anexo II de la Orden.</td>
            </tr>
            <tr>
                <td><strong>Identificación "VERI*FACTU"</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Leyenda impresa <em>"VERI*FACTU — RD 1007/2023"</em> visible en todas las facturas emitidas.</td>
            </tr>
            <tr>
                <td><strong>Registro de eventos</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Laravel log + auditoría de cambios de estado con usuario, timestamp e IP.</td>
            </tr>
            <tr>
                <td><strong>Control de acceso</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Autenticación obligatoria, permisos granulares (<code>ver/crear/editar/eliminar verifactu</code>), políticas por empresa/centro (multi-tenant).</td>
            </tr>
            <tr>
                <td><strong>Protección de datos (RGPD)</strong></td>
                <td><span class="badge">Cumple</span></td>
                <td>Tratamiento conforme al RGPD, cláusula informativa en facturas, minimización de datos, cifrado en tránsito (HTTPS) y en reposo según política del hosting.</td>
            </tr>
        </tbody>
    </table>

    <h2>3. Arquitectura del sistema</h2>
    <p><strong>Backend:</strong> Laravel 12 sobre PHP 8.2 y MySQL 8. Los registros Verifactu se modelan en <code>App\Models\Verifactu</code> y se orquestan mediante <code>App\Services\AeatVerifactuService</code>. El cálculo de la huella utiliza exclusivamente las funciones criptográficas nativas de PHP (<code>hash('sha256', …)</code>).</p>
    <p><strong>Capa de persistencia:</strong> Tabla <code>verifactus</code> con restricciones de unicidad sobre <code>codigo_registro</code> y <code>hash_registro</code>, e índice sobre <code>hash_anterior</code> para validación rápida de la cadena.</p>
    <p><strong>Capa de generación XML:</strong> Construcción conforme al XSD oficial de AEAT, con namespaces <code>sum</code> y <code>sum1</code>, respetando la estructura <em>RegFactuSistemaFacturacion</em>.</p>

    <h2>4. Procedimientos operativos</h2>
    <h3>4.1. Alta de factura</h3>
    <p>Al crear una factura, el sistema: (a) valida datos mediante FormRequest; (b) abre una transacción; (c) persiste la factura; (d) genera el registro Verifactu encadenado al último hash existente; (e) construye el XML AEAT; (f) devuelve el resultado. Si cualquier paso falla, la transacción se revierte íntegramente.</p>
    <h3>4.2. Anulación</h3>
    <p>Las anulaciones no borran registros previos: crean un nuevo registro de tipo <code>anulacion</code> que referencia al original, preservando el historial completo.</p>
    <h3>4.3. Rectificación</h3>
    <p>Las facturas rectificativas generan su propio registro con los campos normativos de rectificación (tipo rectificativa, referencias a la factura original, importes rectificados).</p>
    <h3>4.4. Envío a AEAT</h3>
    <p>El envío se realiza contra el endpoint SOAP oficial. El sistema almacena el CSV de respuesta, el estado y los posibles errores devueltos por AEAT.</p>

    <h2>5. Seguridad y controles</h2>
    <ul>
        <li>Autenticación obligatoria con hash bcrypt y política de contraseñas.</li>
        <li>Protección CSRF en todas las rutas POST/PUT/DELETE.</li>
        <li>Protección contra SQL injection mediante Eloquent ORM y bindings.</li>
        <li>Sanitización de entradas y validación estricta con FormRequest.</li>
        <li>Logs centralizados con rotación automática.</li>
        <li>Backups cifrados de la base de datos.</li>
        <li>Principio de mínimo privilegio aplicado vía Spatie Permission.</li>
    </ul>

    <h2>6. Evidencias operativas actuales</h2>
    <table class="req-table">
        <tbody>
            <tr><td>Total de registros generados</td><td colspan="2"><strong>{{ $stats['total'] }}</strong></td></tr>
            <tr><td>Registros aceptados por AEAT</td><td colspan="2"><strong class="ok">{{ $stats['aceptados'] }}</strong></td></tr>
            <tr><td>Registros pendientes</td><td colspan="2">{{ $stats['pendientes'] }}</td></tr>
            <tr><td>Registros rechazados</td><td colspan="2">{{ $stats['rechazados'] }}</td></tr>
            <tr><td>Registros anulados</td><td colspan="2">{{ $stats['anulados'] }}</td></tr>
            <tr><td>Base imponible total registrada</td><td colspan="2">{{ number_format($stats['base_imponible_total'], 2, ',', '.') }} €</td></tr>
            <tr><td>Cuota tributaria total</td><td colspan="2">{{ number_format($stats['cuota_total'], 2, ',', '.') }} €</td></tr>
            <tr><td>Importe total registrado</td><td colspan="2">{{ number_format($stats['importe_total'], 2, ',', '.') }} €</td></tr>
            <tr><td>Primer registro</td><td colspan="2">{{ $stats['primer_registro']?->format('d/m/Y H:i') ?? '—' }}</td></tr>
            <tr><td>Último registro</td><td colspan="2">{{ $stats['ultimo_registro']?->format('d/m/Y H:i') ?? '—' }}</td></tr>
        </tbody>
    </table>

    <h2>7. Verificación de la cadena de hashes</h2>
    <p>El sistema expone un endpoint (<code>/verifactu/verificar-cadena</code>) que recorre todos los registros ordenados cronológicamente y comprueba que el campo <code>hash_anterior</code> de cada registro coincida exactamente con el <code>hash_registro</code> del registro previo. Cualquier discrepancia se reporta inmediatamente, evidenciando una posible manipulación.</p>

    <h2>8. Conclusión</h2>
    <div class="info-box">
        A la vista de la arquitectura, los controles técnicos implementados y las evidencias operativas recogidas en este informe, se concluye que el sistema <strong>VEXIS cumple en su totalidad</strong> los requisitos establecidos por el Real Decreto 1007/2023 y la Orden HAC/1177/2024 para los sistemas informáticos de facturación bajo la modalidad VERI*FACTU, garantizando la integridad, conservación, accesibilidad, legibilidad, trazabilidad e inalterabilidad de los registros de facturación.
    </div>

    <div class="footer">
        Informe generado automáticamente por VEXIS el {{ date('d/m/Y H:i') }} — Documento complementario a la Declaración Responsable VERI*FACTU.
    </div>
</body>
</html>
