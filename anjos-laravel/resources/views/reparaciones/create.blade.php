<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Solicitar Reparación - Anjos Joyería y Accesorios</title>
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
    <div class="reparacion-container">
      <h2><i class="fas fa-tools"></i> Solicitar Reparación</h2>
      <p class="descripcion-seccion">¿Necesitas reparar una joya? Completa el formulario y nos pondremos en contacto contigo para evaluar el trabajo.</p>

      <div class="formulario-reparacion">
        <form action="{{ route('reparaciones.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          
          <div class="form-group">
            <label for="customer_name">Nombre completo *</label>
            <input type="text" name="customer_name" id="customer_name" required 
                   value="{{ old('customer_name', Auth::user()->name ?? '') }}"
                   placeholder="Tu nombre completo">
            @error('customer_name')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="phone">Número de teléfono *</label>
            <input type="tel" name="phone" id="phone" required 
                   value="{{ old('phone', Auth::user()->phone ?? '') }}"
                   placeholder="Tu número de teléfono">
            @error('phone')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="description">Descripción del problema *</label>
            <textarea name="description" id="description" required rows="4" 
                      placeholder="Describe detalladamente qué necesita reparación, el tipo de joya, el problema específico, etc.">{{ old('description') }}</textarea>
            @error('description')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="image">Imagen de la joya (opcional)</label>
            <input type="file" name="image" id="image" accept="image/*">
            <small class="help-text">Puedes subir una foto de la joya para ayudarnos a entender mejor el problema</small>
            @error('image')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="preview_image">Vista previa de la imagen</label>
            <div id="image_preview" class="image-preview" style="display: none;">
              <img id="preview_img" src="" alt="Vista previa">
              <button type="button" id="remove_image" class="remove-image-btn">Eliminar imagen</button>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="boton-enviar">
              <i class="fas fa-paper-plane"></i> Enviar Solicitud
            </button>
            <a href="{{ route('inicio') }}" class="boton-cancelar">Cancelar</a>
          </div>
        </form>
      </div>

      <div class="info-reparacion">
        <h3><i class="fas fa-info-circle"></i> Información sobre nuestras reparaciones</h3>
        <div class="info-grid">
          <div class="info-item">
            <i class="fas fa-clock"></i>
            <h4>Tiempo estimado</h4>
            <p>7-15 días hábiles dependiendo de la complejidad del trabajo</p>
          </div>
          <div class="info-item">
            <i class="fas fa-dollar-sign"></i>
            <h4>Costo</h4>
            <p>Te contactaremos con una cotización detallada antes de comenzar</p>
          </div>
          <div class="info-item">
            <i class="fas fa-shield-alt"></i>
            <h4>Garantía</h4>
            <p>6 meses de garantía en todos nuestros trabajos de reparación</p>
          </div>
          <div class="info-item">
            <i class="fas fa-truck"></i>
            <h4>Envío</h4>
            <p>Servicio de recogida y entrega disponible en Bogotá</p>
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

    // Manejo de vista previa de imagen
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    const removeImageBtn = document.getElementById('remove_image');

    imageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });

    removeImageBtn.addEventListener('click', function() {
      imageInput.value = '';
      imagePreview.style.display = 'none';
    });
  </script>

  <style>
    .reparacion-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .reparacion-container h2 {
      color: #333;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .descripcion-seccion {
      color: #666;
      margin-bottom: 30px;
      font-size: 1.1rem;
    }

    .formulario-reparacion {
      background: white;
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #000;
    }

    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    .help-text {
      display: block;
      margin-top: 5px;
      color: #666;
      font-size: 0.9rem;
    }

    .error-message {
      display: block;
      margin-top: 5px;
      color: #dc3545;
      font-size: 0.9rem;
    }

    .image-preview {
      margin-top: 10px;
      text-align: center;
    }

    .image-preview img {
      max-width: 200px;
      max-height: 200px;
      border-radius: 5px;
      margin-bottom: 10px;
    }

    .remove-image-btn {
      background: #dc3545;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 3px;
      cursor: pointer;
      font-size: 0.9rem;
    }

    .remove-image-btn:hover {
      background: #c82333;
    }

    .form-actions {
      display: flex;
      gap: 15px;
      margin-top: 30px;
    }

    .boton-enviar {
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

    .boton-enviar:hover {
      background: #333;
    }

    .boton-cancelar {
      background: #6c757d;
      color: white;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
      transition: background 0.3s;
    }

    .boton-cancelar:hover {
      background: #5a6268;
    }

    .info-reparacion {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .info-reparacion h3 {
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .info-item {
      text-align: center;
      padding: 20px;
      border: 1px solid #eee;
      border-radius: 8px;
    }

    .info-item i {
      font-size: 2rem;
      color: #000;
      margin-bottom: 10px;
    }

    .info-item h4 {
      color: #333;
      margin: 10px 0;
    }

    .info-item p {
      color: #666;
      font-size: 0.9rem;
      margin: 0;
    }

    @media (max-width: 768px) {
      .form-actions {
        flex-direction: column;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</body>
</html>









