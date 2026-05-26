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
            ->assertJsonStructure(['summary', 'reset_at']);
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
        $this->actingAsSuperAdmin();
        $html = $this->get('/ai/control')->getContent();
        // Las keys reales nunca aparecen completas
        $this->assertStringNotContainsString('AIzaSyBFSntRXUGKC7GQ96IYzh8sIm2cN1DmfXw', $html);
        $this->assertStringNotContainsString('AIzaSyBl2sA1ogPpmF_Un1iqJKBgKJp4NWNnWNU', $html);
        $this->assertStringContainsString('•', $html); // máscara
    }
}
