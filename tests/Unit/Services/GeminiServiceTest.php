<?php

namespace Tests\Unit\Services;

use App\Models\AiUsage;
use App\Models\Setting;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiServiceTest extends TestCase
{
    public function test_invalid_provider_returns_error_without_http_call(): void
    {
        Http::fake();
        $result = app(GeminiService::class)->generate('invalid', 'x');
        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('inválido', $result['error']);
        Http::assertNothingSent();
    }

    public function test_disabled_setting_blocks_call(): void
    {
        Setting::set('ai_chatbot_enabled', false);
        Http::fake();
        $result = app(GeminiService::class)->generate('chatbot', 'hola');
        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('desactivado', $result['error']);
        Http::assertNothingSent();
        Setting::set('ai_chatbot_enabled', true);
    }

    public function test_successful_call_returns_text_and_tracks_usage(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => 'Respuesta IA']]]]],
                'usageMetadata' => ['promptTokenCount' => 12, 'candidatesTokenCount' => 5],
            ], 200),
        ]);

        $before = AiUsage::where('provider', 'chatbot')->count();
        $result = app(GeminiService::class)->generate('chatbot', 'test');

        $this->assertTrue($result['ok']);
        $this->assertEquals('Respuesta IA', $result['text']);
        $this->assertEquals('gemini-2.5-flash', $result['model']);
        $this->assertEquals($before + 1, AiUsage::where('provider', 'chatbot')->count());

        $last = AiUsage::where('provider', 'chatbot')->latest('id')->first();
        $this->assertEquals(12, $last->tokens_in);
        $this->assertEquals(5, $last->tokens_out);
        $this->assertTrue($last->ok);
    }

    public function test_fallback_to_next_model_on_404(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:*' => Http::response([
                'error' => ['message' => 'Model not found'],
            ], 404),
            'generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => 'Fallback OK']]]]],
                'usageMetadata' => ['promptTokenCount' => 3, 'candidatesTokenCount' => 1],
            ], 200),
        ]);

        $result = app(GeminiService::class)->generate('chatbot', 'test');
        $this->assertTrue($result['ok']);
        $this->assertEquals('gemini-2.0-flash', $result['model']);
    }

    public function test_quota_limit_blocks_call(): void
    {
        Setting::set('ai_quota_chatbot_mensual', 1);
        AiUsage::create([
            'provider' => 'chatbot', 'user_id' => null,
            'tokens_in' => 0, 'tokens_out' => 0, 'ok' => true,
        ]);
        Http::fake();

        $result = app(GeminiService::class)->generate('chatbot', 'x');
        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('Cuota', $result['error']);

        Setting::set('ai_quota_chatbot_mensual', 1000);
    }
}
