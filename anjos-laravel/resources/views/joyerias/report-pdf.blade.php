<!DOCTYPE html>
<html>
<head>
    <title>Reporte Joyería Anjos</title>
</head>
<body>
    <h1>Reporte de Joyería - Anjos Joyería</h1>
    <table border="1">
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
</body>
</html>