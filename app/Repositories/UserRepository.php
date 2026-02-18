<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Departamento;
use App\Models\Centro;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Obtener todos los usuarios con sus relaciones
     */
    public function all()
    {
        return User::with(['empresa', 'departamento', 'centro'])
            ->withCount('restrictions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Buscar usuarios por término de búsqueda
     */
    public function search($searchTerm)
    {
        return User::with(['empresa', 'departamento', 'centro'])
            ->withCount('restrictions')
            ->where(function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%")
                    ->orWhere('apellidos', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('telefono', 'like', "%{$searchTerm}%")
                    ->orWhere('extension', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('empresa', function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('departamento', function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('centro', function($query) use ($searchTerm) {
                $query->where('nombre', 'like', "%{$searchTerm}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Encontrar un usuario por ID
     */
    public function find(int $id)
    {
        return User::with(['empresa', 'departamento', 'centro', 'restrictions.restrictable'])->findOrFail($id);
    }

    /**
     * Crear un nuevo usuario
     */
    public function create(array $data)
    {
        // Encriptar la contraseña
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    /**
     * Actualizar un usuario existente
     */
    public function update(int $id, array $data)
    {
        $user = User::findOrFail($id);

        // Solo encriptar si se proporciona una nueva contraseña
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $user;
    }

    /**
     * Eliminar un usuario
     */
    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    /**
     * Obtener todas las empresas para el formulario
     */
    public function getEmpresas()
    {
        return Empresa::all();
    }

    /**
     * Obtener todos los departamentos para el formulario
     */
    public function getDepartamentos()
    {
        return Departamento::all();
    }

    /**
     * Obtener todos los centros para el formulario
     */
    public function getCentros()
    {
        return Centro::with('empresa')->get();
    }

    /**
     * Obtener centros filtrados por empresa
     */
    public function getCentrosByEmpresa(int $empresaId)
    {
        return Centro::where('empresa_id', $empresaId)->get();
    }
    
    /**
     * Obtener todos los roles para el formulario
     */
    public function getRoles()
    {
        return \Spatie\Permission\Models\Role::all();
    }
}