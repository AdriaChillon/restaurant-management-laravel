@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Menú</h1>
    <form action="{{ route('menus.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1">
            <label class="form-check-label" for="activo">Activo</label>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-sm btn-warning">Cancelar</a>
        <button type="submit" class="btn btn-primary">Agregar Menú</button>
    </form>
</div>
@endsection
