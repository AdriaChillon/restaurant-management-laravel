@extends(Auth::user()->hasRole('admin') ? 'layouts.app' : 'layouts.normal')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Editar Comanda</h2>
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        {{ session('success') }}
    </div>
    @endif
    <form action="{{ route('camarero.update', $comanda->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="mesa_id" class="block text-sm font-medium text-gray-700">Número de Mesa:</label>
            <select name="mesa_id" id="mesa_id" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                @foreach ($mesas as $mesa)
                <option value="{{ $mesa->id }}" {{ $mesa->id == $comanda->mesa_id ? 'selected' : '' }}>
                    {{ $mesa->numero }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="space-y-4">
            @foreach ($categorias as $index => $categoria)
            <div x-data="{ open: false }">
                <button @click="open = !open" type="button" class="flex justify-between items-center w-full p-3 text-left text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                    <span>{{ $categoria->nombre }}</span>
                    <svg class="w-5 h-5" x-bind:class="{ 'transform rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="mt-2 bg-white shadow overflow-hidden rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($categoria->productos as $producto)
                        <li class="p-3 flex justify-between items-center">
                            <div class="flex items-center">
                                <input type="number" name="productos[{{ $producto->id }}]" value="{{ $comanda->productos->find($producto->id)->pivot->cantidad ?? 0 }}" min="0" class="form-input w-16 text-center" id="producto{{ $producto->id }}">
                                <div class="ml-3 flex">
                                    <button type="button" onclick="decreaseQuantity('{{ $producto->id }}')" class="focus:outline-none">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" onclick="increaseQuantity('{{ $producto->id }}')" class="ml-2 focus:outline-none">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <label class="ml-3 text-sm text-gray-600" for="producto{{ $producto->id }}">
                                {{ $producto->nombre }}
                            </label>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($producto->precio, 2) }}€</span>
                            <textarea name="especificaciones[{{ $producto->id }}]" id="especificaciones{{ $producto->id }}" class="form-input mt-1 block w-full sm:w-40 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="2" placeholder="Ingrese las especificaciones"></textarea>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6 text-center flex justify-between">
            <a href="{{ route('camarero.index') }}" class="mr-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded">Cancelar</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar Comanda</button>
        </div>
    </form>
    @vite('resources/js/app.js')
</div>

<script>
    function decreaseQuantity(id) {
        let input = document.getElementById('producto' + id);
        if (input.value > 0) {
            input.value--;
        }
    }

    function increaseQuantity(id) {
        let input = document.getElementById('producto' + id);
        input.value++;
    }
</script>
@endsection