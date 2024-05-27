@extends(Auth::user()->hasRole('admin') ? 'layouts.app' : 'layouts.normal')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Comandas Activas</h1>
    @forelse ($comandas as $comanda)
    <div class="bg-white shadow-lg rounded-lg p-6 mb-4">
        <h2 class="text-lg font-semibold mb-2">Comanda #{{ $comanda->id }}</h2>
        <p class="mb-2">Mesa: {{ $comanda->mesa->numero }}</p>
        <p class="mb-2">Productos:</p>
        <ul class="list-disc ml-6 mb-2">
            @foreach($comanda->productos as $producto)
            <li>{{ $producto->nombre }} - Cantidad: {{ $producto->pivot->cantidad }}</li>
            @endforeach
        </ul>
        <br>
        <div class="flex justify-between items-center mt-4">
                <form action="{{ route('cocinero.cambiarEstado', $comanda->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Estás seguro de cobrar esta comanda?')">Finalizada</button>
                </form>
                <a href="{{ route('cocinero.manejarComanda', $comanda->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Gestionar Comanda</a>
            </div>
    </div>
    @empty
    <p class="text-center">No hay comandas activas.</p>
    @endforelse
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
