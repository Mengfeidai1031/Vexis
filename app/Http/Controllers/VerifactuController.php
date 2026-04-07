<?php

namespace App\Http\Controllers;

use App\Models\Verifactu;
use App\Models\Factura;
use App\Services\AeatVerifactuService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VerifactuController extends Controller
{
    public function index(Request $request)
    {
        $query = Verifactu::with(['factura.cliente', 'factura.empresa', 'factura.marca']);

        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('tipo_operacion')) $query->where('tipo_operacion', $request->tipo_operacion);
        if ($request->filled('fecha_desde')) $query->whereDate('fecha_registro', '>=', $request->fecha_desde);
        if ($request->filled('fecha_hasta')) $query->whereDate('fecha_registro', '<=', $request->fecha_hasta);

        // Sorting
        $sortable = ['id', 'codigo_registro', 'factura_id', 'tipo_operacion', 'tipo_factura', 'nombre_emisor', 'importe_total', 'estado', 'fecha_registro'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $registros = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Verifactu::count(),
            'aceptados' => Verifactu::where('estado', 'aceptado')->count(),
            'pendientes' => Verifactu::whereIn('estado', ['registrado', 'enviado'])->count(),
            'rechazados' => Verifactu::where('estado', 'rechazado')->count(),
        ];

        return view('verifactu.index', compact('registros', 'stats'));
    }

    public function show(Verifactu $verifactu)
    {
        $verifactu->load(['factura.cliente', 'factura.empresa', 'factura.centro', 'factura.marca', 'factura.venta.vehiculo']);

        $anterior = $verifactu->hash_anterior
            ? Verifactu::where('hash_registro', $verifactu->hash_anterior)->first()
            : null;
        $siguiente = Verifactu::where('hash_anterior', $verifactu->hash_registro)->first();

        $xml = $verifactu->buildAeatXml();

        return view('verifactu.show', compact('verifactu', 'anterior', 'siguiente', 'xml'));
    }

    public function create()
    {
        $facturas = Factura::with(['cliente', 'empresa'])
            ->where('estado', '!=', 'anulada')
            ->orderByDesc('fecha_factura')
            ->get();

        return view('verifactu.create', compact('facturas'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'factura_id' => 'required|exists:facturas,id',
            'tipo_operacion' => 'required|in:alta,anulacion',
        ]);

        $factura = Factura::with(['empresa', 'cliente'])->findOrFail($request->factura_id);

        // Check duplicate
        $existente = Verifactu::where('factura_id', $factura->id)
            ->where('tipo_operacion', 'alta')
            ->whereNotIn('estado', ['anulado', 'rechazado'])
            ->first();

        if ($existente && $request->tipo_operacion === 'alta') {
            return redirect()->route('verifactu.index')
                ->with('error', 'Esta factura ya tiene un registro Verifactu activo (' . $existente->codigo_registro . ').');
        }

        $service = new AeatVerifactuService();
        $registro = $service->registrarFactura($factura, $request->tipo_operacion, $request->observaciones);

        return redirect()->route('verifactu.show', $registro)
            ->with('success', "Registro Verifactu {$registro->codigo_registro} creado. Puede enviarlo a AEAT desde esta vista.");
    }

    /**
     * Send a registro to AEAT sandbox.
     */
    public function enviarAeat(Verifactu $verifactu)
    {
        if (!in_array($verifactu->estado, ['registrado', 'rechazado'])) {
            return redirect()->route('verifactu.show', $verifactu)
                ->with('error', 'Solo se pueden enviar registros en estado "Registrado" o "Rechazado".');
        }

        $verifactu->update(['estado' => 'enviado']);

        $service = new AeatVerifactuService();
        $resultado = $service->enviarAeat($verifactu);

        $estado = $resultado['estado'] ?? 'rechazado';
        $msg = $estado === 'aceptado'
            ? "Registro {$verifactu->codigo_registro} aceptado por AEAT. CSV: " . ($resultado['csv'] ?? '—')
            : "Registro rechazado por AEAT: " . ($resultado['errores'][0]['descripcion'] ?? $resultado['descripcion'] ?? 'Error desconocido');

        return redirect()->route('verifactu.show', $verifactu)
            ->with($estado === 'aceptado' ? 'success' : 'error', $msg);
    }

    /**
     * Manually change state (admin override).
     */
    public function cambiarEstado(Request $request, Verifactu $verifactu)
    {
        $request->validate([
            'estado' => 'required|in:registrado,enviado,aceptado,aceptado_errores,rechazado,anulado',
        ]);

        $verifactu->update(['estado' => $request->estado]);

        return redirect()->route('verifactu.show', $verifactu)
            ->with('success', 'Estado actualizado a: ' . Verifactu::$estados[$request->estado]);
    }

    public function declaracion()
    {
        $stats = [
            'total' => Verifactu::count(),
            'aceptados' => Verifactu::where('estado', 'aceptado')->count(),
            'importe_total' => Verifactu::whereNotIn('estado', ['anulado', 'rechazado'])->sum('importe_total'),
            'base_imponible_total' => Verifactu::whereNotIn('estado', ['anulado', 'rechazado'])->sum('base_imponible'),
            'cuota_total' => Verifactu::whereNotIn('estado', ['anulado', 'rechazado'])->sum('cuota_tributaria'),
            'primer_registro' => Verifactu::orderBy('fecha_registro')->first()?->fecha_registro,
            'ultimo_registro' => Verifactu::orderByDesc('fecha_registro')->first()?->fecha_registro,
        ];

        $pdf = Pdf::loadView('verifactu.declaracion-pdf', compact('stats'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('declaracion_responsable_verifactu_' . date('Y-m-d') . '.pdf');
    }

    public function cumplimiento()
    {
        $stats = [
            'total' => Verifactu::count(),
            'aceptados' => Verifactu::where('estado', 'aceptado')->count(),
            'pendientes' => Verifactu::whereIn('estado', ['registrado', 'enviado'])->count(),
            'rechazados' => Verifactu::where('estado', 'rechazado')->count(),
            'anulados' => Verifactu::where('estado', 'anulado')->count(),
            'primer_registro' => Verifactu::orderBy('fecha_registro')->first()?->fecha_registro,
            'ultimo_registro' => Verifactu::orderByDesc('fecha_registro')->first()?->fecha_registro,
            'importe_total' => Verifactu::whereNotIn('estado', ['anulado', 'rechazado'])->sum('importe_total'),
            'base_imponible_total' => Verifactu::whereNotIn('estado', ['anulado', 'rechazado'])->sum('base_imponible'),
            'cuota_total' => Verifactu::whereNotIn('estado', ['anulado', 'rechazado'])->sum('cuota_tributaria'),
        ];

        $pdf = Pdf::loadView('verifactu.cumplimiento-pdf', compact('stats'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('cumplimiento_tecnico_verifactu_' . date('Y-m-d') . '.pdf');
    }

    public function verificarCadena()
    {
        $registros = Verifactu::with('factura')->orderBy('id')->get();
        $errores = [];
        $hashAnterior = null;

        foreach ($registros as $reg) {
            if ($reg->hash_anterior !== $hashAnterior) {
                $errores[] = "Registro {$reg->codigo_registro}: hash_anterior no coincide con el hash del registro previo.";
            }
            $hashAnterior = $reg->hash_registro;
        }

        return response()->json([
            'total_registros' => $registros->count(),
            'cadena_valida' => empty($errores),
            'errores' => $errores,
        ]);
    }

    /**
     * Download XML for a registro.
     */
    public function descargarXml(Verifactu $verifactu)
    {
        $xml = $verifactu->buildAeatXml();
        $filename = 'verifactu_' . $verifactu->codigo_registro . '.xml';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
