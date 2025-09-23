<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mis Pedidos - Anjos Joyería</title>
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
    <div class="container">
      <div class="page-header">
        <h1><i class="fas fa-shopping-bag"></i> Mis Pedidos</h1>
        <a href="{{ route('catalogo') }}" class="btn-shop">
          <i class="fas fa-shopping-cart"></i> Seguir Comprando
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if($orders->count() > 0)
        <div class="orders-list">
          @foreach($orders as $order)
            <div class="order-card">
              <div class="order-header">
                <div class="order-info">
                  <h3>Pedido #{{ $order->order_number }}</h3>
                  <p class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="order-status">
                  <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </div>
              </div>
              
              <div class="order-content">
                <div class="order-items">
                  <h4>Productos:</h4>
                  @foreach($order->orderItems as $item)
                    <div class="order-item">
                      <div class="item-info">
                        <span class="item-name">{{ $item->product->name }}</span>
                        <span class="item-quantity">Cantidad: {{ $item->quantity }}</span>
                      </div>
                      <div class="item-price">
                        ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                      </div>
                    </div>
                  @endforeach
                </div>
                
                <div class="order-summary">
                  <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                  </div>
                  <div class="summary-row">
                    <span>IVA (19%):</span>
                    <span>${{ number_format($order->tax, 0, ',', '.') }}</span>
                  </div>
                  <div class="summary-row total">
                    <span>Total:</span>
                    <span>${{ number_format($order->total, 0, ',', '.') }}</span>
                  </div>
                </div>
              </div>
              
              <div class="order-actions">
                <a href="{{ route('orders.show', $order) }}" class="btn-view">
                  <i class="fas fa-eye"></i> Ver Detalles
                </a>
                @if($order->status == 'pending')
                  <a href="#" class="btn-cancel" onclick="return confirm('¿Estás seguro de cancelar este pedido?')">
                    <i class="fas fa-times"></i> Cancelar
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <div class="pagination">
          {{ $orders->links() }}
        </div>
      @else
        <div class="no-data">
          <i class="fas fa-shopping-bag"></i>
          <h3>No tienes pedidos</h3>
          <p>Aún no has realizado ningún pedido.</p>
          <a href="{{ route('catalogo') }}" class="btn-shop">
            <i class="fas fa-shopping-cart"></i> Comenzar a Comprar
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
      max-width: 1000px;
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

    .btn-shop {
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

    .btn-shop:hover {
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

    .orders-list {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .order-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .order-card:hover {
      transform: translateY(-2px);
    }

    .order-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }

    .order-info h3 {
      margin: 0 0 5px 0;
      color: #333;
    }

    .order-date {
      margin: 0;
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

    .order-content {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
      margin-bottom: 15px;
    }

    .order-items h4 {
      margin: 0 0 10px 0;
      color: #333;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .order-item:last-child {
      border-bottom: none;
    }

    .item-info {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .item-name {
      font-weight: bold;
      color: #333;
    }

    .item-quantity {
      font-size: 0.9rem;
      color: #666;
    }

    .item-price {
      font-weight: bold;
      color: #000;
    }

    .order-summary {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px 0;
    }

    .summary-row.total {
      border-top: 1px solid #dee2e6;
      margin-top: 10px;
      padding-top: 10px;
      font-weight: bold;
      font-size: 1.1rem;
    }

    .order-actions {
      display: flex;
      gap: 10px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }

    .btn-view, .btn-cancel {
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

    .btn-cancel {
      background: #dc3545;
      color: white;
    }

    .btn-cancel:hover {
      background: #c82333;
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

    .pagination {
      display: flex;
      justify-content: center;
      margin-top: 30px;
    }

    @media (max-width: 768px) {
      .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }

      .order-content {
        grid-template-columns: 1fr;
      }

      .order-actions {
        flex-direction: column;
      }
    }
  </style>
</body>
</html>




