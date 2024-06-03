@extends(Auth::user()->hasRole('admin') ? 'layouts.app' : 'layouts.normal')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-center">
        <div class="w-full lg:w-3/4">
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

            <h1 class="text-2xl font-bold mb-4">Comandas Pendientes de Cobro</h1>
            <div id="comandas-container" class="flex flex-wrap -mx-2">
                @foreach ($comandas as $comanda)
                <div class="border-2 border-[#253080] shadow-xl rounded-lg p-6 mb-4 mx-2 flex flex-col justify-between w-full md:w-1/2 lg:w-1/2 xl:w-1/2" data-comanda-id="{{ $comanda->id }}">
                    <div>
                        <h3 class="text-lg font-semibold">Comanda #{{ $comanda->id }}</h3>
                        <p><i class="fas fa-utensils"></i> Mesa: {{ $comanda->mesa->numero }}</p>
                        <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($comanda->fecha_hora)->format('d/m/Y H:i') }}</p>
                        <p><i class="fas fa-euro-sign"></i> Total: {{ number_format($comanda->precio_total, 2) }}€</p>
                        <p class="font-semibold mb-1">Productos:</p>
                        <form action="{{ route('barra.actualizarEstadoProductos', $comanda->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <ul class="list-disc ml-6 mb-2">
                                @foreach ($comanda->productos->filter(function ($producto) {
                                return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes';
                                }) as $producto)
                                <li class="flex justify-between items-center mb-2">
                                    <div class="flex items-center">
                                        <select name="estado_preparacion_{{ $producto->id }}" class="block w-48 py-1 px-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="pendiente" @if ($producto->pivot->estado_preparacion === 'pendiente') selected @endif>Pendiente</option>
                                            <option value="en_proceso" @if ($producto->pivot->estado_preparacion === 'en_proceso') selected @endif>En Proceso</option>
                                            <option value="listo" @if ($producto->pivot->estado_preparacion === 'listo') selected @endif>Listo</option>
                                        </select>
                                        <span class="ml-4">x{{ $producto->pivot->cantidad }} {{ $producto->nombre }} - {{ $producto->pivot->especificaciones ?: 'Ninguna' }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <div class="flex justify-between items-center mt-4">
                                <button type="submit" class="btn-guardar bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-between mt-4 space-x-4">
                        <form class="form-cobrar" action="{{ route('barra.cobrar', $comanda->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="button" class="btn-cobrar bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Marcar como pagada</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function recargarComandas() {
            fetch("{{ route('barra.getPendingComandas') }}")
                .then(response => response.json())
                .then(data => {
                    const comandasContainer = document.getElementById('comandas-container');
                    comandasContainer.innerHTML = '';
                    data.forEach(comanda => {
                        const comandaHtml = `
                        <div class="border-2 border-[#253080] shadow-xl rounded-lg p-6 mb-4 mx-2 flex flex-col justify-between w-full md:w-1/2 lg:w-1/2 xl:w-1/2" data-comanda-id="${comanda.id}">
                            <div>
                                <h3 class="text-lg font-semibold">Comanda #${comanda.id}</h3>
                                <p><i class="fas fa-utensils"></i> Mesa: ${comanda.mesa.numero}</p>
                                <p><i class="fas fa-clock"></i> ${moment(comanda.fecha_hora).format('DD/MM/YYYY HH:mm')}</p>
                                <p><i class="fas fa-euro-sign"></i> Total: ${comanda.precio_total.toFixed(2)}€</p>
                                <p class="font-semibold mb-1">Productos:</p>
                                <form action="/barra/comanda/${comanda.id}/actualizar-productos" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <ul class="list-disc ml-6 mb-2">
                                        ${comanda.productos.filter(producto => producto.categoria.nombre === 'Refrescos' || producto.categoria.nombre === 'Cafes').map(producto => `
                                            <li class="flex justify-between items-center mb-2">
                                                <div class="flex items-center">
                                                    <select name="estado_preparacion_${producto.id}" class="block w-48 py-1 px-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <option value="pendiente" ${producto.pivot.estado_preparacion === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                                                        <option value="en_proceso" ${producto.pivot.estado_preparacion === 'en_proceso' ? 'selected' : ''}>En Proceso</option>
                                                        <option value="listo" ${producto.pivot.estado_preparacion === 'listo' ? 'selected' : ''}>Listo</option>
                                                    </select>
                                                    <span class="ml-4">x${producto.pivot.cantidad} ${producto.nombre} - ${producto.pivot.especificaciones || 'Ninguna'}</span>
                                                </div>
                                            </li>
                                        `).join('')}
                                    </ul>
                                    <div class="flex justify-between items-center mt-4">
                                        <button type="submit" class="btn-guardar px-4 py-2 bg-green-500 hover:bg-green-700 rounded-md text-white font-bold">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                            <div class="flex justify-between mt-4 space-x-4">
                                <form class="form-cobrar" action="/barra/${comanda.id}/cobrar" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="button" class="btn-cobrar bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Marcar como pagada</button>
                                </form>
                            </div>
                        </div>
                        `;
                        comandasContainer.innerHTML += comandaHtml;
                    });

                    // Rebind event listeners to the newly added elements
                    bindCobrarButtons();
                })
                .catch(error => console.error('Error al recargar las comandas:', error));
        }

        function bindCobrarButtons() {
            document.querySelectorAll('.btn-cobrar').forEach(function(button) {
                button.addEventListener('click', function() {
                    const form = this.closest('.form-cobrar');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, cobrar!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        }

        setInterval(recargarComandas, 10000);
        bindCobrarButtons();
    });
</script>
@endsection
