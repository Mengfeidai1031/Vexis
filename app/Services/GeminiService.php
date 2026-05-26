<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AiUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio centralizado para llamadas a Google Gemini.
 * Cada provider (chatbot, pretasacion) usa su propia API key separada.
 * Registra cada llamada en la tabla ai_usage.
 */
class GeminiService
{
    /**
     * Modelos a probar en orden (cae al siguiente si el primero falla con 404).
     */
    private const FALLBACK_MODELS = [
        ['version' => 'v1beta', 'model' => 'gemini-2.5-flash'],
        ['version' => 'v1beta', 'model' => 'gemini-2.0-flash'],
        ['version' => 'v1beta', 'model' => 'gemini-3-flash-preview'],
        ['version' => 'v1beta', 'model' => 'gemini-2.5-pro'],
    ];

    /**
     * Genera texto con el provider indicado.
     *
     * @param  string  $provider  "chatbot" | "pretasacion"
     * @param  array<string, mixed>  $generationConfig  e.g. ['temperature' => 0.7, 'maxOutputTokens' => 800]
     * @return array{ok: bool, text: string, model: ?string, error: ?string}
     */
    public function generate(string $provider, string $prompt, array $generationConfig = []): array
    {
        if (! in_array($provider, ['chatbot', 'pretasacion'], true)) {
            return ['ok' => false, 'text' => '', 'model' => null, 'error' => 'Provider inválido'];
        }

        // Setting toggle global
        if (! \App\Models\Setting::get("ai_{$provider}_enabled", true)) {
            return ['ok' => false, 'text' => '', 'model' => null, 'error' => "El servicio IA ({$provider}) está desactivado por el administrador."];
        }

        // Cuota mensual
        $quotaSetting = (int) \App\Models\Setting::get("ai_quota_{$provider}_mensual", 0);
        if ($quotaSetting > 0) {
            $usedThisMonth = AiUsage::where('provider', $provider)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count();
            if ($usedThisMonth >= $quotaSetting) {
                return ['ok' => false, 'text' => '', 'model' => null, 'error' => "Cuota mensual ({$quotaSetting}) de {$provider} alcanzada."];
            }
        }

        $apiKey = config("services.gemini.{$provider}.api_key");
        if (empty($apiKey)) {
            $this->log($provider, false, null, 0, 0, 'API key no configurada');

            return [
                'ok' => false,
                'text' => '',
                'model' => null,
                'error' => "API key de Gemini ({$provider}) no configurada. Revisa GEMINI_".strtoupper($provider).'_API_KEY en .env',
            ];
        }

        $generationConfig = array_merge(['temperature' => 0.7, 'maxOutputTokens' => 1024], $generationConfig);
        $lastError = null;
        $lastStatus = 0;

        foreach (self::FALLBACK_MODELS as $cfg) {
            $url = "https://generativelanguage.googleapis.com/{$cfg['version']}/models/{$cfg['model']}:generateContent?key=".urlencode($apiKey);
            try {
                $resp = Http::timeout(30)->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => $generationConfig,
                ]);
                $lastStatus = $resp->status();

                if ($resp->successful()) {
                    $data = $resp->json();
                    $text = $this->extractText($data);
                    $usage = $data['usageMetadata'] ?? [];

                    $this->log(
                        $provider, true, $resp->status(),
                        (int) ($usage['promptTokenCount'] ?? 0),
                        (int) ($usage['candidatesTokenCount'] ?? 0),
                        null,
                        $cfg['model'],
                    );

                    return ['ok' => true, 'text' => $text, 'model' => $cfg['model'], 'error' => null];
                }

                $err = $resp->json()['error']['message'] ?? 'HTTP '.$resp->status();
                $lastError = $err;

                if ($resp->status() !== 404) {
                    break;
                }
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::warning("Gemini {$provider} fallo modelo {$cfg['model']}: ".$e->getMessage());
            }
        }

        $this->log($provider, false, $lastStatus, 0, 0, $lastError);

        return [
            'ok' => false,
            'text' => '',
            'model' => null,
            'error' => $lastError ?? 'No se pudo conectar con Gemini',
        ];
    }

    private function extractText(array $data): string
    {
        $parts = $data['candidates'][0]['content']['parts'] ?? [];
        $text = '';
        foreach ($parts as $p) {
            if (is_string($p['text'] ?? null)) {
                $text .= $p['text'];
            }
        }

        return trim($text);
    }

    private function log(string $provider, bool $ok, ?int $status, int $tokensIn, int $tokensOut, ?string $error, ?string $model = null): void
    {
        try {
            AiUsage::create([
                'provider' => $provider,
                'user_id' => Auth::id(),
                'tokens_in' => $tokensIn,
                'tokens_out' => $tokensOut,
                'http_status' => $status,
                'ok' => $ok,
                'endpoint' => $model,
                'error' => $error,
            ]);
        } catch (\Throwable $e) {
            Log::error('No se pudo registrar AiUsage: '.$e->getMessage());
        }
    }
}
