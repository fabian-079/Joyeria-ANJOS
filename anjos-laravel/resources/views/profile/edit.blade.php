<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mi Perfil - Anjos Joyería</title>
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
        <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a> | 
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
        @if(Auth::user()->hasRole('admin'))
          <li><a href="{{ route('dashboard.sales') }}"><i class="fas fa-chart-line"></i> Ventas</a></li>
          <li><a href="{{ route('dashboard.stock') }}"><i class="fas fa-boxes"></i> Stock</a></li>
          <li><a href="{{ route('dashboard.services') }}"><i class="fas fa-tools"></i> Servicios</a></li>
          <li><a href="{{ route('dashboard.reports') }}"><i class="fas fa-file-pdf"></i> Reportes</a></li>
          <li><a href="{{ route('products.index') }}"><i class="fas fa-gem"></i> Productos</a></li>
          <li><a href="{{ route('reparaciones.index') }}"><i class="fas fa-wrench"></i> Reparaciones</a></li>
          <li><a href="{{ route('personalizacion.index') }}"><i class="fas fa-pencil-alt"></i> Personalizaciones</a></li>
          <li><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Usuarios</a></li>
        @endif
        <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i> Mi Perfil</a></li>
      </ul>
    </nav>
  </aside>

  <main class="contenido-principal">
    <div class="profile-container">
      <div class="profile-header">
        <h1><i class="fas fa-user-edit"></i> Mi Perfil</h1>
      </div>

      <div class="profile-content">
        <!-- Información del perfil -->
        <div class="profile-section">
          <h2><i class="fas fa-user"></i> Información Personal</h2>
          <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="form-grid">
              <div class="form-group">
                <label for="name">Nombre completo *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="email">Correo electrónico *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group full-width">
                <label for="address">Dirección</label>
                <textarea id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                @error('address')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Actualizar Perfil
              </button>
            </div>
          </form>
        </div>

        <!-- Cambiar contraseña -->
        <div class="profile-section">
          <h2><i class="fas fa-lock"></i> Cambiar Contraseña</h2>
          <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="form-grid">
              <div class="form-group">
                <label for="current_password">Contraseña actual</label>
                <input type="password" id="current_password" name="current_password">
                @error('current_password')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input type="password" id="password" name="password">
                @error('password')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="password_confirmation">Confirmar nueva contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-save">
                <i class="fas fa-key"></i> Cambiar Contraseña
              </button>
            </div>
          </form>
        </div>

        <!-- Eliminar cuenta -->
        <div class="profile-section danger-section">
          <h2><i class="fas fa-exclamation-triangle"></i> Zona de Peligro</h2>
          <p>Una vez que elimines tu cuenta, no hay vuelta atrás. Por favor, ten cuidado.</p>
          
          <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')">
            @csrf
            @method('DELETE')
            
            <div class="form-group">
              <label for="delete_password">Contraseña para confirmar eliminación</label>
              <input type="password" id="delete_password" name="password" required>
              @error('password')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-danger">
                <i class="fas fa-trash"></i> Eliminar Cuenta
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

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
    .profile-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .profile-header {
      margin-bottom: 30px;
    }

    .profile-header h1 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .profile-content {
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .profile-section {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .profile-section h2 {
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .danger-section {
      border-left: 4px solid #dc3545;
    }

    .danger-section h2 {
      color: #dc3545;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
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
    .form-group textarea {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #000;
    }

    .form-group textarea {
      resize: vertical;
      min-height: 80px;
    }

    .error {
      color: #dc3545;
      font-size: 12px;
      margin-top: 5px;
    }

    .form-actions {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .btn-save, .btn-danger {
      padding: 12px 24px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-save {
      background: #000;
      color: white;
    }

    .btn-save:hover {
      background: #333;
    }

    .btn-danger {
      background: #dc3545;
      color: white;
    }

    .btn-danger:hover {
      background: #c82333;
    }

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }

      .form-actions {
        flex-direction: column;
      }
    }
  </style>
</body>
</html>