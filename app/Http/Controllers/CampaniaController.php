<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\CampaniaFoto;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaniaController extends Controller
{
    public function index(Request $request)
    {
        $query = Campania::with(['marca', 'fotos']);
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }
        $campanias = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('campanias.index', compact('campanias', 'marcas'));
    }

    public function create()
    {
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('campanias.create', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'marca_id' => 'required|exists:marcas,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $campania = Campania::create($request->only('nombre', 'descripcion', 'marca_id', 'fecha_inicio', 'fecha_fin'));

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $i => $foto) {
                $path = $foto->store('campanias/' . $campania->id, 'public');
                CampaniaFoto::create([
                    'campania_id' => $campania->id,
                    'ruta' => $path,
                    'nombre_original' => $foto->getClientOriginalName(),
                    'orden' => $i,
                ]);
            }
        }

        return redirect()->route('campanias.index')->with('success', 'Campaña creada correctamente.');
    }

    public function show(Campania $campania)
    {
        $campania->load(['marca', 'fotos']);
        return view('campanias.show', compact('campania'));
    }

    public function edit(Campania $campania)
    {
        $campania->load('fotos');
        $marcas = Marca::where('activa', true)->orderBy('nombre')->get();
        return view('campanias.edit', compact('campania', 'marcas'));
    }

    public function update(Request $request, Campania $campania)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'marca_id' => 'required|exists:marcas,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $campania->update($request->only('nombre', 'descripcion', 'marca_id', 'fecha_inicio', 'fecha_fin', 'activa'));
        $campania->activa = $request->boolean('activa', true);
        $campania->save();

        if ($request->hasFile('fotos')) {
            $maxOrder = $campania->fotos()->max('orden') ?? -1;
            foreach ($request->file('fotos') as $i => $foto) {
                $path = $foto->store('campanias/' . $campania->id, 'public');
                CampaniaFoto::create([
                    'campania_id' => $campania->id,
                    'ruta' => $path,
                    'nombre_original' => $foto->getClientOriginalName(),
                    'orden' => $maxOrder + $i + 1,
                ]);
            }
        }

        return redirect()->route('campanias.index')->with('success', 'Campaña actualizada correctamente.');
    }

    public function destroyFoto(CampaniaFoto $foto)
    {
        Storage::disk('public')->delete($foto->ruta);
        $foto->delete();
        return back()->with('success', 'Foto eliminada correctamente.');
    }

    public function destroy(Campania $campania)
    {
        foreach ($campania->fotos as $foto) {
            Storage::disk('public')->delete($foto->ruta);
        }
        $campania->delete();
        return redirect()->route('campanias.index')->with('success', 'Campaña eliminada correctamente.');
    }
}
