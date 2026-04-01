<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Módulos
            ['key' => 'modulo_gestion', 'value' => '1', 'type' => 'boolean', 'group' => 'modulos', 'description' => 'Módulo de Gestión (usuarios, departamentos, centros, empresas)'],
            ['key' => 'modulo_comercial', 'value' => '1', 'type' => 'boolean', 'group' => 'modulos', 'description' => 'Módulo Comercial (clientes, vehículos, ofertas, ventas, tasaciones)'],
            ['key' => 'modulo_recambios', 'value' => '1', 'type' => 'boolean', 'group' => 'modulos', 'description' => 'Módulo de Recambios (almacenes, stock, distribución)'],
            ['key' => 'modulo_talleres', 'value' => '1', 'type' => 'boolean', 'group' => 'modulos', 'description' => 'Módulo de Talleres (citas, mecánicos, vehículos sustitución)'],
            ['key' => 'modulo_facturas', 'value' => '1', 'type' => 'boolean', 'group' => 'modulos', 'description' => 'Módulo de Facturas'],
            ['key' => 'modulo_verifactu', 'value' => '1', 'type' => 'boolean', 'group' => 'modulos', 'description' => 'Módulo Verifactu (cumplimiento RD 1007/2023)'],
            ['key' => 'modulo_incidencias', 'value' => '1', 'type' => 'boolean', 'group' => 'modulos', 'description' => 'Módulo de Incidencias (tickets de soporte)'],

            // Verifactu
            ['key' => 'verifactu_envio_aeat', 'value' => '1', 'type' => 'boolean', 'group' => 'verifactu', 'description' => 'Activar envío automático a AEAT'],
            ['key' => 'verifactu_sandbox', 'value' => '1', 'type' => 'boolean', 'group' => 'verifactu', 'description' => 'Usar entorno sandbox de AEAT (pruebas)'],
            ['key' => 'verifactu_qr_facturas', 'value' => '1', 'type' => 'boolean', 'group' => 'verifactu', 'description' => 'Incluir código QR Verifactu en PDFs de facturas'],

            // Sistema
            ['key' => 'modo_mantenimiento', 'value' => '0', 'type' => 'boolean', 'group' => 'sistema', 'description' => 'Modo mantenimiento (solo acceso Super Admin)'],
            ['key' => 'registro_abierto', 'value' => '0', 'type' => 'boolean', 'group' => 'sistema', 'description' => 'Permitir registro de nuevos usuarios'],
            ['key' => 'notificaciones_email', 'value' => '0', 'type' => 'boolean', 'group' => 'sistema', 'description' => 'Enviar notificaciones por email'],
            ['key' => 'nombre_empresa', 'value' => 'Grupo ARI', 'type' => 'string', 'group' => 'sistema', 'description' => 'Nombre de la empresa (aparece en PDFs y cabeceras)'],
            ['key' => 'email_contacto', 'value' => '', 'type' => 'string', 'group' => 'sistema', 'description' => 'Email de contacto principal'],
            ['key' => 'telefono_contacto', 'value' => '', 'type' => 'string', 'group' => 'sistema', 'description' => 'Teléfono de contacto principal'],

            // Seguridad
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'seguridad', 'description' => 'Máximo de intentos de login antes de bloqueo'],
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'integer', 'group' => 'seguridad', 'description' => 'Tiempo de sesión en minutos'],
            ['key' => 'audit_logins', 'value' => '1', 'type' => 'boolean', 'group' => 'seguridad', 'description' => 'Registrar auditoría de inicios de sesión'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
