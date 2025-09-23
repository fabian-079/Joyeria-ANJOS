<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Catálogo - Anjos Joyería y Accesorios</title>
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
    <div class="filtros-container">
      <h2>Catálogo de Productos</h2>
      
      <form method="GET" action="{{ route('catalogo') }}" class="filtros-form">
        <div class="filtros-grid">
          <div class="filtro-grupo">
            <label>Categoría:</label>
            <select name="category">
              <option value="">Todas las categorías</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="filtro-grupo">
            <label>Material:</label>
            <select name="material">
              <option value="">Todos los materiales</option>
              @foreach($materials as $material)
                <option value="{{ $material }}" {{ request('material') == $material ? 'selected' : '' }}>
                  {{ $material }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="filtro-grupo">
            <label>Color:</label>
            <select name="color">
              <option value="">Todos los colores</option>
              @foreach($colors as $color)
                <option value="{{ $color }}" {{ request('color') == $color ? 'selected' : '' }}>
                  {{ $color }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="filtro-grupo">
            <label>Acabado:</label>
            <select name="finish">
              <option value="">Todos los acabados</option>
              @foreach($finishes as $finish)
                <option value="{{ $finish }}" {{ request('finish') == $finish ? 'selected' : '' }}>
                  {{ $finish }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="filtro-grupo">
            <label>Piedras:</label>
            <select name="stones">
              <option value="">Todas las piedras</option>
              @foreach($stones as $stone)
                <option value="{{ $stone }}" {{ request('stones') == $stone ? 'selected' : '' }}>
                  {{ $stone }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="filtro-grupo">
            <label>Precio mínimo:</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0">
          </div>

          <div class="filtro-grupo">
            <label>Precio máximo:</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="5000000">
          </div>

          <div class="filtro-grupo">
            <label>Stock:</label>
            <select name="stock">
              <option value="">Todos</option>
              <option value="available" {{ request('stock') == 'available' ? 'selected' : '' }}>Disponible</option>
              <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Agotado</option>
            </select>
          </div>

          <div class="filtro-grupo">
            <label>Buscar:</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o descripción">
          </div>
        </div>

        <div class="filtros-botones">
          <button type="submit" class="boton-filtrar">Filtrar</button>
          <a href="{{ route('catalogo') }}" class="boton-limpiar">Limpiar filtros</a>
        </div>
      </form>
    </div>

    <div class="productos-grid">
      @forelse($products as $producto)
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
          <h3>No se encontraron productos</h3>
          <p>Intenta ajustar los filtros de búsqueda</p>
        </div>
      @endforelse
    </div>

    <div class="paginacion">
      {{ $products->links() }}
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
    .filtros-container {
      background: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .filtros-form {
      margin-top: 20px;
    }

    .filtros-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    .filtro-grupo {
      display: flex;
      flex-direction: column;
    }

    .filtro-grupo label {
      font-weight: bold;
      margin-bottom: 5px;
      color: #333;
    }

    .filtro-grupo select,
    .filtro-grupo input {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .filtros-botones {
      display: flex;
      gap: 10px;
    }

    .boton-filtrar,
    .boton-limpiar {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .boton-filtrar {
      background: #000;
      color: white;
    }

    .boton-filtrar:hover {
      background: #333;
    }

    .boton-limpiar {
      background: #f0f0f0;
      color: #333;
    }

    .boton-limpiar:hover {
      background: #e0e0e0;
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

    .sin-productos {
      grid-column: 1 / -1;
      text-align: center;
      padding: 40px;
      color: #666;
    }

    .paginacion {
      display: flex;
      justify-content: center;
      margin-top: 30px;
    }
  </style>
</body>
</html>





