<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Favoritos - Anjos Joyería y Accesorios</title>
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
    <div class="favoritos-container">
      <h2><i class="fas fa-heart"></i> Mis Favoritos</h2>

      @if($favorites->count() > 0)
        <div class="favoritos-grid">
          @foreach($favorites as $favorite)
            <div class="tarjeta-favorito">
              @if($favorite->product)
                <img src="{{ $favorite->product->image_url }}" alt="{{ $favorite->product->name }}">
                <h4>{{ strtoupper($favorite->product->name) }}</h4>
                <p class="precio">${{ number_format($favorite->product->price, 0, ',', '.') }}</p>
                <p class="descripcion">{{ Str::limit($favorite->product->description, 100) }}</p>
                <div class="favorito-acciones">
                  <a href="{{ route('producto.show', $favorite->product->id) }}" class="boton-ver">Ver detalles</a>
                  <form action="{{ route('producto.carrito', $favorite->product->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="boton-carrito">Añadir al carrito</button>
                  </form>
                  <form action="{{ route('favoritos.remove', $favorite->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este favorito?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="boton-eliminar"><i class="fas fa-trash"></i></button>
                  </form>
                </div>
              @elseif($favorite->customization)
                <div class="personalizacion-preview">
                  <i class="fas fa-gem"></i>
                  <h4>{{ strtoupper($favorite->customization->jewelry_type) }}</h4>
                  <p class="precio">{{ $favorite->customization->formatted_estimated_price }}</p>
                  <p class="descripcion">{{ Str::limit($favorite->customization->special_instructions, 100) }}</p>
                  <div class="favorito-acciones">
                    <a href="{{ route('personalizacion.show', $favorite->customization->id) }}" class="boton-ver">Ver detalles</a>
                    <form action="{{ route('personalizacion.carrito', $favorite->customization->id) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="boton-carrito">Añadir al carrito</button>
                    </form>
                    <form action="{{ route('favoritos.remove', $favorite->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este favorito?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="boton-eliminar"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <div class="favoritos-vacio">
          <i class="fas fa-heart"></i>
          <h3>No tienes favoritos</h3>
          <p>Agrega productos o personalizaciones a tus favoritos para verlos aquí</p>
          <a href="{{ route('catalogo') }}" class="boton-continuar">Explorar productos</a>
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
    .favoritos-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .favoritos-container h2 {
      color: #333;
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .favoritos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 25px;
    }

    .tarjeta-favorito {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .tarjeta-favorito:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .tarjeta-favorito img {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }

    .personalizacion-preview {
      padding: 20px;
      text-align: center;
    }

    .personalizacion-preview i {
      font-size: 3rem;
      color: #000;
      margin-bottom: 15px;
    }

    .tarjeta-favorito h4 {
      margin: 15px 10px 5px;
      font-size: 1.1rem;
      color: #333;
    }

    .precio {
      margin: 0 10px 5px;
      font-weight: bold;
      color: #000;
      font-size: 1.2rem;
    }

    .descripcion {
      margin: 0 10px 15px;
      color: #666;
      font-size: 0.9rem;
    }

    .favorito-acciones {
      display: flex;
      gap: 10px;
      margin: 0 10px 15px;
      align-items: center;
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
      font-size: 0.9rem;
    }

    .boton-carrito:hover {
      background: #333;
    }

    .boton-eliminar {
      background: #dc3545;
      color: white;
      border: none;
      padding: 8px;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .boton-eliminar:hover {
      background: #c82333;
    }

    .favoritos-vacio {
      text-align: center;
      padding: 60px 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .favoritos-vacio i {
      font-size: 4rem;
      color: #ccc;
      margin-bottom: 20px;
    }

    .favoritos-vacio h3 {
      color: #333;
      margin-bottom: 10px;
    }

    .favoritos-vacio p {
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

    @media (max-width: 768px) {
      .favoritos-grid {
        grid-template-columns: 1fr;
      }

      .favorito-acciones {
        flex-direction: column;
      }
    }
  </style>
</body>
</html>









