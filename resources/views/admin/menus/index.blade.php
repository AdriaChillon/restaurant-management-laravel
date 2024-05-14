@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Menús</h1>
    <a href="{{ route('menus.create') }}" class="btn btn-primary mb-3">Agregar Menú</a>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            <tr>
                <td>{{ $menu->menu_id }}</td>
                <td>{{ $menu->nombre }}</td>
                <td>{{ $menu->descripcion }}</td>
                <td>{{ $menu->activo ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este menú?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
