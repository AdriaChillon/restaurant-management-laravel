<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with('roles')->get();
        return response()->json(['categorias' => $categorias]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'roles' => 'required|array'
        ]);

        $categoria = Categoria::create($request->only('nombre'));
        $categoria->roles()->sync($request->roles);

        return response()->json(['success' => true, 'categoria' => $categoria]);
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'roles' => 'required|array'
        ]);

        $categoria->update($request->only('nombre'));
        $categoria->roles()->sync($request->roles);

        return response()->json(['success' => true, 'categoria' => $categoria]);
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->roles()->detach();
        $categoria->delete();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $categoria = Categoria::with('roles')->findOrFail($id);
        return response()->json(['categoria' => $categoria]);
    }
}
