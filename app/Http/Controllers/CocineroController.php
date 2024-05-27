<?php

namespace App\Http\Controllers;

use App\Events\ComandaUpdated;
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
    
    public function cambiarEstado($id)
    {
        $comanda = Comanda::findOrFail($id);
        $comanda->en_marcha = false;
        $comanda->save();
    
        return redirect()->route('cocinero.index')->with('success', 'Estado de la comanda actualizado.');
    }

    public function handleComandaUpdated(ComandaUpdated $event)
    {
        $comandas = Comanda::where('en_marcha', true)->get();
        return view('cocinero.index', compact('comandas'));
    }
    
    public function manejarComanda(Comanda $comanda)
    {
        // Cargar la relación de productos con sus categorías
        $comanda->load('productos.categoria');

        // Filtrar los productos que no sean de la categoría "Refrescos" o "Cafés"
        $productosFiltrados = $comanda->productos->reject(function ($producto) {
            return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafés';
        });

        return view('cocinero.manejar_comanda', compact('comanda', 'productosFiltrados'));
    }
    
    public function actualizarEstadoProductos(Request $request, Comanda $comanda)
    {
        foreach ($comanda->productos as $producto) {
            if ($request->has('estado_preparacion_' . $producto->id)) {
                $producto->update(['estado_preparacion' => $request->input('estado_preparacion_' . $producto->id)]);
            }
        }
    
        return redirect()->route('cocinero.index')->with('success', 'Estado de la comanda actualizado.');
    }
    
    
    
}
