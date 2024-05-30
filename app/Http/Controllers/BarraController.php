<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;

class BarraController extends Controller
{
    public function index()
    {
        $comandas = Comanda::where('pagado', false)->get();
        return view('barra.index', compact('comandas'));
    }

    // Devuelve las comandas pendientes de cobro en formato JSON
    public function getPendingComandas()
    {
        $comandas = Comanda::with(['mesa', 'productos' => function ($query) {
            $query->withPivot('cantidad', 'estado_preparacion');
        }])->where('pagado', false)->get();

        return response()->json($comandas);
    }

    public function cobrar($id)
    {
        $comanda = Comanda::findOrFail($id);
        
        // Verificar si la comanda está finalizada y todos los productos están listos
        $todosListos = $comanda->productos->every(function ($producto) {
            return $producto->pivot->estado_preparacion === 'listo';
        });

        if ($comanda->en_marcha == false && $todosListos) {
            $comanda->pagado = true;
            $comanda->save();

            return redirect()->route('barra.index')->with('success', 'Comanda cobrada con éxito.');
        } else {
            return redirect()->route('barra.index')->with('error', 'No se puede cobrar la comanda hasta que todos los productos estén listos y la comanda esté finalizada por el cocinero.');
        }
    }

    public function manejarComanda(Comanda $comanda)
    {
        // Cargar la relación de productos con sus categorías
        $comanda->load('productos.categoria');

        // Filtrar los productos que sean de la categoría "Refrescos" o "Cafés"
        $productosFiltrados = $comanda->productos->filter(function ($producto) {
            return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes';
        });

        return view('barra.manejar_comanda', compact('comanda', 'productosFiltrados'));
    }

    public function actualizarEstadoProductos(Request $request, Comanda $comanda)
    {
        foreach ($comanda->productos as $producto) {
            // Verificar si el producto es de la categoría Refrescos o Cafés
            if ($producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes') {
                // Verificar si se recibió el estado del producto en la solicitud
                if ($request->has('estado_preparacion_' . $producto->id)) {
                    $estadoPreparacion = $request->input('estado_preparacion_' . $producto->id);
                    // Actualizar el estado del producto en la tabla pivot
                    $comanda->productos()->updateExistingPivot($producto->id, ['estado_preparacion' => $estadoPreparacion]);
                }
            }
        }

        return redirect()->route('barra.index')->with('success', 'Estado de los productos actualizado.');
    }
}
