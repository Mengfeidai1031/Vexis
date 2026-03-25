<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\CatalogoPrecio;
use App\Models\Marca;
use App\Models\Empresa;
use App\Models\Vehiculo;
use App\Models\Tasacion;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClienteModuloController extends Controller
{
    private $geminiModel = 'gemini-2.5-flash';

    public function inicio()
    {
        return view('cliente.inicio');
    }

    // === CAMPAÑAS ===
    public function campanias()
    {
        $campanias = Campania::with(['marca', 'fotos'])->where('activa', true)->orderByDesc('fecha_inicio')->get();
        return view('cliente.campanias', compact('campanias'));
    }

    // === CHATBOT CON PERMISOS ===
    public function chatbot()
    {
        return view('cliente.chatbot');
    }

    public function chatbotQuery(Request $request)
    {
        $request->validate(['mensaje' => 'required|string|max:500']);
        $mensaje = $request->mensaje;
        $user = Auth::user();

        // Contexto base
        $marcas = Marca::where('activa', true)->pluck('nombre')->toArray();
        $contexto = "Eres VEXIS AI, el asistente virtual inteligente de Grupo ARI, concesionario oficial de " . implode(', ', $marcas) . " en las Islas Canarias.\n\n";

        // Datos según permisos del usuario
        $permisos = [];

        // Catálogo y stock — siempre visible
        $catalogo = CatalogoPrecio::with('marca:id,nombre')
            ->where('disponible', true)->get()
            ->map(fn($c) => ['marca' => $c->marca->nombre ?? '', 'modelo' => $c->modelo, 'version' => $c->version, 'precio' => $c->precio_oferta ?? $c->precio_base, 'combustible' => $c->combustible, 'cv' => $c->potencia_cv])
            ->toArray();
        $contexto .= "CATÁLOGO DE PRECIOS:\n" . json_encode($catalogo, JSON_UNESCAPED_UNICODE) . "\n\n";

        $vehiculos = Vehiculo::select('modelo', 'version', 'color_externo', DB::raw('COUNT(*) as total'))
            ->groupBy('modelo', 'version', 'color_externo')->get()->toArray();
        $contexto .= "VEHÍCULOS EN STOCK:\n" . json_encode($vehiculos, JSON_UNESCAPED_UNICODE) . "\n\n";

        $empresas = Empresa::select('nombre', 'localidad', 'isla')->get()->toArray();
        $contexto .= "CONCESIONARIOS:\n" . json_encode($empresas, JSON_UNESCAPED_UNICODE) . "\n\n";

        $permisos[] = 'catálogo, stock de vehículos, concesionarios';

        // Permisos avanzados según rol
        if ($user) {
            if ($user->hasRole('SuperAdmin') || $user->hasRole('Admin')) {
                // SuperAdmin/Admin: acceso a datos de usuarios, clientes, ventas, tasaciones
                $usuarios = User::select('nombre', 'apellidos', 'email', 'empresa_id')->limit(50)->get()->toArray();
                $contexto .= "USUARIOS DEL SISTEMA:\n" . json_encode($usuarios, JSON_UNESCAPED_UNICODE) . "\n\n";
                $permisos[] = 'usuarios';

                $clientes = Cliente::select('nombre', 'apellidos', 'email', 'telefono')->limit(50)->get()->toArray();
                $contexto .= "CLIENTES:\n" . json_encode($clientes, JSON_UNESCAPED_UNICODE) . "\n\n";
                $permisos[] = 'clientes';

                $ventas = DB::table('ventas')->select('codigo_venta', 'precio_final', 'estado', 'forma_pago', 'fecha_venta')->orderByDesc('fecha_venta')->limit(20)->get()->toArray();
                $contexto .= "ÚLTIMAS VENTAS:\n" . json_encode($ventas, JSON_UNESCAPED_UNICODE) . "\n\n";
                $permisos[] = 'ventas';

                $tasaciones = DB::table('tasaciones')->select('codigo_tasacion', 'vehiculo_marca', 'vehiculo_modelo', 'valor_estimado', 'estado')->orderByDesc('id')->limit(15)->get()->toArray();
                $contexto .= "TASACIONES:\n" . json_encode($tasaciones, JSON_UNESCAPED_UNICODE) . "\n\n";
                $permisos[] = 'tasaciones';
            } elseif ($user->hasRole('Comercial')) {
                // Comercial: acceso a clientes, ventas
                $clientes = Cliente::select('nombre', 'apellidos', 'email', 'telefono')->limit(50)->get()->toArray();
                $contexto .= "CLIENTES:\n" . json_encode($clientes, JSON_UNESCAPED_UNICODE) . "\n\n";
                $permisos[] = 'clientes';

                $ventas = DB::table('ventas')->select('codigo_venta', 'precio_final', 'estado', 'fecha_venta')->orderByDesc('fecha_venta')->limit(15)->get()->toArray();
                $contexto .= "VENTAS RECIENTES:\n" . json_encode($ventas, JSON_UNESCAPED_UNICODE) . "\n\n";
                $permisos[] = 'ventas';
            }
            // Consultor y otros: solo catálogo y stock (ya incluido arriba)
        }

        $rolUsuario = $user ? ($user->roles->first()->name ?? 'Consultor') : 'visitante';
        $contexto .= "INSTRUCCIONES:\n";
        $contexto .= "- El usuario actual tiene rol: {$rolUsuario}\n";
        $contexto .= "- Tiene acceso a: " . implode(', ', $permisos) . "\n";
        $contexto .= "- Si pregunta por datos a los que NO tiene acceso (por ejemplo un cliente preguntando por usuarios o ventas internas), responde amablemente que no tiene permisos suficientes para esa consulta.\n";
        $contexto .= "- Responde siempre en español, de forma amable y profesional.\n";
        $contexto .= "- Si preguntan por vehículos, busca en los datos proporcionados y da info precisa.\n";
        $contexto .= "- Si no tienes la información, sugiere contactar con el concesionario.\n";
        $contexto .= "- Responde de forma completa pero concisa.\n";

        return $this->callGemini($contexto . "\n\nPregunta del usuario: " . $mensaje, 2048, 0.7);
    }

    // === PRETASACIÓN IA (directa, sin consultar BD) ===
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

        $prompt = "Eres un experto en el mercado de vehículos de segunda mano en España, concretamente en las Islas Canarias.\n\n";
        $prompt .= "Un cliente quiere saber cuánto podría valer su vehículo:\n";
        $prompt .= "- Marca: {$request->marca}\n- Modelo: {$request->modelo}\n- Año: {$request->anio}\n";
        $prompt .= "- Kilometraje: " . number_format($request->kilometraje, 0, '', '.') . " km\n";
        if ($request->combustible) $prompt .= "- Combustible: {$request->combustible}\n";
        if ($request->estado) $prompt .= "- Estado general: {$request->estado}\n";
        $prompt .= "\nResponde con:\n1. Un rango estimado de precio en euros (mínimo — máximo)\n";
        $prompt .= "2. Breve explicación de los factores que influyen en el precio\n";
        $prompt .= "3. Aclara que es una estimación orientativa basada en tu conocimiento del mercado\n";
        $prompt .= "4. Recomienda solicitar una tasación formal en Grupo ARI para un valor preciso\n";
        $prompt .= "\nResponde en español, de forma profesional y completa.";

        return $this->callGemini($prompt, 1500, 0.5);
    }

    // === TASACIÓN FORMAL (solicitud cliente) ===
    public function tasacionForm()
    {
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('cliente.tasacion', compact('marcas'));
    }

    public function tasacionStore(Request $request)
    {
        $request->validate([
            'vehiculo_marca' => 'required|string|max:100',
            'vehiculo_modelo' => 'required|string|max:150',
            'vehiculo_anio' => 'required|integer|min:1990|max:2030',
            'kilometraje' => 'required|integer|min:0',
            'matricula' => 'nullable|string|max:15',
            'combustible' => 'nullable|string|max:50',
            'estado_vehiculo' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        // Buscar o crear cliente asociado al usuario
        $clienteId = null;
        if ($user) {
            $cliente = Cliente::where('email', $user->email)->first();
            if ($cliente) $clienteId = $cliente->id;
        }

        $empresa = Empresa::first();
        $codigo = 'TAS-' . date('Ym') . '-' . str_pad(Tasacion::count() + 1, 4, '0', STR_PAD_LEFT);

        Tasacion::create([
            'codigo_tasacion' => $codigo,
            'cliente_id' => $clienteId,
            'empresa_id' => $empresa?->id,
            'vehiculo_marca' => $request->vehiculo_marca,
            'vehiculo_modelo' => $request->vehiculo_modelo,
            'vehiculo_anio' => $request->vehiculo_anio,
            'kilometraje' => $request->kilometraje,
            'matricula' => $request->matricula,
            'combustible' => $request->combustible,
            'estado_vehiculo' => $request->estado_vehiculo,
            'observaciones' => $request->observaciones,
            'estado' => 'pendiente',
            'fecha_tasacion' => now(),
        ]);

        return redirect()->route('cliente.tasacion.form')
            ->with('success', "Solicitud de tasación registrada con código {$codigo}. Nos pondremos en contacto contigo para concertar la cita.");
    }

    // === CONCESIONARIOS ===
    public function concesionarios()
    {
        $centros = Centro::with('empresa')->orderBy('nombre')->get();
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

    // === CONFIGURADOR ===
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

    // === HELPER: Llamar Gemini API ===
    private function callGemini(string $prompt, int $maxTokens = 2048, float $temperature = 0.7)
    {
        try {
            $apiKey = config('services.gemini.api_key');
            if (empty($apiKey)) {
                return response()->json(['respuesta' => 'Error: API key de Gemini no configurada. Añade GEMINI_API_KEY en el archivo .env']);
            }

            $response = Http::withoutVerifying()->timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$this->geminiModel}:generateContent?key={$apiKey}",
                [
                    'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature' => $temperature,
                        'maxOutputTokens' => $maxTokens,
                        'topP' => 0.95,
                        'topK' => 64,
                    ],
                ]
            );

            if ($response->failed()) {
                $error = $response->json('error.message') ?? $response->body();
                return response()->json(['respuesta' => 'Error de la API (' . $response->status() . '): ' . Str::limit($error, 300)]);
            }

            $data = $response->json();

            // Extraer texto de la respuesta
            $text = '';
            $candidates = $data['candidates'] ?? [];
            if (!empty($candidates)) {
                $parts = $candidates[0]['content']['parts'] ?? [];
                foreach ($parts as $part) {
                    if (isset($part['text'])) $text .= $part['text'];
                }
            }

            if (empty($text)) {
                $text = 'No se pudo obtener una respuesta. Inténtalo de nuevo.';
            }

            return response()->json(['respuesta' => $text]);
        } catch (\Exception $e) {
            return response()->json(['respuesta' => 'Error de conexión: ' . Str::limit($e->getMessage(), 200)]);
        }
    }
}
