<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarraController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('id'); // Obtener los roles del usuario

        $comandas = Comanda::with(['mesa', 'productos' => function ($query) use ($roles) {
            $query->withPivot('cantidad', 'estado_preparacion', 'especificaciones')
                  ->whereHas('categoria', function ($query) use ($roles) {
                      $query->whereIn('id', function ($query) use ($roles) {
                          $query->select('categoria_id')
                                ->from('categoria_role')
                                ->whereIn('role_id', $roles);
                      });
                  })
                  ->whereHas('categoria', function ($query) {
                      $query->whereIn('nombre', ['Refrescos', 'Cafes']);
                  });
        }])->where('en_marcha', true)
            ->orderBy('en_marcha', 'desc')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('barra.index', compact('comandas'));
    }

    public function getPendingComandas()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('id'); // Obtener los roles del usuario

        $comandas = Comanda::with(['mesa', 'productos' => function ($query) use ($roles) {
            $query->withPivot('cantidad', 'estado_preparacion', 'especificaciones')
                  ->whereHas('categoria', function ($query) use ($roles) {
                      $query->whereIn('id', function ($query) use ($roles) {
                          $query->select('categoria_id')
                                ->from('categoria_role')
                                ->whereIn('role_id', $roles);
                      });
                  })
                  ->whereHas('categoria', function ($query) {
                      $query->whereIn('nombre', ['Refrescos', 'Cafes']);
                  });
        }])->where('en_marcha', true)->get();

        return response()->json($comandas);
    }

    public function actualizarEstadoProductos(Request $request, Comanda $comanda)
    {
        foreach ($comanda->productos as $producto) {
            if ($request->has('estado_preparacion_' . $producto->id)) {
                $estadoPreparacion = $request->input('estado_preparacion_' . $producto->id);
                $comanda->productos()->updateExistingPivot($producto->id, ['estado_preparacion' => $estadoPreparacion]);
            }
        }

        return redirect()->route('barra.index')->with('success', 'Estado de la comanda actualizado.');
    }

    public function cobrar(Request $request, Comanda $comanda)
    {
        $comanda->update(['pagada' => true]);
        return redirect()->route('barra.index')->with('success', 'Comanda marcada como pagada.');
    }
}
