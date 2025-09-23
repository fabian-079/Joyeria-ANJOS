<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Anjos Joyería y Accesorios</title>
  <link rel="stylesheet" href="{{ asset('css/inicio.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <header class="encabezado">
    <div class="contenedor-logo">
      <a href="{{ url('/') }}"><img src="{{ asset('img/Logo.png') }}" alt="Logo Anjos" class="logo"/></a>
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
        <a href="{{ url('/favoritos') }}"><i class="fas fa-heart"></i></a>
        <a href="{{ route('buscar') }}"><i class="fas fa-search"></i></a>
        <a href="{{ url('/carrito') }}"><i class="fas fa-shopping-cart"></i></a>
      </div>
    </div>
  </header>

  <button class="boton-menu">☰ Menú</button>

  <aside class="menu-lateral" id="menuLateral">
    <nav>
      <ul>
        <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Inicio</a></li>
        <li><a href="{{ url('/catalogo') }}"><i class="fas fa-list"></i> Catálogo</a></li>
        <li><a href="{{ route('personalizacion.create') }}"><i class="fas fa-pencil-alt"></i> Personalización</a></li>
        <li><a href="{{ route('reparaciones.create') }}"><i class="fas fa-tools"></i> Reparaciones</a></li>
      </ul>
    </nav>
  </aside>

  <main class="contenido-principal">
    <div class="carrusel">
      <button class="boton-carrusel izquierda" onclick="moverDiapositiva(-1)">&#10094;</button>
      <div class="contenedor-carrusel">
        <img class="imagen-carrusel activa" src="{{ asset('img/cerrar-joyer¦¼mantes-sobre-fondo-negro-reflejo_293060-11879.avif') }}" alt="Colección de joyería">
        <img class="imagen-carrusel" src="{{ asset('img/anillos-compromiso-boda-gotas-agua_41451-3.avif') }}" alt="Anillos de compromiso">
        <img class="imagen-carrusel" src="{{ asset('img/Reloj-submarino-de-lujo-para-hombre-cron-grafo-de-la-serie-Water-Ghost-multifunci-n-movimiento.webp') }}" alt="Relojes de lujo">
      </div>
      <button class="boton-carrusel derecha" onclick="moverDiapositiva(1)">&#10095;</button>
    </div>

    <div class="destacados">
      <h2>Nuestros Productos Destacados</h2>
      <p>Descubre las piezas exclusivas de esta temporada</p>
    </div>

    <section class="lista-productos">
      <!-- Productos destacados dinámicos desde la base de datos -->
      @forelse ($featuredProducts as $producto)
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
      @empty
        <div class="sin-productos">
          <h3>No hay productos destacados disponibles</h3>
          <p>Pronto agregaremos nuevos productos destacados</p>
        </div>
      @endforelse
    </section>
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

    let diapositivaActual = 0;
    const diapositivas = document.querySelectorAll('.imagen-carrusel');

    function mostrarDiapositiva(indice) {
      diapositivas.forEach((img, i) => {
        img.classList.remove('activa');
        if (i === indice) img.classList.add('activa');
      });
    }

    function moverDiapositiva(paso) {
      diapositivaActual = (diapositivaActual + paso + diapositivas.length) % diapositivas.length;
      mostrarDiapositiva(diapositivaActual);
    }

    setInterval(() => {
      moverDiapositiva(1);
    }, 5000);
  </script>

  <style>
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
      font-size: 0.9rem;
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
      font-size: 0.9rem;
    }

    .boton-carrito:hover {
      background: #333;
    }

    .sin-productos {
      grid-column: 1 / -1;
      text-align: center;
      padding: 40px;
      color: #666;
    }

    @media (max-width: 768px) {
      .producto-acciones {
        flex-direction: column;
      }
    }
  </style>
</body>
</html>
