@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-8">Editar Producto</h1>
    <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data" class="max-w-lg">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
            <input type="text" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2 w-full" id="nombre" name="nombre" value="{{ $producto->nombre }}" required>
        </div>
        <div class="mb-4">
            <label for="precio" class="block text-gray-700 text-sm font-bold mb-2">Precio</label>
            <input type="number" step="0.01" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2 w-full" id="precio" name="precio" value="{{ $producto->precio }}" required>
        </div>
        <div class="mb-4">
            <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
            <textarea class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2 w-full" id="descripcion" name="descripcion">{{ $producto->descripcion }}</textarea>
        </div>
        <div class="mb-4">
            <label for="imagen" class="block text-gray-700 text-sm font-bold mb-2">Imagen</label>
            <input type="file" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2 w-full" id="imagen" name="imagen">
            @if ($producto->imagen)
                <img src="{{ asset('storage/'.$producto->imagen) }}" alt="{{ $producto->nombre }}" class="mt-2 h-20 w-20 object-cover rounded-lg">
            @else
                <p class="mt-2 text-gray-600">No hay imagen disponible</p>
            @endif
        </div>
        <div class="mb-4">
            <label for="categoria_id" class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
            <select class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2 w-full" id="categoria_id" name="categoria_id" required>
                @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" @if($producto->categoria_id == $categoria->id) selected @endif>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end">
            <a href="{{ route('productos.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded mr-4">Cancelar</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Actualizar Producto</button>
        </div>
    </form>
</div>
@endsection
