<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;

class MesaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::all();
        return view('admin.mesas.index', compact('mesas'));
    }

    public function create()
    {
        return view('admin.mesas.create');
    }

    public function store(Request $request)
    {
        Mesa::create($request->all());
        return redirect()->route('mesas.index');
    }

    public function edit(Mesa $mesa)
    {
        return view('admin.mesas.edit', compact('mesa'));
    }

    public function update(Request $request, Mesa $mesa)
    {
        $mesa->update($request->all());
        return redirect()->route('mesas.index');
    }

    public function destroy(Mesa $mesa)
    {
        $mesa->delete();
        return redirect()->route('mesas.index');
    }
}
