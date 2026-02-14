<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    /**
     * Inyección del repositorio
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Mostrar la lista de usuarios
     */
    public function index(Request $request)
    {
        // Si hay búsqueda, filtrar
        if ($request->has('search') && !empty($request->search)) {
            $users = $this->userRepository->search($request->search);
        } else {
            $users = $this->userRepository->all();
        }

        return view('users.index', compact('users'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $empresas = $this->userRepository->getEmpresas();
        $departamentos = $this->userRepository->getDepartamentos();
        $centros = $this->userRepository->getCentros();

        return view('users.create', compact('empresas', 'departamentos', 'centros'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(StoreUserRequest $request)
    {
        $this->userRepository->create($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar un usuario específico
     */
    public function show(int $id)
    {
        $user = $this->userRepository->find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(int $id)
    {
        $user = $this->userRepository->find($id);
        $empresas = $this->userRepository->getEmpresas();
        $departamentos = $this->userRepository->getDepartamentos();
        $centros = $this->userRepository->getCentros();

        return view('users.edit', compact('user', 'empresas', 'departamentos', 'centros'));
    }

    /**
     * Actualizar usuario
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $this->userRepository->update($id, $request->validated());

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(int $id)
    {
        $this->userRepository->delete($id);

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * API: Obtener centros por empresa (para el formulario dinámico)
     */
    public function getCentrosByEmpresa(Request $request)
    {
        $centros = $this->userRepository->getCentrosByEmpresa($request->empresa_id);
        return response()->json($centros);
    }
}