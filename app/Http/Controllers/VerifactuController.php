<?php

namespace App\Http\Controllers;

use App\Models\Verifactu;
use App\Models\Factura;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VerifactuController extends Controller
{
    public function index(Request $request)
    {
        $query = Verifactu::with(['factura.cliente', 'factura.empresa', 'factura.marca']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('codigo_registro', 'like', "%$s%")
                  ->orWhereHas('factura', fn($q2) => $q2->where('codigo_factura', 'like', "%$s%"));
            });
        }
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('tipo_operacion')) $query->where('tipo_operacion', $request->tipo_operacion);

        $registros = $query->orderByDesc('fecha_registro')->paginate(15)->withQueryString();

        // Stats for dashboard
        $stats = [
            'total' => Verifactu::count(),
            'validados' => Verifactu::where('estado', 'validado')->count(),
            'pendientes' => Verifactu::whereIn('estado', ['registrado', 'enviado'])->count(),
            'rechazados' => Verifactu::where('estado', 'rechazado')->count(),
        ];

        return view('verifactu.index', compact('registros', 'stats'));
    }

    public function show(Verifactu $verifactu)
    {
        $verifactu->load(['factura.cliente', 'factura.empresa', 'factura.centro', 'factura.marca', 'factura.venta.vehiculo']);

        // Get previous and next in chain
        $anterior = $verifactu->hash_anterior
            ? Verifactu::where('hash_registro', $verifactu->hash_anterior)->first()
            : null;
        $siguiente = Verifactu::where('hash_anterior', $verifactu->hash_registro)->first();

        return view('verifactu.show', compact('verifactu', 'anterior', 'siguiente'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'factura_id' => 'required|exists:facturas,id',
            'tipo_operacion' => 'required|in:emision,anulacion,rectificacion',
        ]);

        $factura = Factura::with(['empresa', 'cliente'])->findOrFail($request->factura_id);

        // Check if this factura already has a verifactu emision record
        $existente = Verifactu::where('factura_id', $factura->id)
            ->where('tipo_operacion', 'emision')
            ->where('estado', '!=', 'anulado')
            ->first();

        if ($existente && $request->tipo_operacion === 'emision') {
            return redirect()->route('verifactu.index')
                ->with('error', 'Esta factura ya tiene un registro Verifactu activo.');
        }

        // Get last hash in chain
        $ultimoRegistro = Verifactu::orderByDesc('id')->first();
        $hashAnterior = $ultimoRegistro?->hash_registro;

        $hash = Verifactu::generateHash($factura, $hashAnterior);
        $codigo = 'VRF-' . date('Ym') . '-' . str_pad(Verifactu::whereYear('fecha_registro', date('Y'))->count() + 1, 5, '0', STR_PAD_LEFT);

        Verifactu::create([
            'codigo_registro' => $codigo,
            'factura_id' => $factura->id,
            'hash_registro' => $hash,
            'hash_anterior' => $hashAnterior,
            'fecha_registro' => now(),
            'estado' => 'registrado',
            'tipo_operacion' => $request->tipo_operacion,
            'nif_emisor' => $factura->empresa?->cif,
            'nombre_emisor' => $factura->empresa?->nombre,
            'importe_total' => $factura->total,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('verifactu.index')
            ->with('success', "Registro Verifactu {$codigo} creado correctamente.");
    }

    public function create()
    {
        $facturas = Factura::with(['cliente', 'empresa'])
            ->where('estado', '!=', 'anulada')
            ->orderByDesc('fecha_factura')
            ->get();

        return view('verifactu.create', compact('facturas'));
    }

    public function cambiarEstado(Request $request, Verifactu $verifactu)
    {
        $request->validate([
            'estado' => 'required|in:registrado,enviado,validado,rechazado,anulado',
        ]);

        $verifactu->update([
            'estado' => $request->estado,
            'respuesta_aeat' => $request->estado === 'validado'
                ? ['codigo' => 'CSV-' . strtoupper(substr(md5(now()->toString()), 0, 12)), 'fecha_validacion' => now()->format('Y-m-d H:i:s'), 'resultado' => 'Aceptado']
                : ($request->estado === 'rechazado'
                    ? ['codigo_error' => 'ERR-' . rand(1000, 9999), 'descripcion' => 'Error en validación AEAT', 'fecha_rechazo' => now()->format('Y-m-d H:i:s')]
                    : $verifactu->respuesta_aeat),
        ]);

        return redirect()->route('verifactu.show', $verifactu)
            ->with('success', 'Estado actualizado a: ' . Verifactu::$estados[$request->estado]);
    }

    public function declaracion()
    {
        $stats = [
            'total' => Verifactu::count(),
            'validados' => Verifactu::where('estado', 'validado')->count(),
            'importe_total' => Verifactu::where('estado', '!=', 'anulado')->sum('importe_total'),
            'primer_registro' => Verifactu::orderBy('fecha_registro')->first()?->fecha_registro,
            'ultimo_registro' => Verifactu::orderByDesc('fecha_registro')->first()?->fecha_registro,
        ];

        $pdf = Pdf::loadView('verifactu.declaracion-pdf', compact('stats'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('declaracion_responsable_verifactu_' . date('Y-m-d') . '.pdf');
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

            $hashEsperado = Verifactu::generateHash($reg->factura, $hashAnterior);
            if ($reg->hash_registro !== $hashEsperado) {
                $errores[] = "Registro {$reg->codigo_registro}: hash_registro no coincide con el hash calculado.";
            }

            $hashAnterior = $reg->hash_registro;
        }

        return response()->json([
            'total_registros' => $registros->count(),
            'cadena_valida' => empty($errores),
            'errores' => $errores,
        ]);
    }
}
