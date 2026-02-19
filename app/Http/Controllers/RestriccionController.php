<?php

namespace App\Http\Controllers;

use App\Helpers\UserRestrictionHelper;
use App\Models\UserRestriction;
use App\Repositories\Interfaces\RestriccionRepositoryInterface;
use Illuminate\Http\Request;

class RestriccionController extends Controller
{
    protected $restriccionRepository;

    public function __construct(RestriccionRepositoryInterface $restriccionRepository)
    {
        $this->restriccionRepository = $restriccionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('search') && !empty($request->search)) {
            $restricciones = $this->restriccionRepository->search($request->search);
        } else {
            $restricciones = $this->restriccionRepository->all();
        }

        return view('restricciones.index', compact('restricciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', UserRestriction::class);
        
        $users = $this->restriccionRepository->getUsers();
        $availableRestrictions = $this->restriccionRepository->getAvailableRestrictions();
        
        return view('restricciones.create', compact('users', 'availableRestrictions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', UserRestriction::class);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'restriction_type' => 'required|in:empresa,cliente,vehiculo,centro,departamento',
            'restrictable_id' => 'required|integer',
        ]);

        $this->restriccionRepository->create([
            'user_id' => $request->user_id,
            'type' => $request->restriction_type,
            'restrictable_id' => $request->restrictable_id,
        ]);

        return redirect()->route('restricciones.index')
            ->with('success', 'Restricción creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRestriction $restriccion)
    {
        $this->authorize('view', $restriccion);
        
        return view('restricciones.show', compact('restriccion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserRestriction $restriccion)
    {
        $this->authorize('update', $restriccion);
        
        $users = $this->restriccionRepository->getUsers();
        $availableRestrictions = $this->restriccionRepository->getAvailableRestrictions();
        
        return view('restricciones.edit', compact('restriccion', 'users', 'availableRestrictions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserRestriction $restriccion)
    {
        $this->authorize('update', $restriccion);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'restriction_type' => 'required|in:empresa,cliente,vehiculo,centro,departamento',
            'restrictable_id' => 'required|integer',
        ]);

        $this->restriccionRepository->update($restriccion->id, [
            'user_id' => $request->user_id,
            'type' => $request->restriction_type,
            'restrictable_id' => $request->restrictable_id,
        ]);

        return redirect()->route('restricciones.index')
            ->with('success', 'Restricción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRestriction $restriccion)
    {
        $this->authorize('delete', $restriccion);
        
        $this->restriccionRepository->delete($restriccion->id);
        
        return redirect()->route('restricciones.index')
            ->with('success', 'Restricción eliminada exitosamente.');
    }
}
