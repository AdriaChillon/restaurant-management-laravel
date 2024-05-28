@extends(Auth::user()->hasRole('admin') ? 'layouts.app' : 'layouts.normal')

@section('content')
<!-- Incluye SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4">
@if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    <h1 class="text-2xl font-bold mb-4">Comandas Activas</h1>
    @forelse ($comandas as $comanda)
    <div class="bg-white shadow-lg rounded-lg p-6 mb-4">
        <h2 class="text-lg font-semibold mb-2">Comanda #{{ $comanda->id }}</h2>
        <p class="mb-2">Mesa: {{ $comanda->mesa->numero }}</p>
        <p class="mb-2">Fecha y Hora: {{ \Carbon\Carbon::parse($comanda->fecha_hora)->format('d/m/Y H:i') }}</p>
        <p class="mb-2">Productos:</p>
        <ul class="list-disc ml-6 mb-2">
            @foreach($comanda->productos as $producto)
            <li>{{ $producto->nombre }} - Cantidad: {{ $producto->pivot->cantidad }} - Estado: {{ $producto->pivot->estado_preparacion === 'en_proceso' ? 'En proceso' : ucfirst($producto->pivot->estado_preparacion) }}</li>
            @endforeach
        </ul>
        <p>Total: <b>{{ number_format($comanda->precio_total, 2) }}€</b></p>
        <div class="flex justify-between items-center mt-4">
            <form class="form-finalizar" action="{{ route('cocinero.cambiarEstado', $comanda->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Finalizada</button>
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

    // Manejar la confirmación del botón "Finalizada" con SweetAlert2
    document.querySelectorAll('.form-finalizar').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, finalizar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
