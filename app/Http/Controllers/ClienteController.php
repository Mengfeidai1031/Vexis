<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\ClientesExport;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Repositories\Interfaces\ClienteRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClienteController extends Controller
{
    protected $clienteRepository;

    public function __construct(ClienteRepositoryInterface $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function index(Request $request)
    {
        $query = Cliente::with(['empresa', 'tipoCliente']);

        if ($request->filled('tipo_cliente_id')) {
            $query->where('tipo_cliente_id', $request->tipo_cliente_id);
        }

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->whereRaw("CONCAT(nombre, ' ', apellidos) = ?", [$nombre]);
        }
        if ($request->filled('dni')) {
            $query->where('dni', $request->dni);
        }
        if ($request->filled('codigo_postal')) {
            $query->where('codigo_postal', $request->codigo_postal);
        }
        if ($request->filled('domicilio')) {
            $query->where('domicilio', $request->domicilio);
        }
        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }
        if ($request->filled('telefono')) {
            $query->where('telefono', $request->telefono);
        }

        // Sorting
        $sortable = ['id', 'nombre', 'apellidos', 'dni', 'empresa_id', 'domicilio', 'codigo_postal', 'tipo_cliente_id', 'email', 'telefono'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $clientes = $query->paginate(15)->withQueryString();
        $empresas = $this->clienteRepository->getEmpresas();
        $clientes_all = Cliente::orderBy('nombre')->get();
        $codigos_postales = Cliente::whereNotNull('codigo_postal')->distinct()->orderBy('codigo_postal')->pluck('codigo_postal');
        $tipos_cliente = \App\Models\TipoCliente::where('activo', true)->orderBy('nombre')->get();

        return view('clientes.index', compact('clientes', 'empresas', 'clientes_all', 'codigos_postales', 'tipos_cliente'));
    }

    public function create()
    {
        $this->authorize('create', Cliente::class);

        $empresas = $this->clienteRepository->getEmpresas();
        $tipos_cliente = \App\Models\TipoCliente::where('activo', true)->orderBy('nombre')->get();

        return view('clientes.create', compact('empresas', 'tipos_cliente'));
    }

    public function store(StoreClienteRequest $request)
    {
        $this->authorize('create', Cliente::class);

        $this->clienteRepository->create($request->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        $this->authorize('view', $cliente);

        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $this->authorize('update', $cliente);

        $empresas = $this->clienteRepository->getEmpresas();
        $tipos_cliente = \App\Models\TipoCliente::where('activo', true)->orderBy('nombre')->get();

        return view('clientes.edit', compact('cliente', 'empresas', 'tipos_cliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $this->authorize('update', $cliente);

        $this->clienteRepository->update($cliente->id, $request->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->authorize('delete', $cliente);

        try {
            $this->clienteRepository->delete($cliente->id);

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene ofertas asociadas.');
        }
    }

    public function export()
    {
        $fileName = 'clientes_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(new ClientesExport, $fileName);
    }

    public function exportPdf()
    {
        $clientes = Cliente::with('empresa')->orderBy('apellidos')->get();
        $pdf = Pdf::loadView('clientes.pdf', compact('clientes'));
        $fileName = 'clientes_'.date('Y-m-d_His').'.pdf';

        return $pdf->download($fileName);
    }
}
