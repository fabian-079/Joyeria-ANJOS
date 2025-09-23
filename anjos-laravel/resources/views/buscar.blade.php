<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Búsqueda - Anjos Joyería y Accesorios</title>
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
        <a href="{{ route('buscar') }}"><i class="fas fa-search"></i></a>
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
    <div class="busqueda-container">
      <h2><i class="fas fa-search"></i> Buscar Productos</h2>
      
      <div class="busqueda-form">
        <form method="GET" action="{{ route('buscar') }}">
          <div class="busqueda-input">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar productos..." required>
            <button type="submit"><i class="fas fa-search"></i></button>
          </div>
        </form>
      </div>

      @if(request('q'))
        <div class="resultados-busqueda">
          <h3>Resultados para "{{ request('q') }}"</h3>
          
          @if($products->count() > 0)
            <div class="productos-grid">
              @foreach($products as $producto)
                <div class="tarjeta-producto">
                  <img src="{{ $producto->image_url }}" alt="{{ $producto->name }}">
                  <h4>{{ strtoupper($producto->name) }}</h4>
                  <p class="precio">${{ number_format($producto->price, 0, ',', '.') }}</p>
                  <p class="descripcion">{{ Str::limit($producto->description, 100) }}</p>
                  <p class="envio">Envío gratis en 24h</p>
                  <div class="producto-acciones">
                    <a href="{{ route('producto.show', $producto->id) }}" class="boton-ver">Ver detalles</a>
                    @auth
                      <form action="{{ route('producto.carrito', $producto->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="boton-carrito">Añadir al carrito</button>
                      </form>
                    @else
                      <a href="{{ route('login') }}" class="boton-carrito">Iniciar sesión para comprar</a>
                    @endauth
                  </div>
                </div>
              @endforeach
            </div>

            <div class="paginacion">
              {{ $products->appends(request()->query())->links() }}
            </div>
          @else
            <div class="sin-resultados">
              <i class="fas fa-search"></i>
              <h3>No se encontraron productos</h3>
              <p>Intenta con otros términos de búsqueda</p>
              <a href="{{ route('catalogo') }}" class="boton-continuar">Ver todos los productos</a>
            </div>
          @endif
        </div>
      @else
        <div class="sugerencias-busqueda">
          <h3>Sugerencias de búsqueda</h3>
          <div class="sugerencias-grid">
            <div class="sugerencia">
              <i class="fas fa-gem"></i>
              <h4>Anillos</h4>
              <p>Anillos de compromiso, bodas y moda</p>
            </div>
            <div class="sugerencia">
              <i class="fas fa-link"></i>
              <h4>Cadenas</h4>
              <p>Cadenas de oro y plata</p>
            </div>
            <div class="sugerencia">
              <i class="fas fa-clock"></i>
              <h4>Relojes</h4>
              <p>Relojes de lujo para hombre y mujer</p>
            </div>
            <div class="sugerencia">
              <i class="fas fa-hand-paper"></i>
              <h4>Pulseras</h4>
              <p>Pulseras elegantes y modernas</p>
            </div>
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
  </script>

  <style>
    .busqueda-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .busqueda-container h2 {
      color: #333;
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .busqueda-form {
      background: white;
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .busqueda-input {
      display: flex;
      max-width: 600px;
      margin: 0 auto;
    }

    .busqueda-input input {
      flex: 1;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 5px 0 0 5px;
      font-size: 1rem;
    }

    .busqueda-input button {
      background: #000;
      color: white;
      border: none;
      padding: 15px 20px;
      border-radius: 0 5px 5px 0;
      cursor: pointer;
      font-size: 1rem;
    }

    .busqueda-input button:hover {
      background: #333;
    }

    .resultados-busqueda h3 {
      color: #333;
      margin-bottom: 20px;
    }

    .productos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 25px;
      margin-bottom: 30px;
    }

    .producto-acciones {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .boton-ver {
      flex: 1;
      padding: 8px;
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

    .boton-carrito {
      flex: 1;
      padding: 8px;
      background: #000;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
      text-decoration: none;
      text-align: center;
      display: inline-block;
    }

    .boton-carrito:hover {
      background: #333;
    }

    .sin-resultados {
      text-align: center;
      padding: 60px 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .sin-resultados i {
      font-size: 4rem;
      color: #ccc;
      margin-bottom: 20px;
    }

    .sin-resultados h3 {
      color: #333;
      margin-bottom: 10px;
    }

    .sin-resultados p {
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

    .sugerencias-busqueda {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .sugerencias-busqueda h3 {
      color: #333;
      margin-bottom: 20px;
      text-align: center;
    }

    .sugerencias-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .sugerencia {
      text-align: center;
      padding: 20px;
      border: 1px solid #eee;
      border-radius: 8px;
      transition: transform 0.3s;
      cursor: pointer;
    }

    .sugerencia:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .sugerencia i {
      font-size: 2rem;
      color: #000;
      margin-bottom: 10px;
    }

    .sugerencia h4 {
      color: #333;
      margin: 10px 0;
    }

    .sugerencia p {
      color: #666;
      font-size: 0.9rem;
      margin: 0;
    }

    .paginacion {
      display: flex;
      justify-content: center;
      margin-top: 30px;
    }

    @media (max-width: 768px) {
      .busqueda-input {
        flex-direction: column;
      }

      .busqueda-input input {
        border-radius: 5px;
        margin-bottom: 10px;
      }

      .busqueda-input button {
        border-radius: 5px;
      }

      .producto-acciones {
        flex-direction: column;
      }

      .sugerencias-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</body>
</html>









