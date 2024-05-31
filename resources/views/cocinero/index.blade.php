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
        @php
            $comandas_activas = $comandas->where('desactivada', false);
            $comandas_completadas = $comandas->where('desactivada', true);
        @endphp

        @foreach ($comandas_activas as $comanda)
            <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda" data-comanda-id="{{ $comanda->id }}">
                <h2 class="text-lg font-semibold mb-2">Comanda #{{ $comanda->id }}</h2>
                <p class="mb-2">Mesa: {{ $comanda->mesa->numero }}</p>
                <p class="mb-2">Fecha y Hora: {{ \Carbon\Carbon::parse($comanda->fecha_hora)->format('d/m/Y H:i') }}</p>
                <form action="{{ route('cocinero.actualizarEstadoProductos', $comanda->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <p class="font-semibold mb-1">Productos:</p>
                        <ul>
                            @foreach($comanda->productos->reject(function ($producto) { return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes'; }) as $producto)
                            <li class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <select name="estado_preparacion_{{ $producto->id }}" class="block w-32 py-1 px-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="pendiente" @if ($producto->pivot->estado_preparacion === 'pendiente') selected @endif>Pendiente</option>
                                        <option value="en_proceso" @if ($producto->pivot->estado_preparacion === 'en_proceso') selected @endif>En Proceso</option>
                                        <option value="listo" @if ($producto->pivot->estado_preparacion === 'listo') selected @endif>Listo</option>
                                    </select>
                                    <span class="ml-4">x{{ $producto->pivot->cantidad }} {{ $producto->nombre }} - {{ ucfirst($producto->pivot->estado_preparacion) }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="mt-6 px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded-md text-white font-bold">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        @endforeach

        @foreach ($comandas_completadas as $comanda)
            <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda opacity-50 pointer-events-none" data-comanda-id="{{ $comanda->id }}">
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
                    <button class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed" disabled>Gestionar Comanda</button>
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
                            <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda ${comanda.desactivada ? 'opacity-50 pointer-events-none' : ''}" data-comanda-id="${comanda.id}">
                                <h2 class="text-lg font-semibold mb-2">Comanda #${comanda.id}</h2>
                                <p class="mb-2">Mesa: ${comanda.mesa.numero}</p>
                                <p class="mb-2">Fecha y Hora: ${new Date(comanda.fecha_hora).toLocaleString()}</p>
                                <form action="/cocinero/actualizar-estado-productos/${comanda.id}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-2">
                                        <p class="font-semibold mb-1">Productos:</p>
                                        <ul>
                                            ${comanda.productos.filter(producto => producto.categoria.nombre !== 'Refrescos' && producto.categoria.nombre !== 'Cafes').map(producto => `
                                            <li class="flex justify-between items-center mb-2">
                                                <div class="flex items-center">
                                                    <select name="estado_preparacion_${producto.id}" class="block w-32 py-1 px-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <option value="pendiente" ${producto.pivot.estado_preparacion === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                                                        <option value="en_proceso" ${producto.pivot.estado_preparacion === 'en_proceso' ? 'selected' : ''}>En Proceso</option>
                                                        <option value="listo" ${producto.pivot.estado_preparacion === 'listo' ? 'selected' : ''}>Listo</option>
                                                    </select>
                                                    <span class="ml-4">x${producto.pivot.cantidad} ${producto.nombre} - ${capitalizeFirstLetter(producto.pivot.estado_preparacion)}</span>
                                                </div>
                                            </li>
                                            `).join('')}
                                        </ul>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="mt-6 px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded-md text-white font-bold">Guardar Cambios</button>
                                    </div>
                                </form>
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
