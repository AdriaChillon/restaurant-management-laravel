@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-8">Productos</h1>
    <div class="mb-6">
        <a href="{{ route('productos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">Agregar Producto</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Precio</th>
                    <th class="py-3 px-6 text-left">Descripción</th>
                    <th class="py-3 px-6 text-left">Categoría</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($productos as $producto)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $producto->producto_id }}</td>
                    <td class="py-3 px-6 text-left">{{ $producto->nombre }}</td>
                    <td class="py-3 px-6 text-left">{{ $producto->precio }}</td>
                    <td class="py-3 px-6 text-left">{{ $producto->descripcion }}</td>
                    <td class="py-3 px-6 text-left">{{ $producto->categoria->nombre }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('productos.edit', $producto->id) }}" class="mr-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Editar</a>
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
