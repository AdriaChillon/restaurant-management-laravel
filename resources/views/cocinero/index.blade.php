@extends('layouts.normal')

@section('content')
<div class="container">
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
        <form action="{{ route('cocinero.cambiarEstado', $comanda->id) }}" method="POST" class="inline-block">
            @csrf
            @method('PUT')
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Terminada</button>
        </form>
    </div>
    @empty
    <p>No hay comandas activas.</p>
    @endforelse
</div>
<script>
    // // Función para actualizar la vista del cocinero
    // function actualizarVistaCocinero() {
    //     // Obtener el token CSRF de la etiqueta meta CSRF
    //     var csrfToken = $('meta[name="csrf-token"]').attr('content');

    //     // Agregar el token CSRF a la solicitud AJAX
    //     $.ajax({
    //         url: "{{ route('comanda.updated') }}",
    //         method: 'POST',
    //         headers: {
    //             'X-CSRF-TOKEN': csrfToken
    //         },
    //         success: function(response) {
    //             // Actualizar la vista del cocinero con la respuesta del servidor
    //             $('#cocinero-container').html(response);
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error al actualizar la vista del cocinero:', error);
    //         }
    //     });
    // }

    // // Llamar a la función cada 10 segundos
    // setInterval(actualizarVistaCocinero, 10000); // 10000 milisegundos = 10 segundos

    // Función para recargar la página cada 10 segundos
function recargarPagina() {
    location.reload(); // Esta función recarga la página
}

// Llamar a la función para recargar la página cada 10 segundos
setInterval(recargarPagina, 10000); // 10000 milisegundos = 10 segundos

</script>
@endsection