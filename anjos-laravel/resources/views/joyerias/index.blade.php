@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestión de Joyería - Anjos</h1>

    @if (Gate::allows('create', App\Models\Joyeria::class))
        <a href="{{ route('joyerias.create') }}" class="btn btn-primary mb-3">Crear Nueva Joyería</a>
    @endif

    <a href="{{ route('joyerias.report') }}" class="btn btn-info mb-3">Generar Reporte</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($joyerias as $joyeria)
            <tr>
                <td>{{ $joyeria->nombre }}</td>
                <td>{{ $joyeria->descripcion }}</td>
                <td>${{ $joyeria->precio }}</td>
                <td>{{ $joyeria->categoria }}</td>
                <td>{{ $joyeria->stock }}</td>
                <td>
                    @if (Gate::allows('view', $joyeria))
                        <a href="{{ route('joyerias.show', $joyeria) }}" class="btn btn-sm btn-info">Ver</a>
                    @endif
                    @if (Gate::allows('update', $joyeria))
                        <a href="{{ route('joyerias.edit', $joyeria) }}" class="btn btn-sm btn-warning">Editar</a>
                    @endif
                    @if (Gate::allows('delete', $joyeria))
                        <form action="{{ route('joyerias.destroy', $joyeria) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro?')">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $joyerias->links() }}
</div>
@endsection