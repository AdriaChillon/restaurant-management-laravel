@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold mb-8">Productos</h1>
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('productos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">Agregar Producto</a>
        <button id="manageCategoriesButton" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">Gestionar Categorías</button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Precio</th>
                    <th class="py-3 px-6 text-left">Descripción</th>
                    <th class="py-3 px-6 text-left">Imagen</th>
                    <th class="py-3 px-6 text-left">Categoría</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($productos as $producto)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $producto->nombre }}</td>
                    <td class="py-3 px-6 text-left">{{ $producto->precio }}</td>
                    <td class="py-3 px-6 text-left">{{ $producto->descripcion }}</td>
                    <td class="py-3 px-6 text-left">
                        @if ($producto->imagen)
                        <img src="{{ asset('storage/'.$producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-20 w-20 object-cover rounded-lg">
                        @else
                        <span>No hay imagen disponible</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-left">{{ $producto->categoria->nombre }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('productos.edit', $producto->id) }}" class="mr-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Editar</a>
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="delete-product-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded delete-product-button">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- Modal para gestionar categorías -->
<div id="categoriesModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg overflow-hidden w-3/4 max-w-3xl">
        <div class="px-4 py-2 bg-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Gestionar Categorías</h2>
            <button id="closeCategoriesModal" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <div class="p-4">
            <div class="mb-6">
                <button id="addCategoryButton" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">Agregar Categoría</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Nombre</th>
                            <th class="py-3 px-6 text-left">Encargado</th>
                            <th class="py-3 px-6 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody" class="text-gray-600 text-sm font-light">
                        <!-- Aquí se cargarán las categorías dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar categoría -->
<div id="categoryFormModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg overflow-hidden w-full max-w-md">
        <div class="px-4 py-2 bg-gray-200 flex justify-between items-center">
            <h2 id="categoryFormTitle" class="text-xl font-semibold">Agregar Categoría</h2>
            <button id="closeCategoryFormModal" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <div class="p-4">
            <form id="categoryForm" method="POST">
                @csrf
                <input type="hidden" id="categoryId" name="categoryId">
                <div class="mb-4">
                    <label for="categoryName" class="block text-gray-700">Nombre</label>
                    <input type="text" id="categoryName" name="nombre" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-4">
                    <label for="roles" class="block text-gray-700">Encargado</label>
                    <select id="roles" name="roles[]" multiple class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-between">
                    <button type="button" id="cancelCategoryButton" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded">Cancelar</button>
                    <button type="submit" id="saveCategoryButton" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const manageCategoriesButton = document.getElementById('manageCategoriesButton');
    const categoriesModal = document.getElementById('categoriesModal');
    const closeCategoriesModal = document.getElementById('closeCategoriesModal');
    const addCategoryButton = document.getElementById('addCategoryButton');
    const categoryFormModal = document.getElementById('categoryFormModal');
    const closeCategoryFormModal = document.getElementById('closeCategoryFormModal');
    const categoryForm = document.getElementById('categoryForm');
    const categoryFormTitle = document.getElementById('categoryFormTitle');
    const categoryId = document.getElementById('categoryId');
    const categoryName = document.getElementById('categoryName');
    const encargadoSelect = document.getElementById('roles'); // Campo para los roles (Encargado)
    const categoriesTableBody = document.getElementById('categoriesTableBody');

    manageCategoriesButton.addEventListener('click', function() {
        categoriesModal.classList.remove('hidden');
        loadCategories();
    });

    closeCategoriesModal.addEventListener('click', function() {
        categoriesModal.classList.add('hidden');
    });

    addCategoryButton.addEventListener('click', function() {
        openCategoryFormModal('Agregar Categoría', '', '');
    });

    closeCategoryFormModal.addEventListener('click', function() {
        categoryFormModal.classList.add('hidden');
    });

    categoryForm.addEventListener('submit', function(event) {
        event.preventDefault();
        saveCategory();
    });

    document.getElementById('cancelCategoryButton').addEventListener('click', function() {
        categoryFormModal.classList.add('hidden');
    });

    function openCategoryFormModal(title, id, name) {
        categoryFormTitle.textContent = title;
        categoryId.value = id; // Campo oculto para el ID
        categoryName.value = name; // Cargar el nombre en el campo de texto

        // Limpiar la selección de encargados (roles)
        encargadoSelect.querySelectorAll('option').forEach(option => {
            option.selected = false;
        });

        // Si se está editando, cargar los roles seleccionados
        if (id) {
            fetch(`/categorias/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    data.categoria.roles.forEach(role => {
                        encargadoSelect.querySelector(`option[value="${role.id}"]`).selected = true;
                    });
                });
        }

        categoryFormModal.classList.remove('hidden');
    }

    function loadCategories() {
        fetch('/categorias')
            .then(response => response.json())
            .then(data => {
                categoriesTableBody.innerHTML = '';
                data.categorias.forEach(categoria => {
                    const encargados = categoria.roles.map(role => role.name).join(', ');
                    const row = document.createElement('tr');
                    row.classList.add('border-b', 'border-gray-200', 'hover:bg-gray-100');
                    row.innerHTML = `
                        <td class="py-3 px-6 text-left">${categoria.nombre}</td>
                        <td class="py-3 px-6 text-left">${encargados}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex justify-center items-center">
                                <button class="mr-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded" onclick="editCategory(${categoria.id}, '${categoria.nombre}')">Editar</button>
                                <button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded" onclick="confirmDeleteCategory(${categoria.id})">Eliminar</button>
                            </div>
                        </td>
                    `;
                    categoriesTableBody.appendChild(row);
                });
            });
    }

    window.confirmDeleteCategory = function(id) {
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
                deleteCategory(id);
            }
        });
    };

    function deleteCategory(id) {
        console.log('Deleting category with id:', id); // Debugging line
        fetch(`/categorias/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Category deleted successfully:', data); // Debugging line
                    loadCategories();
                } else {
                    Swal.fire('Error', 'No se pudo eliminar la categoría.', 'error');
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                Swal.fire('Error', 'No se pudo eliminar la categoría.', 'error');
            });
    }

    function saveCategory() {
        const id = categoryId.value;
        const url = id ? `/categorias/${id}` : `/categorias`;
        const method = id ? 'PUT' : 'POST';

        const data = {
            nombre: categoryName.value,
            roles: Array.from(encargadoSelect.selectedOptions).map(option => option.value) // Obtener los roles seleccionados
        };

        fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    categoryFormModal.classList.add('hidden');
                    loadCategories();
                } else {
                    Swal.fire('Error', 'No se pudo guardar la categoría.', 'error');
                }
            });
    }

    window.editCategory = function(id, name) {
        openCategoryFormModal('Editar Categoría', id, name);
    };

    // Agregar funcionalidad de confirmación de eliminación para productos
    document.querySelectorAll('.delete-product-button').forEach(button => {
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



