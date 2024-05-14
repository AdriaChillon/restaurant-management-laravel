@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Productos</h1>
    <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Agregar Producto</a>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->producto_id }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->precio }}</td>
                <td>{{ $producto->descripcion }}</td>
                <td>{{ $producto->categoria->nombre }}</td>
                <td>
                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
