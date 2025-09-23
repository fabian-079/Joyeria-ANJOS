<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalles del Pedido - Anjos Joyería</title>
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
        <a href="{{ route('orders.index') }}"><i class="fas fa-arrow-left"></i> Volver a Pedidos</a> | 
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
        <h1><i class="fas fa-shopping-bag"></i> Pedido #{{ $order->order_number }}</h1>
        <div class="header-actions">
          <a href="{{ route('orders.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver a Pedidos
          </a>
        </div>
      </div>

      <div class="order-details">
        <div class="details-grid">
          <div class="detail-card">
            <h3><i class="fas fa-info-circle"></i> Información del Pedido</h3>
            <div class="detail-content">
              <div class="detail-item">
                <label>Número de Pedido:</label>
                <span>{{ $order->order_number }}</span>
              </div>
              <div class="detail-item">
                <label>Fecha:</label>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
              </div>
              <div class="detail-item">
                <label>Estado:</label>
                <span class="status-badge status-{{ $order->status }}">
                  {{ ucfirst($order->status) }}
                </span>
              </div>
              @if($order->notes)
                <div class="detail-item">
                  <label>Notas:</label>
                  <span>{{ $order->notes }}</span>
                </div>
              @endif
            </div>
          </div>

          <div class="detail-card">
            <h3><i class="fas fa-user"></i> Información del Cliente</h3>
            <div class="detail-content">
              <div class="detail-item">
                <label>Cliente:</label>
                <span>{{ $order->user->name }}</span>
              </div>
              <div class="detail-item">
                <label>Email:</label>
                <span>{{ $order->user->email }}</span>
              </div>
              <div class="detail-item">
                <label>Teléfono:</label>
                <span>{{ $order->phone }}</span>
              </div>
            </div>
          </div>

          <div class="detail-card">
            <h3><i class="fas fa-map-marker-alt"></i> Dirección de Envío</h3>
            <div class="detail-content">
              <div class="detail-item">
                <label>Dirección:</label>
                <span>{{ $order->shipping_address }}</span>
              </div>
              <div class="detail-item">
                <label>Dirección de Facturación:</label>
                <span>{{ $order->billing_address }}</span>
              </div>
            </div>
          </div>

          <div class="detail-card full-width">
            <h3><i class="fas fa-shopping-cart"></i> Productos del Pedido</h3>
            <div class="detail-content">
              <div class="order-items-table">
                <table>
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th>Precio Unitario</th>
                      <th>Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($order->orderItems as $item)
                      <tr>
                        <td>
                          <div class="product-info">
                            <span class="product-name">{{ $item->product->name }}</span>
                            @if($item->product->image)
                              <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image">
                            @endif
                          </div>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>${{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="detail-card">
            <h3><i class="fas fa-calculator"></i> Resumen de Costos</h3>
            <div class="detail-content">
              <div class="cost-summary">
                <div class="cost-row">
                  <span>Subtotal:</span>
                  <span>${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="cost-row">
                  <span>IVA (19%):</span>
                  <span>${{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="cost-row total">
                  <span>Total:</span>
                  <span>${{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
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

    .btn-back {
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s;
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

    .order-items-table {
      overflow-x: auto;
    }

    .order-items-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .order-items-table th,
    .order-items-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .order-items-table th {
      background: #f8f9fa;
      font-weight: bold;
      color: #333;
    }

    .product-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .product-name {
      font-weight: bold;
      color: #333;
    }

    .product-image {
      width: 40px;
      height: 40px;
      object-fit: cover;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .cost-summary {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
    }

    .cost-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
    }

    .cost-row.total {
      border-top: 1px solid #dee2e6;
      margin-top: 10px;
      padding-top: 10px;
      font-weight: bold;
      font-size: 1.1rem;
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

      .order-items-table {
        font-size: 0.9rem;
      }

      .product-info {
        flex-direction: column;
        align-items: flex-start;
      }
    }
  </style>
</body>
</html>




