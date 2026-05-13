<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/inventory/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System - Gestión Inteligente de Inventario</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #11225a;
            --primary-dark: #1e3a8a;
            --secondary-color: #06b6d4;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --dark-bg: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Header Styles */
        .header-navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #312e81 100%);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.4s ease;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .header-navbar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(29,78,216,0.6), rgba(139,92,246,0.6), transparent);
        }

        .header-navbar.scrolled {
            background: rgba(15, 23, 42, 0.97);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .header-inner {
            padding: 0 2rem;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Logo */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .logo-container:hover {
            opacity: 0.85;
        }

        .logo-icon-wrap {
            width: 38px;
            height: 38px;
            background: #0f172a;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: white;
            box-shadow: 0 4px 14px rgba(29,78,216,0.45);
            flex-shrink: 0;
        }

        .logo-container img {
            height: 36px;
            width: auto;
            filter: brightness(0) invert(1);
            opacity: 0.92;
        }

        .logo-brand {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }

        .logo-brand-name {
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .logo-brand-sub {
            font-size: 0.65rem;
            color: rgba(165,180,252,0.8);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Nav center badge */
        .nav-badge {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(29,78,216,0.15);
            border: 1px solid rgba(29,78,216,0.3);
            border-radius: 20px;
            padding: 0.3rem 0.9rem;
            font-size: 0.75rem;
            color: rgba(199,210,254,0.9);
            font-weight: 500;
        }

        .nav-badge .dot {
            width: 6px;
            height: 6px;
            background: #4ade80;
            border-radius: 50%;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        /* Nav buttons */
        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
            letter-spacing: 0.2px;
        }

        .nav-btn-ghost {
            background: rgba(255,255,255,0.06);
            color: rgba(226,232,240,0.9);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .nav-btn-ghost:hover {
            background: rgba(255,255,255,0.12);
            color: #ffffff;
            border-color: rgba(255,255,255,0.2);
        }

        .nav-btn-primary {
            background: #0f172a;
            color: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0 4px 14px rgba(29,78,216,0.35);
        }

        .nav-btn-primary:hover {
            background: linear-gradient(135deg, #11225a, #1e3a8a);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(29,78,216,0.5);
        }

        .nav-btn-primary:active {
            transform: translateY(0);
        }

        @media (max-width: 640px) {
            .nav-badge { display: none; }
            .nav-btn-ghost { display: none; }
            .header-inner { padding: 0 1rem; }
        }

        /* Carousel Styles */
        .hero-carousel {
            margin-top: 80px;
            height: 600px;
            position: relative;
            overflow: hidden;
        }

        .carousel-item {
            height: 600px;
            position: relative;
        }

        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
            filter: brightness(0.7);
        }

        .carousel-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.8), rgba(67, 56, 202, 0.6));
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .carousel-content {
            max-width: 800px;
            padding: 0 20px;
        }

        .carousel-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease-out;
        }

        .carousel-subtitle {
            font-size: 1.3rem;
            font-weight: 300;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: fadeInUp 1s ease-out 0.2s;
            animation-fill-mode: both;
        }

        .carousel-cta {
            padding: 15px 35px;
            background: white;
            color: var(--primary-color);
            border: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease-out 0.4s;
            animation-fill-mode: both;
        }

        .carousel-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            color: var(--primary-dark);
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            margin: 0 20px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%) scale(1.1);
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: white;
            position: relative;
        }

        .features-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: skewY(-2deg);
            transform-origin: top left;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-bg);
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            margin: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
            color: white;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 15px;
        }

        .feature-description {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .feature-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .feature-link:hover {
            color: var(--primary-dark);
            transform: translateX(5px);
        }

        .feature-link i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .feature-link:hover i {
            transform: translateX(3px);
        }

        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: white;
            padding: 60px 0 0;
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(29,78,216,0.6), rgba(139,92,246,0.6), transparent);
        }

        .footer-content {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 3rem;
            padding-bottom: 3rem;
        }

        /* Columna marca */
        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .footer-brand-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-brand-icon {
            width: 36px;
            height: 36px;
            background: #0f172a;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            box-shadow: 0 4px 12px rgba(29,78,216,0.4);
            flex-shrink: 0;
        }

        .footer-brand-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
        }

        .footer-brand-desc {
            font-size: 0.875rem;
            color: rgba(148,163,184,0.85);
            line-height: 1.65;
            max-width: 280px;
        }

        .footer-social {
            display: flex;
            gap: 0.6rem;
            margin-top: 0.25rem;
        }

        .footer-social a {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(203,213,225,0.8);
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .footer-social a:hover {
            background: rgba(29,78,216,0.25);
            border-color: rgba(29,78,216,0.5);
            color: #93c5fd;
            transform: translateY(-2px);
        }

        /* Columnas de links */
        .footer-col-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: rgba(165,180,252,0.9);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.1rem;
        }

        .footer-col-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .footer-col-links a {
            color: rgba(148,163,184,0.85);
            text-decoration: none;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .footer-col-links a:hover {
            color: #bfdbfe;
            transform: translateX(3px);
        }

        .footer-col-links a i {
            font-size: 0.75rem;
            opacity: 0.6;
        }

        /* Bottom bar */
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 1.25rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .footer-bottom p {
            font-size: 0.8rem;
            color: rgba(100,116,139,0.9);
            margin: 0;
        }

        .footer-bottom-badge {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.78rem;
            color: rgba(100,116,139,0.8);
        }

        .footer-bottom-badge i {
            color: #ef4444;
            font-size: 0.7rem;
        }

        /* Contacto */
        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .footer-contact-icon {
            width: 32px;
            height: 32px;
            background: rgba(29,78,216,0.15);
            border: 1px solid rgba(29,78,216,0.25);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93c5fd;
            font-size: 0.85rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .footer-contact-label {
            font-size: 0.7rem;
            color: rgba(165,180,252,0.8);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.15rem;
        }

        .footer-contact-value {
            font-size: 0.875rem;
            color: rgba(203,213,225,0.85);
        }

        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .carousel-title {
                font-size: 2.5rem;
            }
            
            .carousel-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .feature-card {
                margin: 10px 0;
            }
            
            .nav-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .nav-btn {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<!-- Header Navigation -->
<header class="header-navbar">

<?php if (isset($_GET['logout'])): ?>
<div style="
    position:fixed; top:80px; left:50%; transform:translateX(-50%);
    background:#dcfce7; color:#166534; border:1px solid #bbf7d0;
    border-radius:10px; padding:.75rem 1.5rem;
    font-size:.9rem; font-weight:600;
    display:flex; align-items:center; gap:.5rem;
    box-shadow:0 4px 20px rgba(0,0,0,.15);
    z-index:9999; animation:fadeInDown .4s ease-out;
" id="logoutAlert">
    <i class="bi bi-check-circle-fill"></i>
    Has cerrado sesi&oacute;n exitosamente.
</div>
<style>
@keyframes fadeInDown {
    from { opacity:0; transform:translateX(-50%) translateY(-10px); }
    to   { opacity:1; transform:translateX(-50%) translateY(0); }
}
</style>
<script>
setTimeout(() => {
    const a = document.getElementById('logoutAlert');
    if (a) { a.style.transition='opacity .5s'; a.style.opacity='0'; setTimeout(()=>a.remove(),500); }
}, 3500);
</script>
<?php endif; ?>
    <div class="header-inner">
        <!-- Logo -->
        <a href="#" class="logo-container">
            <div class="logo-icon-wrap">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="logo-brand">
                <span class="logo-brand-name">Inventory</span>
                <span class="logo-brand-sub">System</span>
            </div>
        </a>

        <!-- Botones -->
        <div class="nav-buttons">
            <a href="desarrollador.php" class="nav-btn nav-btn-ghost">
                <i class="bi bi-code-slash"></i>
                Desarrollador
            </a>
            <a href="../views/usuarios/login.php" class="nav-btn nav-btn-primary">
                <i class="bi bi-box-arrow-in-right"></i>
                Iniciar sesión
            </a>
        </div>
    </div>
</header>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="../img/slider1.jpg" class="d-block w-100" alt="Gestión de Inventario">
            <div class="carousel-overlay">
                <div class="carousel-content">
                    <h1 class="carousel-title">Gestión Inteligente</h1>
                    <p class="carousel-subtitle">Control total de tu inventario con tecnología de punta y análisis en tiempo real</p>
                    <a href="../views/usuarios/login.php" class="carousel-cta">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        Comenzar Ahora
                    </a>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <img src="../img/slider2.jpg" class="d-block w-100" alt="Control de Productos">
            <div class="carousel-overlay">
                <div class="carousel-content">
                    <h1 class="carousel-title">Control Total</h1>
                    <p class="carousel-subtitle">Gestiona productos, movimientos y usuarios desde una plataforma centralizada</p>
                    <a href="../views/usuarios/login.php" class="carousel-cta">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        Explorar Funciones
                    </a>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <img src="../img/slider3.jpg" class="d-block w-100" alt="Análisis en Tiempo Real">
            <div class="carousel-overlay">
                <div class="carousel-content">
                    <h1 class="carousel-title">Análisis en Tiempo Real</h1>
                    <p class="carousel-subtitle">Toma decisiones informadas con datos actualizados al instante</p>
                    <a href="../views/usuarios/login.php" class="carousel-cta">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        Ver Demo
                    </a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Características Principales</h2>
        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
            Descubre todas las herramientas que nuestro sistema pone a tu disposición para optimizar tu gestión de inventario
        </p>
        
        <div class="row">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3 class="feature-title">Productos</h3>
                    <p class="feature-description">
                        Gestiona y controla todos los productos del inventario con categorización y seguimiento completo.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h3 class="feature-title">Movimientos</h3>
                    <p class="feature-description">
                        Control de entradas y salidas de productos en tiempo real con historial completo detallados.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="feature-title">Usuarios</h3>
                    <p class="feature-description">
                        Gestión de usuarios y control de accesos del sistema con permisos personalizados y auditoría de acciones.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="footer-content">
        <div class="footer-grid">

            <!-- Marca -->
            <div class="footer-brand">
                <div class="footer-brand-logo">
                    <div class="footer-brand-icon">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <span class="footer-brand-name">Inventory System</span>
                </div>
                <p class="footer-brand-desc">
                    La solución definitiva para la gestión inteligente de inventarios. Control total, análisis en tiempo real y seguridad empresarial.
                </p>
                <div class="footer-social">
                    <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="#" title="GitHub"><i class="bi bi-github"></i></a>
                </div>
            </div>

            <!-- Contacto -->
            <div>
                <p class="footer-col-title">Contacto</p>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <div class="footer-contact-label">Email</div>
                        <div class="footer-contact-value">Cristianramirez8537@gmail.com</div>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <div class="footer-contact-label">Teléfono</div>
                        <div class="footer-contact-value">+57 3188145842</div>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <div class="footer-contact-label">Ubicación</div>
                        <div class="footer-contact-value">Campoalegre, Hulia </div>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div>
                        <div class="footer-contact-label">Horario</div>
                        <div class="footer-contact-value">Lun – Vie, 7:00 AM – 7:00 PM</div>
                    </div>
                </div>
            </div>

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Inventory System. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script>
// Initialize AOS
AOS.init({
    duration: 1000,
    once: true,
    offset: 100
});

// Header scroll effect
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header-navbar');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Carousel pause on hover
const carousel = document.getElementById('heroCarousel');
carousel.addEventListener('mouseenter', () => {
    const bsCarousel = bootstrap.Carousel.getInstance(carousel);
    bsCarousel.pause();
});

carousel.addEventListener('mouseleave', () => {
    const bsCarousel = bootstrap.Carousel.getInstance(carousel);
    bsCarousel.cycle();
});
</script>

</body>
</html>





