@extends(Auth::user()->hasRole('admin') ? 'layouts.app' : 'layouts.normal')

@section('content')
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
    <div id="comandas-container">
        @foreach ($comandas as $comanda)
            <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda" data-comanda-id="{{ $comanda->id }}">
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
                    <form class="form-finalizar" action="{{ route('cocinero.cambiarEstado', ['id' => $comanda->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn-finalizar bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Finalizada</button>
                    </form>
                    <a href="{{ route('cocinero.manejarComanda', ['comanda' => $comanda->id]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Gestionar Comanda</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function recargarComandas() {
            fetch("{{ route('cocinero.getActiveComandas') }}")
                .then(response => response.json())
                .then(data => {
                    const comandasContainer = document.getElementById('comandas-container');
                    comandasContainer.innerHTML = '';
                    data.forEach(comanda => {
                        const comandaHtml = `
                            <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda" data-comanda-id="${comanda.id}">
                                <h2 class="text-lg font-semibold mb-2">Comanda #${comanda.id}</h2>
                                <p class="mb-2">Mesa: ${comanda.mesa.numero}</p>
                                <p class="mb-2">Fecha y Hora: ${new Date(comanda.fecha_hora).toLocaleString()}</p>
                                <p class="mb-2">Productos:</p>
                                <ul class="list-disc ml-6 mb-2">
                                    ${comanda.productos.map(producto => `
                                        <li>${producto.nombre} - Cantidad: ${producto.pivot.cantidad} - Estado: ${producto.pivot.estado_preparacion === 'en_proceso' ? 'En proceso' : capitalizeFirstLetter(producto.pivot.estado_preparacion)}</li>
                                    `).join('')}
                                </ul>
                                <p>Total: <b>${comanda.precio_total.toFixed(2)}€</b></p>
                                <div class="flex justify-between items-center mt-4">
                                    <form class="form-finalizar" action="/cocinero/${comanda.id}/cambiarEstado" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" class="btn-finalizar bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Finalizada</button>
                                    </form>
                                    <a href="/cocinero/manejar-comanda/${comanda.id}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Gestionar Comanda</a>
                                </div>
                            </div>
                        `;
                        comandasContainer.innerHTML += comandaHtml;
                    });

                    // Rebind event listeners to the newly added elements
                    bindFinalizarButtons();
                })
                .catch(error => console.error('Error al recargar las comandas:', error));
        }

        function bindFinalizarButtons() {
            document.querySelectorAll('.btn-finalizar').forEach(function(button) {
                button.addEventListener('click', function() {
                    const form = this.closest('.form-finalizar');
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
        }

        // Llamar a la función para recargar las comandas cada 10 segundos
        setInterval(recargarComandas, 10000);

        // Llamar a bindFinalizarButtons para los botones existentes en la carga inicial
        bindFinalizarButtons();

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    });
</script>
@endsection
