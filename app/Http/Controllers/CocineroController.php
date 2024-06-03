<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;

class CocineroController extends Controller
{
    // Muestra todas las comandas activas y completadas, ordenadas
    public function index()
    {
        $comandas = Comanda::with(['mesa', 'productos' => function ($query) {
            $query->withPivot('cantidad', 'estado_preparacion', 'especificaciones');
        }])->where('en_marcha', true)
            ->orderBy('en_marcha', 'desc')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('cocinero.index', compact('comandas'));
    }

    // Devuelve las comandas activas en formato JSON
    public function getActiveComandas()
    {
        $comandas = Comanda::with(['mesa', 'productos' => function ($query) {
            $query->withPivot('cantidad', 'estado_preparacion', 'especificaciones');
        }])->where('en_marcha', true)->get();

        return response()->json($comandas);
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
            $comanda->en_marcha = false; // Marcar la comanda como no en marcha
            $comanda->save();
        }

        return redirect()->route('cocinero.index')->with('success', 'Estado de la comanda actualizado.');
    }
}
