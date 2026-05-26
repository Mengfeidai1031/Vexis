@extends('layouts.app')
@section('title', 'Manual de Usuario - VEXIS')
@push('styles')
<style>
.wiki-layout { display: grid; grid-template-columns: 25% 75%; gap: 20px; min-height: calc(100vh - 200px); align-items: start; }
.wiki-sidebar { background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius); padding: 14px 10px; position: sticky; top: calc(var(--vx-navbar-height) + 56px); max-height: calc(100vh - var(--vx-navbar-height) - 80px); overflow-y: auto; }
.wiki-search { width: 100%; padding: 7px 10px; font-size: 12px; border: 1px solid var(--vx-border); border-radius: 6px; background: var(--vx-input-bg, var(--vx-bg)); color: var(--vx-text); margin-bottom: 8px; box-sizing: border-box; }
.wiki-search:focus { outline: none; border-color: var(--vx-primary); }
.wiki-sidebar h3 { font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.6px; color: var(--vx-text-muted); padding: 10px 8px 4px; margin: 0; font-weight: 700; }
.wiki-sidebar a { display: block; padding: 6px 10px; font-size: 12.5px; color: var(--vx-text); border-radius: 5px; text-decoration: none; margin-bottom: 1px; transition: background 0.15s; }
.wiki-sidebar a:hover { background: var(--vx-surface-hover); color: var(--vx-primary); }
.wiki-sidebar a.active { background: rgba(51,170,221,0.14); color: var(--vx-primary); font-weight: 600; }
.wiki-sidebar a.hidden { display: none; }
.wiki-content { background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius); padding: 28px 32px; min-height: 600px; }
.wiki-content h2 { font-size: 24px; margin: 0 0 6px; color: var(--vx-text); }
.wiki-content .lead { color: var(--vx-text-muted); font-size: 13.5px; margin-bottom: 28px; }
.wiki-content h3 { font-size: 18px; margin: 32px 0 12px; color: var(--vx-primary); border-bottom: 1px solid var(--vx-border); padding-bottom: 6px; }
.wiki-section { margin-bottom: 40px; scroll-margin-top: calc(var(--vx-navbar-height) + 70px); }
.wiki-acc { border: 1px solid var(--vx-border); border-radius: 6px; margin-bottom: 8px; overflow: hidden; background: var(--vx-surface); }
.wiki-acc-head { padding: 10px 14px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: 13.5px; user-select: none; }
.wiki-acc-head:hover { background: var(--vx-surface-hover); }
.wiki-acc-head .chev { transition: transform 0.2s; color: var(--vx-text-muted); font-size: 12px; }
.wiki-acc.open .wiki-acc-head .chev { transform: rotate(180deg); }
.wiki-acc-body { display: none; padding: 14px 18px; font-size: 13px; line-height: 1.65; background: var(--vx-bg, var(--vx-surface)); border-top: 1px solid var(--vx-border); color: var(--vx-text); }
.wiki-acc.open .wiki-acc-body { display: block; }
.wiki-acc-body ol, .wiki-acc-body ul { margin: 8px 0; padding-left: 22px; }
.wiki-acc-body code { background: rgba(51,170,221,0.1); padding: 1px 5px; border-radius: 3px; font-size: 12px; font-family: var(--vx-font-mono); color: var(--vx-primary); }
.wiki-acc-body strong { color: var(--vx-text); }
.wiki-callout { background: rgba(51,170,221,0.06); border-left: 3px solid var(--vx-primary); padding: 10px 14px; margin: 12px 0; font-size: 12.5px; border-radius: 0 4px 4px 0; }
.wiki-callout.warn { background: rgba(255,152,0,0.06); border-color: var(--vx-warning); }
.wiki-callout.danger { background: rgba(231,76,60,0.06); border-color: var(--vx-danger); }
.wiki-flow { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin: 12px 0; font-size: 12px; }
.wiki-flow-step { background: var(--vx-primary); color: white; padding: 6px 12px; border-radius: 16px; font-weight: 600; }
.wiki-flow-arrow { color: var(--vx-text-muted); font-size: 14px; }
@media (max-width: 900px) { .wiki-layout { grid-template-columns: 1fr; } .wiki-sidebar { position: static; max-height: none; } }
</style>
@endpush
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-book"></i> Manual de Usuario</h1></div>

<div class="wiki-layout">
    <aside class="wiki-sidebar">
        <input type="text" class="wiki-search" id="wikiSearch" placeholder="Buscar en el manual…" aria-label="Buscar">

        <h3>Empezar</h3>
        <a href="#intro" class="wiki-link">Bienvenida</a>
        <a href="#roles" class="wiki-link">Roles y permisos</a>
        <a href="#restricciones" class="wiki-link">Restricciones de usuario</a>

        <h3>Módulos</h3>
        <a href="#mod-gestion" class="wiki-link">Gestión</a>
        <a href="#mod-comercial" class="wiki-link">Comercial</a>
        <a href="#mod-recambios" class="wiki-link">Recambios</a>
        <a href="#mod-talleres" class="wiki-link">Talleres</a>
        <a href="#mod-dataxis" class="wiki-link">Dataxis</a>
        <a href="#mod-cliente" class="wiki-link">Portal Cliente</a>

        <h3>Flujos críticos</h3>
        <a href="#flujo-factura" class="wiki-link">Venta → Factura → Verifactu</a>
        <a href="#flujo-vehiculo" class="wiki-link">Ciclo de vida del vehículo</a>
        <a href="#flujo-oferta" class="wiki-link">Importar oferta PDF</a>
        <a href="#flujo-tasacion" class="wiki-link">Tasación de vehículo</a>
        <a href="#flujo-cita" class="wiki-link">Citas y taller</a>
        <a href="#flujo-reparto" class="wiki-link">Stock y repartos</a>

        <h3>Documentos</h3>
        <a href="#doc-vehiculo" class="wiki-link">Documentos de vehículo</a>
        <a href="#doc-generar" class="wiki-link">Generar PDFs profesionales</a>
        <a href="#doc-factura" class="wiki-link">PDF de factura con QR</a>

        <h3>Otras herramientas</h3>
        <a href="#vacaciones" class="wiki-link">Vacaciones y festivos</a>
        <a href="#incidencias" class="wiki-link">Incidencias</a>
        <a href="#ia" class="wiki-link">Chatbot y pretasación IA</a>
        <a href="#logs" class="wiki-link">Visor de logs</a>
        <a href="#settings" class="wiki-link">Configuración del sistema</a>
    </aside>

    <main class="wiki-content" id="wikiContent">

        <section class="wiki-section" id="intro">
            <h2>Bienvenida a VEXIS</h2>
            <p class="lead">Sistema integral de gestión de Grupo DAI para automoción (gestión, comercial, recambios, talleres, analítica y portal cliente).</p>
            <div class="wiki-callout">
                <strong>Tip rápido:</strong> usa <code>Ctrl+K</code> para abrir el buscador global o el buscador del sidebar para localizar una sección de este manual.
            </div>
        </section>

        <section class="wiki-section" id="roles">
            <h3>Roles y permisos</h3>
            <div class="wiki-acc"><div class="wiki-acc-head">Jerarquía de roles <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    <ol>
                        <li><strong>Super Admin</strong> — control total + Visor de logs + Control IA + permisos.</li>
                        <li><strong>Administrador</strong> — gestión completa salvo logs y permisos del sistema.</li>
                        <li><strong>Gerente</strong> — toma decisiones de área (lectura amplia, edición clave).</li>
                        <li><strong>Vendedor</strong> — comercial: ofertas, clientes, ventas, tasaciones.</li>
                        <li><strong>Mecánico</strong> — talleres: citas asignadas, vehículos en taller.</li>
                        <li><strong>Recepción Taller</strong> — CRUD citas y coches sustitución.</li>
                        <li><strong>Consultor</strong> — sólo lectura.</li>
                        <li><strong>Cliente</strong> — portal externo (asignado automáticamente en el registro público).</li>
                    </ol>
                    <div class="wiki-callout warn">Solo Super Admin puede asignar Super Admin/Administrador. El rol Cliente nunca se asigna desde el panel admin.</div>
                </div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Gestión de permisos <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    En <code>Gestión → Permisos</code> (Super Admin) puedes crear o eliminar permisos. La asignación a roles se hace en <code>Gestión → Roles</code>. Los permisos se aplican automáticamente vía middleware en cada ruta.
                </div>
            </div>
        </section>

        <section class="wiki-section" id="restricciones">
            <h3>Restricciones de usuario</h3>
            <p style="font-size:13px;color:var(--vx-text-muted);">Sistema polimórfico (<code>UserRestriction</code>) que limita qué entidades ve cada usuario, sin tocar sus permisos. Sólo afecta empresa/centro/departamento.</p>
            <div class="wiki-acc"><div class="wiki-acc-head">Cómo asignar restricciones <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    <ol>
                        <li>Edita un usuario en <code>Gestión → Usuarios → Editar</code>.</li>
                        <li>En la sección "Restricciones" marca las empresas, centros o departamentos a los que el usuario puede acceder.</li>
                        <li>Si no marcas nada de un tipo, el usuario ve <strong>todo</strong> de ese tipo.</li>
                    </ol>
                    <div class="wiki-callout"><code>laura.martin@grupo-dai.com</code> está restringido a Tenerife (empresa 2). <code>antonio.ramirez@grupo-dai.com</code> a centros 1 y 2 de Gran Canaria.</div>
                </div>
            </div>
        </section>

        <section class="wiki-section" id="mod-gestion">
            <h3>Módulo Gestión</h3>
            <div class="wiki-acc"><div class="wiki-acc-head">Usuarios, departamentos, centros, empresas <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Estructura organizacional: <code>Empresa → Centro → Departamento</code>. Cada usuario pertenece a una empresa y opcionalmente a un centro y departamento.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Roles, permisos y restricciones <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Ver secciones <a href="#roles">Roles</a> y <a href="#restricciones">Restricciones</a>.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Festivos, vacaciones, naming PCs <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    <ul>
                        <li><strong>Festivos:</strong> días no laborables del calendario corporativo. Se pueden filtrar por empresa.</li>
                        <li><strong>Vacaciones:</strong> solicitud → aprobación por Super Admin/Administrador. Los sábados y domingos no cuentan en el cálculo.</li>
                        <li><strong>Naming PCs:</strong> registro de equipos informáticos asignados al personal.</li>
                    </ul>
                </div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Noticias e incidencias <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    <ul>
                        <li><strong>Noticias:</strong> publicaciones internas, opcionalmente destacadas en el portal cliente.</li>
                        <li><strong>Incidencias:</strong> tickets de soporte con prioridad, estado y archivos adjuntos. Accesible desde el icono <i class="bi bi-exclamation-triangle"></i> del navbar.</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="wiki-section" id="mod-comercial">
            <h3>Módulo Comercial</h3>
            <p style="font-size:13px;color:var(--vx-text-muted);">El inicio del módulo se divide en 3 secciones: <strong>Gestión Administrativa</strong> (Ofertas, Tasaciones), <strong>Gestión Ventas</strong> (Ventas, Facturas, Verifactu) y <strong>Gestión de Vehículos</strong> (Vehículos, Generar documentos, Catálogo).</p>
            <div class="wiki-acc"><div class="wiki-acc-head">Clientes y vehículos <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    <ul>
                        <li><strong>Clientes:</strong> 6 tipos disponibles (Particular, Empresa, Autónomo, etc). DNI/CIF, datos de contacto, empresa asociada.</li>
                        <li><strong>Vehículos:</strong> chasis (VIN, obligatorio) + matrícula (opcional, botón "Nueva" autogenera siguiendo la serie N). Estado de ciclo de vida: <code>disponible</code> · <code>reservado</code> · <code>vendido</code> · <code>taller</code> · <code>baja</code>.</li>
                    </ul>
                </div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Catálogo de precios <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Listado de modelo + versión + precio + combustible. <strong>Requisito previo</strong> para vender un vehículo: si el modelo no está en catálogo, no se puede registrar la venta.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Ofertas con parser PDF <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Sube un PDF de oferta Nissan o Renault y el sistema extrae automáticamente cliente, vehículo, líneas e importes. Si el parser se equivoca, usa la acción "Editar" para corregir. El PDF original se conserva como evidencia.</div>
            </div>
        </section>

        <section class="wiki-section" id="mod-recambios">
            <h3>Módulo Recambios</h3>
            <div class="wiki-acc"><div class="wiki-acc-head">Almacenes y stock <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Cada almacén pertenece a una empresa. El stock se gestiona por referencia (código de pieza) y muestra cantidad disponible y mínima.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Repartos (distribución) <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    <ol>
                        <li>Solicitud desde un centro indicando origen, destino, referencia y cantidad.</li>
                        <li>Estado <code>pendiente</code> → <code>en_transito</code> → <code>entregado</code>.</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="wiki-section" id="mod-talleres">
            <h3>Módulo Talleres</h3>
            <div class="wiki-acc"><div class="wiki-acc-head">Talleres y mecánicos <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Cada taller pertenece a una empresa y opcionalmente a una marca. Los mecánicos están asignados a un taller.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Citas <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    Cuando creas una cita puedes elegir un cliente y vehículo registrados (dropdown FK) o introducir los datos como texto libre. Si asocias un vehículo registrado y la cita pasa a <code>confirmada</code> o <code>en_curso</code>, el vehículo pasa automáticamente a estado <code>taller</code>.
                </div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Coches de sustitución <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    Flota de coches que se prestan al cliente cuando su vehículo está en taller. Al crear uno puedes marcar <strong>"Reservar este coche al crearlo"</strong> para asignarlo directamente a un cliente con fechas y estado.
                </div>
            </div>
        </section>

        <section class="wiki-section" id="mod-dataxis">
            <h3>Módulo Dataxis (analítica)</h3>
            <p style="font-size:13px;color:var(--vx-text-muted);">7 dashboards con KPIs y gráficos Chart.js sobre datos del rango 2024-01-01 → 2026-04-20.</p>
            <ul style="font-size:13px;line-height:1.7;">
                <li><strong>Inicio</strong> — visión general con KPIs principales.</li>
                <li><strong>General</strong> — facturación, ventas, clientes nuevos.</li>
                <li><strong>Ventas</strong> — vendedores, marcas, evolución mensual.</li>
                <li><strong>Stock</strong> — niveles por almacén, alertas de mínimo.</li>
                <li><strong>Taller</strong> — citas atendidas, tiempos medios, mecánicos.</li>
                <li><strong>Facturas</strong> — emitidas, pagadas, vencidas, totales.</li>
                <li><strong>Incidencias</strong> — abiertas, cerradas, prioridad media.</li>
            </ul>
        </section>

        <section class="wiki-section" id="mod-cliente">
            <h3>Portal Cliente</h3>
            <p style="font-size:13px;color:var(--vx-text-muted);">Accesible públicamente tras registro. Asigna automáticamente el rol <code>Cliente</code> sin permisos administrativos.</p>
            <ul style="font-size:13px;line-height:1.7;">
                <li><strong>Chatbot</strong> — asistente IA con permisos del cliente (Gemini).</li>
                <li><strong>Pretasación</strong> — valoración orientativa por IA.</li>
                <li><strong>Tasación formal</strong> — solicitud que pasa al módulo Comercial.</li>
                <li><strong>Configurador</strong> — explorar modelos del catálogo.</li>
                <li><strong>Noticias / Talleres / Concesionarios</strong> — información pública.</li>
            </ul>
        </section>

        <section class="wiki-section" id="flujo-factura">
            <h3>Flujo: Venta → Factura → Verifactu</h3>
            <div class="wiki-flow">
                <span class="wiki-flow-step">Catálogo</span><span class="wiki-flow-arrow">→</span>
                <span class="wiki-flow-step">Vehículo</span><span class="wiki-flow-arrow">→</span>
                <span class="wiki-flow-step">Venta</span><span class="wiki-flow-arrow">→</span>
                <span class="wiki-flow-step">Factura</span><span class="wiki-flow-arrow">→</span>
                <span class="wiki-flow-step">Verifactu</span>
            </div>
            <div class="wiki-acc open"><div class="wiki-acc-head">1. Catálogo de precios <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Antes de cualquier venta, el <strong>modelo y versión</strong> del vehículo deben existir en <code>Comercial → Catálogo precios</code>. Sin esto no se podrá crear la venta.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">2. Registrar el vehículo <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">En <code>Comercial → Vehículos → Nuevo</code> introduce chasis (obligatorio). Matrícula opcional — el botón <strong>"Nueva"</strong> autogenera la siguiente disponible. Estado inicial: <code>disponible</code>.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">3. Crear la venta <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">En <code>Comercial → Ventas → Nueva</code> selecciona vehículo disponible. Aplica IGIC 7% en Canarias (CP 35xxx/38xxx) o IVA 21% peninsular. El vehículo pasa automáticamente a <code>reservado</code> y queda registro en el historial.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">4. Generar factura <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Código generado automáticamente con la serie configurada en <code>setting('factura_serie_actual')</code>. Formato: <code>FAC-A-YYYYMM-NNNN</code>.</div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">5. Registro Verifactu automático <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    Si <code>modulo_verifactu</code> está activo, se genera registro AEAT con hash encadenado, huella SHA-256 y URL QR de validación. El PDF de la factura embebe el QR si <code>verifactu_qr_facturas</code> está activo.
                    <div class="wiki-callout">Endpoint sandbox: <code>prewww2.aeat.es</code> · Producción: <code>www2.aeat.es</code>. Toggle en <code>Configuración → Verifactu → Sandbox</code>.</div>
                </div>
            </div>
        </section>

        <section class="wiki-section" id="flujo-vehiculo">
            <h3>Flujo: Ciclo de vida del vehículo</h3>
            <div class="wiki-flow">
                <span class="wiki-flow-step">disponible</span><span class="wiki-flow-arrow">↔</span>
                <span class="wiki-flow-step">reservado</span><span class="wiki-flow-arrow">→</span>
                <span class="wiki-flow-step">vendido</span>
            </div>
            <div class="wiki-flow">
                <span class="wiki-flow-step">disponible</span><span class="wiki-flow-arrow">↔</span>
                <span class="wiki-flow-step">taller</span><span class="wiki-flow-arrow">↔</span>
                <span class="wiki-flow-step">disponible</span>
            </div>
            <p style="font-size:13px;line-height:1.7;">Las transiciones se registran en <code>vehiculo_historial</code> con usuario, fecha y motivo. Eventos automáticos:</p>
            <ul style="font-size:13px;line-height:1.7;">
                <li>Venta creada (estado <code>reservada</code>) → vehículo a <code>reservado</code>.</li>
                <li>Venta a <code>entregada</code> → vehículo a <code>vendido</code>.</li>
                <li>Venta a <code>cancelada</code> → vehículo de vuelta a <code>disponible</code>.</li>
                <li>Cita taller en <code>confirmada</code>/<code>en_curso</code> con vehículo asociado → vehículo a <code>taller</code>.</li>
            </ul>
        </section>

        <section class="wiki-section" id="flujo-oferta">
            <h3>Flujo: Importar oferta PDF</h3>
            <ol style="font-size:13px;line-height:1.7;">
                <li>En <code>Comercial → Ofertas → Nueva</code> sube el PDF (Nissan o Renault).</li>
                <li>El parser extrae cliente, vehículo, líneas (descuentos, accesorios) e importes.</li>
                <li>Revisa el resultado. Si hay errores, usa "Editar" para corregir manualmente.</li>
                <li>El PDF original queda almacenado como evidencia.</li>
            </ol>
        </section>

        <section class="wiki-section" id="flujo-tasacion">
            <h3>Flujo: Tasación de vehículo</h3>
            <ol style="font-size:13px;line-height:1.7;">
                <li>El cliente puede solicitar una <strong>pretasación IA</strong> en el portal antes de venir.</li>
                <li>El concesionario crea una tasación formal en <code>Comercial → Tasaciones → Nueva</code> con datos completos (KM, estado, combustible, valor estimado).</li>
                <li>Se genera un PDF individual con la valoración orientativa.</li>
            </ol>
        </section>

        <section class="wiki-section" id="flujo-cita">
            <h3>Flujo: Cita de taller con vehículo</h3>
            <ol style="font-size:13px;line-height:1.7;">
                <li>En <code>Talleres → Citas → Nueva</code> selecciona mecánico, taller, fecha y hora.</li>
                <li>Asocia cliente (registrado o texto libre) y vehículo (registrado o texto libre).</li>
                <li>Al cambiar la cita a <code>confirmada</code> o <code>en_curso</code>, el vehículo registrado pasa automáticamente a <code>taller</code>.</li>
                <li>Al completar la cita, gestiona manualmente el cambio de estado del vehículo si procede.</li>
            </ol>
        </section>

        <section class="wiki-section" id="flujo-reparto">
            <h3>Flujo: Stock y reparto</h3>
            <ol style="font-size:13px;line-height:1.7;">
                <li>El operario crea el reparto en <code>Recambios → Repartos → Nuevo</code> (origen, destino, referencia, cantidad).</li>
                <li>Estado <code>pendiente</code> hasta que el transporte sale.</li>
                <li><code>en_transito</code> mientras se entrega.</li>
                <li><code>entregado</code> al confirmar recepción.</li>
            </ol>
        </section>

        <section class="wiki-section" id="doc-vehiculo">
            <h3>Documentos de vehículo</h3>
            <p style="font-size:13px;line-height:1.7;">Cada vehículo tiene su carpeta de documentos accesible desde <code>Vehículos → Documentos</code> (icono carpeta en la fila). La vista de <strong>Ver</strong> es solo lectura (descargar); la gestión (subir/eliminar) se hace en la vista dedicada de Documentos.</p>
            <div class="wiki-callout">Tipos soportados: ficha técnica, ITV, permiso de circulación, seguro, contrato, otro. Formatos: PDF, JPG, PNG (máx. 10 MB).</div>
        </section>

        <section class="wiki-section" id="doc-generar">
            <h3>Generar PDFs profesionales</h3>
            <p style="font-size:13px;line-height:1.7;">En <code>Comercial → Inicio → Generar documentos</code> hay un hub para producir PDFs profesionales de:</p>
            <ul style="font-size:13px;line-height:1.7;">
                <li>Ficha técnica</li>
                <li>ITV</li>
                <li>Permiso de circulación</li>
                <li>Seguro</li>
                <li>Contrato</li>
            </ul>
            <p style="font-size:13px;line-height:1.7;">Dos modos: <strong>Sólo generar PDF</strong> (descarga directa) o <strong>Generar y subir</strong> (descarga + guarda en documentos del vehículo + entrada en historial).</p>
        </section>

        <section class="wiki-section" id="doc-factura">
            <h3>PDF de factura con QR Verifactu</h3>
            <p style="font-size:13px;line-height:1.7;">Desde <code>Comercial → Facturas → Acción Ver</code> → botón "Descargar PDF". El PDF incluye logo de marca, datos del cliente y emisor, líneas, importes y un código QR en la esquina inferior si <code>verifactu_qr_facturas</code> está activo. El QR apunta a la URL de validación AEAT.</p>
        </section>

        <section class="wiki-section" id="vacaciones">
            <h3>Vacaciones y festivos</h3>
            <div class="wiki-acc"><div class="wiki-acc-head">Cómo solicitar vacaciones <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">
                    <ol>
                        <li><code>Gestión → Vacaciones → Solicitar</code>.</li>
                        <li>Selecciona fecha inicio y fin. Los sábados y domingos no cuentan.</li>
                        <li>Estado inicial: pendiente. Un administrador la aprueba o rechaza.</li>
                    </ol>
                    <div class="wiki-callout">Los días anuales se configuran en <code>setting('dias_vacaciones_anuales')</code> (default 22).</div>
                </div>
            </div>
            <div class="wiki-acc"><div class="wiki-acc-head">Festivos <i class="bi bi-chevron-down chev"></i></div>
                <div class="wiki-acc-body">Solo Super Admin/Administrador puede crear festivos. Aparecen en el calendario corporativo y afectan la planificación de citas.</div>
            </div>
        </section>

        <section class="wiki-section" id="incidencias">
            <h3>Incidencias (tickets)</h3>
            <p style="font-size:13px;line-height:1.7;">Accesible desde el navbar (<i class="bi bi-exclamation-triangle"></i>) si tienes permiso <code>ver incidencias</code>. Cada incidencia tiene código (<code>INC-YYYYMM-NNNN</code>), prioridad, estado, descripción y archivos adjuntos.</p>
        </section>

        <section class="wiki-section" id="ia">
            <h3>Chatbot y pretasación IA</h3>
            <p style="font-size:13px;line-height:1.7;">VEXIS usa dos claves Google Gemini separadas:</p>
            <ul style="font-size:13px;line-height:1.7;">
                <li><strong>Chatbot</strong> (<code>GEMINI_CHATBOT_API_KEY</code>) — asistente del portal cliente. Responde con permisos del rol del cliente.</li>
                <li><strong>Pretasación</strong> (<code>GEMINI_PRETASACION_API_KEY</code>) — valoración orientativa de vehículos.</li>
            </ul>
            <p style="font-size:13px;line-height:1.7;">Cada llamada se registra en la tabla <code>ai_usage</code>. Super Admin puede ver el consumo en el icono <i class="bi bi-cpu"></i> del navbar. Toggles globales y cuota mensual en <code>Configuración → IA</code>.</p>
        </section>

        <section class="wiki-section" id="logs">
            <h3>Visor de logs (Super Admin)</h3>
            <p style="font-size:13px;line-height:1.7;">Accesible desde el navbar (<i class="bi bi-journal-text"></i>). Parsea el canal <code>security</code> (eventos de auth, escalada de privilegios, excepciones) y el canal <code>laravel</code> general. Auto-refresh cada 10 segundos. Permite filtrar por nivel y buscar.</p>
        </section>

        <section class="wiki-section" id="settings">
            <h3>Configuración del sistema</h3>
            <p style="font-size:13px;line-height:1.7;"><code>Configuración</code> (icono engranaje del avatar, sólo Super Admin) controla 29 ajustes en 7 grupos:</p>
            <ul style="font-size:13px;line-height:1.7;">
                <li><strong>Módulos</strong> — activar/desactivar gestión, comercial, recambios, talleres, facturas, verifactu, incidencias.</li>
                <li><strong>Verifactu</strong> — envío AEAT, sandbox, QR en PDFs.</li>
                <li><strong>Facturación</strong> — IVA/IGIC defaults, serie actual, clave régimen.</li>
                <li><strong>RRHH</strong> — días de vacaciones anuales.</li>
                <li><strong>IA</strong> — toggles chatbot/pretasación, cuotas mensuales.</li>
                <li><strong>Sistema</strong> — modo mantenimiento, nombre empresa, registro abierto.</li>
                <li><strong>Seguridad</strong> — longitud mínima contraseña, intentos login, timeout sesión.</li>
            </ul>
        </section>

    </main>
</div>

@push('scripts')
<script>
(function(){
    // Acordeones
    document.querySelectorAll('.wiki-acc-head').forEach(h => h.addEventListener('click', () => h.parentElement.classList.toggle('open')));

    // Search
    const search = document.getElementById('wikiSearch');
    const links = document.querySelectorAll('.wiki-link');
    search.addEventListener('input', () => {
        const q = search.value.toLowerCase().trim();
        links.forEach(a => {
            const match = !q || a.textContent.toLowerCase().includes(q);
            a.classList.toggle('hidden', !match);
        });
    });

    // Scroll-spy
    const sections = document.querySelectorAll('.wiki-section');
    function activateLink() {
        const top = window.scrollY + 120;
        let active = sections[0];
        sections.forEach(s => { if (s.offsetTop <= top) active = s; });
        links.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + active.id));
    }
    window.addEventListener('scroll', activateLink, { passive: true });
    activateLink();

    // Smooth scroll
    links.forEach(a => a.addEventListener('click', e => {
        const id = a.getAttribute('href');
        if (id && id.startsWith('#')) {
            const target = document.querySelector(id);
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        }
    }));
})();
</script>
@endpush
@endsection
