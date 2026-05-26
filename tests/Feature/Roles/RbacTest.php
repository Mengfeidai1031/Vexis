<?php

namespace Tests\Feature\Roles;

use Tests\TestCase;

class RbacTest extends TestCase
{
    public function test_unauthenticated_redirects_to_login(): void
    {
        $this->get('/users')->assertRedirect('/login');
        $this->get('/vehiculos')->assertRedirect('/login');
        $this->get('/dataxis')->assertRedirect('/login');
    }

    public function test_super_admin_sees_logs_and_settings_and_ai(): void
    {
        $this->actingAsSuperAdmin();
        $this->get('/gestion/logs')->assertOk();
        $this->get('/settings')->assertOk();
        $this->get('/ai/control')->assertOk();
        $this->get('/permisos')->assertOk();
    }

    public function test_admin_blocked_from_super_admin_only_routes(): void
    {
        $this->actingAsAdmin();
        $this->get('/gestion/logs')->assertForbidden();
        $this->get('/settings')->assertForbidden();
        $this->get('/ai/control')->assertForbidden();
        $this->get('/permisos')->assertForbidden();
    }

    public function test_admin_can_access_gestion(): void
    {
        $this->actingAsAdmin();
        $this->get('/users')->assertOk();
        $this->get('/departamentos')->assertOk();
        $this->get('/centros')->assertOk();
        $this->get('/empresas')->assertOk();
    }

    public function test_vendedor_can_access_comercial(): void
    {
        $this->actingAsVendedor();
        $this->get('/clientes')->assertOk();
        $this->get('/vehiculos')->assertOk();
        $this->get('/ofertas')->assertOk();
    }

    public function test_vendedor_blocked_from_admin_routes(): void
    {
        $this->actingAsVendedor();
        $this->get('/permisos')->assertForbidden();
        $this->get('/settings')->assertForbidden();
        $this->get('/gestion/logs')->assertForbidden();
    }

    public function test_mecanico_accesses_talleres(): void
    {
        $this->actingAsMecanico();
        $this->get('/citas')->assertOk();
        $this->get('/vehiculos')->assertOk();
    }

    public function test_recepcion_taller_can_manage_citas(): void
    {
        $this->actingAsRecepcion();
        $this->get('/citas')->assertOk();
        $this->get('/coches-sustitucion')->assertOk();
    }

    public function test_consultor_is_readonly(): void
    {
        $this->actingAsConsultor();
        // Tiene `ver clientes`, `ver vehículos`, `ver usuarios`
        $this->get('/clientes')->assertOk();
        $this->get('/vehiculos')->assertOk();
        $this->get('/users')->assertOk();
        // Bloqueado en super-admin only
        $this->get('/settings')->assertForbidden();
        $this->get('/gestion/logs')->assertForbidden();
    }

    public function test_cliente_role_only_accesses_portal(): void
    {
        $this->actingAsCliente();
        // Bloqueado en admin
        $this->get('/users')->assertForbidden();
        $this->get('/vehiculos')->assertForbidden();
        $this->get('/facturas')->assertForbidden();
        $this->get('/gestion/logs')->assertForbidden();
        $this->get('/settings')->assertForbidden();
        // Permitido en portal
        $this->get('/cliente')->assertOk();
        $this->get('/cliente/chatbot')->assertOk();
        $this->get('/cliente/pretasacion')->assertOk();
    }
}
