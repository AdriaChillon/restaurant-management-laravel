<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        // Devolver las categorías en formato JSON para las solicitudes AJAX
        $categorias = Categoria::all();
        return response()->json(['categorias' => $categorias]);
    }

    public function store(Request $request)
    {
        // Validar y crear la categoría
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $categoria = Categoria::create($request->all());

        return response()->json(['success' => true, 'categoria' => $categoria]);
    }

    public function update(Request $request, Categoria $categoria)
    {
        // Validar y actualizar la categoría
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $categoria->update($request->all());

        return response()->json(['success' => true, 'categoria' => $categoria]);
    }

    public function destroy(Categoria $categoria)
    {
        // Eliminar la categoría
        $categoria->delete();

        return response()->json(['success' => true]);
    }
}
