<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Repositories\Interfaces\ClienteRepositoryInterface;
use Illuminate\Http\Request;

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
        $empresas = $this->clienteRepository->getEmpresas();
        return view('clientes.create', compact('empresas'));
    }

    public function store(StoreClienteRequest $request)
    {
        $this->clienteRepository->create($request->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function show(int $id)
    {
        $cliente = $this->clienteRepository->find($id);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(int $id)
    {
        $cliente = $this->clienteRepository->find($id);
        $empresas = $this->clienteRepository->getEmpresas();
        return view('clientes.edit', compact('cliente', 'empresas'));
    }

    public function update(UpdateClienteRequest $request, int $id)
    {
        $this->clienteRepository->update($id, $request->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(int $id)
    {
        try {
            $this->clienteRepository->delete($id);
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene ofertas asociadas.');
        }
    }
}