@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-8">Editar Mesa</h1>
    <form action="{{ route('mesas.update', $mesa->id) }}" method="POST" class="max-w-lg">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="numero" class="block text-sm font-medium text-gray-700">Número</label>
            <input type="text" id="numero" name="numero" value="{{ $mesa->numero }}" required
                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
        </div>
        <div class="mb-4">
            <label for="capacidad" class="block text-sm font-medium text-gray-700">Capacidad</label>
            <input type="number" id="capacidad" name="capacidad" value="{{ $mesa->capacidad }}" required
                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
        </div>
        <div class="mb-4">
            <label for="ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
            <input type="text" id="ubicacion" name="ubicacion" value="{{ $mesa->ubicacion }}"
                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
        </div>
        <div class="flex justify-end">
            <a href="{{ route('mesas.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded mr-4">Cancelar</a>
            <button type="submit"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded">Actualizar Mesa</button>
        </div>
    </form>
</div>
@endsection