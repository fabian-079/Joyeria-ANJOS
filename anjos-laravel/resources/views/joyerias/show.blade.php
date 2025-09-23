@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de {{ $joyeria->nombre }}</h1>
    <p><strong>Descripción:</strong> {{ $joyeria->descripcion }}</p>
    <p><strong>Precio:</strong> ${{ $joyeria->precio }}</p>
    <p><strong>Categoría:</strong> {{ $joyeria->categoria }}</p>
    <p><strong>Stock:</strong> {{ $joyeria->stock }}</p>
    <a href="{{ route('joyerias.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection