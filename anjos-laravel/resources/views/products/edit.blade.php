<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Editar Producto - Anjos Joyería</title>
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
        <a href="{{ route('products.index') }}"><i class="fas fa-arrow-left"></i> Volver a Productos</a> | 
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
        <h1><i class="fas fa-edit"></i> Editar Producto: {{ $product->name }}</h1>
      </div>

      <div class="form-container">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          
          <div class="form-grid">
            <div class="form-group">
              <label for="name">Nombre del Producto *</label>
              <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
              @error('name')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="category_id">Categoría *</label>
              <select id="category_id" name="category_id" required>
                <option value="">Seleccionar categoría</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              @error('category_id')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="price">Precio *</label>
              <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
              @error('price')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="stock">Stock *</label>
              <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
              @error('stock')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="material">Material</label>
              <input type="text" id="material" name="material" value="{{ old('material', $product->material) }}" placeholder="Ej: Oro 18k, Plata 925">
              @error('material')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="color">Color</label>
              <input type="text" id="color" name="color" value="{{ old('color', $product->color) }}" placeholder="Ej: Dorado, Plateado">
              @error('color')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="finish">Acabado</label>
              <input type="text" id="finish" name="finish" value="{{ old('finish', $product->finish) }}" placeholder="Ej: Brillante, Mate">
              @error('finish')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="stones">Piedras</label>
              <input type="text" id="stones" name="stones" value="{{ old('stones', $product->stones) }}" placeholder="Ej: Diamantes, Zafiros">
              @error('stones')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-group full-width">
            <label for="description">Descripción *</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
            @error('description')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="image">Imagen del Producto</label>
            @if($product->image)
              <div class="current-image">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-preview">
                <p class="image-info">Imagen actual</p>
              </div>
            @endif
            <input type="file" id="image" name="image" accept="image/*">
            <small class="help-text">Deja vacío para mantener la imagen actual</small>
            @error('image')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="placement">Ubicación del Producto *</label>
            <select id="placement" name="placement" required>
              <option value="">Seleccionar ubicación</option>
              <option value="catalog" {{ old('placement', $product->placement) == 'catalog' ? 'selected' : '' }}>Solo en Catálogo</option>
              <option value="carousel" {{ old('placement', $product->placement) == 'carousel' ? 'selected' : '' }}>Solo en Carrusel del Inicio</option>
              <option value="home_products" {{ old('placement', $product->placement) == 'home_products' ? 'selected' : '' }}>Solo en Productos del Inicio</option>
              <option value="all" {{ old('placement', $product->placement) == 'all' ? 'selected' : '' }}>En Todas las Secciones</option>
            </select>
            @error('placement')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
              <span class="checkmark"></span>
              Producto destacado
            </label>
          </div>

          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
              <span class="checkmark"></span>
              Producto activo
            </label>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-save">
              <i class="fas fa-save"></i> Actualizar Producto
            </button>
            <a href="{{ route('products.index') }}" class="btn-cancel">
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
    .form-group select,
    .form-group textarea {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
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

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      font-weight: normal;
    }

    .checkbox-label input[type="checkbox"] {
      width: auto;
      margin: 0;
    }

    .current-image {
      margin-bottom: 10px;
    }

    .product-preview {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .image-info {
      font-size: 12px;
      color: #666;
      margin: 5px 0;
    }

    .help-text {
      font-size: 12px;
      color: #666;
      margin-top: 5px;
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