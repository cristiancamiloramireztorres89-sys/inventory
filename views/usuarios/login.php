<?php
session_start();

// ALERTA
$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

// Alerta de cierre de sesión
if (!$alert && isset($_GET['logout'])) {
    $alert = ['type' => 'success', 'text' => 'Has cerrado sesión exitosamente.'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/inventory/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System - Iniciar Sesión</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #11225a;
            --primary-dark: #1e3a8a;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.18), 0 8px 25px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            max-width: 950px;
            width: 100%;
            min-height: 520px;
            animation: slideUp 0.6s ease-out;
        }

        .login-container .row {
            min-height: 520px;
            align-items: stretch;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-left {
            background: white;
            padding: 80px 40px 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 520px;
        }

        .login-right {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            min-height: 520px;
        }

        .login-right::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .logo-container img {
            max-width: 200px;
            height: auto;
            filter: brightness(0) invert(1);
            margin-bottom: 20px;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 40px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
            display: block;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px 12px 45px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: white;
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 42px;
            color: var(--text-muted);
            font-size: 18px;
            z-index: 1;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            margin-top: 25px;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
            position: relative;
            z-index: 1;
        }

        .features-list li {
            padding: 12px 0;
            display: flex;
            align-items: center;
            font-size: 15px;
            opacity: 0.9;
        }

        .features-list i {
            margin-right: 12px;
            font-size: 18px;
        }

        .welcome-text {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            position: relative;
            z-index: 1;
        }

        .welcome-description {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .login-left, .login-right {
                padding: 40px 30px;
            }
            
            .form-title {
                font-size: 24px;
            }
            
            .welcome-text {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<div class="login-container">
    <div class="row g-0 h-100">
        <!-- IZQUIERDA - FORMULARIO -->
        <div class="col-lg-6 login-left">
            <h2 class="form-title">Bienvenido de nuevo</h2>
            <p class="form-subtitle">Ingresa tus credenciales para acceder al sistema</p>

            <!-- ALERTA -->
            <?php if ($alert): ?>
                <div class="alert alert-custom alert-<?= $alert['type']; ?> alert-dismissible fade show" role="alert">
                    <i class="bi bi-<?= $alert['type'] === 'success' ? 'check-circle' : ($alert['type'] === 'danger' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
                    <?= $alert['text']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="../../controllers/Authcontroller.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Correo electrónico</label>
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="correo" class="form-control" placeholder="tu@correo.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Iniciar sesión
                </button>
            </form>

            <div style="text-align:center;margin-top:.75rem;">
                <a href="../../public/index.php"
                   style="font-size:13px;color:#94a3b8;text-decoration:none;
                          display:inline-flex;align-items:center;gap:.3rem;
                          transition:color .2s;"
                   onmouseover="this.style.color='#11225a'"
                   onmouseout="this.style.color='#94a3b8'">
                    <i class="bi bi-arrow-left"></i> Volver al inicio
                </a>
            </div>
        </div>

        <!-- DERECHA - INFORMACIÓN -->
        <div class="col-lg-6 login-right">
            <h1 class="welcome-text">Gestiona tu inventario de forma inteligente</h1>
            
            <p class="welcome-description">
                Accede a tu cuenta para gestionar existencias, registrar productos y mantener tu inventario actualizado en tiempo real.
            </p>

            <ul class="features-list">
                <li>
                    <i class="bi bi-graph-up-arrow"></i>
                    Control total de tu inventario
                </li>
                <li>
                    <i class="bi bi-shield-check"></i>
                    Seguridad y respaldo de datos
                </li>
                <li>
                    <i class="bi bi-clock-history"></i>
                    Actualizaciones en tiempo real
                </li>
                <li>
                    <i class="bi bi-people"></i>
                    Gestión de usuarios y permisos
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.add('fade');
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Add input focus effects
document.querySelectorAll('.form-control').forEach(function(input) {
    input.addEventListener('focus', function() {
        this.parentElement.querySelector('.input-icon').style.color = 'var(--primary-color)';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.querySelector('.input-icon').style.color = 'var(--text-muted)';
    });
});
</script>

</body>
</html>


