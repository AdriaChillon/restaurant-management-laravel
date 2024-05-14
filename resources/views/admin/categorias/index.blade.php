@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Categorías</h1>
    <div class="mb-3">
        <a href="{{ route('categorias.create') }}" class="btn btn-primary">Agregar Categoría</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categorias as $categoria)
            <tr>
                <td>{{ $categoria->categoria_id }}</td>
                <td>{{ $categoria->nombre }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Acciones">
                        <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-primary  mr-2">Editar</a>
                        <form action="{{ route('categorias.destroy', $categoria) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar esta categoría?')">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
