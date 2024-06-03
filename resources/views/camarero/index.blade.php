@extends(Auth::user()->hasRole('admin') ? 'layouts.app' : 'layouts.normal')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-center">
        <div class="w-full lg:w-3/4">
            <h1 class="text-2xl font-bold mb-4">Comandas Activas</h1>
            <div class="mt-6 text-right">
                <a href="{{ route('camarero.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Comanda</a>
            </div>
            <br>
            <div id="comandas-container" class="flex flex-wrap -mx-2">
                @foreach ($comandas as $comanda)
                <div class="border-2 border-[#253080] shadow-xl rounded-lg p-6 mb-4 mx-2 flex flex-col justify-between w-full md:w-1/2 lg:w-1/3 xl:w-1/3" data-comanda-id="{{ $comanda->id }}">
                    <div>
                        <h3 class="text-lg font-semibold">Comanda #{{ $comanda->id }}</h3>
                        <p><i class="fas fa-utensils"></i> Mesa: {{ $comanda->mesa->numero }}</p>
                        <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($comanda->fecha_hora)->format('d/m/Y H:i') }}</p>
                        <p><i class="fas fa-euro-sign"></i> Total: {{ number_format($comanda->precio_total, 2) }}€</p>
                        <ul class="list-disc ml-6 mb-2">
                            @foreach ($comanda->productos as $producto)
                            <li>x{{ $producto->pivot->cantidad }} {{ $producto->nombre }} -
                            {{ $producto->pivot->estado_preparacion === 'en_proceso' ? 'En proceso' : ucfirst($producto->pivot->estado_preparacion) }} - {{ $producto->pivot->especificaciones ?? 'Ninguna' }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('camarero.edit', $comanda->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Editar</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function recargarComandas() {
            fetch("{{ route('camarero.getActiveComandas') }}")
                .then(response => response.json())
                .then(data => {
                    const comandasContainer = document.getElementById('comandas-container');
                    comandasContainer.innerHTML = '';
                    data.forEach(comanda => {
                        const comandaHtml = `
                            <div class="border-2 border-[#253080] shadow-xl rounded-lg p-6 mb-4 mx-2 flex flex-col justify-between w-full md:w-1/2 lg:w-1/3 xl:w-1/3" data-comanda-id="${comanda.id}">
                                <div>
                                    <h3 class="text-lg font-semibold">Comanda #${comanda.id}</h3>
                                    <p><i class="fas fa-utensils"></i> Mesa: ${comanda.mesa.numero}</p>
                                    <p><i class="fas fa-clock"></i> ${moment(comanda.fecha_hora).format('DD/MM/YYYY HH:mm')}</p>
                                    <p><i class="fas fa-euro-sign"></i> Total: ${comanda.precio_total.toFixed(2)}€</p>
                                    <ul class="list-disc ml-6 mb-2">
                                        ${comanda.productos.map(producto => `
                                                <li>x${producto.pivot.cantidad} ${producto.nombre} - ${producto.pivot.estado_preparacion === 'en_proceso' ? 'En proceso' : capitalizeFirstLetter(producto.pivot.estado_preparacion)} - ${producto.pivot.especificaciones ?? 'Ninguna'}</li>
                                            `).join('')}
                                    </ul>
                                </div>
                                <div class="flex justify-between items-center mt-4">
                                    <a href="/camarero/${comanda.id}/edit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Editar</a>
                                </div>
                            </div>
                        `;
                        comandasContainer.innerHTML += comandaHtml;
                    });
                })
                .catch(error => console.error('Error al recargar las comandas:', error));
        }

        setInterval(recargarComandas, 10000);

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    });
</script>

@endsection