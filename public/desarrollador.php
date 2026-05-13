<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/inventory/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desarrollador — Cristian Camilo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family:'Inter', sans-serif;
            min-height:100vh;
            display:flex;
            flex-direction:column;
            position:relative;
            overflow-x:hidden;
        }

        /* Fondo con imagen difuminada */
        .bg {
            position:fixed; inset:0; z-index:0;
            background: url('../img/slider1.jpg') center/cover no-repeat;
        }
        .bg::after {
            content:'';
            position:absolute; inset:0;
            background: linear-gradient(135deg,
                rgba(15,23,42,.82) 0%,
                rgba(30,27,75,.78) 50%,
                rgba(49,46,129,.75) 100%);
            backdrop-filter: blur(3px);
        }

        /* Nav */
        nav {
            position:relative; z-index:10;
            padding:1.1rem 2rem;
            display:flex; align-items:center; justify-content:space-between;
            border-bottom:1px solid rgba(255,255,255,.08);
            background:rgba(15,23,42,.5);
            backdrop-filter:blur(12px);
        }
        .nav-brand {
            display:flex; align-items:center; gap:.5rem;
        }
        .nav-brand-icon {
            width:30px; height:30px;
            background:#0f172a;
            border-radius:7px;
            display:flex; align-items:center; justify-content:center;
            font-size:.8rem; color:white;
        }
        .nav-brand-name { font-size:.9rem; font-weight:700; color:#e0e7ff; }

        .nav-back {
            display:inline-flex; align-items:center; gap:.35rem;
            color:rgba(203,213,225,.8); text-decoration:none;
            font-size:.82rem; font-weight:500;
            transition:color .2s;
        }
        .nav-back:hover { color:#fff; }

        /* Main */
        main {
            position:relative; z-index:10;
            flex:1;
            display:flex; align-items:center; justify-content:center;
            padding:3rem 1.5rem;
        }

        /* Tarjeta */
        .card {
            background:rgba(15,23,42,.72);
            border:1px solid rgba(255,255,255,.1);
            border-radius:20px;
            padding:2.25rem 2rem 2rem;
            max-width:460px; width:100%;
            backdrop-filter:blur(20px);
            box-shadow:0 24px 60px rgba(0,0,0,.4);
            text-align:center;
        }

        /* Badge rol */
        .role-badge {
            display:inline-flex; align-items:center; gap:.4rem;
            background:rgba(29,78,216,.18);
            border:1px solid rgba(29,78,216,.35);
            border-radius:20px;
            padding:.3rem .9rem;
            font-size:.7rem; font-weight:700; color:#93c5fd;
            text-transform:uppercase; letter-spacing:.8px;
            margin-bottom:1.5rem;
        }
        .role-badge i { font-size:.65rem; }

        /* Avatar */
        .avatar-wrap {
            position:relative; width:fit-content; margin:0 auto 1.25rem;
        }
        .avatar {
            width:110px; height:110px;
            border-radius:50%;
            border:3px solid rgba(29,78,216,.6);
            box-shadow:0 0 24px rgba(29,78,216,.4);
            overflow:hidden;
            background:#1e293b;
        }
        .avatar img { width:100%; height:100%; object-fit:cover; display:block; }
        .online-dot {
            position:absolute; bottom:6px; right:6px;
            width:14px; height:14px;
            background:#4ade80;
            border-radius:50%;
            border:2px solid rgba(15,23,42,.9);
        }

        /* Texto */
        .dev-name {
            font-size:1.4rem; font-weight:800; color:#fff;
            margin-bottom:.25rem; letter-spacing:-.3px;
        }
        .dev-role {
            font-size:.85rem; color:#60a5fa; font-weight:500;
            margin-bottom:1.1rem;
        }
        .dev-bio {
            font-size:.83rem; color:rgba(203,213,225,.75);
            line-height:1.7; margin-bottom:1.5rem;
            padding-bottom:1.5rem;
            border-bottom:1px solid rgba(255,255,255,.07);
        }

        /* Sección label */
        .sec-label {
            font-size:.65rem; font-weight:700; color:rgba(165,180,252,.7);
            text-transform:uppercase; letter-spacing:1px;
            margin-bottom:.75rem;
        }

        /* Tags tecnologías */
        .tags {
            display:flex; flex-wrap:wrap; gap:.4rem;
            justify-content:center;
            margin-bottom:1.5rem;
            padding-bottom:1.5rem;
            border-bottom:1px solid rgba(255,255,255,.07);
        }
        .tag {
            background:rgba(255,255,255,.07);
            border:1px solid rgba(255,255,255,.1);
            border-radius:6px; padding:.25rem .7rem;
            font-size:.75rem; font-weight:500; color:#e2e8f0;
            transition:all .2s;
        }
        .tag:hover {
            background:rgba(29,78,216,.2);
            border-color:rgba(29,78,216,.4);
            color:#bfdbfe;
        }

        /* Botones contacto */
        .social-row {
            display:flex; gap:.55rem; justify-content:center; flex-wrap:wrap;
        }
        .icon-btn {
            width:44px; height:44px;
            background:rgba(255,255,255,.07);
            border:1px solid rgba(255,255,255,.1);
            border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            color:rgba(203,213,225,.8);
            text-decoration:none;
            transition:all .2s;
        }
        .icon-btn:hover {
            background:rgba(29,78,216,.25);
            border-color:rgba(29,78,216,.5);
            color:#93c5fd;
            transform:translateY(-2px);
        }
        .icon-btn svg { width:18px; height:18px; }

        @media(max-width:480px){
            .card { padding:1.75rem 1.25rem; }
            .dev-name { font-size:1.2rem; }
        }
    </style>
</head>
<body>

<!-- Fondo -->
<div class="bg"></div>

<!-- Nav -->
<nav>
    <div class="nav-brand">
        <div class="nav-brand-icon">&#128230;</div>
        <span class="nav-brand-name">Inventory System</span>
    </div>
    <a href="index.php" class="nav-back">&#8592; Volver al inicio</a>
</nav>

<!-- Contenido -->
<main>
    <div class="card">

        <!-- Badge -->
        <div class="role-badge">
            &#60;/&#62; Desarrollador del Sistema
        </div>

        <!-- Avatar -->
        <div class="avatar-wrap">
            <div class="avatar">
                <img src="../img/avatar.png" alt="Cristian Camilo">
            </div>
            <div class="online-dot"></div>
        </div>

        <div class="dev-name">Cristian Camilo Ram&iacute;rez Torres</div>
        <div class="dev-role">Desarrollador de Software</div>

        <p class="dev-bio">
            Apasionado por construir soluciones web limpias y funcionales.
            Me enfoco en escribir c&oacute;digo ordenado, con buenas pr&aacute;cticas
            y arquitecturas escalables. Siempre aprendiendo algo nuevo.
        </p>

        <!-- Tecnologías -->
        <div class="sec-label">Tecnolog&iacute;as</div>
        <div class="tags">
            <span class="tag">PHP</span>
            <span class="tag">JavaScript</span>
            <span class="tag">React</span>
            <span class="tag">Python</span>
            <span class="tag">MySQL</span>
            <span class="tag">HTML / CSS</span>
        </div>

        <!-- Contacto -->
        <div class="sec-label">Contacto</div>
        <div class="social-row">
            <a href="https://github.com/cristiancamiloramireztorres89-sys" target="_blank" class="icon-btn" title="GitHub">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/>
                </svg>
            </a>
            <a href="mailto:cristiancamiloramireztorres89@gmail.com" class="icon-btn" title="Correo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
            </a>
            <a href="https://www.instagram.com/criss.scrip?igsh=dnZnbGo4MDEydnli" target="_blank" class="icon-btn" title="Instagram">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                    <circle cx="12" cy="12" r="4"/>
                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                </svg>
            </a>
            <a href="https://wa.me/qr/F25VJAT2KPQUO1" target="_blank" class="icon-btn" title="WhatsApp">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                </svg>
            </a>
        </div>

    </div>
</main>

</body>
</html>





