@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Menú</h1>
    <form action="{{ route('menus.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $menu->nombre }}" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion">{{ $menu->descripcion }}</textarea>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" {{ $menu->activo ? 'checked' : '' }}>
            <label class="form-check-label" for="activo">Activo</label>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Menú</button>
    </form>
</div>
@endsection
