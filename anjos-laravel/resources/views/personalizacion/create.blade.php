<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Personalización - Anjos Joyería y Accesorios</title>
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
    <div class="personalizacion-container">
      <h2><i class="fas fa-pencil-alt"></i> Personaliza tu Joya</h2>
      <p class="descripcion-seccion">Crea la joya perfecta para ti. Selecciona cada detalle y nosotros la haremos realidad.</p>

      <div class="formulario-personalizacion">
        <form action="{{ route('personalizacion.store') }}" method="POST">
          @csrf
          
          <div class="form-section">
            <h3><i class="fas fa-gem"></i> Tipo de Joya</h3>
            <div class="form-group">
              <label for="jewelry_type">Selecciona el tipo de joya *</label>
              <select name="jewelry_type" id="jewelry_type" required>
                <option value="">Selecciona una opción</option>
                <option value="anillo" {{ old('jewelry_type') == 'anillo' ? 'selected' : '' }}>Anillo</option>
                <option value="cadena" {{ old('jewelry_type') == 'cadena' ? 'selected' : '' }}>Cadena</option>
                <option value="pulsera" {{ old('jewelry_type') == 'pulsera' ? 'selected' : '' }}>Pulsera</option>
                <option value="aretes" {{ old('jewelry_type') == 'aretes' ? 'selected' : '' }}>Aretes</option>
                <option value="dije" {{ old('jewelry_type') == 'dije' ? 'selected' : '' }}>Dije</option>
                <option value="reloj" {{ old('jewelry_type') == 'reloj' ? 'selected' : '' }}>Reloj</option>
              </select>
              @error('jewelry_type')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-section">
            <h3><i class="fas fa-palette"></i> Diseño y Estilo</h3>
            <div class="form-row">
              <div class="form-group">
                <label for="design">Estilo de diseño *</label>
                <select name="design" id="design" required>
                  <option value="">Selecciona un estilo</option>
                  <option value="clasico" {{ old('design') == 'clasico' ? 'selected' : '' }}>Clásico</option>
                  <option value="moderno" {{ old('design') == 'moderno' ? 'selected' : '' }}>Moderno</option>
                  <option value="vintage" {{ old('design') == 'vintage' ? 'selected' : '' }}>Vintage</option>
                  <option value="minimalista" {{ old('design') == 'minimalista' ? 'selected' : '' }}>Minimalista</option>
                  <option value="elegante" {{ old('design') == 'elegante' ? 'selected' : '' }}>Elegante</option>
                </select>
                @error('design')
                  <span class="error-message">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="finish">Acabado *</label>
                <select name="finish" id="finish" required>
                  <option value="">Selecciona un acabado</option>
                  <option value="brillante" {{ old('finish') == 'brillante' ? 'selected' : '' }}>Brillante</option>
                  <option value="mate" {{ old('finish') == 'mate' ? 'selected' : '' }}>Mate</option>
                  <option value="satin" {{ old('finish') == 'satin' ? 'selected' : '' }}>Satín</option>
                  <option value="texturizado" {{ old('finish') == 'texturizado' ? 'selected' : '' }}>Texturizado</option>
                </select>
                @error('finish')
                  <span class="error-message">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>

          <div class="form-section">
            <h3><i class="fas fa-gem"></i> Material y Piedras</h3>
            <div class="form-row">
              <div class="form-group">
                <label for="material">Material *</label>
                <select name="material" id="material" required>
                  <option value="">Selecciona un material</option>
                  <option value="oro 18k" {{ old('material') == 'oro 18k' ? 'selected' : '' }}>Oro 18k</option>
                  <option value="oro 14k" {{ old('material') == 'oro 14k' ? 'selected' : '' }}>Oro 14k</option>
                  <option value="plata sterling" {{ old('material') == 'plata sterling' ? 'selected' : '' }}>Plata Sterling</option>
                  <option value="acero inoxidable" {{ old('material') == 'acero inoxidable' ? 'selected' : '' }}>Acero Inoxidable</option>
                  <option value="platino" {{ old('material') == 'platino' ? 'selected' : '' }}>Platino</option>
                </select>
                @error('material')
                  <span class="error-message">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="color">Color *</label>
                <select name="color" id="color" required>
                  <option value="">Selecciona un color</option>
                  <option value="dorado" {{ old('color') == 'dorado' ? 'selected' : '' }}>Dorado</option>
                  <option value="plateado" {{ old('color') == 'plateado' ? 'selected' : '' }}>Plateado</option>
                  <option value="rosa" {{ old('color') == 'rosa' ? 'selected' : '' }}>Rosa</option>
                  <option value="blanco" {{ old('color') == 'blanco' ? 'selected' : '' }}>Blanco</option>
                  <option value="negro" {{ old('color') == 'negro' ? 'selected' : '' }}>Negro</option>
                </select>
                @error('color')
                  <span class="error-message">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="stones">Piedras *</label>
                <select name="stones" id="stones" required>
                  <option value="">Selecciona piedras</option>
                  <option value="ninguna" {{ old('stones') == 'ninguna' ? 'selected' : '' }}>Sin piedras</option>
                  <option value="diamante" {{ old('stones') == 'diamante' ? 'selected' : '' }}>Diamante</option>
                  <option value="esmeralda" {{ old('stones') == 'esmeralda' ? 'selected' : '' }}>Esmeralda</option>
                  <option value="rubi" {{ old('stones') == 'rubi' ? 'selected' : '' }}>Rubí</option>
                  <option value="zafiro" {{ old('stones') == 'zafiro' ? 'selected' : '' }}>Zafiro</option>
                  <option value="perla" {{ old('stones') == 'perla' ? 'selected' : '' }}>Perla</option>
                  <option value="cristal" {{ old('stones') == 'cristal' ? 'selected' : '' }}>Cristal</option>
                </select>
                @error('stones')
                  <span class="error-message">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>

          <div class="form-section">
            <h3><i class="fas fa-edit"></i> Personalización Adicional</h3>
            <div class="form-group">
              <label for="engraving">Grabado personalizado</label>
              <input type="text" name="engraving" id="engraving" 
                     value="{{ old('engraving') }}"
                     placeholder="Ej: Iniciales, fecha especial, nombre...">
              <small class="help-text">Máximo 20 caracteres</small>
              @error('engraving')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="special_instructions">Instrucciones especiales</label>
              <textarea name="special_instructions" id="special_instructions" rows="4" 
                        placeholder="Describe cualquier detalle específico, medidas especiales, o requerimientos particulares...">{{ old('special_instructions') }}</textarea>
              @error('special_instructions')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="precio-estimado">
            <h3><i class="fas fa-calculator"></i> Precio Estimado</h3>
            <div class="precio-display">
              <span class="precio-label">Precio aproximado:</span>
              <span class="precio-valor" id="precio-estimado">Se calculará al enviar</span>
            </div>
            <small class="precio-nota">* El precio final puede variar según la complejidad del diseño</small>
          </div>

          <div class="form-actions">
            <button type="submit" class="boton-enviar">
              <i class="fas fa-paper-plane"></i> Enviar Solicitud
            </button>
            <a href="{{ route('inicio') }}" class="boton-cancelar">Cancelar</a>
          </div>
        </form>
      </div>

      <div class="info-personalizacion">
        <h3><i class="fas fa-info-circle"></i> Proceso de Personalización</h3>
        <div class="proceso-steps">
          <div class="step">
            <div class="step-number">1</div>
            <h4>Envío de solicitud</h4>
            <p>Completa el formulario con todos los detalles de tu joya ideal</p>
          </div>
          <div class="step">
            <div class="step-number">2</div>
            <h4>Evaluación y cotización</h4>
            <p>Nuestros diseñadores evaluarán tu solicitud y te enviarán una cotización detallada</p>
          </div>
          <div class="step">
            <div class="step-number">3</div>
            <h4>Confirmación y pago</h4>
            <p>Una vez aprobada la cotización, procederemos con el pago y la fabricación</p>
          </div>
          <div class="step">
            <div class="step-number">4</div>
            <h4>Fabricación y entrega</h4>
            <p>Tu joya personalizada será fabricada y entregada en 15-30 días hábiles</p>
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

    // Cálculo de precio estimado
    function calcularPrecioEstimado() {
      const material = document.getElementById('material').value;
      const stones = document.getElementById('stones').value;
      
      let precioBase = 500000;
      let multiplicador = 1;

      // Multiplicadores por material
      const multiplicadoresMaterial = {
        'oro 18k': 2.5,
        'oro 14k': 2.0,
        'plata sterling': 1.0,
        'acero inoxidable': 0.5,
        'platino': 3.0
      };

      if (multiplicadoresMaterial[material]) {
        multiplicador = multiplicadoresMaterial[material];
      }

      // Ajuste por piedras
      if (stones !== 'ninguna') {
        multiplicador += 0.5;
      }

      const precioEstimado = precioBase * multiplicador;
      document.getElementById('precio-estimado').textContent = '$' + precioEstimado.toLocaleString();
    }

    // Event listeners para calcular precio
    document.getElementById('material').addEventListener('change', calcularPrecioEstimado);
    document.getElementById('stones').addEventListener('change', calcularPrecioEstimado);
  </script>

  <style>
    .personalizacion-container {
      max-width: 900px;
      margin: 0 auto;
      padding: 20px;
    }

    .personalizacion-container h2 {
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

    .formulario-personalizacion {
      background: white;
      border-radius: 10px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .form-section {
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 1px solid #eee;
    }

    .form-section:last-of-type {
      border-bottom: none;
    }

    .form-section h3 {
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
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
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
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

    .precio-estimado {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
      margin: 20px 0;
    }

    .precio-estimado h3 {
      color: #333;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .precio-display {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .precio-label {
      font-weight: bold;
      color: #333;
    }

    .precio-valor {
      font-size: 1.5rem;
      font-weight: bold;
      color: #000;
    }

    .precio-nota {
      color: #666;
      font-style: italic;
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

    .info-personalizacion {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .info-personalizacion h3 {
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .proceso-steps {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .step {
      text-align: center;
      padding: 20px;
      border: 1px solid #eee;
      border-radius: 8px;
    }

    .step-number {
      width: 40px;
      height: 40px;
      background: #000;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      margin: 0 auto 15px auto;
    }

    .step h4 {
      color: #333;
      margin: 0 0 10px 0;
    }

    .step p {
      color: #666;
      font-size: 0.9rem;
      margin: 0;
    }

    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }

      .form-actions {
        flex-direction: column;
      }

      .proceso-steps {
        grid-template-columns: 1fr;
      }

      .precio-display {
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }
    }
  </style>
</body>
</html>









