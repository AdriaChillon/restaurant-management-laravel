@extends('layouts.normal')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6">Comandas Activas</h2>
    <div class="space-y-4">
        @forelse ($comandas as $comanda)
        <div class="p-4 bg-white shadow rounded-lg">
            <h3 class="text-lg font-semibold">Comanda #{{ $comanda->id }}</h3>
            <p>Mesa: {{ $comanda->mesa->numero }}</p>
            <p>Fecha y Hora: {{ \Carbon\Carbon::parse($comanda->fecha_hora)->format('d/m/Y H:i') }}</p>
            <p>Total: {{ number_format($comanda->precio_total, 2) }}€</p>
            <ul class="list-disc ml-6 mb-2">
                @foreach($comanda->productos as $producto)
                <li>{{ $producto->nombre }} - Cantidad: {{ $producto->pivot->cantidad }}</li>
                @endforeach
            </ul>
            <a href="{{ route('camarero.edit', $comanda->id) }}" class="btn btn-sm btn-primary">Editar</a>
        </div>
        @empty
        <p>No hay comandas activas.</p>
        @endforelse
    </div>
    <div class="mt-6">
        <a href="{{ route('camarero.create') }}" class="btn btn-primary">Crear Comanda</a>
    </div>
</div>
<script>
    // Función para recargar la página cada 10 segundos
    function recargarPagina() {
        location.reload(); // Esta función recarga la página
    }

    // Llamar a la función para recargar la página cada 10 segundos
    setInterval(recargarPagina, 10000); // 10000 milisegundos = 10 segundos
</script>
@endsection