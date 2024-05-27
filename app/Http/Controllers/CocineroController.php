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
}
