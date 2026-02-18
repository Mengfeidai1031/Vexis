<?php

namespace App\Http\Controllers;

use App\Helpers\UserRestrictionHelper;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\Interfaces\RestriccionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userRepository;
    protected $restriccionRepository;

    /**
     * Inyección del repositorio
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RestriccionRepositoryInterface $restriccionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->restriccionRepository = $restriccionRepository;
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
        $this->authorize('create', User::class);
        
        $empresas = $this->userRepository->getEmpresas();
        $departamentos = $this->userRepository->getDepartamentos();
        $centros = $this->userRepository->getCentros();
        $roles = $this->userRepository->getRoles();
        $availableRestrictions = $this->restriccionRepository->getAvailableRestrictions();

        return view('users.create', compact('empresas', 'departamentos', 'centros', 'roles', 'availableRestrictions'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        
        $user = $this->userRepository->create($request->validated());

        // Asignar roles si se proporcionaron
        if ($request->has('roles')) {
            // Convertir IDs a instancias de Role
            $roles = Role::whereIn('id', $request->roles)->get();
            $user->syncRoles($roles);
        }

        // Gestionar restricciones si se proporcionaron
        if ($request->has('restrictions')) {
            $restrictions = [
                'empresas' => $request->input('restrictions.empresas', []),
                'clientes' => $request->input('restrictions.clientes', []),
                'vehiculos' => $request->input('restrictions.vehiculos', []),
                'centros' => $request->input('restrictions.centros', []),
                'departamentos' => $request->input('restrictions.departamentos', []),
            ];
            $this->restriccionRepository->syncUserRestrictions($user->id, $restrictions);
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar un usuario específico
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        return view('users.show', compact('user'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $empresas = $this->userRepository->getEmpresas();
        $departamentos = $this->userRepository->getDepartamentos();
        $centros = $this->userRepository->getCentros();
        $roles = $this->userRepository->getRoles();
        $availableRestrictions = $this->restriccionRepository->getAvailableRestrictions();
        $userRestrictions = $this->restriccionRepository->getUserRestrictions($user->id);

        return view('users.edit', compact('user', 'empresas', 'departamentos', 'centros', 'roles', 'availableRestrictions', 'userRestrictions'));
    }

    /**
     * Actualizar usuario
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        
        $user = $this->userRepository->update($user->id, $request->validated());
        
        // Sincronizar roles
        if ($request->has('roles')) {
            // Convertir IDs a instancias de Role
            $roles = Role::whereIn('id', $request->roles)->get();
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        // Gestionar restricciones
        $restrictions = [
            'empresas' => $request->input('restrictions.empresas', []),
            'clientes' => $request->input('restrictions.clientes', []),
            'vehiculos' => $request->input('restrictions.vehiculos', []),
            'centros' => $request->input('restrictions.centros', []),
            'departamentos' => $request->input('restrictions.departamentos', []),
        ];
        $this->restriccionRepository->syncUserRestrictions($user->id, $restrictions);
    
        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        $this->userRepository->delete($user->id);

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