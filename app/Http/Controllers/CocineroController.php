<?php
namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CocineroController extends Controller
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
                  ->whereDoesntHave('categoria', function ($query) {
                      $query->whereIn('nombre', ['Refrescos', 'Cafes']);
                  });
        }])->where('en_marcha', true)
            ->orderBy('en_marcha', 'desc')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('cocinero.index', compact('comandas'));
    }

    public function getActiveComandas()
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
                  ->whereDoesntHave('categoria', function ($query) {
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

        $productosFiltrados = $comanda->productos->reject(function ($producto) {
            return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes';
        });

        $todosListos = $productosFiltrados->every(function ($producto) {
            return $producto->pivot->estado_preparacion === 'listo';
        });

        if ($todosListos) {
            $comanda->en_marcha = false;
            $comanda->save();
        }

        return redirect()->route('cocinero.index')->with('success', 'Estado de la comanda actualizado.');
    }
}
