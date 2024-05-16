@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <div class="py-8">
            <h2 class="text-2xl font-semibold text-gray-900 text-center mb-8">Asignar Roles</h2>
            
            <form action="{{ route('admin.assign-role') }}" method="POST" class="mx-auto w-full max-w-md">
                @csrf
                
                <div class="mb-4">
                    <label for="user" class="block text-sm font-medium text-gray-700">Usuario</label>
                    <select id="user" name="user_id" class="mt-1 block w-full p-2 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select id="role" name="role" class="mt-1 block w-full p-2 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Asignar Rol</button>
                </div>
            </form>
        </div>
    </div>
@endsection
