<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        $categorias = Categoria::all();
        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required',
            'categoria_id' => 'required', // Asegúrate de validar la categoría
        ]);
    
        // Verificar si se ha subido una imagen
        if ($request->hasFile('imagen')) {
            // Almacenar la imagen en el sistema de archivos
            $path = $request->file('imagen')->store('productos', 'public');
            // Guardar la ruta de la imagen en la base de datos
            $request->merge(['imagen' => $path]);
        }
    
        // Crear el producto con los datos del formulario
        Producto::create($request->all());
    
        return redirect()->route('productos.index');
    }
    

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($request->all());
        return redirect()->route('productos.index');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index');
    }
}
