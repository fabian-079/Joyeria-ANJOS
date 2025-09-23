<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Anjos Joyería y Accesorios</title>
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
                <a href="{{ route('inicio') }}"><i class="fas fa-home"></i> Inicio</a> | 
                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
            </div>
        </div>
    </header>

    <main class="contenido-principal">
        <div class="auth-container">
            <div class="auth-card">
                <h2><i class="fas fa-key"></i> Recuperar Contraseña</h2>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <p>Ingresa tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Enlace de Recuperación
                    </button>
                </form>

                <div class="auth-links">
                    <p>¿Recordaste tu contraseña? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
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

    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</body>
</html>


