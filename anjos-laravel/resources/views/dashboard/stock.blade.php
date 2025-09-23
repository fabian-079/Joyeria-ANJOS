<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Stock - Anjos Joyería</title>
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
        <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a> | 
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Salir</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </div>
  </header>

  <button class="boton-menu">☰ Menú</button>

  <aside class="menu-lateral" id="menuLateral">
    <nav>
      <ul>
        <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="{{ route('dashboard.sales') }}"><i class="fas fa-chart-line"></i> Ventas</a></li>
        <li><a href="{{ route('dashboard.stock') }}"><i class="fas fa-boxes"></i> Stock</a></li>
        <li><a href="{{ route('dashboard.services') }}"><i class="fas fa-tools"></i> Servicios</a></li>
        <li><a href="{{ route('dashboard.reports') }}"><i class="fas fa-file-pdf"></i> Reportes</a></li>
        <li><a href="{{ route('products.index') }}"><i class="fas fa-gem"></i> Productos</a></li>
        <li><a href="{{ route('reparaciones.index') }}"><i class="fas fa-wrench"></i> Reparaciones</a></li>
        <li><a href="{{ route('personalizacion.index') }}"><i class="fas fa-pencil-alt"></i> Personalizaciones</a></li>
        <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Usuarios</a></li>
      </ul>
    </nav>
  </aside>

  <main class="contenido-principal">
    <div class="admin-container">
      <div class="admin-header">
        <h1><i class="fas fa-boxes"></i> Gestión de Stock</h1>
        <a href="{{ route('products.create') }}" class="btn-add">
          <i class="fas fa-plus"></i> Agregar Producto
        </a>
      </div>

      <!-- Estadísticas de stock -->
      <div class="stock-stats">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-boxes"></i>
          </div>
          <div class="stat-info">
            <h3>{{ $products->total() }}</h3>
            <p>Total Productos</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <div class="stat-info">
            <h3>{{ $products->where('stock', '<=', 5)->count() }}</h3>
            <p>Stock Bajo</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-times-circle"></i>
          </div>
          <div class="stat-info">
            <h3>{{ $products->where('stock', 0)->count() }}</h3>
            <p>Sin Stock</p>
          </div>
        </div>
      </div>

      <!-- Tabla de productos -->
      <div class="stock-table">
        <h3>Inventario de Productos</h3>
        <table>
          <thead>
            <tr>
              <th>Imagen</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($products as $product)
              <tr>
                <td>
                  @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                  @else
                    <div class="no-image">
                      <i class="fas fa-image"></i>
                    </div>
                  @endif
                </td>
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
                <td>
                  <div class="action-buttons">
                    <a href="{{ route('products.edit', $product) }}" class="btn-edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="no-data">No se encontraron productos</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="pagination">
        {{ $products->links() }}
      </div>
    </div>
  </main>

  <script>
    const botonMenu = document.querySelector('.boton-menu');
    const menuLateral = document.getElementById('menuLateral');

    botonMenu.addEventListener('click', () => {
      menuLateral.classList.toggle('activo');
      botonMenu.classList.toggle('activo');
      document.querySelector('.contenido-principal').classList.toggle('menu-activo');
    });
  </script>

  <style>
    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
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
    }

    .btn-add {
      background: #000;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s;
    }

    .btn-add:hover {
      background: #333;
    }

    .stock-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: white;
    }

    .stat-card:nth-child(1) .stat-icon {
      background: #007bff;
    }

    .stat-card:nth-child(2) .stat-icon {
      background: #ffc107;
    }

    .stat-card:nth-child(3) .stat-icon {
      background: #dc3545;
    }

    .stat-info h3 {
      font-size: 24px;
      margin: 0;
      color: #333;
    }

    .stat-info p {
      margin: 0;
      color: #666;
      font-size: 14px;
    }

    .stock-table {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .stock-table h3 {
      margin-bottom: 20px;
      color: #333;
    }

    .stock-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .stock-table th,
    .stock-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .stock-table th {
      background: #f8f9fa;
      font-weight: bold;
      color: #333;
    }

    .product-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 5px;
    }

    .no-image {
      width: 50px;
      height: 50px;
      background: #f8f9fa;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
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

    .action-buttons {
      display: flex;
      gap: 5px;
    }

    .btn-edit, .btn-delete {
      padding: 5px 8px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      transition: background 0.3s;
    }

    .btn-edit {
      background: #007bff;
      color: white;
    }

    .btn-edit:hover {
      background: #0056b3;
    }

    .btn-delete {
      background: #dc3545;
      color: white;
    }

    .btn-delete:hover {
      background: #c82333;
    }

    .no-data {
      text-align: center;
      color: #666;
      font-style: italic;
      padding: 20px;
    }

    .pagination {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    @media (max-width: 768px) {
      .admin-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }

      .stock-stats {
        grid-template-columns: 1fr;
      }

      .stock-table {
        overflow-x: auto;
      }
    }
  </style>
</body>
</html>




