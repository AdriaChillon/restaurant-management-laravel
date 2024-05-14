@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mesas</h1>
    <a href="{{ route('mesas.create') }}" class="btn btn-primary mb-3">Agregar Mesa</a>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Número</th>
                <th>Capacidad</th>
                <th>Ubicación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mesas as $mesa)
            <tr>
                <td>{{ $mesa->mesa_id }}</td>
                <td>{{ $mesa->numero }}</td>
                <td>{{ $mesa->capacidad }}</td>
                <td>{{ $mesa->ubicacion }}</td>
                <td>
                    <a href="{{ route('mesas.edit', $mesa) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta mesa?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
