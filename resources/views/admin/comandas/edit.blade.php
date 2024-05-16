@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-8">Editar Comanda</h1>
    <form action="{{ route('comandas.update', $comanda->id) }}" method="POST" class="max-w-lg">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="mesa_id" class="block text-gray-700 text-sm font-bold mb-2">Mesa</label>
            <select class="form-select block w-full mt-1" id="mesa_id" name="mesa_id" required>
                @foreach($mesas as $mesa)
                <option value="{{ $mesa->id }}" {{ $comanda->id == $mesa->id ? 'selected' : '' }}>{{ $mesa->numero }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="fecha_hora" class="block text-gray-700 text-sm font-bold mb-2">Fecha y Hora</label>
            <input type="datetime-local" class="form-input block w-full mt-1" id="fecha_hora" name="fecha_hora" value="{{ date('Y-m-d\TH:i', strtotime($comanda->fecha_hora)) }}" required>
        </div>
        <div class="mb-4">
            <input type="checkbox" class="form-checkbox" id="en_marcha" name="en_marcha" value="1" {{ $comanda->en_marcha ? 'checked' : '' }}>
            <label class="inline-block text-sm font-bold text-gray-700" for="en_marcha">En marcha</label>
        </div>
        <div class="mb-4">
            <input type="checkbox" class="form-checkbox" id="pagado" name="pagado" value="1" {{ $comanda->pagado ? 'checked' : '' }}>
            <label class="inline-block text-sm font-bold text-gray-700" for="pagado">Pagado</label>
        </div>
        <div class="flex justify-between">
            <a href="{{ route('comandas.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded mr-4">Cancelar</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Actualizar Comanda</button>
        </div>
    </form>
</div>
@endsection
