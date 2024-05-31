<div class="w-64 h-88 rounded-lg bg-white shadow-md border-2 border-[#253080]">
    <div class=" flex flex-col items-center  rounded-t-lg">
        @php
            // Array asociativo para mapear los nombres de los roles a las URL de las imágenes
            $roleImages = [
                'cocina' =>
                    'https://st2.depositphotos.com/1341440/7182/v/450/depositphotos_71824861-stock-illustration-chef-hat-vector-black-silhouette.jpg',
                'camarero' =>
                    'https://static.vecteezy.com/system/resources/previews/005/520/446/non_2x/cartoon-drawing-of-a-waiter-vector.jpg',
                'barra' =>
                    'https://st5.depositphotos.com/1432405/67415/v/450/depositphotos_674150990-stock-illustration-modern-bar-icon-outline-vector.jpg',
                'admin' =>
                    'https://img.freepik.com/vector-gratis/ilustracion-concepto-abstracto-administrador-cuenta-administracion-cuentas-software-trabajo-administrador-linea-procesamiento-consultas-administracion-plataformas-administrador-transmisiones_335657-3358.jpg',
            ];
        @endphp
        {{-- Verificar el rol del usuario y mostrar la imagen correspondiente --}}
        @foreach ($rolesUsuario as $role)
            @if (array_key_exists($role->name, $roleImages))
                <img src="{{ $roleImages[$role->name] }}" alt="{{ $name }}"
                    class="w-20 h-20 mt-7 border-4 border-white rounded-full shadow-md  ">
            @break
        @endif
    @endforeach
    <h1 class="mt-2 text-lg font-bold">{{ $name }}</h1>
    @foreach ($rolesUsuario as $role)
        @if ($role->name === 'admin')
            <h3 class="text-sm bg-gray-300 text-gray-700 px-2 py-1 rounded-full mt-1">{{ $role->name }}</h3>
        @break
    @endif
    <h3 class="text-sm bg-gray-300 text-gray-700 px-2 py-1 rounded-full mt-1">{{ $role->name }}</h3>
@endforeach
</div>
<div class="flex flex-col items-center h-1/2 bg-gray-100 rounded-b-lg mt-8">
<div class="text-center mt-2">
    <ul>
        <li class="py-1"><a href="#" class="text-gray-500 text-base ">{{ $email }}</a></li>
        <li class="py-1"><a href="#" class="text-gray-500 text-base ">{{ $actions ?? '' }}</a></li>
        <li class="py-1">
            <form action="{{ route('roles.update', ['user' => $user->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Agregar roles seleccionables con checkboxes -->
                @foreach ($roles as $role)
                    <div class="flex items-center">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                            {{ $user->hasRole($role->name) ? 'checked' : '' }}
                            class="form-checkbox h-5 w-5 text-blue-600"><span
                            class="ml-2">{{ $role->name }}</span>
                    </div>
                @endforeach
                <br>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Actualizar
                    Rol</button>
            </form>
        </li>
    </ul>
</div>
</div>
</div>
