<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mi Cuenta - Anjos Joyería y Accesorios</title>
  <link rel="stylesheet" href="{{ asset('css/inicio.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <header class="encabezado">
    <div class="contenedor-logo">
      <a href="{{ route('inicio') }}"><img src="{{ asset('img/Logo.png') }}" alt="Logo Anjos" class="logo"/></a>
    </div>
    <div class="contenedor-derecha">
      <div class="acciones-usuario">
        <a href="{{ route('inicio') }}"><i class="fas fa-home"></i> Sitio Web</a> | 
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
        <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Mi Cuenta</a></li>
        <li><a href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag"></i> Mis Pedidos</a></li>
        <li><a href="{{ route('reparaciones.index') }}"><i class="fas fa-wrench"></i> Mis Reparaciones</a></li>
        <li><a href="{{ route('personalizacion.index') }}"><i class="fas fa-pencil-alt"></i> Mis Personalizaciones</a></li>
        <li><a href="{{ route('favoritos') }}"><i class="fas fa-heart"></i> Favoritos</a></li>
        <li><a href="{{ route('carrito') }}"><i class="fas fa-shopping-cart"></i> Carrito</a></li>
        <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i> Perfil</a></li>
      </ul>
    </nav>
  </aside>

  <main class="contenido-principal">
    <div class="dashboard-container">
      <h1><i class="fas fa-user"></i> Bienvenido, {{ Auth::user()->name }}</h1>
      
      <!-- Acciones rápidas -->
      <div class="quick-actions">
        <h2>Acciones Rápidas</h2>
        <div class="actions-grid">
          <a href="{{ route('catalogo') }}" class="action-card">
            <i class="fas fa-gem"></i>
            <h3>Ver Catálogo</h3>
            <p>Explora nuestros productos</p>
          </a>
          <a href="{{ route('reparaciones.create') }}" class="action-card">
            <i class="fas fa-tools"></i>
            <h3>Solicitar Reparación</h3>
            <p>Repara tus joyas</p>
          </a>
          <a href="{{ route('personalizacion.create') }}" class="action-card">
            <i class="fas fa-pencil-alt"></i>
            <h3>Personalizar Joya</h3>
            <p>Crea algo único</p>
          </a>
          <a href="{{ route('orders.index') }}" class="action-card">
            <i class="fas fa-shopping-bag"></i>
            <h3>Mis Pedidos</h3>
            <p>Ver historial</p>
          </a>
        </div>
      </div>

      <!-- Pedidos recientes -->
      <div class="recent-section">
        <h2><i class="fas fa-clock"></i> Pedidos Recientes</h2>
        @if($recentOrders->count() > 0)
          <div class="orders-list">
            @foreach($recentOrders as $order)
              <div class="order-card">
                <div class="order-info">
                  <h4>Pedido #{{ $order->order_number }}</h4>
                  <p>Total: ${{ number_format($order->total, 0, ',', '.') }}</p>
                  <p>Fecha: {{ $order->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="order-status">
                  <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </div>
                <div class="order-actions">
                  <a href="{{ route('orders.show', $order) }}" class="btn-view">Ver detalles</a>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p class="no-data">No tienes pedidos aún. <a href="{{ route('catalogo') }}">¡Explora nuestro catálogo!</a></p>
        @endif
      </div>

      <!-- Reparaciones recientes -->
      <div class="recent-section">
        <h2><i class="fas fa-tools"></i> Reparaciones Recientes</h2>
        @if($recentRepairs->count() > 0)
          <div class="repairs-list">
            @foreach($recentRepairs as $repair)
              <div class="repair-card">
                <div class="repair-info">
                  <h4>Reparación #{{ $repair->repair_number }}</h4>
                  <p>{{ Str::limit($repair->description, 100) }}</p>
                  <p>Fecha: {{ $repair->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="repair-status">
                  <span class="status-badge status-{{ $repair->status }}">
                    {{ ucfirst($repair->status) }}
                  </span>
                </div>
                <div class="repair-actions">
                  <a href="{{ route('reparaciones.show', $repair) }}" class="btn-view">Ver detalles</a>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p class="no-data">No tienes reparaciones solicitadas. <a href="{{ route('reparaciones.create') }}">¡Solicita una reparación!</a></p>
        @endif
      </div>

      <!-- Personalizaciones recientes -->
      <div class="recent-section">
        <h2><i class="fas fa-pencil-alt"></i> Personalizaciones Recientes</h2>
        @if($recentCustomizations->count() > 0)
          <div class="customizations-list">
            @foreach($recentCustomizations as $customization)
              <div class="customization-card">
                <div class="customization-info">
                  <h4>{{ $customization->jewelry_type }}</h4>
                  <p>{{ $customization->design }} - {{ $customization->material }}</p>
                  <p>Precio estimado: ${{ number_format($customization->estimated_price, 0, ',', '.') }}</p>
                  <p>Fecha: {{ $customization->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="customization-status">
                  <span class="status-badge status-{{ $customization->status }}">
                    {{ ucfirst($customization->status) }}
                  </span>
                </div>
                <div class="customization-actions">
                  <a href="{{ route('personalizacion.show', $customization) }}" class="btn-view">Ver detalles</a>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p class="no-data">No tienes personalizaciones solicitadas. <a href="{{ route('personalizacion.create') }}">¡Crea algo único!</a></p>
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
    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .dashboard-container h1 {
      color: #333;
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .quick-actions {
      margin-bottom: 40px;
    }

    .quick-actions h2 {
      color: #333;
      margin-bottom: 20px;
    }

    .actions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .action-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      text-decoration: none;
      color: #333;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      text-align: center;
    }

    .action-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .action-card i {
      font-size: 2rem;
      color: #000;
      margin-bottom: 10px;
    }

    .action-card h3 {
      margin: 10px 0 5px 0;
      color: #333;
    }

    .action-card p {
      margin: 0;
      color: #666;
      font-size: 0.9rem;
    }

    .recent-section {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .recent-section h2 {
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .orders-list, .repairs-list, .customizations-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .order-card, .repair-card, .customization-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px;
      border: 1px solid #eee;
      border-radius: 8px;
      background: #f9f9f9;
    }

    .order-info, .repair-info, .customization-info {
      flex: 1;
    }

    .order-info h4, .repair-info h4, .customization-info h4 {
      margin: 0 0 5px 0;
      color: #333;
    }

    .order-info p, .repair-info p, .customization-info p {
      margin: 2px 0;
      color: #666;
      font-size: 0.9rem;
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

    .status-processing, .status-in_progress {
      background: #d1ecf1;
      color: #0c5460;
    }

    .status-completed, .status-delivered {
      background: #d4edda;
      color: #155724;
    }

    .status-cancelled {
      background: #f8d7da;
      color: #721c24;
    }

    .btn-view {
      background: #000;
      color: white;
      padding: 8px 15px;
      text-decoration: none;
      border-radius: 5px;
      font-size: 0.9rem;
      transition: background 0.3s;
    }

    .btn-view:hover {
      background: #333;
    }

    .no-data {
      text-align: center;
      color: #666;
      font-style: italic;
      padding: 20px;
    }

    .no-data a {
      color: #000;
      text-decoration: none;
    }

    .no-data a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .actions-grid {
        grid-template-columns: 1fr;
      }

      .order-card, .repair-card, .customization-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      .order-actions, .repair-actions, .customization-actions {
        align-self: stretch;
      }

      .btn-view {
        display: block;
        text-align: center;
      }
    }
  </style>
</body>
</html>




