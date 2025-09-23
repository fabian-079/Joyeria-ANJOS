<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalles de Reparación - Anjos Joyería</title>
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
        <a href="{{ route('reparaciones.index') }}"><i class="fas fa-arrow-left"></i> Volver a Reparaciones</a> | 
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
        @if(Auth::user()->hasRole('admin'))
          <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
          <li><a href="{{ route('dashboard.sales') }}"><i class="fas fa-chart-line"></i> Ventas</a></li>
          <li><a href="{{ route('dashboard.stock') }}"><i class="fas fa-boxes"></i> Stock</a></li>
          <li><a href="{{ route('dashboard.services') }}"><i class="fas fa-tools"></i> Servicios</a></li>
          <li><a href="{{ route('dashboard.reports') }}"><i class="fas fa-file-pdf"></i> Reportes</a></li>
          <li><a href="{{ route('products.index') }}"><i class="fas fa-gem"></i> Productos</a></li>
          <li><a href="{{ route('reparaciones.index') }}"><i class="fas fa-wrench"></i> Reparaciones</a></li>
          <li><a href="{{ route('personalizacion.index') }}"><i class="fas fa-pencil-alt"></i> Personalizaciones</a></li>
          <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Usuarios</a></li>
        @else
          <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Mi Cuenta</a></li>
          <li><a href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag"></i> Mis Pedidos</a></li>
          <li><a href="{{ route('reparaciones.index') }}"><i class="fas fa-wrench"></i> Mis Reparaciones</a></li>
          <li><a href="{{ route('personalizacion.index') }}"><i class="fas fa-pencil-alt"></i> Mis Personalizaciones</a></li>
          <li><a href="{{ route('favoritos') }}"><i class="fas fa-heart"></i> Favoritos</a></li>
          <li><a href="{{ route('carrito') }}"><i class="fas fa-shopping-cart"></i> Carrito</a></li>
          <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i> Perfil</a></li>
        @endif
      </ul>
    </nav>
  </aside>

  <main class="contenido-principal">
    <div class="container">
      <div class="page-header">
        <h1><i class="fas fa-wrench"></i> Reparación #{{ $repair->repair_number }}</h1>
        <div class="header-actions">
          @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('reparaciones.edit', $repair) }}" class="btn-edit">
              <i class="fas fa-edit"></i> Editar
            </a>
          @endif
          <a href="{{ route('reparaciones.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <div class="repair-details">
        <div class="details-grid">
          <div class="detail-card">
            <h3><i class="fas fa-info-circle"></i> Información General</h3>
            <div class="detail-content">
              <div class="detail-item">
                <label>Número de Reparación:</label>
                <span>{{ $repair->repair_number }}</span>
              </div>
              <div class="detail-item">
                <label>Cliente:</label>
                <span>{{ $repair->customer_name }}</span>
              </div>
              <div class="detail-item">
                <label>Teléfono:</label>
                <span>{{ $repair->phone }}</span>
              </div>
              <div class="detail-item">
                <label>Fecha de Solicitud:</label>
                <span>{{ $repair->created_at->format('d/m/Y H:i') }}</span>
              </div>
              <div class="detail-item">
                <label>Estado:</label>
                <span class="status-badge status-{{ $repair->status }}">
                  {{ ucfirst($repair->status) }}
                </span>
              </div>
            </div>
          </div>

          @if(Auth::user()->hasRole('admin'))
            <div class="detail-card">
              <h3><i class="fas fa-user-cog"></i> Asignación</h3>
              <div class="detail-content">
                @if($repair->assignedTechnician)
                  <div class="detail-item">
                    <label>Técnico Asignado:</label>
                    <span>{{ $repair->assignedTechnician->name }}</span>
                  </div>
                @else
                  <div class="detail-item">
                    <label>Técnico Asignado:</label>
                    <span class="text-muted">Sin asignar</span>
                  </div>
                @endif
                @if($repair->estimated_cost)
                  <div class="detail-item">
                    <label>Costo Estimado:</label>
                    <span>${{ number_format($repair->estimated_cost, 0, ',', '.') }}</span>
                  </div>
                @endif
                @if($repair->notes)
                  <div class="detail-item">
                    <label>Notas del Técnico:</label>
                    <span>{{ $repair->notes }}</span>
                  </div>
                @endif
              </div>
            </div>
          @endif

          <div class="detail-card full-width">
            <h3><i class="fas fa-clipboard-list"></i> Descripción del Trabajo</h3>
            <div class="detail-content">
              <p>{{ $repair->description }}</p>
            </div>
          </div>

          @if($repair->image)
            <div class="detail-card full-width">
              <h3><i class="fas fa-image"></i> Imagen de Referencia</h3>
              <div class="detail-content">
                <img src="{{ asset('storage/' . $repair->image) }}" alt="Imagen de reparación" class="repair-image">
              </div>
            </div>
          @endif
        </div>

        @if(Auth::user()->hasRole('admin') && !$repair->assignedTechnician)
          <div class="assign-technician">
            <h3><i class="fas fa-user-plus"></i> Asignar Técnico</h3>
            <form action="{{ route('reparaciones.asignar', $repair) }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="assigned_technician_id">Seleccionar Técnico:</label>
                <select name="assigned_technician_id" id="assigned_technician_id" required>
                  <option value="">Seleccionar técnico</option>
                  @foreach(\App\Models\User::whereHas('roles', function($query) {
                      $query->where('name', 'empleado');
                  })->get() as $technician)
                    <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                  @endforeach
                </select>
              </div>
              <button type="submit" class="btn-assign">
                <i class="fas fa-user-check"></i> Asignar Técnico
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>
  </main>

  <footer class="pie-pagina">
    <div class="contenido-pie">
      <div class="informacion">
        <h4>CONTACTO</h4>
        <p><i class="fas fa-map-marker-alt"></i> CALLE 38C SUR #87D - 09 / BOGOTÁ, COLOMBIA</p>
        <p><i class="fas fa-phone"></i> 3132090475 - 3013774549</p>
        <p><i class="fas fa-envelope"></i> ANJOS@GMAIL.COM</p>
      </div>
      <div class="redes-sociales">
        <h4>SÍGUENOS</h4>
        <div class="iconos-sociales">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-pinterest"></i></a>
        </div>
      </div>
    </div>
    <p class="derechos">© 2025 ANJOS JOYERÍA Y ACCESORIOS - Todos los derechos reservados</p>
  </footer>

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
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .page-header h1 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header-actions {
      display: flex;
      gap: 10px;
    }

    .btn-edit, .btn-back {
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s;
    }

    .btn-edit {
      background: #28a745;
      color: white;
    }

    .btn-edit:hover {
      background: #1e7e34;
    }

    .btn-back {
      background: #6c757d;
      color: white;
    }

    .btn-back:hover {
      background: #5a6268;
    }

    .details-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .detail-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .detail-card.full-width {
      grid-column: 1 / -1;
    }

    .detail-card h3 {
      color: #333;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-child {
      border-bottom: none;
    }

    .detail-item label {
      font-weight: bold;
      color: #666;
    }

    .detail-item span {
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

    .repair-image {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .assign-technician {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .assign-technician h3 {
      color: #333;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
      color: #333;
    }

    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .btn-assign {
      background: #000;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s;
    }

    .btn-assign:hover {
      background: #333;
    }

    .text-muted {
      color: #6c757d;
      font-style: italic;
    }

    @media (max-width: 768px) {
      .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }

      .header-actions {
        width: 100%;
        justify-content: flex-start;
      }

      .details-grid {
        grid-template-columns: 1fr;
      }

      .detail-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
      }
    }
  </style>
</body>
</html>




