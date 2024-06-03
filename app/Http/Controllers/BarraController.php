<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;

class BarraController extends Controller
{
    public function index()
    {
        $comandas = Comanda::with(['mesa', 'productos' => function ($query) {
            $query->withPivot('cantidad', 'estado_preparacion', 'especificaciones');
        }])->where('pagado', false)->get();

        return view('barra.index', compact('comandas'));
    }

    public function getPendingComandas()
    {
        $comandas = Comanda::with(['mesa', 'productos' => function ($query) {
            $query->withPivot('cantidad', 'estado_preparacion', 'especificaciones');
        }])->where('pagado', false)->get();

        return response()->json($comandas);
    }

    public function cobrar($id)
    {
        $comanda = Comanda::findOrFail($id);
        
        $todosListos = $comanda->productos->every(function ($producto) {
            return $producto->pivot->estado_preparacion === 'listo';
        });

        if ($todosListos) {
            $comanda->pagado = true;
            $comanda->en_marcha = false;
            $comanda->save();

            return redirect()->route('barra.index')->with('success', 'Comanda cobrada con éxito.');
        } else {
            return redirect()->route('barra.index')->with('error', 'No se puede cobrar la comanda hasta que todos los productos estén listos.');
        }
    }

    public function manejarComanda(Comanda $comanda)
    {
        $comanda->load('productos.categoria');

        $productosFiltrados = $comanda->productos->filter(function ($producto) {
            return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes';
        });

        return view('barra.manejar_comanda', compact('comanda', 'productosFiltrados'));
    }

    public function actualizarEstadoProductos(Request $request, Comanda $comanda)
    {
        foreach ($comanda->productos as $producto) {
            if ($producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes') {
                if ($request->has('estado_preparacion_' . $producto->id)) {
                    $estadoPreparacion = $request->input('estado_preparacion_' . $producto->id);
                    $comanda->productos()->updateExistingPivot($producto->id, ['estado_preparacion' => $estadoPreparacion]);
                }
            }
        }

        return redirect()->route('barra.index')->with('success', 'Estado de los productos actualizado.');
    }
}
