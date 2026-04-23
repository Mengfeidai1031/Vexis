<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\Interfaces\RestriccionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $query = User::with(['empresa', 'departamento', 'centro'])
            ->withCount('restrictions');

        if ($request->filled('id')) {
            $query->where('id', (int) $request->id);
        }
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }
        if ($request->filled('centro_id')) {
            $query->where('centro_id', $request->centro_id);
        }
        if ($request->filled('departamento_id')) {
            $query->where('departamento_id', $request->departamento_id);
        }
        if ($request->filled('rol')) {
            $query->role($request->rol);
        }
        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->whereRaw("CONCAT(nombre, ' ', apellidos) = ?", [$nombre]);
        }
        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }
        if ($request->filled('telefono')) {
            $query->where('telefono', $request->telefono);
        }

        // Sorting
        $sortable = ['id', 'nombre', 'apellidos', 'email', 'empresa_id', 'departamento_id', 'centro_id', 'telefono'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $users = $query->paginate(15)->withQueryString();
        $empresas = $this->userRepository->getEmpresas();
        $departamentos = $this->userRepository->getDepartamentos();
        $centros = $this->userRepository->getCentros();
        $roles = Role::orderBy('name')->get();
        $users_all = User::orderBy('nombre')->get();

        return view('users.index', compact('users', 'empresas', 'departamentos', 'centros', 'roles', 'users_all'));
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

        // Asignar roles si se proporcionaron (bloquea escalada a Super Admin salvo Super Admin).
        if ($request->has('roles')) {
            $roles = $this->filterAssignableRoles($request->roles);
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

        // Sincronizar roles (bloquea escalada a Super Admin salvo Super Admin; preserva Super Admin existente).
        if ($request->has('roles')) {
            $roles = $this->filterAssignableRoles($request->roles, $user);
            $user->syncRoles($roles);
        } else {
            // No permitir que un no-Super-Admin revoque el rol Super Admin de otro usuario.
            if ($user->hasRole('Super Admin') && ! Auth::user()?->hasRole('Super Admin')) {
                // No tocar roles si no es Super Admin quien edita.
            } else {
                $user->syncRoles([]);
            }
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

    /**
     * Filtra IDs de roles asegurando que:
     * - Solo Super Admin puede asignar el rol "Super Admin".
     * - Solo Super Admin puede asignar el rol "Administrador".
     * - Nunca se asigna el rol "Cliente" desde el panel admin (es de uso exclusivo registro público).
     * - Al editar a un Super Admin existente, si el actor no es Super Admin, preserva ese rol.
     */
    private function filterAssignableRoles(array $roleIds, ?User $target = null): \Illuminate\Support\Collection
    {
        $currentUser = Auth::user();
        $isSuperAdmin = $currentUser?->hasRole('Super Admin') ?? false;

        $roles = Role::whereIn('id', $roleIds)->get();

        $filtered = $roles->reject(function (Role $role) use ($isSuperAdmin, $currentUser) {
            if ($role->name === 'Cliente') {
                Log::channel('security')->warning('role.assignment.blocked.cliente', [
                    'actor_id' => $currentUser?->id,
                    'role' => $role->name,
                ]);

                return true;
            }
            if (in_array($role->name, ['Super Admin', 'Administrador'], true) && ! $isSuperAdmin) {
                Log::channel('security')->warning('role.assignment.privilege_escalation_attempt', [
                    'actor_id' => $currentUser?->id,
                    'role' => $role->name,
                ]);

                return true;
            }

            return false;
        })->values();

        // Preservar Super Admin si el actor no es Super Admin y el objetivo lo tiene.
        if ($target && $target->hasRole('Super Admin') && ! $isSuperAdmin) {
            $superAdminRole = Role::where('name', 'Super Admin')->first();
            if ($superAdminRole) {
                $filtered = $filtered->push($superAdminRole)->unique('id')->values();
            }
        }

        return $filtered;
    }
}
