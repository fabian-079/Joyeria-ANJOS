<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $product->name }} - Anjos Joyería y Accesorios</title>
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
        @auth
          <a href="{{ route('dashboard') }}"><i class="fas fa-user"></i> {{ Auth::user()->name }}</a> | 
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Salir</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        @else
          <a href="{{ route('login') }}"><i class="fas fa-user"></i> Ingresar</a> | 
          <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Registrarse</a>
        @endauth
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
    <div class="producto-detalle-container">
      <div class="breadcrumb">
        <a href="{{ route('inicio') }}">Inicio</a> > 
        <a href="{{ route('catalogo') }}">Catálogo</a> > 
        <span>{{ $product->name }}</span>
      </div>

      <div class="producto-detalle">
        <div class="producto-imagen">
          <img src="{{ $product->image_url }}" alt="{{ $product->name }}" id="imagen-principal">
          @if($product->gallery && count($product->gallery) > 0)
            <div class="galeria-miniaturas">
              @foreach($product->gallery as $imagen)
                <img src="{{ asset('storage/' . $imagen) }}" alt="{{ $product->name }}" class="miniatura" onclick="cambiarImagen('{{ asset('storage/' . $imagen) }}')">
              @endforeach
            </div>
          @endif
        </div>

        <div class="producto-info">
          <h1>{{ $product->name }}</h1>
          <p class="precio">${{ number_format($product->price, 0, ',', '.') }}</p>
          
          <div class="producto-descripcion">
            <h3>Descripción</h3>
            <p>{{ $product->description }}</p>
          </div>

          <div class="producto-especificaciones">
            <h3>Especificaciones</h3>
            <div class="especificaciones-grid">
              <div class="especificacion">
                <span class="label">Categoría:</span>
                <span class="valor">{{ $product->category->name }}</span>
              </div>
              @if($product->material)
                <div class="especificacion">
                  <span class="label">Material:</span>
                  <span class="valor">{{ $product->material }}</span>
                </div>
              @endif
              @if($product->color)
                <div class="especificacion">
                  <span class="label">Color:</span>
                  <span class="valor">{{ $product->color }}</span>
                </div>
              @endif
              @if($product->finish)
                <div class="especificacion">
                  <span class="label">Acabado:</span>
                  <span class="valor">{{ $product->finish }}</span>
                </div>
              @endif
              @if($product->stones)
                <div class="especificacion">
                  <span class="label">Piedras:</span>
                  <span class="valor">{{ $product->stones }}</span>
                </div>
              @endif
              <div class="especificacion">
                <span class="label">Stock disponible:</span>
                <span class="valor {{ $product->stock <= 5 ? 'stock-bajo' : '' }}">{{ $product->stock }}</span>
              </div>
            </div>
          </div>

          <div class="producto-acciones">
            @if($product->stock > 0)
              <form action="{{ route('producto.carrito', $product->id) }}" method="POST" class="form-carrito">
                @csrf
                <div class="cantidad-selector">
                  <label for="quantity">Cantidad:</label>
                  <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                </div>
                <button type="submit" class="boton-carrito">
                  <i class="fas fa-shopping-cart"></i> Añadir al carrito
                </button>
              </form>
            @else
              <div class="sin-stock">
                <p>Producto agotado</p>
              </div>
            @endif

            @auth
              <form action="{{ route('producto.favoritos', $product->id) }}" method="POST" class="form-favoritos">
                @csrf
                <button type="submit" class="boton-favoritos">
                  <i class="fas fa-heart"></i> Agregar a favoritos
                </button>
              </form>
            @else
              <a href="{{ route('login') }}" class="boton-favoritos">
                <i class="fas fa-heart"></i> Iniciar sesión para agregar a favoritos
              </a>
            @endauth
          </div>

          <div class="envio-info">
            <p><i class="fas fa-truck"></i> Envío gratis en 24 horas</p>
            <p><i class="fas fa-shield-alt"></i> Garantía de 6 meses</p>
            <p><i class="fas fa-undo"></i> Devolución gratuita</p>
          </div>
        </div>
      </div>

      @if($relatedProducts->count() > 0)
        <div class="productos-relacionados">
          <h3>Productos Relacionados</h3>
          <div class="productos-grid">
            @foreach($relatedProducts as $relatedProduct)
              <div class="tarjeta-producto">
                <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}">
                <h4>{{ strtoupper($relatedProduct->name) }}</h4>
                <p class="precio">${{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                <p class="descripcion">{{ Str::limit($relatedProduct->description, 100) }}</p>
                <a href="{{ route('producto.show', $relatedProduct->id) }}" class="boton-ver">Ver detalles</a>
              </div>
            @endforeach
          </div>
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

    function cambiarImagen(src) {
      document.getElementById('imagen-principal').src = src;
    }
  </script>

  <style>
    .producto-detalle-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .breadcrumb {
      margin-bottom: 20px;
      color: #666;
    }

    .breadcrumb a {
      color: #000;
      text-decoration: none;
    }

    .breadcrumb a:hover {
      text-decoration: underline;
    }

    .producto-detalle {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      margin-bottom: 40px;
    }

    .producto-imagen img {
      width: 100%;
      height: 400px;
      object-fit: cover;
      border-radius: 10px;
    }

    .galeria-miniaturas {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }

    .miniatura {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 5px;
      cursor: pointer;
      border: 2px solid transparent;
      transition: border-color 0.3s;
    }

    .miniatura:hover {
      border-color: #000;
    }

    .producto-info h1 {
      color: #333;
      margin-bottom: 15px;
    }

    .precio {
      font-size: 2rem;
      font-weight: bold;
      color: #000;
      margin-bottom: 20px;
    }

    .producto-descripcion,
    .producto-especificaciones {
      margin-bottom: 25px;
    }

    .producto-descripcion h3,
    .producto-especificaciones h3 {
      color: #333;
      margin-bottom: 10px;
    }

    .especificaciones-grid {
      display: grid;
      gap: 10px;
    }

    .especificacion {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px solid #eee;
    }

    .especificacion .label {
      font-weight: bold;
      color: #666;
    }

    .especificacion .valor {
      color: #333;
    }

    .stock-bajo {
      color: #dc3545;
      font-weight: bold;
    }

    .producto-acciones {
      margin-bottom: 25px;
    }

    .form-carrito {
      display: flex;
      gap: 15px;
      align-items: end;
      margin-bottom: 15px;
    }

    .cantidad-selector {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .cantidad-selector label {
      font-weight: bold;
      color: #333;
    }

    .cantidad-selector input {
      width: 80px;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .boton-carrito {
      background: #000;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s;
    }

    .boton-carrito:hover {
      background: #333;
    }

    .sin-stock {
      background: #f8d7da;
      color: #721c24;
      padding: 15px;
      border-radius: 5px;
      text-align: center;
    }

    .boton-favoritos {
      background: #dc3545;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.3s;
    }

    .boton-favoritos:hover {
      background: #c82333;
    }

    .envio-info {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
    }

    .envio-info p {
      margin: 5px 0;
      color: #333;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .productos-relacionados {
      margin-top: 40px;
    }

    .productos-relacionados h3 {
      color: #333;
      margin-bottom: 20px;
    }

    .productos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 25px;
    }

    .tarjeta-producto {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .tarjeta-producto:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .tarjeta-producto img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .tarjeta-producto h4 {
      margin: 15px 10px 5px;
      font-size: 1rem;
      color: #333;
    }

    .tarjeta-producto .precio {
      margin: 0 10px 5px;
      font-weight: bold;
      color: #000;
      font-size: 1.1rem;
    }

    .tarjeta-producto .descripcion {
      margin: 0 10px 10px;
      color: #666;
      font-size: 0.9rem;
    }

    .boton-ver {
      display: block;
      width: calc(100% - 20px);
      margin: 0 10px 15px;
      padding: 10px;
      background: #f0f0f0;
      color: #333;
      text-decoration: none;
      text-align: center;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .boton-ver:hover {
      background: #e0e0e0;
    }

    @media (max-width: 768px) {
      .producto-detalle {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .form-carrito {
        flex-direction: column;
        align-items: stretch;
      }

      .cantidad-selector {
        align-items: center;
      }

      .cantidad-selector input {
        width: 100px;
      }
    }
  </style>
</body>
</html>









