<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comanda;
use App\Models\Mesa;

class ComandaController extends Controller
{
    public function index(Request $request)
    {
        $comandas = Comanda::with('mesa');

        // Filtrar por fecha si se proporciona
        if ($request->has('fecha')) {
            $comandas->whereDate('fecha_hora', $request->fecha);
        }

        $comandas = $comandas->get();
        $mesas = Mesa::all();
        return view('admin.comandas.index', compact('comandas', 'mesas'));
    }

    public function create()
    {
        $mesas = Mesa::all();
        return view('admin.comandas.create', compact('mesas'));
    }

    public function edit(Comanda $comanda)
    {
        return response()->json($comanda);
    }

    public function update(Request $request, Comanda $comanda)
    {
        // Verificar si el checkbox está marcado
        $enMarcha = $request->has('en_marcha') ? true : false;
        $pagado = $request->has('pagado') ? true : false;

        // Actualizar los datos de la comanda
        $comanda->mesa_id = $request->mesa_id;
        $comanda->fecha_hora = $request->fecha_hora;
        $comanda->en_marcha = $enMarcha;
        $comanda->pagado = $pagado;
        $comanda->save();

        return redirect()->route('comandas.index');
    }

    public function destroy(Comanda $comanda)
    {
        $comanda->delete();
        return redirect()->route('comandas.index');
    }
}
