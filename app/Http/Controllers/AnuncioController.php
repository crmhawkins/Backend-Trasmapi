<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AnuncioController extends Controller
{
    public function index()
    {
        $anuncios = Anuncio::latest()->paginate(10);
        return view('anuncios.index', compact('anuncios'));
    }
    public function create()
    {
        return view('anuncios.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'tipo' => 'required|in:imagen,video',
            'media' => 'required|file',
            'texto' => 'nullable',
            'link' => 'nullable|url'
        ]);

        $path = $request->file('media')->store('anuncios', 'public');

        Anuncio::create([
            'titulo' => $request->titulo,
            'texto' => $request->texto,
            'tipo' => $request->tipo,
            'media' => $path,
            'link' => $request->link,
        ]);

        return redirect()->route('anuncios.index')->with('success', 'Anuncio creado correctamente.');
    }
    public function edit($id)
    {
        $anuncio = Anuncio::findOrFail($id);
        return view('anuncios.edit', compact('anuncio'));
    }
    public function update(Request $request, $id)
    {
        $anuncio = Anuncio::findOrFail($id);

        $request->validate([
            'titulo' => 'required',
            'tipo' => 'required|in:imagen,video',
            'media' => 'nullable|file',
            'texto' => 'nullable',
            'link' => 'nullable|url'
        ]);

        if ($request->hasFile('media')) {
            Storage::disk('public')->delete($anuncio->media);
            $anuncio->media = $request->file('media')->store('anuncios', 'public');
        }

        $anuncio->update([
            'titulo' => $request->titulo,
            'texto' => $request->texto,
            'tipo' => $request->tipo,
            'link' => $request->link,
            'media' => $anuncio->media
        ]);

        return redirect()->route('anuncios.index')->with('success', 'Anuncio actualizado.');
    }
    public function mostrar()
    {
        $anuncio = Anuncio::inRandomOrder()->first();

        return response()->json([
            'titulo' => $anuncio->titulo,
            'texto' => $anuncio->texto,
            'tipo' => $anuncio->tipo,
            'media' => asset('storage/' . $anuncio->media),
            'link' => $anuncio->link,
        ]);
    }
    public function destroy($id)
    {
        $anuncio = Anuncio::findOrFail($id);

        // Elimina el archivo multimedia
        if ($anuncio->media) {
            Storage::disk('public')->delete($anuncio->media);
        }

        $anuncio->delete();

        return redirect()->route('anuncios.index')->with('success', 'Anuncio eliminado.');
    }
}
