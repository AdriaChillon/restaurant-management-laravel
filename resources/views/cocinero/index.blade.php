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
            <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda {{ $comanda->desactivada ? 'opacity-50 pointer-events-none' : '' }}" data-comanda-id="{{ $comanda->id }}">
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
                @if(!$comanda->desactivada)
                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('cocinero.manejarComanda', ['comanda' => $comanda->id]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Gestionar Comanda</a>
                    </div>
                @endif
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
                            <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda ${comanda.desactivada ? 'opacity-50 pointer-events-none' : ''}" data-comanda-id="${comanda.id}">
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
                                ${!comanda.desactivada ? `
                                <div class="flex justify-between items-center mt-4">
                                    <a href="/cocinero/manejar-comanda/${comanda.id}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Gestionar Comanda</a>
                                </div>` : ''}
                            </div>
                        `;
                        comandasContainer.innerHTML += comandaHtml;
                    });
                })
                .catch(error => console.error('Error al recargar las comandas:', error));
        }

        // Llamar a la función para recargar las comandas cada 10 segundos
        setInterval(recargarComandas, 10000);

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    });
</script>
@endsection
