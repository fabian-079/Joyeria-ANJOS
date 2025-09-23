<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Usuarios - Anjos Joyería</title>
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
        <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
      </div>

      <!-- Tabla de usuarios -->
      <div class="users-table">
        <h3>Lista de Usuarios</h3>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Rol</th>
              <th>Fecha Registro</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? 'No especificado' }}</td>
                <td>
                  @foreach($user->roles as $role)
                    <span class="role-badge role-{{ $role->name }}">
                      {{ ucfirst($role->name) }}
                    </span>
                  @endforeach
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>
                  <div class="action-buttons">
                    <a href="{{ route('users.edit', $user) }}" class="btn-edit">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="no-data">No se encontraron usuarios</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="pagination">
        {{ $users->links() }}
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
      max-width: 1200px;
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

    .users-table {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .users-table h3 {
      margin-bottom: 20px;
      color: #333;
    }

    .users-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .users-table th,
    .users-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .users-table th {
      background: #f8f9fa;
      font-weight: bold;
      color: #333;
    }

    .role-badge {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: bold;
      margin-right: 5px;
    }

    .role-admin {
      background: #dc3545;
      color: white;
    }

    .role-cliente {
      background: #007bff;
      color: white;
    }

    .action-buttons {
      display: flex;
      gap: 5px;
    }

    .btn-edit {
      padding: 5px 10px;
      background: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 3px;
      font-size: 0.8rem;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: background 0.3s;
    }

    .btn-edit:hover {
      background: #218838;
    }

    .no-data {
      text-align: center;
      color: #666;
      font-style: italic;
      padding: 20px;
    }

    .pagination {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    @media (max-width: 768px) {
      .users-table {
        overflow-x: auto;
      }
    }
  </style>
</body>
</html>




