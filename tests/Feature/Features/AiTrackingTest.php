<?php

namespace Tests\Feature\Features;

use App\Models\AiUsage;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiTrackingTest extends TestCase
{
    public function test_chatbot_disabled_setting_blocks_call(): void
    {
        \App\Models\Setting::set('ai_chatbot_enabled', false);
        $svc = app(GeminiService::class);
        $result = $svc->generate('chatbot', 'test', []);
        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('desactivado', $result['error']);
        \App\Models\Setting::set('ai_chatbot_enabled', true);
    }

    public function test_gemini_call_is_tracked_via_http_fake(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => 'OK fake']]]]],
                'usageMetadata' => ['promptTokenCount' => 5, 'candidatesTokenCount' => 2],
            ], 200),
        ]);

        $before = AiUsage::where('provider', 'chatbot')->count();
        $this->actingAsSuperAdmin();
        $svc = app(GeminiService::class);
        $svc->generate('chatbot', 'hola', ['maxOutputTokens' => 10]);
        $after = AiUsage::where('provider', 'chatbot')->count();

        $this->assertEquals($before + 1, $after);
        $last = AiUsage::latest()->first();
        $this->assertEquals(5, $last->tokens_in);
        $this->assertEquals(2, $last->tokens_out);
        $this->assertTrue($last->ok);
    }

    public function test_invalid_provider_returns_error(): void
    {
        $result = app(GeminiService::class)->generate('invalid', 'x');
        $this->assertFalse($result['ok']);
    }
}
