<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AiUsage;
use Illuminate\Http\JsonResponse;

class AiControlController extends Controller
{
    public function index()
    {
        $summary = AiUsage::summary();
        $apis = [
            'chatbot' => [
                'label' => 'Gemini Chatbot',
                'key_masked' => $this->maskKey(config('services.gemini.chatbot.api_key')),
                'project' => config('services.gemini.chatbot.project'),
            ],
            'pretasacion' => [
                'label' => 'Gemini Pretasación',
                'key_masked' => $this->maskKey(config('services.gemini.pretasacion.api_key')),
                'project' => config('services.gemini.pretasacion.project'),
            ],
        ];

        return view('ai.control', [
            'summary' => $summary,
            'apis' => $apis,
            'limits' => $this->limits(),
        ]);
    }

    public function summary(): JsonResponse
    {
        return response()->json([
            'summary' => AiUsage::summary(),
            'limits' => $this->limits(),
        ]);
    }

    /**
     * Límites del plan gratuito de Google (referencia) + coste.
     *
     * @return array{rpm:int, rpd:int, tpm:int, cost:string, reset:string}
     */
    private function limits(): array
    {
        return [
            'rpm' => (int) config('services.gemini.free_tier.rpm', 15),
            'rpd' => (int) config('services.gemini.free_tier.rpd', 1500),
            'tpm' => (int) config('services.gemini.free_tier.tpm', 1000000),
            'cost' => '0 € (plan gratuito, sin facturación)',
            'reset' => 'Límite diario: se reinicia a medianoche (hora del Pacífico)',
        ];
    }

    private function maskKey(?string $key): string
    {
        if (! $key) {
            return '— sin configurar —';
        }

        return substr($key, 0, 6).str_repeat('•', max(0, strlen($key) - 10)).substr($key, -4);
    }
}
