<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\CampaniaFoto;
use App\Models\CatalogoPrecio;
use App\Models\Marca;
use App\Models\Empresa;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClienteModuloController extends Controller
{
    public function inicio()
    {
        return view('cliente.inicio');
    }

    // === CAMPAÑAS (solo vista) ===
    public function campanias()
    {
        $campanias = Campania::with(['marca', 'fotos'])->where('activa', true)->orderByDesc('fecha_inicio')->get();
        return view('cliente.campanias', compact('campanias'));
    }

    // === CHATBOT ===
    public function chatbot()
    {
        return view('cliente.chatbot');
    }

    public function chatbotQuery(Request $request)
    {
        $request->validate(['mensaje' => 'required|string|max:500']);
        $mensaje = $request->mensaje;

        // Recopilar datos de la base de datos para contexto
        $vehiculos = Vehiculo::select('modelo', 'version', 'color_externo', DB::raw('COUNT(*) as total'))
            ->groupBy('modelo', 'version', 'color_externo')->get()->toArray();

        $catalogo = CatalogoPrecio::with('marca:id,nombre')
            ->where('disponible', true)->get()
            ->map(fn($c) => ['marca' => $c->marca->nombre ?? '', 'modelo' => $c->modelo, 'version' => $c->version, 'precio' => $c->precio_oferta ?? $c->precio_base, 'combustible' => $c->combustible, 'cv' => $c->potencia_cv])
            ->toArray();

        $marcas = Marca::where('activa', true)->pluck('nombre')->toArray();

        $empresas = Empresa::select('nombre', 'localidad', 'isla')->get()->toArray();

        $contexto = "Eres un asistente virtual de Grupo ARI, concesionario oficial de " . implode(', ', $marcas) . " en las Islas Canarias.\n\n";
        $contexto .= "DATOS DE VEHÍCULOS EN STOCK:\n" . json_encode($vehiculos, JSON_UNESCAPED_UNICODE) . "\n\n";
        $contexto .= "CATÁLOGO DE PRECIOS:\n" . json_encode($catalogo, JSON_UNESCAPED_UNICODE) . "\n\n";
        $contexto .= "CONCESIONARIOS:\n" . json_encode($empresas, JSON_UNESCAPED_UNICODE) . "\n\n";
        $contexto .= "Responde siempre en español, de forma amable y profesional. Si te preguntan por vehículos, busca en los datos proporcionados. Da información precisa sobre stock, precios y disponibilidad. Si no tienes la información, sugiere que contacten con el concesionario.";

        try {
            $apiKey = config('services.gemini.api_key');
            if (empty($apiKey)) {
                return response()->json(['respuesta' => 'Error: API key de Gemini no configurada. Añade GEMINI_API_KEY en el archivo .env']);
            }

            $response = Http::withoutVerifying()->timeout(30)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $contexto . "\n\nPregunta del cliente: " . $mensaje]]]
                ],
                'generationConfig' => ['temperature' => 0.7, 'maxOutputTokens' => 800],
            ]);

            if ($response->failed()) {
                $error = $response->json('error.message') ?? $response->body();
                return response()->json(['respuesta' => 'Error de la API: ' . Str::limit($error, 200)]);
            }

            $data = $response->json();
            $respuesta = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Lo siento, no pude procesar tu consulta. Inténtalo de nuevo.';
        } catch (\Exception $e) {
            $respuesta = 'Error al conectar con el asistente: ' . Str::limit($e->getMessage(), 150);
        }

        return response()->json(['respuesta' => $respuesta]);
    }

    // === PRETASACIÓN IA ===
    public function pretasacion()
    {
        return view('cliente.pretasacion');
    }

    public function pretasacionQuery(Request $request)
    {
        $request->validate([
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:150',
            'anio' => 'required|integer|min:1990|max:2030',
            'kilometraje' => 'required|integer|min:0',
            'combustible' => 'nullable|string|max:50',
            'estado' => 'nullable|string|max:50',
        ]);

        $prompt = "Eres un experto tasador de vehículos en España (Islas Canarias). Te pido una pretasación orientativa para:\n";
        $prompt .= "- Marca: {$request->marca}\n- Modelo: {$request->modelo}\n- Año: {$request->anio}\n";
        $prompt .= "- Kilometraje: {$request->kilometraje} km\n";
        if ($request->combustible) $prompt .= "- Combustible: {$request->combustible}\n";
        if ($request->estado) $prompt .= "- Estado general: {$request->estado}\n";
        $prompt .= "\nDa una estimación de precio en euros con un rango (mínimo-máximo). Explica brevemente los factores que afectan al valor. Responde en español, de forma profesional. Aclara que es una estimación orientativa y que para una tasación precisa deben acudir al concesionario Grupo ARI.";

        try {
            $apiKey = config('services.gemini.api_key');
            if (empty($apiKey)) {
                return response()->json(['respuesta' => 'Error: API key de Gemini no configurada. Añade GEMINI_API_KEY en el archivo .env']);
            }

            $response = Http::withoutVerifying()->timeout(30)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey, [
                'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
                'generationConfig' => ['temperature' => 0.5, 'maxOutputTokens' => 600],
            ]);

            if ($response->failed()) {
                $error = $response->json('error.message') ?? $response->body();
                return response()->json(['respuesta' => 'Error de la API: ' . Str::limit($error, 200)]);
            }

            $data = $response->json();
            $respuesta = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se pudo generar la pretasación.';
        } catch (\Exception $e) {
            $respuesta = 'Error al conectar con el servicio de tasación: ' . Str::limit($e->getMessage(), 150);
        }

        return response()->json(['respuesta' => $respuesta]);
    }

    // === CONCESIONARIOS ===
    public function concesionarios()
    {
        $centros = \App\Models\Centro::with('empresa')->orderBy('nombre')->get();
        return view('cliente.concesionarios', compact('centros'));
    }

    // === LISTA DE PRECIOS ===
    public function precios(Request $request)
    {
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $marcaSeleccionada = $request->filled('marca_id') ? $request->marca_id : ($marcas->first()->id ?? null);
        $catalogo = CatalogoPrecio::with('marca')
            ->where('disponible', true)
            ->when($marcaSeleccionada, fn($q) => $q->where('marca_id', $marcaSeleccionada))
            ->orderBy('modelo')->orderBy('precio_base')->get();
        return view('cliente.precios', compact('catalogo', 'marcas', 'marcaSeleccionada'));
    }

    // === CONFIGURADOR DE VEHÍCULOS ===
    public function configurador(Request $request)
    {
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $marcaId = $request->marca_id;
        $modelos = [];
        if ($marcaId) {
            $modelos = CatalogoPrecio::where('marca_id', $marcaId)
                ->where('disponible', true)
                ->select('modelo')->distinct()->orderBy('modelo')->pluck('modelo');
        }
        $modeloSeleccionado = $request->modelo;
        $versiones = [];
        if ($marcaId && $modeloSeleccionado) {
            $versiones = CatalogoPrecio::with('marca')
                ->where('marca_id', $marcaId)
                ->where('modelo', $modeloSeleccionado)
                ->where('disponible', true)
                ->orderBy('precio_base')->get();
        }
        return view('cliente.configurador', compact('marcas', 'marcaId', 'modelos', 'modeloSeleccionado', 'versiones'));
    }
}
