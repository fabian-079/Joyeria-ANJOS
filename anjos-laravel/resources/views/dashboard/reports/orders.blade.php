<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reporte de Pedidos - Anjos Joyería</title>
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
        <h1><i class="fas fa-shopping-cart"></i> Reporte de Pedidos</h1>
        <div class="header-actions">
          <a href="{{ route('dashboard.reports', array_merge(request()->query(), ['type' => 'orders', 'export' => 'pdf'])) }}" class="btn-export">
            <i class="fas fa-file-pdf"></i> Exportar PDF
          </a>
        </div>
      </div>

      <!-- Filtros -->
      <div class="filters-container">
        <form method="GET" action="{{ route('dashboard.reports') }}" class="filters-form">
          <input type="hidden" name="type" value="orders">
          
          <div class="filter-group">
            <label for="date_from">Fecha desde:</label>
            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
          </div>
          
          <div class="filter-group">
            <label for="date_to">Fecha hasta:</label>
            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
          </div>
          
          <div class="filter-group">
            <label for="status">Estado:</label>
            <select id="status" name="status">
              <option value="">Todos</option>
              <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
              <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
              <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
              <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
              <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
          </div>
          
          <div class="filter-actions">
            <button type="submit" class="btn-filter">
              <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="{{ route('dashboard.reports', ['type' => 'orders']) }}" class="btn-clear">
              <i class="fas fa-times"></i> Limpiar
            </a>
          </div>
        </form>
      </div>

      <div class="report-content">
        <div class="report-summary">
          <div class="summary-card">
            <h3>Total Pedidos</h3>
            <p>{{ $orders->count() }}</p>
          </div>
          <div class="summary-card">
            <h3>Total Ventas</h3>
            <p>${{ number_format($orders->sum('total'), 0, ',', '.') }}</p>
          </div>
          <div class="summary-card">
            <h3>Promedio por Pedido</h3>
            <p>${{ number_format(($orders->count() > 0 ? $orders->sum('total') / $orders->count() : 0), 0, ',', '.') }}</p>
          </div>
        </div>
        
        <div class="report-table">
          <table>
            <thead>
              <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)
                <tr>
                  <td>{{ $order->order_number ?? '#' . $order->id }}</td>
                  <td>{{ $order->user->name }}</td>
                  <td>${{ number_format($order->total, 0, ',', '.') }}</td>
                  <td>
                    <span class="status-badge status-{{ $order->status }}">
                      {{ ucfirst($order->status) }}
                    </span>
                  </td>
                  <td>{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
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

    .status-badge {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: bold;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-processing {
      background: #d1ecf1;
      color: #0c5460;
    }

    .status-shipped {
      background: #d4edda;
      color: #155724;
    }

    .status-delivered {
      background: #d1ecf1;
      color: #0c5460;
    }

    .status-cancelled {
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
