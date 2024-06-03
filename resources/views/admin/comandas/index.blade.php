@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-8">Comandas</h1>
    <div class="mb-4">
        <form action="{{ route('comandas.index') }}" method="GET" class="flex flex-wrap items-center space-x-4">
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700">Buscar por fecha:</label>
                <input type="date" id="fecha" name="fecha" value="{{ request()->input('fecha') }}" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2">
            </div>
            <div>
                <label for="mesa_id" class="block text-sm font-medium text-gray-700">Mesa:</label>
                <select id="mesa_id" name="mesa_id" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2">
                    <option value="">Todas</option>
                    @foreach($mesas as $mesa)
                        <option value="{{ $mesa->id }}" @if(request()->input('mesa_id') == $mesa->id) selected @endif>{{ $mesa->numero }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="precio_min" class="block text-sm font-medium text-gray-700">Precio mínimo:</label>
                <input type="number" step="0.01" id="precio_min" name="precio_min" value="{{ request()->input('precio_min') }}" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2">
            </div>
            <div>
                <label for="precio_max" class="block text-sm font-medium text-gray-700">Precio máximo:</label>
                <input type="number" step="0.01" id="precio_max" name="precio_max" value="{{ request()->input('precio_max') }}" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-md px-4 py-2">
            </div>
            <div class="self-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-6">Buscar</button>
            </div>
        </form>
    </div>
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="px-6 py-3 text-left">Mesa</th>
                <th class="px-6 py-3 text-left">Fecha y Hora</th>
                <th class="px-6 py-3 text-left">En Marcha</th>
                <th class="px-6 py-3 text-left">Precio Total</th>
                <th class="px-6 py-3 text-left">Pagado</th>
                <th class="px-6 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach($comandas as $comanda)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="px-6 py-3 text-left">{{ $comanda->mesa->numero }}</td>
                <td class="px-6 py-3 text-left">{{ $comanda->fecha_hora }}</td>
                <td class="px-6 py-3 text-left">{{ $comanda->en_marcha ? 'Sí' : 'No' }}</td>
                <td class="px-6 py-3 text-left">{{ $comanda->precio_total }}€</td>
                <td class="px-6 py-3 text-left">{{ $comanda->pagado ? 'Sí' : 'No' }}</td>
                <td class="px-6 py-3 text-center">
                    <div class="flex justify-center items-center space-x-2">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded" onclick="openEditModal('{{ $comanda->id }}')">Editar</button>
                        <form action="{{ route('comandas.destroy', $comanda->id) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded delete-button">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold">Editar Comanda</h3>
            <button class="text-gray-600 hover:text-gray-900" onclick="closeEditModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editComandaForm" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="mesa_id" class="block text-gray-700 text-sm font-bold mb-2">Mesa</label>
                    <select class="form-select block w-full mt-1" id="mesa_id" name="mesa_id" required>
                        @foreach($mesas as $mesa)
                        <option value="{{ $mesa->id }}">{{ $mesa->numero }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="fecha_hora" class="block text-gray-700 text-sm font-bold mb-2">Fecha y Hora</label>
                    <input type="datetime-local" class="form-input block w-full mt-1" id="fecha_hora" name="fecha_hora" required>
                </div>
                <div class="mb-4">
                    <input type="checkbox" class="form-checkbox" id="en_marcha" name="en_marcha" value="1">
                    <label class="inline-block text-sm font-bold text-gray-700" for="en_marcha">En marcha</label>
                </div>
                <div class="mb-4">
                    <input type="checkbox" class="form-checkbox" id="pagado" name="pagado" value="1">
                    <label class="inline-block text-sm font-bold text-gray-700" for="pagado">Pagado</label>
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded" onclick="closeEditModal()">Cancelar</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Actualizar Comanda</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const editModal = document.getElementById('editModal');
    const editComandaForm = document.getElementById('editComandaForm');

    function openEditModal(comandaId) {
        fetch(`/comandas/${comandaId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                editComandaForm.action = `/comandas/${comandaId}`;
                document.getElementById('mesa_id').value = data.mesa_id;
                document.getElementById('fecha_hora').value = new Date(data.fecha_hora).toISOString().slice(0, 16);
                document.getElementById('en_marcha').checked = data.en_marcha;
                document.getElementById('pagado').checked = data.pagado;
                editModal.classList.remove('hidden');
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    function closeEditModal() {
        editModal.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
