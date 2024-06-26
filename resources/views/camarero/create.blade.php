@extends(Auth::user()->hasRole('admin') ? 'layouts.app' : 'layouts.normal')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Crear Comanda</h2>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('camarero.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="mesa_id" class="block text-sm font-medium text-gray-700">Número de Mesa:</label>
            <select name="mesa_id" id="mesa_id" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                @foreach ($mesas as $mesa)
                <option value="{{ $mesa->id }}">{{ $mesa->numero }}</option>
                @endforeach
            </select>
        </div>

        <div class="space-y-4">
            @foreach ($categorias as $index => $categoria)
            <div x-data="{ open: false }">
                <button @click="open = !open" type="button" class="flex justify-between items-center w-full p-3 text-left text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                    <span>{{ $categoria->nombre }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 011.414 0L10 12.586l3.293-2.879a1 1 0 011.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="mt-2 bg-white shadow overflow-hidden rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($categoria->productos as $producto)
                        <li class="p-3 flex justify-between items-center">
                            <div class="flex items-center">
                                <input type="number" name="productos[{{ $producto->id }}]" value="0" min="0" class="form-input w-16 text-center" id="producto{{ $producto->id }}">
                                <div class="ml-3 flex">
                                    <button type="button" onclick="decreaseQuantity('{{ $producto->id }}')" class="focus:outline-none">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" onclick="increaseQuantity('{{ $producto->id }}')" class="ml-2 focus:outline-none">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($producto->imagen)
                            <img src="{{ asset('storage/'.$producto->imagen) }}" alt="{{ $producto->nombre }}" class="ml-3 h-20 w-20 object-cover rounded-lg">
                            @else
                            <p class="ml-3 text-gray-600"></p>
                            @endif
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
