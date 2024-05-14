@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Comandas</h1>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Mesa</th>
                <th>Fecha y Hora</th>
                <th>En Marcha</th>
                <th>Precio Total</th>
                <th>Pagado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comandas as $comanda)
            <tr>
                <td>{{ $comanda->id }}</td>
                <td>{{ $comanda->mesa->numero }}</td>
                <td>{{ $comanda->fecha_hora }}</td>
                <td>{{ $comanda->en_marcha ? 'Sí' : 'No' }}</td>
                <td>{{ $comanda->precio_total }}€</td>
                <td>{{ $comanda->pagado ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('comandas.edit', $comanda->id) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('comandas.destroy', $comanda->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta comanda?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
