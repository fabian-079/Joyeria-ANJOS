<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ventas - Anjos Joyería</title>
  <link rel="stylesheet" href="{{ asset('css/inicio.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <h1><i class="fas fa-chart-line"></i> Gestión de Ventas</h1>
      </div>

      <!-- Filtros -->
      <div class="filters-section">
        <form method="GET" action="{{ route('dashboard.sales') }}" class="filters-form">
          <div class="filters-grid">
            <div class="filter-group">
              <label>Fecha desde:</label>
              <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
              <label>Fecha hasta:</label>
              <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="filter-group">
              <label>Estado:</label>
              <select name="status">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Cliente:</label>
              <select name="user_id">
                <option value="">Todos los clientes</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="filter-group">
              <label>Total mínimo:</label>
              <input type="number" name="min_total" value="{{ request('min_total') }}" placeholder="0">
            </div>
            <div class="filter-group">
              <label>Total máximo:</label>
              <input type="number" name="max_total" value="{{ request('max_total') }}" placeholder="1000000">
            </div>
          </div>
          <div class="filter-actions">
            <button type="submit" class="btn-filter">
              <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="{{ route('dashboard.sales') }}" class="btn-clear">
              <i class="fas fa-times"></i> Limpiar
            </a>
          </div>
        </form>
      </div>

      <!-- Gráfico de ventas -->
      @if(!empty($salesChart))
        <div class="chart-section">
          <h3>Gráfico de Ventas</h3>
          <canvas id="salesChart"></canvas>
        </div>
      @endif

      <!-- Tabla de ventas -->
      <div class="sales-table">
        <h3>Pedidos</h3>
        <table>
          <thead>
            <tr>
              <th>Número</th>
              <th>Cliente</th>
              <th>Total</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($orders as $order)
              <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->user->name }}</td>
                <td>${{ number_format($order->total, 0, ',', '.') }}</td>
                <td>
                  <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>
                  <a href="{{ route('orders.show', $order) }}" class="btn-view">
                    <i class="fas fa-eye"></i> Ver
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="no-data">No se encontraron pedidos</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="pagination">
        {{ $orders->links() }}
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

    // Gráfico de ventas
    @if(!empty($salesChart))
      const salesCtx = document.getElementById('salesChart').getContext('2d');
      new Chart(salesCtx, {
        type: 'line',
        data: {
          labels: {!! json_encode(array_keys($salesChart)) !!},
          datasets: [{
            label: 'Ventas (COP)',
            data: {!! json_encode(array_values($salesChart)) !!},
            borderColor: '#000',
            backgroundColor: 'rgba(0, 0, 0, 0.1)',
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '$' + value.toLocaleString();
                }
              }
            }
          }
        }
      });
    @endif
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

    .admin-header h1 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .filters-section {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .filters-form h3 {
      margin-bottom: 20px;
      color: #333;
    }

    .filters-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    .filter-group {
      display: flex;
      flex-direction: column;
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
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s;
    }

    .btn-filter {
      background: #000;
      color: white;
    }

    .btn-filter:hover {
      background: #333;
    }

    .btn-clear {
      background: #6c757d;
      color: white;
    }

    .btn-clear:hover {
      background: #5a6268;
    }

    .chart-section {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .chart-section h3 {
      margin-bottom: 20px;
      color: #333;
    }

    .sales-table {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .sales-table h3 {
      margin-bottom: 20px;
      color: #333;
    }

    .sales-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .sales-table th,
    .sales-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .sales-table th {
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

    .btn-view {
      background: #007bff;
      color: white;
      padding: 5px 10px;
      text-decoration: none;
      border-radius: 3px;
      font-size: 0.8rem;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .btn-view:hover {
      background: #0056b3;
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
      .filters-grid {
        grid-template-columns: 1fr;
      }

      .filter-actions {
        flex-direction: column;
      }

      .sales-table {
        overflow-x: auto;
      }
    }
  </style>
</body>
</html>




