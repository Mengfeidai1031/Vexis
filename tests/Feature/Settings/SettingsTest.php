<?php

namespace Tests\Feature\Settings;

use App\Models\Setting;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    public function test_super_admin_can_view_settings(): void
    {
        $this->actingAsSuperAdmin();
        $this->get('/settings')->assertOk()
            ->assertSee('Configuración')
            ->assertSee('Módulos')
            ->assertSee('Facturación')
            ->assertSee('Inteligencia Artificial')
            ->assertSee('Seguridad');
    }

    public function test_non_super_admin_cannot_view_settings(): void
    {
        $this->actingAsAdmin();
        $this->get('/settings')->assertForbidden();
    }

    public function test_setting_helper_returns_seeded_values(): void
    {
        $this->assertEquals(8, (int) setting('password_min_length'));
        $this->assertEquals(22, (int) setting('dias_vacaciones_anuales'));
        $this->assertEquals('A', setting('factura_serie_actual'));
        $this->assertTrue((bool) setting('ai_chatbot_enabled'));
        $this->assertTrue((bool) setting('ai_pretasacion_enabled'));
        $this->assertEquals('08', setting('verifactu_clave_regimen'));
    }

    public function test_setting_update_persists_and_invalidates_cache(): void
    {
        $this->actingAsSuperAdmin();
        $original = Setting::get('password_min_length');

        $this->put('/settings', [
            'settings' => ['password_min_length' => 10],
        ])->assertRedirect();

        $this->assertEquals(10, (int) Setting::get('password_min_length'));

        // restaurar
        Setting::set('password_min_length', $original);
    }

    public function test_all_setting_groups_exist(): void
    {
        $groups = Setting::pluck('group')->unique()->all();
        foreach (['modulos', 'verifactu', 'facturacion', 'rrhh', 'ia', 'sistema', 'seguridad'] as $g) {
            $this->assertContains($g, $groups, "Falta grupo $g");
        }
    }
}
