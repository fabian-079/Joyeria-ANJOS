<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Editar Personalización - Anjos Joyería</title>
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
        <a href="{{ route('personalizacion.show', $customization) }}"><i class="fas fa-arrow-left"></i> Volver a Detalles</a> | 
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
        <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="{{ route('dashboard.sales') }}"><i class="fas fa-chart-line"></i> Ventas</a></li>
        <li><a href="{{ route('dashboard.stock') }}"><i class="fas fa-boxes"></i> Stock</a></li>
        <li><a href="{{ route('dashboard.services') }}"><i class="fas fa-tools"></i> Servicios</a></li>
        <li><a href="{{ route('dashboard.reports') }}"><i class="fas fa-file-pdf"></i> Reportes</a></li>
        <li><a href="{{ route('products.index') }}"><i class="fas fa-gem"></i> Productos</a></li>
        <li><a href="{{ route('reparaciones.index') }}"><i class="fas fa-wrench"></i> Reparaciones</a></li>
        <li><a href="{{ route('personalizacion.index') }}"><i class="fas fa-pencil-alt"></i> Personalizaciones</a></li>
        <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Usuarios</a></li>
      </ul>
    </nav>
  </aside>

  <main class="contenido-principal">
    <div class="container">
      <div class="page-header">
        <h1><i class="fas fa-edit"></i> Editar Personalización: {{ $customization->jewelry_type }}</h1>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('personalizacion.update', $customization) }}" method="POST" class="customization-form">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
          <div class="form-group">
            <label for="status">Estado *</label>
            <select name="status" id="status" required>
              <option value="pending" {{ old('status', $customization->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
              <option value="approved" {{ old('status', $customization->status) == 'approved' ? 'selected' : '' }}>Aprobado</option>
              <option value="rejected" {{ old('status', $customization->status) == 'rejected' ? 'selected' : '' }}>Rechazado</option>
              <option value="in_progress" {{ old('status', $customization->status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
              <option value="completed" {{ old('status', $customization->status) == 'completed' ? 'selected' : '' }}>Completado</option>
            </select>
          </div>

          <div class="form-group">
            <label for="estimated_price">Precio Estimado</label>
            <input type="number" name="estimated_price" id="estimated_price" 
                   value="{{ old('estimated_price', $customization->estimated_price) }}" 
                   min="0" step="0.01" placeholder="0.00">
          </div>

          <div class="form-group full-width">
            <label for="admin_notes">Notas Administrativas</label>
            <textarea name="admin_notes" id="admin_notes" rows="4" 
                      placeholder="Notas internas sobre esta personalización...">{{ old('admin_notes', $customization->admin_notes) }}</textarea>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Actualizar Personalización
          </button>
          <a href="{{ route('personalizacion.show', $customization) }}" class="btn-cancel">
            <i class="fas fa-times"></i> Cancelar
          </a>
        </div>
      </form>

      <!-- Información de solo lectura -->
      <div class="readonly-info">
        <h3><i class="fas fa-info-circle"></i> Información del Cliente</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>Cliente:</label>
            <span>{{ $customization->user->name }}</span>
          </div>
          <div class="info-item">
            <label>Email:</label>
            <span>{{ $customization->user->email }}</span>
          </div>
          <div class="info-item">
            <label>Fecha de Solicitud:</label>
            <span>{{ $customization->created_at->format('d/m/Y H:i') }}</span>
          </div>
        </div>
      </div>

      <div class="readonly-info">
        <h3><i class="fas fa-gem"></i> Especificaciones de la Joya</h3>
        <div class="info-grid">
          <div class="info-item">
            <label>Tipo de Joya:</label>
            <span>{{ $customization->jewelry_type }}</span>
          </div>
          <div class="info-item">
            <label>Diseño:</label>
            <span>{{ $customization->design }}</span>
          </div>
          <div class="info-item">
            <label>Material:</label>
            <span>{{ $customization->material }}</span>
          </div>
          <div class="info-item">
            <label>Color:</label>
            <span>{{ $customization->color }}</span>
          </div>
          <div class="info-item">
            <label>Acabado:</label>
            <span>{{ $customization->finish }}</span>
          </div>
          <div class="info-item">
            <label>Piedras:</label>
            <span>{{ $customization->stones }}</span>
          </div>
          @if($customization->engraving)
            <div class="info-item">
              <label>Grabado:</label>
              <span>{{ $customization->engraving }}</span>
            </div>
          @endif
          @if($customization->special_instructions)
            <div class="info-item full-width">
              <label>Instrucciones Especiales:</label>
              <span>{{ $customization->special_instructions }}</span>
            </div>
          @endif
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
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .page-header {
      margin-bottom: 30px;
    }

    .page-header h1 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert {
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .alert-danger {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .customization-form {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group.full-width {
      grid-column: 1 / -1;
    }

    .form-group label {
      font-weight: bold;
      margin-bottom: 5px;
      color: #333;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .form-actions {
      display: flex;
      gap: 15px;
      justify-content: flex-end;
    }

    .btn-save {
      background: #000;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-save:hover {
      background: #333;
    }

    .btn-cancel {
      background: #6c757d;
      color: white;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-cancel:hover {
      background: #5a6268;
    }

    .readonly-info {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .readonly-info h3 {
      color: #333;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 15px;
    }

    .info-item {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .info-item.full-width {
      grid-column: 1 / -1;
    }

    .info-item label {
      font-weight: bold;
      color: #666;
      font-size: 0.9rem;
    }

    .info-item span {
      color: #333;
      padding: 8px;
      background: #f8f9fa;
      border-radius: 5px;
      border: 1px solid #e9ecef;
    }

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }

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




