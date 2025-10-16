<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación - {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #2563eb;">{{ config('app.name') }}</h1>
        <h2>Verificación de Acceso</h2>
    </div>

    <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <p>Hola <strong>{{ $user->name }}</strong>,</p>

        @if($messageType === 'login')
            <p>Has iniciado sesión exitosamente. Para continuar, haz clic en el botón de abajo:</p>
        @elseif($messageType === 'register')
            <p>Tu cuenta ha sido creada exitosamente. Para continuar, haz clic en el botón de abajo:</p>
        @else
            <p>Para acceder al sistema, haz clic en el botón de abajo:</p>
        @endif
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $redirectUrl }}"
           style="display: inline-block; background: #2563eb; color: white; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; font-size: 16px;">
            🔐 Verificar y Continuar
        </a>
    </div>

    @php
        $authorizedEmail = 'ernesto.rosales354@gmail.com';
        $isAuthorized = $user->email === $authorizedEmail;
    @endphp

    <div style="background: {{ $isAuthorized ? '#ecfdf5' : '#fffbeb' }}; padding: 15px; border-radius: 6px; border-left: 4px solid {{ $isAuthorized ? '#10b981' : '#f59e0b' }};">
        @if($isAuthorized)
            <p><strong>✅ Usuario Autorizado:</strong> Serás redirigido al Dashboard de Administración.</p>
        @else
            <p><strong>ℹ️ Acceso de Visitante:</strong> Serás redirigido a la página principal para explorar productos.</p>
        @endif
    </div>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280;">
        <p><strong>Enlace directo:</strong></p>
        <p style="word-break: break-all;"><a href="{{ $redirectUrl }}">{{ $redirectUrl }}</a></p>
        <p style="margin-top: 15px;">Este enlace es válido por 24 horas y es único para tu sesión.</p>
    </div>

    <div style="text-align: center; margin-top: 30px; font-size: 12px; color: #9ca3af;">
        <p>{{ config('app.name') }} - Sistema de Gestión de Productos</p>
        <p>Este es un correo automático, no respondas a esta dirección.</p>
    </div>

</body>
</html>
