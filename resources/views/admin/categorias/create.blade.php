@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Categoría</h1>
    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-sm btn-warning">Cancelar</a>
        <button type="submit" class="btn btn-primary">Agregar Categoría</button>
    </form>
</div>
@endsection
