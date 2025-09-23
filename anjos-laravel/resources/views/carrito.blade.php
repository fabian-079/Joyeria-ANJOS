<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Carrito - Anjos Joyería y Accesorios</title>
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
        <a href="{{ route('dashboard') }}"><i class="fas fa-user"></i> {{ Auth::user()->name }}</a> | 
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Salir</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
      <div class="iconos">
        <a href="{{ route('favoritos') }}"><i class="fas fa-heart"></i></a>
        <a href="#"><i class="fas fa-search"></i></a>
        <a href="{{ route('carrito') }}"><i class="fas fa-shopping-cart"></i></a>
      </div>
    </div>
  </header>

  <button class="boton-menu">☰ Menú</button>

  <aside class="menu-lateral" id="menuLateral">
    <nav>
      <ul>
        <li><a href="{{ route('inicio') }}"><i class="fas fa-home"></i> Inicio</a></li>
        <li><a href="{{ route('catalogo') }}"><i class="fas fa-list"></i> Catálogo</a></li>
        <li><a href="{{ route('personalizacion.create') }}"><i class="fas fa-pencil-alt"></i> Personalización</a></li>
        <li><a href="{{ route('reparaciones.create') }}"><i class="fas fa-tools"></i> Reparaciones</a></li>
      </ul>
    </nav>
  </aside>

  <main class="contenido-principal">
    <div class="carrito-container">
      <h2><i class="fas fa-shopping-cart"></i> Mi Carrito</h2>

      @if($cartItems->count() > 0)
        <div class="carrito-items">
          @foreach($cartItems as $item)
            <div class="carrito-item">
              <div class="item-imagen">
                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
              </div>
              <div class="item-info">
                <h4>{{ $item->product->name }}</h4>
                <p class="item-precio">${{ number_format($item->product->price, 0, ',', '.') }}</p>
                <p class="item-material">{{ $item->product->material }} - {{ $item->product->color }}</p>
              </div>
              <div class="item-cantidad">
                <form action="{{ route('carrito.update', $item->id) }}" method="POST" class="cantidad-form">
                  @csrf
                  @method('PUT')
                  <label>Cantidad:</label>
                  <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                  <button type="submit" class="boton-actualizar">Actualizar</button>
                </form>
              </div>
              <div class="item-subtotal">
                <p class="subtotal">${{ number_format($item->subtotal, 0, ',', '.') }}</p>
              </div>
              <div class="item-acciones">
                <form action="{{ route('carrito.remove', $item->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="boton-eliminar"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </div>
          @endforeach
        </div>

        <div class="carrito-resumen">
          <div class="resumen-detalle">
            <div class="resumen-linea">
              <span>Subtotal:</span>
              <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="resumen-linea">
              <span>IVA (19%):</span>
              <span>${{ number_format($tax, 0, ',', '.') }}</span>
            </div>
            <div class="resumen-linea total">
              <span>Total:</span>
              <span>${{ number_format($total, 0, ',', '.') }}</span>
            </div>
          </div>
          
          <div class="metodos-pago">
            <h4>Método de Pago</h4>
            <div class="opciones-pago">
              <label class="opcion-pago">
                <input type="radio" name="payment_method" value="tarjeta" checked>
                <span class="icono-pago">💳</span>
                <span>Tarjeta de Crédito/Débito</span>
              </label>
              <label class="opcion-pago">
                <input type="radio" name="payment_method" value="pse">
                <span class="icono-pago">🏦</span>
                <span>PSE</span>
              </label>
              <label class="opcion-pago">
                <input type="radio" name="payment_method" value="efectivo">
                <span class="icono-pago">💵</span>
                <span>Efectivo</span>
              </label>
            </div>
          </div>
        </div>

        <div class="carrito-checkout">
          <button type="button" class="boton-checkout" onclick="mostrarFormularioCheckout()">
            Proceder al Pago
          </button>
        </div>

        <!-- Formulario de checkout (oculto inicialmente) -->
        <div id="formulario-checkout" class="formulario-checkout" style="display: none;">
          <h3>Información de Envío</h3>
          <form action="{{ route('carrito.checkout') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="shipping_address">Dirección de envío:</label>
              <textarea name="shipping_address" id="shipping_address" required rows="3" placeholder="Ingresa tu dirección completa">{{ Auth::user()->address }}</textarea>
            </div>
            <div class="form-group">
              <label for="billing_address">Dirección de facturación:</label>
              <textarea name="billing_address" id="billing_address" required rows="3" placeholder="Ingresa tu dirección de facturación">{{ Auth::user()->address }}</textarea>
            </div>
            <div class="form-group">
              <label for="phone">Teléfono:</label>
              <input type="tel" name="phone" id="phone" required value="{{ Auth::user()->phone }}" placeholder="Tu número de teléfono">
            </div>
            <div class="form-group">
              <label for="notes">Notas adicionales:</label>
              <textarea name="notes" id="notes" rows="2" placeholder="Instrucciones especiales para la entrega"></textarea>
            </div>
            <div class="form-botones">
              <button type="button" class="boton-cancelar" onclick="ocultarFormularioCheckout()">Cancelar</button>
              <button type="submit" class="boton-confirmar">Confirmar Pedido</button>
            </div>
          </form>
        </div>

      @else
        <div class="carrito-vacio">
          <i class="fas fa-shopping-cart"></i>
          <h3>Tu carrito está vacío</h3>
          <p>Agrega algunos productos para comenzar tu compra</p>
          <a href="{{ route('catalogo') }}" class="boton-continuar">Continuar comprando</a>
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

    function mostrarFormularioCheckout() {
      document.getElementById('formulario-checkout').style.display = 'block';
      document.querySelector('.boton-checkout').style.display = 'none';
    }

    function ocultarFormularioCheckout() {
      document.getElementById('formulario-checkout').style.display = 'none';
      document.querySelector('.boton-checkout').style.display = 'block';
    }
  </script>

  <style>
    .carrito-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
    }

    .carrito-container h2 {
      color: #333;
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .carrito-items {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .carrito-item {
      display: grid;
      grid-template-columns: 100px 1fr 200px 120px 60px;
      gap: 20px;
      align-items: center;
      padding: 20px 0;
      border-bottom: 1px solid #eee;
    }

    .carrito-item:last-child {
      border-bottom: none;
    }

    .item-imagen img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 5px;
    }

    .item-info h4 {
      margin: 0 0 5px 0;
      color: #333;
    }

    .item-precio {
      font-weight: bold;
      color: #000;
      margin: 0 0 5px 0;
    }

    .item-material {
      color: #666;
      font-size: 0.9rem;
      margin: 0;
    }

    .cantidad-form {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .cantidad-form label {
      font-size: 0.9rem;
      color: #666;
    }

    .cantidad-form input {
      width: 60px;
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 3px;
    }

    .boton-actualizar {
      padding: 5px 10px;
      background: #000;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      font-size: 0.8rem;
    }

    .boton-actualizar:hover {
      background: #333;
    }

    .subtotal {
      font-weight: bold;
      color: #000;
      margin: 0;
    }

    .boton-eliminar {
      background: #dc3545;
      color: white;
      border: none;
      padding: 8px;
      border-radius: 3px;
      cursor: pointer;
    }

    .boton-eliminar:hover {
      background: #c82333;
    }

    .carrito-resumen {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .resumen-detalle {
      max-width: 300px;
      margin-left: auto;
    }

    .resumen-linea {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .resumen-linea.total {
      font-weight: bold;
      font-size: 1.2rem;
      border-top: 2px solid #000;
      padding-top: 10px;
      margin-top: 10px;
    }

    .carrito-checkout {
      text-align: center;
      margin-bottom: 20px;
    }

    .boton-checkout {
      background: #000;
      color: white;
      padding: 15px 30px;
      border: none;
      border-radius: 5px;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    .boton-checkout:hover {
      background: #333;
    }

    .formulario-checkout {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .formulario-checkout h3 {
      margin-bottom: 20px;
      color: #333;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    .form-botones {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 20px;
    }

    .boton-cancelar {
      background: #6c757d;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .boton-cancelar:hover {
      background: #5a6268;
    }

    .boton-confirmar {
      background: #28a745;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .boton-confirmar:hover {
      background: #218838;
    }

    .carrito-vacio {
      text-align: center;
      padding: 60px 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .carrito-vacio i {
      font-size: 4rem;
      color: #ccc;
      margin-bottom: 20px;
    }

    .carrito-vacio h3 {
      color: #333;
      margin-bottom: 10px;
    }

    .carrito-vacio p {
      color: #666;
      margin-bottom: 20px;
    }

    .boton-continuar {
      background: #000;
      color: white;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
      transition: background 0.3s;
    }

    .boton-continuar:hover {
      background: #333;
    }

    .metodos-pago {
      margin-top: 20px;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 8px;
    }

    .metodos-pago h4 {
      margin-bottom: 15px;
      color: #333;
    }

    .opciones-pago {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .opcion-pago {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px;
      background: white;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .opcion-pago:hover {
      background: #f0f0f0;
    }

    .opcion-pago input[type="radio"] {
      margin: 0;
    }

    .icono-pago {
      font-size: 1.2rem;
    }

    @media (max-width: 768px) {
      .carrito-item {
        grid-template-columns: 1fr;
        gap: 10px;
        text-align: center;
      }

      .item-imagen {
        justify-self: center;
      }

      .resumen-detalle {
        margin-left: 0;
        max-width: none;
      }

      .opciones-pago {
        flex-direction: column;
      }
    }
  </style>
</body>
</html>
