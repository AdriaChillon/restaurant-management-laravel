@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-center">
        <div class="w-full lg:w-3/4">
            <h1 class="text-3xl font-semibold mb-4">Usuarios y Roles</h1>
            <!-- Mostrar mensajes de éxito o error -->
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
            @endif

            <!-- Tabla de usuarios y roles -->
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Nombre</th>
                        <th class="border px-4 py-2">Correo Electrónico</th>
                        <th class="border px-4 py-2">Roles</th>
                        <th class="border px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2">
                            @foreach ($user->roles as $role)
                            <span class="bg-gray-200 rounded-full px-2 py-1 text-xs font-semibold text-gray-700 mr-1">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="border px-4 py-2">
                            <form action="{{ route('roles.update', ['user' => $user->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <!-- Agregar roles seleccionables con checkboxes -->
                                @foreach ($roles as $role)
                                <div class="flex items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-600"><span class="ml-2">{{ $role->name }}</span>
                                </div>
                                @endforeach
                                <br>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Actualizar Rol</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection