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
        $reset_at = now()->addMonthNoOverflow()->startOfMonth()->format('d/m/Y H:i');

        return view('ai.control', compact('summary', 'apis', 'reset_at'));
    }

    public function summary(): JsonResponse
    {
        return response()->json([
            'summary' => AiUsage::summary(),
            'reset_at' => now()->addMonthNoOverflow()->startOfMonth()->format('d/m/Y H:i'),
        ]);
    }

    private function maskKey(?string $key): string
    {
        if (! $key) {
            return '— sin configurar —';
        }

        return substr($key, 0, 6).str_repeat('•', max(0, strlen($key) - 10)).substr($key, -4);
    }
}
