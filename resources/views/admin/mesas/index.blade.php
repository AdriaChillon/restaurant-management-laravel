@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-8">Mesas</h1>
    <div class="mb-6">
        <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">Agregar Mesa</button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Número</th>
                    <th class="py-3 px-6 text-left">Capacidad</th>
                    <th class="py-3 px-6 text-left">Ubicación</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($mesas as $mesa)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $mesa->numero }}</td>
                    <td class="py-3 px-6 text-left">{{ $mesa->capacidad }}</td>
                    <td class="py-3 px-6 text-left">{{ $mesa->ubicacion }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex justify-center items-center">
                            <button onclick="openEditModal('{{$mesa}}')" class="mr-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">Editar</button>
                            <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded delete-button">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para crear mesa -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold">Agregar Mesa</h3>
            <button class="text-gray-600 hover:text-gray-900" onclick="closeCreateModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('mesas.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-4">
                <label for="create-numero" class="block text-sm font-medium text-gray-700">Número</label>
                <input type="text" id="create-numero" name="numero" required
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="create-capacidad" class="block text-sm font-medium text-gray-700">Capacidad</label>
                <input type="number" id="create-capacidad" name="capacidad" required
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="create-ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" id="create-ubicacion" name="ubicacion"
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded" onclick="closeCreateModal()">Cancelar</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Agregar Mesa</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar mesa -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold">Editar Mesa</h3>
            <button class="text-gray-600 hover:text-gray-900" onclick="closeEditModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit-numero" class="block text-sm font-medium text-gray-700">Número</label>
                <input type="text" id="edit-numero" name="numero" required
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="edit-capacidad" class="block text-sm font-medium text-gray-700">Capacidad</label>
                <input type="number" id="edit-capacidad" name="capacidad" required
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="edit-ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" id="edit-ubicacion" name="ubicacion"
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded" onclick="closeEditModal()">Cancelar</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Actualizar Mesa</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(mesa) {
        document.getElementById('edit-numero').value = mesa.numero;
        document.getElementById('edit-capacidad').value = mesa.capacidad;
        document.getElementById('edit-ubicacion').value = mesa.ubicacion;
        document.getElementById('editForm').action = `/mesas/${mesa.id}`;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
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
