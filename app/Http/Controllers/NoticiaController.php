<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Noticia::with('autor');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('titulo', 'like', "%$s%")->orWhere('contenido', 'like', "%$s%");
            });
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        $noticias = $query->orderByDesc('fecha_publicacion')->paginate(10)->withQueryString();
        return view('noticias.index', compact('noticias'));
    }

    public function create()
    {
        return view('noticias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'categoria' => 'required|in:general,empresa,comercial,rrhh,tecnologia',
            'destacada' => 'nullable|boolean',
            'publicada' => 'nullable|boolean',
        ]);

        Noticia::create([
            ...$request->only('titulo', 'contenido', 'categoria'),
            'destacada' => $request->boolean('destacada'),
            'publicada' => $request->boolean('publicada', true),
            'autor_id' => Auth::id(),
            'fecha_publicacion' => now(),
        ]);

        return redirect()->route('noticias.index')->with('success', 'Noticia creada correctamente.');
    }

    public function show(Noticia $noticia)
    {
        $noticia->load('autor');
        return view('noticias.show', compact('noticia'));
    }

    public function edit(Noticia $noticia)
    {
        return view('noticias.edit', compact('noticia'));
    }

    public function update(Request $request, Noticia $noticia)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'categoria' => 'required|in:general,empresa,comercial,rrhh,tecnologia',
        ]);

        $noticia->update([
            ...$request->only('titulo', 'contenido', 'categoria'),
            'destacada' => $request->boolean('destacada'),
            'publicada' => $request->boolean('publicada', true),
        ]);

        return redirect()->route('noticias.index')->with('success', 'Noticia actualizada correctamente.');
    }

    public function destroy(Noticia $noticia)
    {
        $noticia->delete();
        return redirect()->route('noticias.index')->with('success', 'Noticia eliminada correctamente.');
    }
}
