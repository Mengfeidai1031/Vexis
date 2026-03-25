<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\CampaniaFoto;
use App\Models\CatalogoPrecio;
use App\Models\Centro;
use App\Models\Cliente;
use App\Models\Marca;
use App\Models\Empresa;
use App\Models\Tasacion;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $mensaje = trim($request->mensaje);
        /** @var User $user */
        $user = Auth::user();

        // Rechazo explícito para consultas de datos restringidos.
        $restrictedRequest = $this->detectRestrictedRequestWithoutPermission($mensaje, $user);
        if ($restrictedRequest !== null) {
            return response()->json([
                'respuesta' => "No tienes permisos suficientes para consultar {$restrictedRequest}. " .
                    "Puedes preguntarme por los datos autorizados para tu usuario. " .
                    "Si quieres, pregúntame: \"¿Qué permisos tengo?\" o \"¿Qué tablas puedo consultar?\"."
            ]);
        }

        $allowedTables = [];
        $contextBlocks = [];

        // Datos públicos del asistente (siempre disponibles en módulo cliente).
        $marcas = Marca::where('activa', true)->pluck('nombre')->toArray();
        $catalogo = CatalogoPrecio::with('marca:id,nombre')
            ->where('disponible', true)
            ->limit(300)
            ->get()
            ->map(fn($c) => [
                'marca' => $c->marca->nombre ?? '',
                'modelo' => $c->modelo,
                'version' => $c->version,
                'precio' => $c->precio_oferta ?? $c->precio_base,
                'combustible' => $c->combustible,
                'cv' => $c->potencia_cv
            ])
            ->toArray();
        $concesionarios = Empresa::with('centros:id,empresa_id,nombre,direccion,provincia,municipio')
            ->select('id', 'nombre', 'domicilio', 'telefono')
            ->get()
            ->map(function ($empresa) {
                return [
                    'nombre' => $empresa->nombre,
                    'domicilio' => $empresa->domicilio,
                    'telefono' => $empresa->telefono,
                    'centros' => $empresa->centros->map(fn($centro) => [
                        'nombre' => $centro->nombre,
                        'direccion' => $centro->direccion,
                        'provincia' => $centro->provincia,
                        'municipio' => $centro->municipio,
                    ])->toArray(),
                ];
            })
            ->toArray();
        $stockResumen = Vehiculo::select('modelo', 'version', DB::raw('COUNT(*) as total'))
            ->groupBy('modelo', 'version')
            ->limit(250)
            ->get()
            ->toArray();

        $allowedTables[] = 'catalogo_precios_publico';
        $allowedTables[] = 'concesionarios';
        $allowedTables[] = 'stock_vehiculos_publico';

        $contextBlocks[] = "CATALOGO_PRECIOS_PUBLICO:\n" . json_encode($catalogo, JSON_UNESCAPED_UNICODE);
        $contextBlocks[] = "CONCESIONARIOS:\n" . json_encode($concesionarios, JSON_UNESCAPED_UNICODE);
        $contextBlocks[] = "STOCK_VEHICULOS_PUBLICO:\n" . json_encode($stockResumen, JSON_UNESCAPED_UNICODE);

        // Tablas sensibles según permisos del usuario.
        if ($user->can('ver usuarios')) {
            $allowedTables[] = 'usuarios';
            $contextBlocks[] = "USUARIOS:\n" . json_encode(
                User::select('id', 'nombre', 'apellidos', 'email', 'empresa_id', 'departamento_id', 'centro_id')
                    ->limit(150)
                    ->get()
                    ->toArray(),
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($user->can('ver clientes')) {
            $allowedTables[] = 'clientes';
            $contextBlocks[] = "CLIENTES:\n" . json_encode(
                Cliente::select('id', 'nombre', 'apellidos', 'email', 'telefono', 'empresa_id')
                    ->limit(250)
                    ->get()
                    ->toArray(),
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($user->can('ver tasaciones')) {
            $allowedTables[] = 'tasaciones';
            $contextBlocks[] = "TASACIONES:\n" . json_encode(
                Tasacion::select(
                    'id',
                    'codigo_tasacion',
                    'vehiculo_marca',
                    'vehiculo_modelo',
                    'vehiculo_anio',
                    'valor_estimado',
                    'valor_final',
                    'estado',
                    'fecha_tasacion'
                )->limit(250)->get()->toArray(),
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($user->can('ver stocks')) {
            $allowedTables[] = 'stocks';
            $contextBlocks[] = "STOCKS:\n" . json_encode(
                \App\Models\Stock::select('id', 'referencia', 'nombre_pieza', 'cantidad', 'stock_minimo', 'almacen_id', 'empresa_id')
                    ->limit(300)
                    ->get()
                    ->toArray(),
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($user->can('ver ventas')) {
            $allowedTables[] = 'ventas';
            $contextBlocks[] = "VENTAS:\n" . json_encode(
                \App\Models\Venta::select('id', 'codigo_venta', 'precio_final', 'estado', 'fecha_venta', 'cliente_id', 'marca_id')
                    ->limit(250)
                    ->get()
                    ->toArray(),
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($user->can('ver vehículos')) {
            $allowedTables[] = 'vehiculos';
            $contextBlocks[] = "VEHICULOS:\n" . json_encode(
                Vehiculo::select('id', 'chasis', 'modelo', 'version', 'color_externo', 'empresa_id')
                    ->limit(300)
                    ->get()
                    ->toArray(),
                JSON_UNESCAPED_UNICODE
            );
        }

        $allowedTablesText = implode(', ', $allowedTables);

        if ($this->isPermissionsQuestion($mensaje)) {
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();
            return response()->json([
                'respuesta' => "Tus permisos actuales son: " . (count($permissions) ? implode(', ', $permissions) : 'sin permisos administrativos') .
                    ".\n\nTablas que puedo consultar para ti: {$allowedTablesText}.\n\n" .
                    "Puedes preguntarme por datos y resúmenes de esas tablas, y si pides otra tabla te indicaré que no tienes permisos suficientes."
            ]);
        }

        $contexto = "Eres un asistente virtual de Grupo ARI.\n";
        $contexto .= "Usuario actual: {$user->nombre_completo} ({$user->email}).\n";
        $contexto .= "Permisos del usuario: " . implode(', ', $user->getAllPermissions()->pluck('name')->toArray()) . ".\n";
        $contexto .= "TABLAS AUTORIZADAS PARA ESTE USUARIO: {$allowedTablesText}.\n\n";
        $contexto .= "REGLAS OBLIGATORIAS:\n";
        $contexto .= "1) Solo responde usando información de TABLAS AUTORIZADAS.\n";
        $contexto .= "2) Si pregunta por una tabla no autorizada, responde que no tiene permisos suficientes.\n";
        $contexto .= "3) Si pregunta por permisos o qué puede consultar, responde listando TABLAS AUTORIZADAS y ejemplos.\n";
        $contexto .= "4) Responde siempre en español, tono profesional y claro.\n\n";
        $contexto .= "MARCAS OFICIALES: " . implode(', ', $marcas) . "\n\n";
        $contexto .= implode("\n\n", $contextBlocks);

        try {
            $apiKey = config('services.gemini.api_key');
            if (empty($apiKey)) {
                return response()->json(['respuesta' => 'Error: API key de Gemini no configurada. Añade GEMINI_API_KEY en el archivo .env']);
            }

            // Usar modelos disponibles según la API v1beta
            $combinations = [
                ['version' => 'v1beta', 'model' => 'gemini-2.5-flash'],
                ['version' => 'v1beta', 'model' => 'gemini-3-flash-preview'],
                ['version' => 'v1beta', 'model' => 'gemini-2.0-flash'],
                ['version' => 'v1beta', 'model' => 'gemini-2.5-pro'],
            ];
            
            $response = null;
            $workingConfig = null;
            $lastError = null;
            
            foreach ($combinations as $config) {
                $url = "https://generativelanguage.googleapis.com/{$config['version']}/models/{$config['model']}:generateContent?key=" . urlencode($apiKey);
                try {
                    $testResponse = Http::timeout(30)->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $contexto . "\n\nPregunta del cliente: " . $mensaje]
                                ]
                            ]
                        ],
                        'generationConfig' => ['temperature' => 0.7, 'maxOutputTokens' => 800],
                    ]);
                    
                    if ($testResponse->successful()) {
                        $response = $testResponse;
                        $workingConfig = $config;
                        break;
                    } else {
                        $errorData = $testResponse->json();
                        $lastError = $errorData['error']['message'] ?? "HTTP " . $testResponse->status();
                        // Si es 404, continuar con el siguiente
                        if ($testResponse->status() === 404) {
                            continue;
                        }
                    }
                } catch (\Exception $e) {
                    $lastError = $e->getMessage();
                    continue;
                }
            }
            
            if (!$response) {
                return response()->json([
                    'respuesta' => 'Error: No se pudo conectar con ningún modelo de Gemini disponible. Último error: ' . Str::limit($lastError ?? 'Desconocido', 200) . "\n\nPor favor, verifica tu API key y conexión a internet."
                ]);
            }

            $data = $response->json();
            $respuesta = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Lo siento, no pude procesar tu consulta. Inténtalo de nuevo.';
            
            // Añadir información del modelo que funcionó
            if ($workingConfig) {
                $respuesta .= "\n\n[INFO: Modelo funcionando - API: {$workingConfig['version']}, Modelo: {$workingConfig['model']}]";
            }
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
        $prompt .= "\nDevuelve SIEMPRE una respuesta completa y cerrada en español con este formato:";
        $prompt .= "\n1) Rango estimado en euros (mínimo-máximo).";
        $prompt .= "\n2) 3 factores clave que influyen en el valor.";
        $prompt .= "\n3) Cierre con aclaración: es orientativo y la tasación formal se solicita en concesionario.";
        $prompt .= "\nNo dejes frases a medias ni respuestas incompletas.";

        try {
            $apiKey = config('services.gemini.api_key');
            if (empty($apiKey)) {
                return response()->json(['respuesta' => 'Error: API key de Gemini no configurada. Añade GEMINI_API_KEY en el archivo .env']);
            }

            // Usar modelos disponibles según la API v1beta
            $combinations = [
                ['version' => 'v1beta', 'model' => 'gemini-2.5-flash'],
                ['version' => 'v1beta', 'model' => 'gemini-3-flash-preview'],
                ['version' => 'v1beta', 'model' => 'gemini-2.0-flash'],
                ['version' => 'v1beta', 'model' => 'gemini-2.5-pro'],
            ];
            
            $response = null;
            $workingConfig = null;
            $lastError = null;
            
            foreach ($combinations as $config) {
                $url = "https://generativelanguage.googleapis.com/{$config['version']}/models/{$config['model']}:generateContent?key=" . urlencode($apiKey);
                try {
                    $testResponse = Http::timeout(30)->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ],
                        'generationConfig' => ['temperature' => 0.45, 'maxOutputTokens' => 1800],
                    ]);
                    
                    if ($testResponse->successful()) {
                        $response = $testResponse;
                        $workingConfig = $config;
                        break;
                    } else {
                        $errorData = $testResponse->json();
                        $lastError = $errorData['error']['message'] ?? "HTTP " . $testResponse->status();
                        // Si es 404, continuar con el siguiente
                        if ($testResponse->status() === 404) {
                            continue;
                        }
                    }
                } catch (\Exception $e) {
                    $lastError = $e->getMessage();
                    continue;
                }
            }
            
            if (!$response) {
                return response()->json([
                    'respuesta' => 'Error: No se pudo conectar con ningún modelo de Gemini disponible. Último error: ' . Str::limit($lastError ?? 'Desconocido', 200) . "\n\nPor favor, verifica tu API key y conexión a internet."
                ]);
            }

            $data = $response->json();
            $respuesta = $this->extractGeminiText($data);
            $finishReason = Str::upper((string) data_get($data, 'candidates.0.finishReason', ''));

            if (
                $workingConfig &&
                (
                    $finishReason === 'MAX_TOKENS' ||
                    $this->seemsIncompleteResponse($respuesta)
                )
            ) {
                $retryPrompt = $prompt .
                    "\n\nLa respuesta anterior quedó incompleta. Reescribe TODA la respuesta completa desde cero, " .
                    "siguiendo exactamente el formato solicitado y sin cortar ninguna frase.";

                $retryUrl = "https://generativelanguage.googleapis.com/{$workingConfig['version']}/models/{$workingConfig['model']}:generateContent?key=" . urlencode($apiKey);
                $retryResponse = Http::timeout(30)->post($retryUrl, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $retryPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => ['temperature' => 0.35, 'maxOutputTokens' => 2200],
                ]);

                if ($retryResponse->successful()) {
                    $retryText = $this->extractGeminiText($retryResponse->json());
                    if (!empty($retryText) && !$this->seemsIncompleteResponse($retryText)) {
                        $respuesta = $retryText;
                    }
                }
            }

            if ($respuesta === '') {
                $respuesta = 'No se pudo generar la pretasación.';
            }
        } catch (\Exception $e) {
            $respuesta = 'Error al conectar con el servicio de tasación: ' . Str::limit($e->getMessage(), 150);
        }

        return response()->json(['respuesta' => $respuesta]);
    }

    // === CONCESIONARIOS ===
    public function concesionarios()
    {
        $centros = Centro::with('empresa')
            ->orderBy('nombre')
            ->get();

        return view('cliente.concesionarios', compact('centros'));
    }

    public function tasacion()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();

        /** @var User $user */
        $user = Auth::user();
        $cliente = Cliente::where('email', $user->email)->first();
        $solicitudes = collect();
        if ($cliente) {
            $solicitudes = Tasacion::where('cliente_id', $cliente->id)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        }

        return view('cliente.tasacion', compact('empresas', 'marcas', 'solicitudes'));
    }

    public function tasacionStore(Request $request)
    {
        $request->validate([
            'vehiculo_marca' => 'required|string|max:100',
            'vehiculo_modelo' => 'required|string|max:150',
            'vehiculo_anio' => 'required|integer|min:1990|max:2030',
            'kilometraje' => 'required|integer|min:0',
            'matricula' => 'nullable|string|max:12',
            'combustible' => 'nullable|string|max:50',
            'estado_vehiculo' => 'required|in:excelente,bueno,regular,malo',
            'empresa_id' => 'required|exists:empresas,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'observaciones' => 'nullable|string|max:1200',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $cliente = Cliente::firstOrCreate(
            ['email' => $user->email],
            [
                'nombre' => $user->nombre,
                'apellidos' => $user->apellidos,
                'telefono' => $user->telefono,
                'empresa_id' => $request->empresa_id,
            ]
        );

        if (!$cliente->empresa_id) {
            $cliente->empresa_id = $request->empresa_id;
            $cliente->save();
        }

        $codigo = $this->generateTasacionCode();

        Tasacion::create([
            'codigo_tasacion' => $codigo,
            'cliente_id' => $cliente->id,
            'empresa_id' => $request->empresa_id,
            'marca_id' => $request->marca_id,
            'tasador_id' => null,
            'vehiculo_marca' => $request->vehiculo_marca,
            'vehiculo_modelo' => $request->vehiculo_modelo,
            'vehiculo_anio' => $request->vehiculo_anio,
            'kilometraje' => $request->kilometraje,
            'matricula' => $request->matricula ? strtoupper($request->matricula) : null,
            'combustible' => $request->combustible,
            'estado_vehiculo' => $request->estado_vehiculo,
            'valor_estimado' => null,
            'valor_final' => null,
            'estado' => 'pendiente',
            'observaciones' => $request->observaciones,
            'fecha_tasacion' => now()->toDateString(),
        ]);

        return redirect()
            ->route('cliente.tasacion')
            ->with('success', 'Solicitud de tasación enviada correctamente. El equipo la revisará y actualizará su estado.');
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

    private function detectRestrictedRequestWithoutPermission(string $message, User $user): ?string
    {
        $checks = [
            ['label' => 'usuarios', 'permission' => 'ver usuarios', 'keywords' => ['usuario', 'usuarios', 'empleados', 'staff']],
            ['label' => 'clientes internos', 'permission' => 'ver clientes', 'keywords' => ['clientes internos', 'tabla clientes', 'clientes registrados']],
            ['label' => 'tasaciones', 'permission' => 'ver tasaciones', 'keywords' => ['tasacion', 'tasaciones']],
            ['label' => 'ventas', 'permission' => 'ver ventas', 'keywords' => ['venta', 'ventas', 'facturacion']],
            ['label' => 'stocks', 'permission' => 'ver stocks', 'keywords' => ['stock interno', 'stocks', 'inventario']],
            ['label' => 'vehículos internos', 'permission' => 'ver vehículos', 'keywords' => ['chasis', 'vehiculos internos']],
        ];

        $normalizedMessage = Str::lower($message);
        foreach ($checks as $check) {
            foreach ($check['keywords'] as $keyword) {
                if (Str::contains($normalizedMessage, $keyword) && !$user->can($check['permission'])) {
                    return $check['label'];
                }
            }
        }

        return null;
    }

    private function generateTasacionCode(): string
    {
        $year = now()->year;
        $sequence = Tasacion::whereYear('fecha_tasacion', $year)->count() + 1;
        return 'TAS-' . now()->format('Ym') . '-' . str_pad((string)$sequence, 4, '0', STR_PAD_LEFT);
    }

    private function extractGeminiText(array $payload): string
    {
        $parts = data_get($payload, 'candidates.0.content.parts', []);
        if (!is_array($parts) || count($parts) === 0) {
            return '';
        }

        $text = '';
        foreach ($parts as $part) {
            $chunk = isset($part['text']) ? (string) $part['text'] : '';
            if ($chunk !== '') {
                $text .= ($text === '' ? '' : "\n") . $chunk;
            }
        }

        return trim($text);
    }

    private function seemsIncompleteResponse(string $text): bool
    {
        $trimmed = trim($text);
        if ($trimmed === '') {
            return true;
        }

        // Markdown sin cerrar (ej: "**13.") o cierre abrupto.
        if (substr_count($trimmed, '**') % 2 !== 0) {
            return true;
        }

        $lastChar = mb_substr($trimmed, -1);
        if (!in_array($lastChar, ['.', '!', '?', '€', ')'])) {
            return true;
        }

        return false;
    }

    private function isPermissionsQuestion(string $message): bool
    {
        $normalized = Str::lower($message);
        return Str::contains($normalized, [
            'que permisos tengo',
            'qué permisos tengo',
            'mis permisos',
            'que puedo preguntar',
            'qué puedo preguntar',
            'que tablas puedo consultar',
            'qué tablas puedo consultar',
            'tablas autorizadas',
        ]);
    }
}
