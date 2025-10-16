<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Acceso - {{ config('app.name') }}</title>
    <style>
        /* Reset y estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8fafc;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .logo {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
        }

        /* Content */
        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        /* Botón principal */
        .cta-container {
            text-align: center;
            margin: 40px 0;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
        }

        /* Status cards */
        .status-card {
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid;
        }

        .status-authorized {
            background-color: #f0fff4;
            border-left-color: #38a169;
            color: #276749;
        }

        .status-unauthorized {
            background-color: #fffbf0;
            border-left-color: #ed8936;
            color: #744210;
        }

        .status-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .status-card p {
            font-size: 14px;
            line-height: 1.5;
        }

        .status-icon {
            margin-right: 8px;
            font-size: 18px;
        }

        /* Info box */
        .info-box {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .info-box h3 {
            color: #2b6cb0;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .info-box ul {
            color: #2c5282;
            font-size: 14px;
            padding-left: 20px;
        }

        .info-box li {
            margin-bottom: 8px;
        }

        /* Footer */
        .footer {
            background-color: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            font-size: 14px;
            color: #718096;
            margin-bottom: 10px;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }

            .content {
                padding: 30px 20px;
            }

            .header {
                padding: 25px 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            .cta-button {
                padding: 14px 24px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                🛡️
            </div>
            <h1>Verificación de Acceso</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                ¡Hola {{ $user->name }}!
            </div>

            @if($messageType === 'login')
                <div class="message">
                    Has iniciado sesión exitosamente en nuestro sistema. Para continuar y ser redirigido a tu área correspondiente, haz clic en el botón de verificación a continuación.
                </div>
            @elseif($messageType === 'register')
                <div class="message">
                    Tu cuenta ha sido creada exitosamente. Para completar el proceso y ser redirigido a tu área correspondiente, verifica tu acceso haciendo clic en el botón a continuación.
                </div>
            @else
                <div class="message">
                    Para acceder al sistema y ser redirigido a tu área correspondiente, necesitas verificar tu identidad haciendo clic en el botón a continuación.
                </div>
            @endif

            <!-- Status Card -->
            @php
                $authorizedEmail = 'ernesto.rosales354@gmail.com';
                $isAuthorized = $user->email === $authorizedEmail;
            @endphp

            @if($isAuthorized)
                <div class="status-card status-authorized">
                    <h3><span class="status-icon">✅</span> Usuario Autorizado</h3>
                    <p>Tienes acceso completo al sistema. Serás redirigido al <strong>Dashboard de Administración</strong> donde podrás gestionar productos, usuarios y todas las funcionalidades avanzadas.</p>
                </div>
            @else
                <div class="status-card status-unauthorized">
                    <h3><span class="status-icon">ℹ️</span> Acceso de Visitante</h3>
                    <p>Tu acceso es limitado a la visualización del catálogo público. Serás redirigido a la <strong>Página Principal</strong> donde podrás explorar nuestros productos disponibles.</p>
                </div>
            @endif

            <!-- CTA Button -->
            <div class="cta-container">
                <a href="{{ $redirectUrl }}" class="cta-button">
                    🔐 Verificar y Continuar
                </a>
            </div>

            <!-- Instructions -->
            <div class="info-box">
                <h3>📋 Instrucciones importantes:</h3>
                <ul>
                    <li>Este enlace es <strong>único y seguro</strong> para tu sesión</li>
                    <li>Al hacer clic serás <strong>redirigido automáticamente</strong> a tu área correspondiente</li>
                    <li>Si eres usuario autorizado: accederás al <strong>Dashboard</strong></li>
                    <li>Si eres visitante: accederás al <strong>Catálogo público</strong></li>
                    <li>El enlace es válido por <strong>24 horas</strong></li>
                </ul>
            </div>

            <!-- Security notice -->
            <div class="message" style="font-size: 14px; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 30px;">
                <strong>🔒 Nota de seguridad:</strong> Si no has realizado esta acción, ignora este correo. Tu cuenta permanece segura.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Sistema de Gestión de Productos | Verificación automática</p>
            <p>
                Si tienes problemas con el botón, copia y pega esta URL en tu navegador:<br>
                <a href="{{ $redirectUrl }}">{{ Str::limit($redirectUrl, 50) }}</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px;">
                Este es un correo automático, por favor no respondas a esta dirección.
            </p>
        </div>
    </div>
</body>
</html>
