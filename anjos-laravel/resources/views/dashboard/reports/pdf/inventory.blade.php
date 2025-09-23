<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario - Anjos Joyería</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        .summary {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-around;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #666;
        }
        .summary-item p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .stock-ok { color: #155724; }
        .stock-low { color: #856404; }
        .stock-out { color: #721c24; }
        .status-active { color: #155724; }
        .status-inactive { color: #721c24; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Anjos Joyería</h1>
        <p>Reporte de Inventario - {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h3>Total Productos</h3>
            <p>{{ $products->count() }}</p>
        </div>
        <div class="summary-item">
            <h3>Stock Bajo</h3>
            <p>{{ $products->where('stock', '<=', 5)->count() }}</p>
        </div>
        <div class="summary-item">
            <h3>Sin Stock</h3>
            <p>{{ $products->where('stock', 0)->count() }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'Sin categoría' }}</td>
                    <td>${{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="stock-{{ $product->stock <= 5 ? ($product->stock == 0 ? 'out' : 'low') : 'ok' }}">
                        {{ $product->stock }}
                    </td>
                    <td class="status-{{ $product->is_active ? 'active' : 'inactive' }}">
                        {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }} - Anjos Joyería</p>
    </div>
</body>
</html>