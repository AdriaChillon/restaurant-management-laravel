@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Mesa</h1>
    <form action="{{ route('mesas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="numero">Número</label>
            <input type="text" class="form-control" id="numero" name="numero" required>
        </div>
        <div class="form-group">
            <label for="capacidad">Capacidad</label>
            <input type="number" class="form-control" id="capacidad" name="capacidad" required>
        </div>
        <div class="form-group">
            <label for="ubicacion">Ubicación</label>
            <input type="text" class="form-control" id="ubicacion" name="ubicacion">
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-sm btn-warning">Cancelar</a>
        <button type="submit" class="btn btn-primary">Agregar Mesa</button>
    </form>
</div>
@endsection
