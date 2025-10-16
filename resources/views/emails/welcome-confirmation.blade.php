<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }}</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Base styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8fafc;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .message {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .welcome-message {
            background-color: #f0fff4;
            border-left: 4px solid #48bb78;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }

        .login-message {
            background-color: #fffaf0;
            border-left: 4px solid #ed8936;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }

        .unauthorized-message {
            background-color: #fed7d7;
            border-left: 4px solid #e53e3e;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s ease;
            margin: 20px 0;
        }

        .cta-button:hover {
            transform: translateY(-2px);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .feature {
            background-color: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: white;
        }

        .feature h3 {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .feature p {
            color: #718096;
            font-size: 14px;
        }

        .info-box {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .info-box h3 {
            color: #2b6cb0;
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .info-box p {
            color: #2c5282;
            font-size: 14px;
            margin: 5px 0;
        }

        .footer {
            background-color: #2d3748;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .footer p {
            font-size: 14px;
            opacity: 0.8;
            margin: 5px 0;
        }

        .footer a {
            color: #90cdf4;
            text-decoration: none;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #90cdf4;
            text-decoration: none;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }

            .header,
            .content,
            .footer {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .greeting {
                font-size: 18px;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $appName }}</h1>
            <p>Sistema de Gestión de Productos</p>
        </div>

        <!-- Content -->
        <div class="content">
            @if($messageType === 'welcome')
                <div class="greeting">¡Hola {{ $user->name }}! 👋</div>

                @if(isset($additionalData['test_mode']) && $additionalData['test_mode'])
                    <div style="background-color: #fef5e7; border-left: 4px solid #f6ad55; padding: 20px; margin: 20px 0; border-radius: 0 8px 8px 0;">
                        <h3 style="color: #c05621; margin-bottom: 10px;">🧪 CORREO DE PRUEBA</h3>
                        <p style="color: #9c4221;">
                            Este es un correo de prueba enviado desde el sistema.
                            Enviado el: {{ $additionalData['test_time'] ?? 'Hora no disponible' }}
                        </p>
                    </div>
                @endif

                <div class="welcome-message">
                    <h3 style="color: #38a169; margin-bottom: 10px;">🎉 ¡Bienvenido/a a nuestro sistema!</h3>
                    <p style="color: #2f855a;">
                        Nos complace darte la bienvenida al <strong>Sistema de Gestión de Productos</strong>.
                        Tu cuenta ha sido creada exitosamente y ya puedes comenzar a explorar todas nuestras funcionalidades.
                    </p>
                </div>                <div class="message">
                    <p>Tu cuenta se ha registrado con el correo: <strong>{{ $user->email }}</strong></p>
                    <p>Ahora puedes acceder a nuestro sistema y descubrir todo lo que tenemos preparado para ti.</p>
                </div>

                <div style="text-align: center;">
                    <a href="{{ $appUrl }}" class="cta-button">
                        🚀 Acceder al Sistema
                    </a>
                </div>

                <div class="features">
                    <div class="feature">
                        <div class="feature-icon">📦</div>
                        <h3>Gestión de Productos</h3>
                        <p>CRUD completo para administrar tu inventario de manera eficiente</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🖼️</div>
                        <h3>Subida de Imágenes</h3>
                        <p>Integración con Cloudinary para gestión optimizada de imágenes</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🔒</div>
                        <h3>Seguridad Avanzada</h3>
                        <p>Sistema de autenticación robusto y control de acceso</p>
                    </div>
                </div>

            @elseif($messageType === 'login')
                <div class="greeting">Hola {{ $user->name }}</div>

                <div class="login-message">
                    <h3 style="color: #dd6b20; margin-bottom: 10px;">🔐 Nuevo inicio de sesión</h3>
                    <p style="color: #c05621;">
                        Se ha detectado un nuevo inicio de sesión en tu cuenta.
                    </p>
                </div>

                <div class="info-box">
                    <h3>Detalles del acceso:</h3>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                    @if(isset($additionalData['ip']))
                        <p><strong>IP:</strong> {{ $additionalData['ip'] }}</p>
                    @endif
                    @if(isset($additionalData['user_agent']))
                        <p><strong>Navegador:</strong> {{ $additionalData['user_agent'] }}</p>
                    @endif
                </div>

                <div class="message">
                    <p>Si fuiste tú, puedes ignorar este mensaje. Si no reconoces este acceso, te recomendamos cambiar tu contraseña inmediatamente.</p>
                </div>

            @elseif($messageType === 'unauthorized')
                <div class="greeting">Hola {{ $user->name }}</div>

                <div class="unauthorized-message">
                    <h3 style="color: #e53e3e; margin-bottom: 10px;">⚠️ Intento de acceso no autorizado</h3>
                    <p style="color: #c53030;">
                        Se ha detectado un intento de acceso a funcionalidades restringidas desde tu cuenta.
                    </p>
                </div>

                <div class="info-box">
                    <h3>Información del intento:</h3>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Acción:</strong> Intento de acceso a área administrativa</p>
                    @if(isset($additionalData['ip']))
                        <p><strong>IP:</strong> {{ $additionalData['ip'] }}</p>
                    @endif
                </div>

                <div class="message">
                    <p>Por motivos de seguridad, el acceso ha sido denegado. Solo usuarios autorizados pueden acceder a las funcionalidades de gestión del sistema.</p>
                    <p>Si crees que esto es un error, contacta al administrador del sistema.</p>
                </div>

            @else
                <div class="greeting">Hola {{ $user->name }}</div>
                <div class="message">
                    <p>Has recibido una notificación del Sistema de Gestión de Productos.</p>
                </div>
            @endif

            <div class="info-box">
                <h3>🛡️ Nota de Seguridad</h3>
                <p>Este correo ha sido enviado automáticamente por el sistema. No respondas a este mensaje.</p>
                <p>Si no solicitaste esta acción, ignora este correo o contacta al soporte técnico.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $appName }}</strong></p>
            <p>Sistema de Gestión de Productos con Laravel + Vue.js</p>

            <div class="social-links">
                <a href="{{ $appUrl }}">🌐 Visitar Sistema</a>
                <a href="mailto:{{ config('mail.from.address') }}">📧 Soporte</a>
            </div>

            <p style="font-size: 12px; margin-top: 20px;">
                © {{ date('Y') }} {{ $appName }}. Todos los derechos reservados.
            </p>
            <p style="font-size: 12px;">
                Desarrollado con ❤️ usando Laravel, Vue.js e Inertia.js
            </p>
        </div>
    </div>
</body>
</html>
