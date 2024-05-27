@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Manejar Comanda</h1>
    <form action="{{ route('barra.actualizarEstadoProductos', $comanda->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white shadow-lg rounded-lg p-6 mb-4">
            <h2 class="text-lg font-semibold mb-2">Comanda #{{ $comanda->id }}</h2>
            <p class="mb-2">Mesa: {{ $comanda->mesa->numero }}</p>
            <div class="mb-2">
                <p class="font-semibold mb-1">Productos:</p>
                <ul>
                    @foreach($productosFiltrados as $producto)
                    <li class="flex justify-between items-center mb-2">
                        <span>{{ $producto->nombre }} - Cantidad: {{ $producto->pivot->cantidad }}</span>
                        <div>
                            <!-- Agrega un select para seleccionar el estado de preparación -->
                            <select name="estado_preparacion_{{ $producto->id }}" class="block w-32 py-1 px-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="pendiente" @if ($producto->comanda_productos && $producto->comanda_productos->estado_preparacion === 'pendiente') selected @endif>Pendiente</option>
                                <option value="en_proceso" @if ($producto->comanda_productos && $producto->comanda_productos->estado_preparacion === 'en_proceso') selected @endif>En Proceso</option>
                                <option value="listo" @if ($producto->comanda_productos && $producto->comanda_productos->estado_preparacion === 'listo') selected @endif>Listo</option>
                            </select>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="text-right">
                <!-- Cambia el tipo de botón a submit -->
                <button type="submit" class="mt-6 px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded-md text-white font-bold">Guardar Cambios</button>
            </div>
        </div>
    </form>
</div>
@endsection