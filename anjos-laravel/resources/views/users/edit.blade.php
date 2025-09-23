<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Editar Usuario - Anjos Joyería</title>
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
        <a href="{{ route('users.index') }}"><i class="fas fa-arrow-left"></i> Volver a Usuarios</a> | 
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
    <div class="admin-container">
      <div class="admin-header">
        <h1><i class="fas fa-user-edit"></i> Editar Usuario: {{ $user->name }}</h1>
      </div>

      <div class="form-container">
        <form action="{{ route('users.update', $user) }}" method="POST">
          @csrf
          @method('PUT')
          
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

            <div class="form-group">
              <label for="password">Nueva contraseña (opcional)</label>
              <input type="password" id="password" name="password">
              @error('password')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="password_confirmation">Confirmar nueva contraseña</label>
              <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="form-group">
              <label for="role">Rol *</label>
              <select id="role" name="role" required>
                @foreach($roles as $role)
                  <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                  </option>
                @endforeach
              </select>
              @error('role')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-save">
              <i class="fas fa-save"></i> Actualizar Usuario
            </button>
            <a href="{{ route('users.index') }}" class="btn-cancel">
              <i class="fas fa-times"></i> Cancelar
            </a>
          </div>
        </form>
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
    .admin-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .admin-header {
      margin-bottom: 30px;
    }

    .admin-header h1 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-container {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    .form-group textarea,
    .form-group select {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
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
      margin-top: 30px;
    }

    .btn-save, .btn-cancel {
      padding: 12px 24px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
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

    .btn-cancel {
      background: #6c757d;
      color: white;
    }

    .btn-cancel:hover {
      background: #5a6268;
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