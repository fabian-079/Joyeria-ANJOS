<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Servicios - Anjos Joyería</title>
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
        <h1><i class="fas fa-tools"></i> Gestión de Servicios</h1>
      </div>

      <!-- Estadísticas de servicios -->
      <div class="services-stats">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-wrench"></i>
          </div>
          <div class="stat-info">
            <h3>{{ $repairs->count() }}</h3>
            <p>Reparaciones</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-pencil-alt"></i>
          </div>
          <div class="stat-info">
            <h3>{{ $customizations->count() }}</h3>
            <p>Personalizaciones</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-clock"></i>
          </div>
          <div class="stat-info">
            <h3>{{ $repairs->where('status', 'pending')->count() + $customizations->where('status', 'pending')->count() }}</h3>
            <p>Pendientes</p>
          </div>
        </div>
      </div>

      <!-- Reparaciones -->
      <div class="services-section">
        <div class="section-header">
          <h2><i class="fas fa-wrench"></i> Reparaciones</h2>
          <a href="{{ route('reparaciones.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Nueva Reparación
          </a>
        </div>
        
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Técnico</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($repairs as $repair)
                <tr>
                  <td>#{{ $repair->id }}</td>
                  <td>{{ $repair->user->name }}</td>
                  <td>{{ Str::limit($repair->description, 50) }}</td>
                  <td>
                    <span class="status-badge status-{{ $repair->status }}">
                      {{ ucfirst($repair->status) }}
                    </span>
                  </td>
                  <td>{{ $repair->assignedTechnician->name ?? 'Sin asignar' }}</td>
                  <td>{{ $repair->created_at->format('d/m/Y') }}</td>
                  <td>
                    <div class="action-buttons">
                      <a href="{{ route('reparaciones.show', $repair) }}" class="btn-view">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('reparaciones.edit', $repair) }}" class="btn-edit">
                        <i class="fas fa-edit"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="no-data">No hay reparaciones registradas</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Personalizaciones -->
      <div class="services-section">
        <div class="section-header">
          <h2><i class="fas fa-pencil-alt"></i> Personalizaciones</h2>
          <a href="{{ route('personalizacion.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Nueva Personalización
          </a>
        </div>
        
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Diseño</th>
                <th>Estado</th>
                <th>Precio</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($customizations as $customization)
                <tr>
                  <td>#{{ $customization->id }}</td>
                  <td>{{ $customization->user->name }}</td>
                  <td>{{ $customization->jewelry_type }}</td>
                  <td>{{ Str::limit($customization->design, 30) }}</td>
                  <td>
                    <span class="status-badge status-{{ $customization->status }}">
                      {{ ucfirst($customization->status) }}
                    </span>
                  </td>
                  <td>
                    @if($customization->estimated_price)
                      ${{ number_format($customization->estimated_price, 0, ',', '.') }}
                    @else
                      <span class="no-price">Por cotizar</span>
                    @endif
                  </td>
                  <td>{{ $customization->created_at->format('d/m/Y') }}</td>
                  <td>
                    <div class="action-buttons">
                      <a href="{{ route('personalizacion.show', $customization) }}" class="btn-view">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('personalizacion.edit', $customization) }}" class="btn-edit">
                        <i class="fas fa-edit"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="no-data">No hay personalizaciones registradas</td>
                </tr>
              @endforelse
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

    .admin-header h1 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .services-stats {
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
      background: #28a745;
    }

    .stat-card:nth-child(3) .stat-icon {
      background: #ffc107;
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

    .services-section {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .section-header h2 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .btn-add {
      background: #000;
      color: white;
      padding: 8px 16px;
      text-decoration: none;
      border-radius: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-add:hover {
      background: #333;
    }

    .table-container {
      overflow-x: auto;
    }

    .table-container table {
      width: 100%;
      border-collapse: collapse;
    }

    .table-container th,
    .table-container td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .table-container th {
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

    .status-in_progress {
      background: #d1ecf1;
      color: #0c5460;
    }

    .status-completed {
      background: #d4edda;
      color: #155724;
    }

    .status-cancelled {
      background: #f8d7da;
      color: #721c24;
    }

    .no-price {
      color: #6c757d;
      font-style: italic;
    }

    .action-buttons {
      display: flex;
      gap: 5px;
    }

    .btn-view, .btn-edit {
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

    .btn-view {
      background: #007bff;
      color: white;
    }

    .btn-view:hover {
      background: #0056b3;
    }

    .btn-edit {
      background: #28a745;
      color: white;
    }

    .btn-edit:hover {
      background: #218838;
    }

    .no-data {
      text-align: center;
      color: #666;
      font-style: italic;
      padding: 20px;
    }

    @media (max-width: 768px) {
      .section-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }

      .services-stats {
        grid-template-columns: 1fr;
      }
    }
  </style>
</body>
</html>




