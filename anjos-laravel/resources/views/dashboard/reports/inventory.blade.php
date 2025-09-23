<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reporte de Inventario - Anjos Joyería</title>
  <link rel="stylesheet" href="{{ asset('css/inicio.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <header class="encabezado">
    <div class="contenedor-logo">
      <a href="{{ route('dashboard') }}"><img src="{{ asset('img/Logo.png') }}" alt="Logo Anjos" class="logo"/></a>
    </div>
    <div class="contenedor-derecha">
      <div class="acciones-usuario">
        <a href="{{ route('dashboard.reports') }}"><i class="fas fa-arrow-left"></i> Volver a Reportes</a> | 
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Salir</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </div>
  </header>

  <main class="contenido-principal">
    <div class="admin-container">
      <div class="admin-header">
        <h1><i class="fas fa-boxes"></i> Reporte de Inventario</h1>
        <div class="header-actions">
          <a href="{{ route('dashboard.reports', array_merge(request()->query(), ['type' => 'inventory', 'export' => 'pdf'])) }}" class="btn-export">
            <i class="fas fa-file-pdf"></i> Exportar PDF
          </a>
        </div>
      </div>

      <!-- Filtros -->
      <div class="filters-container">
        <form method="GET" action="{{ route('dashboard.reports') }}" class="filters-form">
          <input type="hidden" name="type" value="inventory">
          
          <div class="filter-group">
            <label for="category_id">Categoría:</label>
            <select id="category_id" name="category_id">
              <option value="">Todas</option>
              @foreach(\App\Models\Category::all() as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="filter-group">
            <label for="stock_status">Estado de Stock:</label>
            <select id="stock_status" name="stock_status">
              <option value="">Todos</option>
              <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stock Bajo (≤5)</option>
              <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Sin Stock</option>
            </select>
          </div>
          
          <div class="filter-actions">
            <button type="submit" class="btn-filter">
              <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="{{ route('dashboard.reports', ['type' => 'inventory']) }}" class="btn-clear">
              <i class="fas fa-times"></i> Limpiar
            </a>
          </div>
        </form>
      </div>

      <div class="report-content">
        <div class="report-summary">
          <div class="summary-card">
            <h3>Total Productos</h3>
            <p>{{ $products->count() }}</p>
          </div>
          <div class="summary-card">
            <h3>Stock Bajo</h3>
            <p>{{ $products->where('stock', '<=', 5)->count() }}</p>
          </div>
          <div class="summary-card">
            <h3>Sin Stock</h3>
            <p>{{ $products->where('stock', 0)->count() }}</p>
          </div>
        </div>
        
        <div class="report-table">
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
                  <td>
                    <span class="stock-badge stock-{{ $product->stock <= 5 ? ($product->stock == 0 ? 'out' : 'low') : 'ok' }}">
                      {{ $product->stock }}
                    </span>
                  </td>
                  <td>
                    <span class="status-badge status-{{ $product->is_active ? 'active' : 'inactive' }}">
                      {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <style>
    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .admin-header {
      margin-bottom: 30px;
    }

    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .admin-header h1 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 0;
    }

    .header-actions {
      display: flex;
      gap: 10px;
    }

    .btn-export {
      padding: 10px 20px;
      background: #dc3545;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-export:hover {
      background: #c82333;
    }

    .filters-container {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }

    .filters-form {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      align-items: end;
    }

    .filter-group {
      display: flex;
      flex-direction: column;
      min-width: 150px;
    }

    .filter-group label {
      font-weight: bold;
      margin-bottom: 5px;
      color: #333;
    }

    .filter-group input,
    .filter-group select {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .filter-actions {
      display: flex;
      gap: 10px;
    }

    .btn-filter, .btn-clear {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-filter {
      background: #007bff;
      color: white;
    }

    .btn-filter:hover {
      background: #0056b3;
    }

    .btn-clear {
      background: #6c757d;
      color: white;
    }

    .btn-clear:hover {
      background: #545b62;
    }

    .report-content {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .report-summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .summary-card {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
    }

    .summary-card h3 {
      margin: 0 0 10px 0;
      color: #666;
      font-size: 14px;
    }

    .summary-card p {
      margin: 0;
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    .report-table {
      overflow-x: auto;
    }

    .report-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .report-table th {
      background: #f8f9fa;
      font-weight: bold;
      color: #333;
    }

    .stock-badge {
      padding: 4px 8px;
      border-radius: 4px;
      font-weight: bold;
      font-size: 0.8rem;
    }

    .stock-ok {
      background: #d4edda;
      color: #155724;
    }

    .stock-low {
      background: #fff3cd;
      color: #856404;
    }

    .stock-out {
      background: #f8d7da;
      color: #721c24;
    }

    .status-badge {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: bold;
    }

    .status-active {
      background: #d4edda;
      color: #155724;
    }

    .status-inactive {
      background: #f8d7da;
      color: #721c24;
    }

    @media (max-width: 768px) {
      .report-summary {
        grid-template-columns: 1fr;
      }
    }
  </style>
</body>
</html>
