<?php
namespace App\Http\Controllers;

use App\Events\comandaUpdated;
use App\Models\Comanda;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CamareroController extends Controller
{
    // Muestra todas las comandas activas
    public function index()
    {
        $comandas = Comanda::where('en_marcha', true)->get();
        return view('camarero.index', compact('comandas'));
    }

    // Muestra el formulario para crear una nueva comanda
    public function create()
    {
        $mesas = Mesa::all();
        $categorias = Categoria::with('productos')->get();
        return view('camarero.create', compact('mesas', 'categorias'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mesa_id' => 'required|integer|exists:mesas,id',
            'productos.*' => 'integer|min:0',
        ]);
    
        $comanda = new Comanda([
            'mesa_id' => $validatedData['mesa_id'],
            'fecha_hora' => now(),
            'en_marcha' => true, // Aseguramos que se proporcione un valor para 'en_marcha'
            'precio_total' => 0,
        ]);
        $comanda->save();
    
        $precio_total = 0;
        foreach ($request->productos as $producto_id => $cantidad) {
            if ($cantidad > 0) {
                $producto = Producto::find($producto_id);
                $comanda->productos()->attach($producto_id, [
                    'cantidad' => $cantidad,
                    'precio' => $producto->precio
                ]);
                $precio_total += $producto->precio * $cantidad;
            }
        }
    
        $comanda->precio_total = $precio_total;
        $comanda->save();
        
        // Dentro del método store y update
        event(new ComandaUpdated($comanda));
        return redirect()->route('camarero.index')->with('success', 'Comanda creada con éxito.');
    }
    
    // Muestra el formulario para editar una comanda existente
    public function edit($id)
    {
        $comanda = Comanda::with(['productos', 'mesa'])->findOrFail($id); // Asegúrate de incluir 'mesa'
        $mesas = Mesa::all();
        $categorias = Categoria::with('productos')->get();
        return view('camarero.edit', compact('comanda', 'mesas', 'categorias'));
    }
    

    // Actualiza una comanda existente
    public function update(Request $request, $id)
    {
        $comanda = Comanda::findOrFail($id);
        $comanda->mesa_id = $request->mesa_id;
        $comanda->save();

        $comanda->productos()->detach();  // Remove current relations to update

        $precio_total = 0;
        foreach ($request->productos as $producto_id => $cantidad) {
            if ($cantidad > 0) {
                $producto = Producto::find($producto_id);
                $comanda->productos()->attach($producto_id, ['cantidad' => $cantidad, 'precio' => $producto->precio]);
                $precio_total += $producto->precio * $cantidad;
            }
        }

        $comanda->precio_total = $precio_total;
        $comanda->save();
        // Dentro del método store y update
        event(new comandaUpdated($comanda));

        return redirect()->route('camarero.index')->with('success', 'Comanda actualizada con éxito.');
    }
}
