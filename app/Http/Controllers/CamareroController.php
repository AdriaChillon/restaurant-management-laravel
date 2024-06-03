<?php

namespace App\Http\Controllers;

use App\Events\ComandaUpdated;
use App\Models\Comanda;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CamareroController extends Controller
{
    // Muestra todas las comandas activas hasta que sean pagadas
    public function index()
    {
        $comandas = Comanda::with(['mesa', 'productos' => function ($query) {
            $query->withPivot('cantidad', 'estado_preparacion', 'especificaciones');
        }])->where('pagado', false)->get();

        return view('camarero.index', compact('comandas'));
    }

    // Devuelve las comandas activas en formato JSON hasta que sean pagadas
    public function getActiveComandas()
    {
        $comandas = Comanda::with(['mesa', 'productos' => function ($query) {
            $query->withPivot('cantidad', 'estado_preparacion', 'especificaciones');
        }])->where('pagado', false)->get();

        return response()->json($comandas);
    }

    // Muestra el formulario para crear una nueva comanda
    public function create()
    {
        $mesasOcupadas = Comanda::where('pagado', false)->pluck('mesa_id');
        $mesas = Mesa::whereNotIn('id', $mesasOcupadas)->get();
        $categorias = Categoria::with('productos')->get();
        return view('camarero.create', compact('mesas', 'categorias'));
    }

    public function edit($id)
    {
        $comanda = Comanda::with(['productos', 'mesa'])->findOrFail($id);
        $mesasOcupadas = Comanda::where('pagado', false)->pluck('mesa_id');
        $mesas = Mesa::whereNotIn('id', $mesasOcupadas)->orWhere('id', $comanda->mesa_id)->get();
        $categorias = Categoria::with('productos')->get();
        return view('camarero.edit', compact('comanda', 'mesas', 'categorias'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mesa_id' => 'required|integer|exists:mesas,id',
            'productos.*' => 'integer|min:0',
            'especificaciones.*' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $comanda = Comanda::create([
                'mesa_id' => $validatedData['mesa_id'],
                'fecha_hora' => now(),
                'en_marcha' => true,
                'precio_total' => 0,
            ]);

            $precio_total = 0;
            foreach ($validatedData['productos'] as $producto_id => $cantidad) {
                if ($cantidad > 0) {
                    $producto = Producto::find($producto_id);
                    $especificaciones = $validatedData['especificaciones'][$producto_id] ?? '';
                    $comanda->productos()->attach($producto_id, [
                        'cantidad' => $cantidad,
                        'precio' => $producto->precio,
                        'especificaciones' => $especificaciones,
                    ]);
                    $precio_total += $producto->precio * $cantidad;
                }
            }

            $comanda->precio_total = $precio_total;
            $comanda->save();

            DB::commit();

            event(new ComandaUpdated($comanda));

            return redirect()->route('camarero.index')->with('success', 'Comanda creada con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al crear la comanda: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'mesa_id' => 'required|integer|exists:mesas,id',
            'productos.*' => 'integer|min:0',
            'especificaciones.*' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $comanda = Comanda::findOrFail($id);
            $comanda->mesa_id = $validatedData['mesa_id'];

            $comanda->productos()->detach();

            $precio_total = 0;
            foreach ($validatedData['productos'] as $producto_id => $cantidad) {
                if ($cantidad > 0) {
                    $producto = Producto::find($producto_id);
                    $especificaciones = $validatedData['especificaciones'][$producto_id] ?? '';
                    $comanda->productos()->attach($producto_id, [
                        'cantidad' => $cantidad,
                        'precio' => $producto->precio,
                        'especificaciones' => $especificaciones,
                    ]);
                    $precio_total += $producto->precio * $cantidad;
                }
            }
            $comanda->precio_total = $precio_total;
            $comanda->save();

            DB::commit();

            event(new ComandaUpdated($comanda));

            return redirect()->route('camarero.index')->with('success', 'Comanda actualizada con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al actualizar la comanda: ' . $e->getMessage());
        }
    }
}
