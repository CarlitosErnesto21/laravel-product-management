<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5;">

    <div style="max-width: 500px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 5px;">

        <h2 style="color: #333; text-align: center;">Sistema de Productos</h2>

        <p>Hola <strong>{{ $user->name }}</strong>,</p>

        <p>Para acceder al sistema, haz clic en el siguiente enlace:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $redirectUrl }}" style="background-color: #007cba; color: white; padding: 12px 25px; text-decoration: none; border-radius: 4px; display: inline-block;">
                Acceder al Sistema
            </a>
        </div>

        <p style="font-size: 14px; color: #666;">
            O copia y pega este enlace en tu navegador:<br>
            <a href="{{ $redirectUrl }}">{{ $redirectUrl }}</a>
        </p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

        <p style="font-size: 12px; color: #999; text-align: center;">
            Este correo fue enviado automáticamente. No respondas a esta dirección.
        </p>

    </div>

</body>
</html>
