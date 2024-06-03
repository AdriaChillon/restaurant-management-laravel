@extends('layouts.default')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-white mb-6">Dashboard</h2>
        <p class="text-lg text-gray-300 mb-4">Bienvenido a tu dashboard. No tienes roles asignados actualmente.</p>
        <p class="text-lg text-gray-300">Contacta con el administrador para que te asigne los roles adecuados.</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function checkUserRole() {
            $.ajax({
                url: "{{ route('check.role') }}",
                method: "GET",
                success: function(data) {
                    if (data.role) {
                        if (data.role === 'admin') {
                            window.location.href = "{{ route('productos.index') }}";
                        } else if (data.role === 'barra') {
                            window.location.href = "{{ route('barra.index') }}";
                        } else if (data.role === 'cocina') {
                            window.location.href = "{{ route('cocinero.index') }}";
                        } else if (data.role === 'camarero') {
                            window.location.href = "{{ route('camarero.index') }}";
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al verificar el rol del usuario:', error);
                }
            });
        }

        // Verificar el rol del usuario cada 10 segundos
        setInterval(checkUserRole, 10000);
    });
</script>
@endsection
