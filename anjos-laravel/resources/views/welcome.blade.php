<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Anjos Joyería - Inicio</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .welcome-container {
            max-width: 600px;
            padding: 40px;
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #D4AF37;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        }
        h1 {
            font-family: 'Playfair Display', serif;
            color: #D4AF37;
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.1em;
            margin-bottom: 30px;
            color: #d1d1d1;
        }
        .btn {
            background-color: #D4AF37;
            color: #000000;
            border: 2px solid #D4AF37;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background-color: #ffffff;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Bienvenido a Anjos Joyería</h1>
        <p>Descubre la elegancia y el brillo de nuestras joyas exclusivas. Inicia sesión para gestionar tu cuenta o explorar nuestro catálogo.</p>
        @if (Route::has('login'))
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn">Ir al Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn">Iniciar Sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn ml-4">Registrarse</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</body>
</html>