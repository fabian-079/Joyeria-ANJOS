<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalles de Personalización - Anjos Joyería</title>
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
        <a href="{{ route('personalizacion.index') }}"><i class="fas fa-arrow-left"></i> Volver a Personalizaciones</a> | 
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
        <h1><i class="fas fa-pencil-alt"></i> Personalización: {{ $customization->jewelry_type }}</h1>
        <div class="header-actions">
          @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('personalizacion.edit', $customization) }}" class="btn-edit">
              <i class="fas fa-edit"></i> Editar
            </a>
          @endif
          <a href="{{ route('personalizacion.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <div class="customization-details">
        <div class="details-grid">
          <div class="detail-card">
            <h3><i class="fas fa-info-circle"></i> Información General</h3>
            <div class="detail-content">
              <div class="detail-item">
                <label>Cliente:</label>
                <span>{{ $customization->user->name }}</span>
              </div>
              <div class="detail-item">
                <label>Email:</label>
                <span>{{ $customization->user->email }}</span>
              </div>
              <div class="detail-item">
                <label>Fecha de Solicitud:</label>
                <span>{{ $customization->created_at->format('d/m/Y H:i') }}</span>
              </div>
              <div class="detail-item">
                <label>Estado:</label>
                <span class="status-badge status-{{ $customization->status }}">
                  {{ ucfirst($customization->status) }}
                </span>
              </div>
            </div>
          </div>

          <div class="detail-card">
            <h3><i class="fas fa-gem"></i> Especificaciones de la Joya</h3>
            <div class="detail-content">
              <div class="detail-item">
                <label>Tipo de Joya:</label>
                <span>{{ $customization->jewelry_type }}</span>
              </div>
              <div class="detail-item">
                <label>Diseño:</label>
                <span>{{ $customization->design }}</span>
              </div>
              <div class="detail-item">
                <label>Material:</label>
                <span>{{ $customization->material }}</span>
              </div>
              <div class="detail-item">
                <label>Color:</label>
                <span>{{ $customization->color }}</span>
              </div>
              <div class="detail-item">
                <label>Acabado:</label>
                <span>{{ $customization->finish }}</span>
              </div>
              <div class="detail-item">
                <label>Piedras:</label>
                <span>{{ $customization->stones }}</span>
              </div>
              @if($customization->engraving)
                <div class="detail-item">
                  <label>Grabado:</label>
                  <span>{{ $customization->engraving }}</span>
                </div>
              @endif
              <div class="detail-item">
                <label>Precio Estimado:</label>
                <span class="price">${{ number_format($customization->estimated_price, 0, ',', '.') }}</span>
              </div>
            </div>
          </div>

          @if($customization->special_instructions)
            <div class="detail-card full-width">
              <h3><i class="fas fa-clipboard-list"></i> Instrucciones Especiales</h3>
              <div class="detail-content">
                <p>{{ $customization->special_instructions }}</p>
              </div>
            </div>
          @endif

          @if(Auth::user()->hasRole('admin'))
            <div class="detail-card full-width">
              <h3><i class="fas fa-comments"></i> Notas Administrativas</h3>
              <div class="detail-content">
                @if($customization->admin_notes)
                  <p>{{ $customization->admin_notes }}</p>
                @else
                  <p class="text-muted">No hay notas administrativas</p>
                @endif
              </div>
            </div>
          @endif
        </div>

        @if($customization->status == 'approved' && !Auth::user()->hasRole('admin'))
          <div class="action-section">
            <h3><i class="fas fa-shopping-cart"></i> Acciones Disponibles</h3>
            <div class="action-buttons">
              <a href="{{ route('personalizacion.carrito', $customization) }}" class="btn-cart">
                <i class="fas fa-shopping-cart"></i> Agregar al Carrito
              </a>
              <a href="{{ route('personalizacion.favoritos', $customization) }}" class="btn-favorite">
                <i class="fas fa-heart"></i> Agregar a Favoritos
              </a>
            </div>
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

    .detail-item .price {
      font-weight: bold;
      color: #000;
      font-size: 1.1rem;
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

    .status-approved {
      background: #d4edda;
      color: #155724;
    }

    .status-rejected {
      background: #f8d7da;
      color: #721c24;
    }

    .status-in_progress {
      background: #d1ecf1;
      color: #0c5460;
    }

    .status-completed {
      background: #d1ecf1;
      color: #0c5460;
    }

    .action-section {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .action-section h3 {
      color: #333;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .action-buttons {
      display: flex;
      gap: 15px;
    }

    .btn-cart, .btn-favorite {
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .btn-cart {
      background: #000;
      color: white;
    }

    .btn-cart:hover {
      background: #333;
    }

    .btn-favorite {
      background: #dc3545;
      color: white;
    }

    .btn-favorite:hover {
      background: #c82333;
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

      .action-buttons {
        flex-direction: column;
      }
    }
  </style>
</body>
</html>




