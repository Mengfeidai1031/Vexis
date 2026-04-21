<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Vehiculo;
use App\Models\VehiculoDocumento;
use App\Services\VehiculoEstadoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehiculoDocumentoGeneradorController extends Controller
{
    private const TIPOS_VALIDOS = ['ficha_tecnica', 'itv', 'permiso_circulacion', 'seguro', 'contrato'];

    public function __construct(
        private readonly VehiculoEstadoService $estadoService,
    ) {}

    /**
     * Hub general desde Gestión de Vehículos: selector de tipo + vehículo.
     */
    public function hub()
    {
        abort_unless(Auth::user()?->can('subir documentos vehiculos'), 403);

        $vehiculos = Vehiculo::with(['marca', 'empresa'])->orderBy('matricula')->get();
        $tipos = collect(VehiculoDocumento::$tipos)->except('otro');

        return view('vehiculos.documentos.hub', compact('vehiculos', 'tipos'));
    }

    public function form(Vehiculo $vehiculo, string $tipo)
    {
        $this->authorize('update', $vehiculo);
        abort_unless(in_array($tipo, self::TIPOS_VALIDOS, true), 404);

        $vehiculo->load(['marca', 'empresa']);
        $clientes = Cliente::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $titulo = VehiculoDocumento::$tipos[$tipo];

        return view('vehiculos.documentos.form', compact('vehiculo', 'tipo', 'titulo', 'clientes', 'empresas'));
    }

    public function generate(Request $request, Vehiculo $vehiculo, string $tipo): RedirectResponse|Response
    {
        $this->authorize('update', $vehiculo);
        abort_unless(in_array($tipo, self::TIPOS_VALIDOS, true), 404);

        $rules = $this->reglasPorTipo($tipo);
        $datos = $request->validate($rules);
        $soloPdf = $request->boolean('solo_pdf');

        $vehiculo->load(['marca', 'empresa']);
        $titulo = VehiculoDocumento::$tipos[$tipo];

        $cliente = ! empty($datos['cliente_id']) ? Cliente::find($datos['cliente_id']) : null;
        $empresa = ! empty($datos['empresa_id']) ? Empresa::find($datos['empresa_id']) : $vehiculo->empresa;

        $pdf = Pdf::loadView("vehiculos.documentos.pdf.{$tipo}", [
            'vehiculo' => $vehiculo,
            'datos' => $datos,
            'cliente' => $cliente,
            'empresa' => $empresa,
            'titulo' => $titulo,
            'emisor' => Auth::user(),
        ])->setPaper('a4', 'portrait');

        $nombreArchivo = $tipo.'_'.$vehiculo->id.'_'.date('Ymd_His').'.pdf';

        if ($soloPdf) {
            $this->estadoService->registrar($vehiculo, 'documento_generado_descarga', "{$titulo} generado (sólo descarga)");

            return $pdf->download($nombreArchivo);
        }

        $ruta = "vehiculos/{$vehiculo->id}/documentos/{$nombreArchivo}";
        Storage::disk('public')->put($ruta, $pdf->output());

        $documento = VehiculoDocumento::create([
            'vehiculo_id' => $vehiculo->id,
            'user_id' => Auth::id(),
            'tipo' => $tipo,
            'nombre_original' => $nombreArchivo,
            'ruta' => $ruta,
            'mime' => 'application/pdf',
            'tamano_bytes' => Storage::disk('public')->size($ruta),
            'fecha_vencimiento' => $datos['fecha_vencimiento'] ?? null,
            'observaciones' => 'Generado automáticamente desde el formulario VEXIS',
        ]);

        $this->estadoService->registrar($vehiculo, 'documento_generado', "{$titulo} generado y subido (#{$documento->id})");

        return redirect()
            ->route('vehiculos.documentos.download', $documento)
            ->with('success', $titulo.' generado y guardado en la documentación del vehículo.');
    }

    private function reglasPorTipo(string $tipo): array
    {
        $base = [
            'fecha_vencimiento' => 'nullable|date',
        ];

        return match ($tipo) {
            'ficha_tecnica' => $base + [
                'numero_homologacion' => 'required|string|max:40',
                'fecha_emision' => 'required|date',
                'combustible' => 'required|in:Gasolina,Diésel,Híbrido,Eléctrico,GLP',
                'cilindrada_cc' => 'nullable|integer|min:0|max:10000',
                'potencia_cv' => 'required|integer|min:0|max:2000',
                'plazas' => 'required|integer|min:1|max:9',
                'transmision' => 'required|in:Manual,Automática,Semiautomática',
                'categoria' => 'required|in:M1,M2,M3,N1,N2,N3',
                'peso_vacio_kg' => 'nullable|integer|min:0',
                'peso_maximo_kg' => 'nullable|integer|min:0',
                'emisiones_co2' => 'nullable|integer|min:0',
                'observaciones' => 'nullable|string|max:1000',
            ],
            'itv' => $base + [
                'numero_informe' => 'required|string|max:40',
                'fecha_inspeccion' => 'required|date',
                'proxima_revision' => 'required|date|after:fecha_inspeccion',
                'estacion_itv' => 'required|string|max:200',
                'kilometraje' => 'required|integer|min:0|max:9999999',
                'resultado' => 'required|in:favorable,favorable_con_defectos_leves,desfavorable,negativa',
                'defectos' => 'nullable|string|max:2000',
                'observaciones' => 'nullable|string|max:1000',
            ],
            'permiso_circulacion' => $base + [
                'numero_permiso' => 'required|string|max:40',
                'fecha_matriculacion' => 'required|date',
                'cliente_id' => 'required|exists:clientes,id',
                'empresa_id' => 'required|exists:empresas,id',
                'jefatura_trafico' => 'required|string|max:150',
                'uso' => 'required|in:particular,servicio_publico,alquiler,autoescuela',
                'observaciones' => 'nullable|string|max:1000',
            ],
            'seguro' => $base + [
                'aseguradora' => 'required|string|max:150',
                'numero_poliza' => 'required|string|max:60',
                'tipo_cobertura' => 'required|in:terceros,terceros_ampliado,todo_riesgo,todo_riesgo_franquicia',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'cliente_id' => 'required|exists:clientes,id',
                'prima_anual' => 'required|numeric|min:0|max:99999.99',
                'franquicia' => 'nullable|numeric|min:0|max:99999.99',
                'observaciones' => 'nullable|string|max:1000',
            ],
            'contrato' => $base + [
                'numero_contrato' => 'required|string|max:40',
                'tipo_contrato' => 'required|in:compraventa,deposito,alquiler,custodia,cesion',
                'fecha_contrato' => 'required|date',
                'cliente_id' => 'required|exists:clientes,id',
                'empresa_id' => 'required|exists:empresas,id',
                'importe' => 'nullable|numeric|min:0|max:9999999.99',
                'duracion_meses' => 'nullable|integer|min:0|max:600',
                'clausulas_adicionales' => 'nullable|string|max:3000',
                'observaciones' => 'nullable|string|max:1000',
            ],
            default => $base,
        };
    }

    public function generarCodigo(string $tipo): string
    {
        $prefijo = match ($tipo) {
            'ficha_tecnica' => 'FT',
            'itv' => 'ITV',
            'permiso_circulacion' => 'PC',
            'seguro' => 'SEG',
            'contrato' => 'CNT',
            default => 'DOC',
        };

        return $prefijo.'-'.date('Ymd').'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    }

    public function ajaxCodigo(string $tipo)
    {
        abort_unless(in_array($tipo, self::TIPOS_VALIDOS, true), 404);

        return response()->json(['codigo' => $this->generarCodigo($tipo)]);
    }
}
