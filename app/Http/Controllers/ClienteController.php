<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Exports\ClientesExport;
use App\Repositories\Interfaces\ClienteRepositoryInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ClienteController extends Controller
{
    protected $clienteRepository;

    public function __construct(ClienteRepositoryInterface $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function index(Request $request)
    {
        if ($request->has('search') && !empty($request->search)) {
            $clientes = $this->clienteRepository->search($request->search);
        } else {
            $clientes = $this->clienteRepository->all();
        }

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $this->authorize('create', Cliente::class);
        
        $empresas = $this->clienteRepository->getEmpresas();
        return view('clientes.create', compact('empresas'));
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
        return view('clientes.edit', compact('cliente', 'empresas'));
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
        $fileName = 'clientes_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new ClientesExport(), $fileName);
    }

    public function exportPdf()
    {
        $clientes = Cliente::with('empresa')->orderBy('apellidos')->get();
        $pdf = Pdf::loadView('clientes.pdf', compact('clientes'));
        $fileName = 'clientes_' . date('Y-m-d_His') . '.pdf';
        return $pdf->download($fileName);
    }
}