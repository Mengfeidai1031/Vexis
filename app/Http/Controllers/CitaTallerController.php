<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CitaTaller;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\Mecanico;
use App\Models\Taller;
use App\Models\Vehiculo;
use App\Services\VehiculoEstadoService;
use Illuminate\Http\Request;

class CitaTallerController extends Controller
{
    public function __construct(private readonly VehiculoEstadoService $estadoService) {}

    public function index(Request $request)
    {
        $query = CitaTaller::with(['mecanico', 'taller', 'marca', 'cliente', 'vehiculo']);
        if ($request->filled('taller_id')) {
            $query->where('taller_id', (int) $request->input('taller_id'));
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->input('fecha'));
        }
        if ($request->filled('mecanico_id')) {
            $query->where('mecanico_id', (int) $request->input('mecanico_id'));
        }
        if ($request->filled('marca_id')) {
            $query->where('marca_id', (int) $request->input('marca_id'));
        }
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', (int) $request->input('cliente_id'));
        }
        if ($request->filled('cliente_nombre')) {
            $query->where('cliente_nombre', $request->input('cliente_nombre'));
        }

        $sortable = ['id', 'fecha', 'hora_inicio', 'hora_fin', 'cliente_nombre', 'vehiculo_info', 'mecanico_id', 'taller_id', 'estado'];
        $sortBy = $request->input('sort_by');
        if ($sortBy && in_array($sortBy, $sortable, true)) {
            $dir = $request->input('sort_dir') === 'desc' ? 'desc' : 'asc';
            $query->reorder($sortBy, $dir);
        } else {
            $query->reorder('fecha', 'desc')->orderBy('hora_inicio', 'desc');
        }

        $citas = $query->paginate(15)->withQueryString();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();

        $semanaInicio = $request->filled('semana') ? \Carbon\Carbon::parse($request->input('semana'))->startOfWeek() : now()->startOfWeek();
        $semanaFin = $semanaInicio->copy()->endOfWeek();
        $citasSemana = CitaTaller::with(['mecanico', 'cliente', 'vehiculo'])
            ->whereBetween('fecha', [$semanaInicio, $semanaFin])
            ->when($request->filled('taller_id'), fn ($q) => $q->where('taller_id', (int) $request->input('taller_id')))
            ->orderBy('fecha')->orderBy('hora_inicio')->get()
            ->groupBy(fn ($c) => $c->fecha->format('Y-m-d'));

        $mecanicos = Mecanico::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $clientes_citas = Cliente::orderBy('nombre')->get();

        return view('citas.index', compact('citas', 'talleres', 'citasSemana', 'semanaInicio', 'semanaFin', 'mecanicos', 'marcas', 'clientes_citas'));
    }

    public function create()
    {
        $mecanicos = Mecanico::where('activo', true)->orderBy('apellidos')->get();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $vehiculos = Vehiculo::orderBy('matricula')->get();

        return view('citas.create', compact('mecanicos', 'talleres', 'marcas', 'empresas', 'clientes', 'vehiculos'));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $cita = CitaTaller::create($data);
        $this->sincronizarVehiculo($cita);

        return redirect()->route('citas.index')->with('success', 'Cita creada correctamente.');
    }

    public function show(CitaTaller $cita)
    {
        $cita->load(['mecanico', 'taller', 'marca', 'empresa', 'cliente', 'vehiculo']);

        return view('citas.show', compact('cita'));
    }

    public function edit(CitaTaller $cita)
    {
        $mecanicos = Mecanico::where('activo', true)->orderBy('apellidos')->get();
        $talleres = Taller::where('activo', true)->orderBy('nombre')->get();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $vehiculos = Vehiculo::orderBy('matricula')->get();

        return view('citas.edit', compact('cita', 'mecanicos', 'talleres', 'marcas', 'empresas', 'clientes', 'vehiculos'));
    }

    public function update(Request $request, CitaTaller $cita)
    {
        $data = $this->validar($request);
        $cita->update($data);
        $this->sincronizarVehiculo($cita);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(CitaTaller $cita)
    {
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }

    private function validar(Request $request): array
    {
        $request->validate([
            'mecanico_id' => 'required|exists:mecanicos,id',
            'taller_id' => 'required|exists:talleres,id',
            'empresa_id' => 'required|exists:empresas,id',
            'cliente_id' => 'nullable|required_without:cliente_nombre|exists:clientes,id',
            'vehiculo_id' => 'nullable|exists:vehiculos,id',
            'cliente_nombre' => 'nullable|required_without:cliente_id|max:255',
            'vehiculo_info' => 'nullable|max:255',
            'marca_id' => 'nullable|exists:marcas,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'nullable',
            'estado' => 'required|in:pendiente,confirmada,en_curso,completada,cancelada',
            'descripcion' => 'nullable|string',
        ], [
            'cliente_id.required_without' => 'Debes seleccionar un cliente registrado o introducir su nombre.',
            'cliente_nombre.required_without' => 'Debes seleccionar un cliente registrado o introducir su nombre.',
        ]);

        return $request->only([
            'mecanico_id', 'taller_id', 'marca_id', 'empresa_id',
            'cliente_id', 'vehiculo_id', 'cliente_nombre', 'vehiculo_info',
            'fecha', 'hora_inicio', 'hora_fin', 'descripcion', 'estado',
        ]);
    }

    private function sincronizarVehiculo(CitaTaller $cita): void
    {
        if (! $cita->vehiculo_id) {
            return;
        }
        $vehiculo = $cita->vehiculo()->first();
        if ($vehiculo) {
            $this->estadoService->sincronizarConCita($vehiculo, $cita->estado, (string) $cita->id);
        }
    }
}
