@extends(Auth::user()->hasRole('admin') ? 'layouts.app' : 'layouts.normal')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-4">Comandas Activas</h2>
    <div class="mt-6">
        <a href="{{ route('camarero.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Comanda</a>
    </div>
    <br>
    <div id="comandas-container" class="space-y-4">
        @foreach ($comandas as $comanda)
        <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda" data-comanda-id="{{ $comanda->id }}">
            <h3 class="text-lg font-semibold">Comanda #{{ $comanda->id }}</h3>
            <p>Mesa: {{ $comanda->mesa->numero }}</p>
            <p>Fecha y Hora: {{ \Carbon\Carbon::parse($comanda->fecha_hora)->format('d/m/Y H:i') }}</p>
            <p>Total: {{ number_format($comanda->precio_total, 2) }}€</p>
            <ul class="list-disc ml-6 mb-2">
                @foreach($comanda->productos as $producto)
                <li>x{{ $producto->pivot->cantidad }} {{ $producto->nombre }} - Estado: {{ $producto->pivot->estado_preparacion === 'en_proceso' ? 'En proceso' : ucfirst($producto->pivot->estado_preparacion) }}</li>
                @endforeach
            </ul>
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('camarero.edit', $comanda->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Editar</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para recargar las comandas
        function recargarComandas() {
            fetch('{{ route("camarero.getActiveComandas") }}')
                .then(response => response.json())
                .then(data => {
                    const comandasContainer = document.getElementById('comandas-container');
                    comandasContainer.innerHTML = '';
                    data.forEach(comanda => {
                        const comandaHtml = `
                            <div class="bg-white shadow-lg rounded-lg p-6 mb-4 comanda" data-comanda-id="${comanda.id}">
                                <h3 class="text-lg font-semibold">Comanda #${comanda.id}</h3>
                                <p>Mesa: ${comanda.mesa.numero}</p>
                                <p>Fecha y Hora: ${new Date(comanda.fecha_hora).toLocaleString()}</p>
                                <p>Total: ${comanda.precio_total.toFixed(2)}€</p>
                                <ul class="list-disc ml-6 mb-2">
                                    ${comanda.productos.map(producto => `
                                        <li>x${producto.pivot.cantidad} ${producto.nombre} - Estado: ${producto.pivot.estado_preparacion === 'en_proceso' ? 'En proceso' : capitalizeFirstLetter(producto.pivot.estado_preparacion)}</li>
                                    `).join('')}
                                </ul>
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

        // Llamar a la función para recargar las comandas cada 10 segundos
        setInterval(recargarComandas, 10000); // 10000 milisegundos = 10 segundos

        // Función para capitalizar la primera letra de una cadena
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    });
</script>
@endsection
