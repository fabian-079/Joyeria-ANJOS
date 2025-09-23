@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reporte de Joyería - Filtros Multicriterio</h1>
    
    <!-- Formulario de filtros -->
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="nombre" placeholder="Nombre" value="{{ request('nombre') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <input type="text" name="categoria" placeholder="Categoría" value="{{ request('categoria') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <input type="number" name="min_precio" placeholder="Precio Mín" value="{{ request('min_precio') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_precio" placeholder="Precio Máx" value="{{ request('max_precio') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('joyerias.report') }}?export=pdf" class="btn btn-success">Exportar PDF</a>
            </div>
        </div>
    </form>

    <!-- Tabla de resultados -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportes as $joyeria)
            <tr>
                <td>{{ $joyeria->nombre }}</td>
                <td>{{ $joyeria->descripcion }}</td>
                <td>${{ $joyeria->precio }}</td>
                <td>{{ $joyeria->categoria }}</td>
                <td>{{ $joyeria->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection