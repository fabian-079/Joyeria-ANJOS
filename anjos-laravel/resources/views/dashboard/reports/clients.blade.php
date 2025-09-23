<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reporte de Clientes - Anjos Joyería</title>
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
        <a href="{{ route('dashboard.reports') }}"><i class="fas fa-arrow-left"></i> Volver a Reportes</a> | 
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Salir</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </div>
  </header>

  <main class="contenido-principal">
    <div class="admin-container">
      <div class="admin-header">
        <h1><i class="fas fa-users"></i> Reporte de Clientes</h1>
        <div class="header-actions">
          <a href="{{ route('dashboard.reports', array_merge(request()->query(), ['type' => 'clients', 'export' => 'pdf'])) }}" class="btn-export">
            <i class="fas fa-file-pdf"></i> Exportar PDF
          </a>
        </div>
      </div>

      <!-- Filtros -->
      <div class="filters-container">
        <form method="GET" action="{{ route('dashboard.reports') }}" class="filters-form">
          <input type="hidden" name="type" value="clients">
          
          <div class="filter-group">
            <label for="date_from">Fecha desde:</label>
            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
          </div>
          
          <div class="filter-group">
            <label for="date_to">Fecha hasta:</label>
            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
          </div>
          
          <div class="filter-actions">
            <button type="submit" class="btn-filter">
              <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="{{ route('dashboard.reports', ['type' => 'clients']) }}" class="btn-clear">
              <i class="fas fa-times"></i> Limpiar
            </a>
          </div>
        </form>
      </div>

      <div class="report-content">
        <div class="report-summary">
          <div class="summary-card">
            <h3>Total Clientes</h3>
            <p>{{ $clients->count() }}</p>
          </div>
          <div class="summary-card">
            <h3>Clientes Activos</h3>
            <p>{{ $clients->where('orders_count', '>', 0)->count() }}</p>
          </div>
          <div class="summary-card">
            <h3>Promedio Pedidos/Cliente</h3>
            <p>{{ number_format($clients->avg('orders_count'), 1) }}</p>
          </div>
        </div>
        
        <div class="report-table">
          <table>
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Pedidos</th>
                <th>Fecha Registro</th>
              </tr>
            </thead>
            <tbody>
              @foreach($clients as $client)
                <tr>
                  <td>{{ $client->name }}</td>
                  <td>{{ $client->email }}</td>
                  <td>{{ $client->phone ?? 'No especificado' }}</td>
                  <td>{{ $client->orders_count }}</td>
                  <td>{{ $client->created_at->format('d/m/Y') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <style>
    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .admin-header {
      margin-bottom: 30px;
    }

    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .admin-header h1 {
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 0;
    }

    .header-actions {
      display: flex;
      gap: 10px;
    }

    .btn-export {
      padding: 10px 20px;
      background: #dc3545;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-export:hover {
      background: #c82333;
    }

    .filters-container {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }

    .filters-form {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      align-items: end;
    }

    .filter-group {
      display: flex;
      flex-direction: column;
      min-width: 150px;
    }

    .filter-group label {
      font-weight: bold;
      margin-bottom: 5px;
      color: #333;
    }

    .filter-group input,
    .filter-group select {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .filter-actions {
      display: flex;
      gap: 10px;
    }

    .btn-filter, .btn-clear {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-filter {
      background: #007bff;
      color: white;
    }

    .btn-filter:hover {
      background: #0056b3;
    }

    .btn-clear {
      background: #6c757d;
      color: white;
    }

    .btn-clear:hover {
      background: #545b62;
    }

    .report-content {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .report-summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .summary-card {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
    }

    .summary-card h3 {
      margin: 0 0 10px 0;
      color: #666;
      font-size: 14px;
    }

    .summary-card p {
      margin: 0;
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    .report-table {
      overflow-x: auto;
    }

    .report-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .report-table th {
      background: #f8f9fa;
      font-weight: bold;
      color: #333;
    }

    @media (max-width: 768px) {
      .report-summary {
        grid-template-columns: 1fr;
      }
    }
  </style>
</body>
</html>
