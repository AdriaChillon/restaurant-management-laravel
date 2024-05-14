<?php

namespace App\Http\Controllers;

use App\Events\ComandaUpdated;
use App\Models\Comanda;
use Illuminate\Contracts\View\View;
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
        // Aquí puedes manejar las actualizaciones necesarias en la vista del cocinero
        // Por ejemplo, puedes cargar de nuevo todas las comandas activas y devolver la vista actualizada
        $comandas = Comanda::where('en_marcha', true)->get();
        return view('cocinero.index', compact('comandas'));
    }
}
