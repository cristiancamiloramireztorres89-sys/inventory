<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .header-navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .logo-container {
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05);
        }

        .logo-container img {
            height: 50px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .nav-btn {
            padding: 10px 24px;
            border: 2px solid var(--primary-color);
            background: transparent;
            color: var(--primary-color);
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }

        .nav-btn.primary {
            background: var(--primary-color);
            color: white;
        }

        .nav-btn.primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
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
            background: linear-gradient(135deg, var(--dark-bg), #334155);
            color: white;
            padding: 60px 0 30px;
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        .footer-content {
            position: relative;
            z-index: 1;
        }

        .footer-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .footer-logo img {
            height: 60px;
            filter: brightness(0) invert(1);
            margin-bottom: 15px;
        }

        .footer-text {
            text-align: center;
            opacity: 0.8;
            margin-bottom: 20px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.6;
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
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center py-3">
            <div class="logo-container">
                <img src="../img/logo.png" alt="Inventory System Logo">
            </div>
            <div class="nav-buttons">
                <a href="#" class="nav-btn">
                    <i class="bi bi-code-slash me-2"></i>
                    DESARROLLADORES
                </a>
                <a href="../views/usuarios/login.php" class="nav-btn primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    INICIAR SESIÓN
                </a>
            </div>
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
                        Gestiona y controla todos los productos del inventario con categorización, códigos de barras y seguimiento completo.
                    </p>
                    <a href="#" class="feature-link">
                        Explorar más
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h3 class="feature-title">Movimientos</h3>
                    <p class="feature-description">
                        Control de entradas y salidas de productos en tiempo real con historial completo y reportes detallados.
                    </p>
                    <a href="#" class="feature-link">
                        Explorar más
                        <i class="bi bi-arrow-right"></i>
                    </a>
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
                    <a href="#" class="feature-link">
                        Explorar más
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container footer-content">
        <div class="footer-logo">
            <img src="../img/logo.png" alt="Inventory System Logo">
            <h4 class="text-white mb-3">Inventory System</h4>
        </div>
        
        <p class="footer-text">
            La solución definitiva para la gestión inteligente de inventarios
        </p>
        
        <div class="footer-links">
            <a href="#"><i class="bi bi-facebook me-2"></i>Facebook</a>
            <a href="#"><i class="bi bi-twitter me-2"></i>Twitter</a>
            <a href="#"><i class="bi bi-linkedin me-2"></i>LinkedIn</a>
            <a href="#"><i class="bi bi-github me-2"></i>GitHub</a>
        </div>
        
        <div class="footer-bottom">
            <p class="mb-0">&copy; 2024 Sistema de Inventario. Todos los derechos reservados.</p>
            <p class="mb-0 small mt-2">Desarrollado con ❤️ para optimizar tu negocio</p>
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