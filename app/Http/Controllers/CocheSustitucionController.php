<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CocheSustitucion;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\ReservaSustitucion;
use App\Models\Taller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CocheSustitucionController extends Controller
{
    public function index(Request $request)
    {
        $query = CocheSustitucion::with(['marca', 'taller', 'empresa']);
        if ($request->filled('taller_id')) {
            $query->where('taller_id', (int) $request->input('taller_id'));
        }
        if ($request->filled('marca_id')) {
            $query->where('marca_id', (int) $request->input('marca_id'));
        }
        if ($request->filled('disponible')) {
            $query->where('disponible', $request->input('disponible'));
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', (int) $request->input('empresa_id'));
        }
        if ($request->filled('matricula')) {
            $query->where('matricula', $request->input('matricula'));
        }
        if ($request->filled('modelo')) {
            $query->where('modelo', $request->input('modelo'));
        }

        $sortable = ['id', 'matricula', 'modelo', 'marca_id', 'color', 'taller_id', 'disponible'];
        $sortBy = $request->input('sort_by');
        if ($sortBy && in_array($sortBy, $sortable, true)) {
            $dir = $request->input('sort_dir') === 'desc' ? 'desc' : 'asc';
            $query->reorder($sortBy, $dir);
        } else {
            $query->orderBy('matricula');
        }

        $coches = $query->paginate(15)->withQueryString();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();

        $mes = $request->filled('mes') ? \Carbon\Carbon::parse($request->input('mes').'-01') : now()->startOfMonth();
        $reservas = ReservaSustitucion::with(['coche', 'cliente'])
            ->where('estado', '!=', 'cancelado')
            ->where(function ($q) use ($mes) {
                $q->whereBetween('fecha_inicio', [$mes, $mes->copy()->endOfMonth()])
                    ->orWhereBetween('fecha_fin', [$mes, $mes->copy()->endOfMonth()]);
            })
            ->get()->map(fn ($r) => [
                'title' => $r->coche->matricula.' — '.$r->cliente_display,
                'start' => $r->fecha_inicio->format('Y-m-d'),
                'end' => $r->fecha_fin->copy()->addDay()->format('Y-m-d'),
                'color' => match ($r->estado) {
                    'reservado' => '#f39c12',
                    'entregado' => '#3498db',
                    default => '#2ecc71',
                },
            ]);

        $marcas = Marca::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $matriculas_cs = CocheSustitucion::distinct()->orderBy('matricula')->pluck('matricula');
        $modelos_cs = CocheSustitucion::distinct()->orderBy('modelo')->pluck('modelo');

        return view('coches-sustitucion.index', compact('coches', 'talleres', 'reservas', 'mes', 'marcas', 'empresas', 'matriculas_cs', 'modelos_cs'));
    }

    public function create()
    {
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('coches-sustitucion.create', compact('talleres', 'marcas', 'empresas', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula' => 'required|max:10|unique:coches_sustitucion',
            'modelo' => 'required|max:100',
            'marca_id' => 'required|exists:marcas,id',
            'taller_id' => 'required|exists:talleres,id',
            'empresa_id' => 'required|exists:empresas,id',
            'reservar' => 'nullable|boolean',
            'cliente_id' => 'nullable|exists:clientes,id',
            'cliente_nombre' => 'nullable|max:200',
            'fecha_inicio' => 'nullable|required_if:reservar,1|date',
            'fecha_fin' => 'nullable|required_if:reservar,1|date|after_or_equal:fecha_inicio',
            'estado_reserva' => 'nullable|in:reservado,entregado,devuelto,cancelado',
            'observaciones_reserva' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $coche = CocheSustitucion::create($request->only([
                'matricula', 'modelo', 'marca_id', 'taller_id', 'empresa_id', 'color', 'anio', 'observaciones',
            ]));

            if ($request->boolean('reservar')) {
                if (! $request->filled('cliente_id') && ! $request->filled('cliente_nombre')) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'cliente_id' => 'Debes seleccionar un cliente registrado o introducir su nombre.',
                    ]);
                }
                ReservaSustitucion::create([
                    'coche_id' => $coche->id,
                    'cliente_id' => $request->input('cliente_id'),
                    'cliente_nombre' => $request->input('cliente_nombre'),
                    'fecha_inicio' => $request->input('fecha_inicio'),
                    'fecha_fin' => $request->input('fecha_fin'),
                    'estado' => $request->input('estado_reserva', 'reservado'),
                    'observaciones' => $request->input('observaciones_reserva'),
                ]);
                if (in_array($request->input('estado_reserva', 'reservado'), ['reservado', 'entregado'], true)) {
                    $coche->update(['disponible' => false]);
                }
            }
        });

        return redirect()->route('coches-sustitucion.index')->with('success', 'Coche registrado correctamente.');
    }

    public function show(CocheSustitucion $coches_sustitucion)
    {
        $coches_sustitucion->load(['marca', 'taller', 'empresa', 'reservas.cliente']);

        return view('coches-sustitucion.show', compact('coches_sustitucion'));
    }

    public function edit(CocheSustitucion $coches_sustitucion)
    {
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();

        return view('coches-sustitucion.edit', compact('coches_sustitucion', 'talleres', 'marcas', 'empresas'));
    }

    public function update(Request $request, CocheSustitucion $coches_sustitucion)
    {
        $request->validate([
            'matricula' => 'required|max:10|unique:coches_sustitucion,matricula,'.$coches_sustitucion->id,
            'modelo' => 'required|max:100',
            'marca_id' => 'required|exists:marcas,id',
            'taller_id' => 'required|exists:talleres,id',
            'empresa_id' => 'required|exists:empresas,id',
        ]);
        $coches_sustitucion->update([
            ...$request->only(['matricula', 'modelo', 'marca_id', 'taller_id', 'empresa_id', 'color', 'anio', 'observaciones']),
            'disponible' => $request->boolean('disponible', true),
        ]);

        return redirect()->route('coches-sustitucion.index')->with('success', 'Coche actualizado.');
    }

    public function destroy(CocheSustitucion $coches_sustitucion)
    {
        $coches_sustitucion->delete();

        return redirect()->route('coches-sustitucion.index')->with('success', 'Coche eliminado.');
    }

    public function reservar(Request $request, CocheSustitucion $coche)
    {
        $request->validate([
            'cliente_id' => 'nullable|required_without:cliente_nombre|exists:clientes,id',
            'cliente_nombre' => 'nullable|required_without:cliente_id|max:200',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:reservado,entregado,devuelto,cancelado',
            'observaciones' => 'nullable|string',
        ]);

        $solape = $coche->reservas()
            ->where('estado', '!=', 'cancelado')
            ->where(function ($q) use ($request) {
                $q->whereBetween('fecha_inicio', [$request->input('fecha_inicio'), $request->input('fecha_fin')])
                    ->orWhereBetween('fecha_fin', [$request->input('fecha_inicio'), $request->input('fecha_fin')])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('fecha_inicio', '<=', $request->input('fecha_inicio'))
                            ->where('fecha_fin', '>=', $request->input('fecha_fin'));
                    });
            })->exists();

        if ($solape) {
            return back()->withInput()->with('error', 'El coche ya tiene una reserva activa en esas fechas.');
        }

        ReservaSustitucion::create([
            'coche_id' => $coche->id,
            'cliente_id' => $request->input('cliente_id'),
            'cliente_nombre' => $request->input('cliente_nombre'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin'),
            'estado' => $request->input('estado'),
            'observaciones' => $request->input('observaciones'),
        ]);

        if (in_array($request->input('estado'), ['reservado', 'entregado'], true)) {
            $coche->update(['disponible' => false]);
        }

        return back()->with('success', 'Reserva creada correctamente.');
    }
}
