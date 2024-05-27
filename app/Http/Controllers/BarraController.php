<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;

class BarraController extends Controller
{
    public function index()
    {
        // $comandas = Comanda::where('en_marcha', false)->where('pagado', false)->get();
        $comandas = Comanda::where('pagado', false)->get();
        return view('barra.index', compact('comandas'));
    }

    public function cobrar($id)
    {
        $comanda = Comanda::findOrFail($id);
        $comanda->pagado = true;
        $comanda->save();

        return redirect()->route('barra.index')->with('success', 'Comanda cobrada con éxito.');
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
            if ($producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafés') {
                continue; // Saltar la actualización si el producto es de esas categorías
            }

            // Verificar si se recibió el estado del producto en la solicitud
            if ($request->has('estado_preparacion_' . $producto->id)) {
                $producto->update(['estado_preparacion' => $request->input('estado_preparacion_' . $producto->id)]);
            }
        }

        return redirect()->route('barra.index')->with('success', 'Estado de los productos actualizado.');
    }
}
