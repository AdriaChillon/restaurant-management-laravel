@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-8">Comandas</h1>
    <div class="mb-4">
        <form action="{{ route('comandas.index') }}" method="GET">
            <label for="fecha">Buscar por fecha:</label>
            <input type="date" id="fecha" name="fecha" value="{{ request()->input('fecha') }}" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Buscar</button>
        </form>
    </div>
    <table class="table-auto w-full">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Mesa</th>
                <th class="px-4 py-2">Fecha y Hora</th>
                <th class="px-4 py-2">En Marcha</th>
                <th class="px-4 py-2">Precio Total</th>
                <th class="px-4 py-2">Pagado</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach($comandas as $comanda)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="px-4 py-2">{{ $comanda->id }}</td>
                <td class="px-4 py-2">{{ $comanda->mesa->numero }}</td>
                <td class="px-4 py-2">{{ $comanda->fecha_hora }}</td>
                <td class="px-4 py-2">{{ $comanda->en_marcha ? 'Sí' : 'No' }}</td>
                <td class="px-4 py-2">{{ $comanda->precio_total }}€</td>
                <td class="px-4 py-2">{{ $comanda->pagado ? 'Sí' : 'No' }}</td>
                <td class="px-4 py-2">
                    <div class="flex justify-center items-center">
                        <a href="{{ route('comandas.edit', $comanda->id) }}" class="mr-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded">Editar</a>
                        <form action="{{ route('comandas.destroy', $comanda->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded" onclick="return confirm('¿Estás seguro de que deseas eliminar esta comanda?')">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
