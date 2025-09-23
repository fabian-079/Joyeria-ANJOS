<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Anjos Joyería</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            background: #000;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: #000;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Anjos Joyería y Accesorios</h1>
        <h2>Restablecer Contraseña</h2>
    </div>
    
    <div class="content">
        <p>Hola,</p>
        
        <p>Recibiste este correo porque solicitaste restablecer tu contraseña en Anjos Joyería.</p>
        
        <p>Para restablecer tu contraseña, haz clic en el siguiente enlace:</p>
        
        <div style="text-align: center;">
            <a href="{{ url('password/reset/' . $token . '?email=' . urlencode($email)) }}" class="button">
                Restablecer Contraseña
            </a>
        </div>
        
        <p>Este enlace expirará en 24 horas por motivos de seguridad.</p>
        
        <p>Si no solicitaste este restablecimiento de contraseña, puedes ignorar este correo.</p>
        
        <p>Saludos,<br>El equipo de Anjos Joyería</p>
    </div>
    
    <div class="footer">
        <p>© 2025 ANJOS JOYERÍA Y ACCESORIOS - Todos los derechos reservados</p>
        <p>CALLE 38C SUR #87D - 09 / BOGOTÁ, COLOMBIA</p>
        <p>3132090475 - 3013774549 | ANJOS@GMAIL.COM</p>
    </div>
</body>
</html>


