<?php

namespace Tests\Feature\Features;

use App\Models\AiUsage;
use Tests\TestCase;

class AiControlTest extends TestCase
{
    public function test_only_super_admin_can_view_ai_control(): void
    {
        $this->actingAsAdmin();
        $this->get('/ai/control')->assertForbidden();

        $this->actingAsSuperAdmin();
        $this->get('/ai/control')->assertOk()
            ->assertSee('Control de IA')
            ->assertSee('Gemini Chatbot')
            ->assertSee('Gemini Pretasación');
    }

    public function test_ai_summary_endpoint_returns_json(): void
    {
        $this->actingAsSuperAdmin();
        $this->get('/ai/control/summary')
            ->assertOk()
            ->assertJsonStructure(['summary', 'limits']);
    }

    public function test_ai_usage_summary_includes_both_providers(): void
    {
        $summary = AiUsage::summary();
        $providers = collect($summary)->pluck('provider')->all();
        $this->assertContains('chatbot', $providers);
        $this->assertContains('pretasacion', $providers);
    }

    public function test_api_keys_masked_in_view(): void
    {
        // Claves ficticias inyectadas en tiempo de test: nunca usamos claves reales aquí.
        $fakeChatbot = 'AIzaSyTESTchatbot0000000000000000000000';
        $fakePretasa = 'AIzaSyTESTpretasa1111111111111111111111';
        config([
            'services.gemini.chatbot.api_key' => $fakeChatbot,
            'services.gemini.pretasacion.api_key' => $fakePretasa,
        ]);

        $this->actingAsSuperAdmin();
        $html = $this->get('/ai/control')->getContent();

        // La clave completa nunca debe aparecer en el HTML…
        $this->assertStringNotContainsString($fakeChatbot, $html);
        $this->assertStringNotContainsString($fakePretasa, $html);
        // …sino enmascarada.
        $this->assertStringContainsString('•', $html);
    }
}
