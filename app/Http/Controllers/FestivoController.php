<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Festivo;
use Illuminate\Http\Request;

class FestivoController extends Controller
{
    public function index(Request $request)
    {
        $anio = $request->input('anio', now()->year);
        $query = Festivo::where('anio', $anio);

        if ($request->filled('ambito')) {
            $query->where('ambito', $request->ambito);
        }
        if ($request->filled('municipio')) {
            $query->where('municipio', $request->municipio);
        }

        // Sorting
        $sortable = ['id', 'fecha', 'nombre', 'ambito', 'municipio'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $sortable)) {
            $dir = $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->reorder()->orderBy($request->sort_by, $dir);
        }

        $festivos = $query->paginate(20)->withQueryString();

        // Datos para calendario
        $eventos = Festivo::where('anio', $anio)->get()->map(fn ($f) => [
            'title' => $f->nombre,
            'start' => $f->fecha->format('Y-m-d'),
            'color' => match ($f->ambito) {
                'nacional' => '#e74c3c',
                'autonomico' => '#3498db',
                default => '#2ecc71',
            },
            'municipio' => $f->municipio ?? 'Todos',
        ]);

        $municipios = Festivo::where('anio', $anio)->whereNotNull('municipio')
            ->distinct()->orderBy('municipio')->pluck('municipio');

        return view('festivos.index', compact('festivos', 'eventos', 'anio', 'municipios'));
    }

    public function create()
    {
        return view('festivos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'municipio' => 'nullable|string|max:150',
            'ambito' => 'required|in:nacional,autonomico,local',
        ]);

        Festivo::create([
            ...$request->only('nombre', 'fecha', 'municipio', 'ambito'),
            'anio' => \Carbon\Carbon::parse($request->fecha)->year,
        ]);

        return redirect()->route('festivos.index')->with('success', 'Festivo creado correctamente.');
    }

    public function show(Festivo $festivo)
    {
        return view('festivos.show', compact('festivo'));
    }

    public function edit(Festivo $festivo)
    {
        return view('festivos.edit', compact('festivo'));
    }

    public function update(Request $request, Festivo $festivo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'municipio' => 'nullable|string|max:150',
            'ambito' => 'required|in:nacional,autonomico,local',
        ]);

        $festivo->update([
            ...$request->only('nombre', 'fecha', 'municipio', 'ambito'),
            'anio' => \Carbon\Carbon::parse($request->fecha)->year,
        ]);

        return redirect()->route('festivos.index')->with('success', 'Festivo actualizado correctamente.');
    }

    public function destroy(Festivo $festivo)
    {
        $festivo->delete();

        return redirect()->route('festivos.index')->with('success', 'Festivo eliminado correctamente.');
    }
}
