<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;

class BarraController extends Controller
{
    public function index()
    {
        $comandas = Comanda::where('en_marcha', false)->where('pagado', false)->get();
        return view('barra.index', compact('comandas'));
    }

    public function cobrar($id)
    {
        $comanda = Comanda::findOrFail($id);
        $comanda->pagado = true;
        $comanda->save();

        return redirect()->route('barra.index')->with('success', 'Comanda cobrada con éxito.');
    }
}
