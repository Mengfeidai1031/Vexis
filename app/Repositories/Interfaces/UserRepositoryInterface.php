<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    /**
     * Obtener todos los usuarios con sus relaciones
     */
    public function all();

    /**
     * Buscar usuarios por término de búsqueda
     */
    public function search($searchTerm);

    /**
     * Encontrar un usuario por ID
     */
    public function find(int $id);

    /**
     * Crear un nuevo usuario
     */
    public function create(array $data);

    /**
     * Actualizar un usuario existente
     */
    public function update(int $id, array $data);

    /**
     * Eliminar un usuario
     */
    public function delete(int $id);

    /**
     * Obtener todas las empresas para el formulario
     */
    public function getEmpresas();

    /**
     * Obtener todos los departamentos para el formulario
     */
    public function getDepartamentos();

    /**
     * Obtener todos los centros para el formulario
     */
    public function getCentros();

    /**
     * Obtener centros filtrados por empresa
     */
    public function getCentrosByEmpresa(int $empresaId);

    /**
     * Obtener todos los roles para el formulario
     */
    public function getRoles();
}