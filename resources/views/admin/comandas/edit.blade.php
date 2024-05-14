@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Comanda</h1>
    <form action="{{ route('comandas.update', $comanda->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="mesa_id">Mesa</label>
            <select class="form-control" id="mesa_id" name="mesa_id" required>
                @foreach($mesas as $mesa)
                <option value="{{ $mesa->id }}" {{ $comanda->id == $mesa->id ? 'selected' : '' }}>{{ $mesa->numero }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="fecha_hora">Fecha y Hora</label>
            <input type="datetime-local" class="form-control" id="fecha_hora" name="fecha_hora" value="{{ date('Y-m-d\TH:i', strtotime($comanda->fecha_hora)) }}" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="en_marcha" name="en_marcha" value="1" {{ $comanda->en_marcha ? 'checked' : '' }}>
            <label class="form-check-label" for="en_marcha">En marcha</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="pagado" name="pagado" value="1" {{ $comanda->pagado ? 'checked' : '' }}>
            <label class="form-check-label" for="pagado">Pagado</label>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Comanda</button>
    </form>
</div>
@endsection
