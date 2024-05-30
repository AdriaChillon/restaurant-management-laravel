<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;

class CocineroController extends Controller
{
    // Muestra todas las comandas activas
    public function index()
    {
        $comandas = Comanda::where('en_marcha', true)->get();
        return view('cocinero.index', compact('comandas'));
    }

    // Devuelve las comandas activas en formato JSON
    public function getActiveComandas()
    {
        $comandas = Comanda::with(['mesa', 'productos' => function ($query) {
            $query->withPivot('cantidad', 'estado_preparacion');
        }])->where('en_marcha', true)->get();
    
        return response()->json($comandas);
    }
    
    

    // Cambia el estado de la comanda
    // public function cambiarEstado($id)
    // {
    //     $comanda = Comanda::findOrFail($id);

    //     // Filtrar los productos que no sean de la categoría "Refrescos" o "Cafés"
    //     $productosFiltrados = $comanda->productos->reject(function ($producto) {
    //         return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes';
    //     });

    //     // Verificar si todos los productos filtrados están listos
    //     $todosListos = $productosFiltrados->every(function ($producto) {
    //         return $producto->pivot->estado_preparacion === 'listo';
    //     });

    //     if ($todosListos) {
    //         $comanda->en_marcha = false;
    //         $comanda->save();
    //         return redirect()->route('cocinero.index')->with('success', 'Estado de la comanda actualizado.');
    //     } else {
    //         return redirect()->route('cocinero.index')->with('error', 'No se puede finalizar la comanda hasta que todos los productos estén listos.');
    //     }
    // }

    public function manejarComanda(Comanda $comanda)
    {
        // Cargar la relación de productos con sus categorías
        $comanda->load('productos.categoria');

        // Filtrar los productos que no sean de la categoría "Refrescos" o "Cafés"
        $productosFiltrados = $comanda->productos->reject(function ($producto) {
            return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes';
        });

        return view('cocinero.manejar_comanda', compact('comanda', 'productosFiltrados'));
    }

    public function actualizarEstadoProductos(Request $request, Comanda $comanda)
    {
        foreach ($comanda->productos as $producto) {
            // Verificar si se recibió el estado del producto en la solicitud
            if ($request->has('estado_preparacion_' . $producto->id)) {
                $estadoPreparacion = $request->input('estado_preparacion_' . $producto->id);
                // Actualizar el estado del producto en la tabla pivot
                $comanda->productos()->updateExistingPivot($producto->id, ['estado_preparacion' => $estadoPreparacion]);
            }
        }
    
        // Verificar si todos los productos están listos (excepto Refrescos y Cafés)
        $productosFiltrados = $comanda->productos->reject(function ($producto) {
            return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes';
        });
    
        $todosListos = $productosFiltrados->every(function ($producto) {
            return $producto->pivot->estado_preparacion === 'listo';
        });
    
        if ($todosListos) {
            $comanda->save();
        }
    
        return redirect()->route('cocinero.index')->with('success', 'Estado de la comanda actualizado.');
    }
    
}
