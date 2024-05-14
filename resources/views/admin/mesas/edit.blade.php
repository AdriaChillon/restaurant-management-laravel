@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Mesa</h1>
    <form action="{{ route('mesas.update', $mesa->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="numero">Número</label>
            <input type="text" class="form-control" id="numero" name="numero" value="{{ $mesa->numero }}" required>
        </div>
        <div class="form-group">
            <label for="capacidad">Capacidad</label>
            <input type="number" class="form-control" id="capacidad" name="capacidad" value="{{ $mesa->capacidad }}" required>
        </div>
        <div class="form-group">
            <label for="ubicacion">Ubicación</label>
            <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="{{ $mesa->ubicacion }}">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Mesa</button>
    </form>
</div>
@endsection
