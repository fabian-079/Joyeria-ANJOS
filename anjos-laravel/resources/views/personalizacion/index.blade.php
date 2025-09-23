<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mis Personalizaciones - Anjos Joyería</title>
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
        <h1><i class="fas fa-pencil-alt"></i> 
          @if(Auth::user()->hasRole('admin'))
            Gestión de Personalizaciones
          @else
            Mis Personalizaciones
          @endif
        </h1>
        <a href="{{ route('personalizacion.create') }}" class="btn-create">
          <i class="fas fa-plus"></i> Nueva Personalización
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if($customizations->count() > 0)
        <div class="customizations-grid">
          @foreach($customizations as $customization)
            <div class="customization-card">
              <div class="customization-header">
                <h3>{{ $customization->jewelry_type }}</h3>
                <span class="status-badge status-{{ $customization->status }}">
                  {{ ucfirst($customization->status) }}
                </span>
              </div>
              
              <div class="customization-content">
                <div class="customization-details">
                  <div class="detail-row">
                    <span class="label">Diseño:</span>
                    <span class="value">{{ $customization->design }}</span>
                  </div>
                  <div class="detail-row">
                    <span class="label">Material:</span>
                    <span class="value">{{ $customization->material }}</span>
                  </div>
                  <div class="detail-row">
                    <span class="label">Color:</span>
                    <span class="value">{{ $customization->color }}</span>
                  </div>
                  <div class="detail-row">
                    <span class="label">Acabado:</span>
                    <span class="value">{{ $customization->finish }}</span>
                  </div>
                  <div class="detail-row">
                    <span class="label">Piedras:</span>
                    <span class="value">{{ $customization->stones }}</span>
                  </div>
                  @if($customization->engraving)
                    <div class="detail-row">
                      <span class="label">Grabado:</span>
                      <span class="value">{{ $customization->engraving }}</span>
                    </div>
                  @endif
                  <div class="detail-row">
                    <span class="label">Precio Estimado:</span>
                    <span class="value price">${{ number_format($customization->estimated_price, 0, ',', '.') }}</span>
                  </div>
                  <div class="detail-row">
                    <span class="label">Fecha:</span>
                    <span class="value">{{ $customization->created_at->format('d/m/Y') }}</span>
                  </div>
                </div>
                
                @if($customization->special_instructions)
                  <div class="special-instructions">
                    <h4>Instrucciones Especiales:</h4>
                    <p>{{ $customization->special_instructions }}</p>
                  </div>
                @endif
              </div>
              
              <div class="customization-actions">
                <a href="{{ route('personalizacion.show', $customization) }}" class="btn-view">
                  <i class="fas fa-eye"></i> Ver Detalles
                </a>
                @if(Auth::user()->hasRole('admin'))
                  <a href="{{ route('personalizacion.edit', $customization) }}" class="btn-edit">
                    <i class="fas fa-edit"></i> Editar
                  </a>
                @endif
                @if($customization->status == 'approved')
                  <a href="{{ route('personalizacion.carrito', $customization) }}" class="btn-cart">
                    <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="no-data">
          <i class="fas fa-pencil-alt"></i>
          <h3>No hay personalizaciones</h3>
          <p>No tienes personalizaciones registradas aún.</p>
          <a href="{{ route('personalizacion.create') }}" class="btn-create">
            <i class="fas fa-plus"></i> Crear Personalización
          </a>
        </div>
      @endif
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

    .btn-create {
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

    .btn-create:hover {
      background: #333;
    }

    .alert {
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .customizations-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
      gap: 20px;
    }

    .customization-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .customization-card:hover {
      transform: translateY(-2px);
    }

    .customization-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }

    .customization-header h3 {
      margin: 0;
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

    .customization-details {
      margin-bottom: 15px;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-row .label {
      font-weight: bold;
      color: #666;
      font-size: 0.9rem;
    }

    .detail-row .value {
      color: #333;
      text-align: right;
    }

    .detail-row .value.price {
      font-weight: bold;
      color: #000;
    }

    .special-instructions {
      margin: 15px 0;
      padding: 10px;
      background: #f8f9fa;
      border-radius: 5px;
      border-left: 4px solid #000;
    }

    .special-instructions h4 {
      margin: 0 0 5px 0;
      color: #333;
      font-size: 0.9rem;
    }

    .special-instructions p {
      margin: 0;
      color: #666;
      font-size: 0.9rem;
    }

    .customization-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }

    .btn-view, .btn-edit, .btn-cart {
      padding: 8px 15px;
      text-decoration: none;
      border-radius: 5px;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 5px;
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
      background: #1e7e34;
    }

    .btn-cart {
      background: #000;
      color: white;
    }

    .btn-cart:hover {
      background: #333;
    }

    .no-data {
      text-align: center;
      padding: 60px 20px;
      color: #666;
    }

    .no-data i {
      font-size: 4rem;
      color: #ddd;
      margin-bottom: 20px;
    }

    .no-data h3 {
      margin: 0 0 10px 0;
      color: #333;
    }

    .no-data p {
      margin: 0 0 20px 0;
    }

    @media (max-width: 768px) {
      .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }

      .customizations-grid {
        grid-template-columns: 1fr;
      }

      .customization-actions {
        flex-direction: column;
      }

      .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
      }

      .detail-row .value {
        text-align: left;
      }
    }
  </style>
</body>
</html>




