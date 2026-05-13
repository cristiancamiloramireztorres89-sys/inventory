<?php
// Variables de sesión
if (session_status() === PHP_SESSION_NONE) session_start();

$usuario = $usuario ?? $GLOBALS['usuario'] ?? $_SESSION['usuario'] ?? [];
$rol     = $usuario['rol']    ?? $GLOBALS['rol']    ?? 'invitado';
$nombre  = $usuario['nombre'] ?? $GLOBALS['nombre'] ?? 'Usuario';
$correo  = $usuario['correo'] ?? $GLOBALS['correo'] ?? '';

// Ruta activa
$rutaActual = $_SERVER['REQUEST_URI'];
function esActivo(string $fragmento, string $ruta): string {
    // Extrae solo el nombre del archivo de la URL actual
    $archivo = basename(parse_url($ruta, PHP_URL_PATH));
    return str_contains($archivo, $fragmento) ? 'nav-active' : '';
}

function navActivo(string $fragmento, string $ruta): string {
    return str_contains($ruta, $fragmento) ? 'nav-active' : '';
}
?>

<style>
/* ===== SIDEBAR ===== */
.sidebar {
    position: fixed;
    top: 0; left: 0;
    width: 260px;
    height: 100vh;
    background: #0f172a;
    display: flex;
    flex-direction: column;
    z-index: 1000;
    box-shadow: 4px 0 24px rgba(0,0,0,0.35);
    transition: transform 0.3s ease;
    overflow: hidden;
}

/* Logo */
.sidebar-logo {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 1.4rem 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0;
}

.sidebar-logo-icon {
    width: 36px; height: 36px;
    background: #0f172a;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1rem;
    box-shadow: 0 4px 12px rgba(29,78,216,0.4);
    flex-shrink: 0;
}

.sidebar-logo-text {
    display: flex; flex-direction: column; line-height: 1;
}

.sidebar-logo-name {
    font-size: 0.95rem; font-weight: 700; color: #fff;
}

.sidebar-logo-sub {
    font-size: 0.62rem; color: rgba(165,180,252,0.7);
    font-weight: 500; letter-spacing: 0.5px; text-transform: uppercase;
    margin-top: 2px;
}

/* Sección de rol */
.sidebar-role-badge {
    margin: 0.85rem 1.25rem 0.25rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(29,78,216,0.12);
    border: 1px solid rgba(29,78,216,0.25);
    border-radius: 20px;
    padding: 0.25rem 0.75rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: rgba(165,180,252,0.9);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: fit-content;
}

.sidebar-role-badge .dot {
    width: 6px; height: 6px;
    background: #4ade80;
    border-radius: 50%;
}

/* Sección label */
.nav-section-label {
    font-size: 0.65rem;
    font-weight: 700;
    color: rgba(100,116,139,0.8);
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0.9rem 1.25rem 0.4rem;
}

/* Nav */
.sidebar-nav {
    flex: 1;
    padding: 0.25rem 0.75rem;
    overflow-y: auto;
    scrollbar-width: none;
}

.sidebar-nav::-webkit-scrollbar { display: none; }

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.7rem 0.9rem;
    border-radius: 9px;
    color: rgba(148,163,184,0.85);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-bottom: 2px;
    position: relative;
}

.nav-item:hover {
    background: rgba(255,255,255,0.06);
    color: #e2e8f0;
}

.nav-active {
    background: rgba(29,78,216,0.18) !important;
    color: #bfdbfe !important;
    border-left: 3px solid #1e3a8a;
    padding-left: calc(0.9rem - 3px);
}

.nav-item-icon {
    width: 30px; height: 30px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem;
    flex-shrink: 0;
    background: rgba(255,255,255,0.05);
    transition: background 0.2s;
}

.nav-item:hover .nav-item-icon,
.nav-active .nav-item-icon {
    background: rgba(29,78,216,0.2);
}

/* Colores de iconos por módulo */
.icon-dashboard { color: #60a5fa; }
.icon-usuarios  { color: #60a5fa; }
.icon-productos { color: #a78bfa; }
.icon-categorias{ color: #34d399; }
.icon-compras   { color: #fb923c; }
.icon-ventas    { color: #4ade80; }
.icon-reportes  { color: #f472b6; }

/* Divisor */
.nav-divider {
    height: 1px;
    background: rgba(255,255,255,0.06);
    margin: 0.5rem 0.75rem;
}

/* Usuario info */
.sidebar-user {
    padding: 1rem 1.25rem;
    border-top: 1px solid rgba(255,255,255,0.07);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}

.sidebar-user-avatar {
    width: 36px; height: 36px;
    background: #0f172a;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 0.85rem;
    flex-shrink: 0;
}

.sidebar-user-info { flex: 1; min-width: 0; }

.sidebar-user-name {
    font-size: 0.85rem; font-weight: 600; color: #e2e8f0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.sidebar-user-email {
    font-size: 0.72rem; color: rgba(100,116,139,0.9);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.sidebar-logout {
    width: 32px; height: 32px;
    background: rgba(239,68,68,0.1);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #f87171; text-decoration: none;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.sidebar-logout:hover {
    background: rgba(239,68,68,0.22);
    color: #fca5a5;
}

/* ===== MAIN WRAPPER ===== */
.main-wrapper {
    position: fixed;
    top: 0;
    left: 260px;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    background: #f1f5f9;
    overflow: hidden;
}

/* Top Header */
.top-header {
    height: 64px;
    background: #11225a;
    color: white;
    padding: 0 1.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 12px rgba(0,0,0,0.2);
    flex-shrink: 0;
    border-bottom: 1px solid rgba(29,78,216,0.25);
}

.top-header-left { display: flex; align-items: center; gap: 0.85rem; }

.mobile-menu-btn {
    display: none;
    background: rgba(255,255,255,0.1);
    border: none; color: white;
    width: 36px; height: 36px;
    border-radius: 8px;
    align-items: center; justify-content: center;
    cursor: pointer; font-size: 1rem;
}

.top-header-title { font-size: 1.1rem; font-weight: 600; color: #e0e7ff; }
.top-header-sub   { font-size: 0.75rem; color: rgba(165,180,252,0.7); margin-top: 1px; }

.top-header-right { display: flex; align-items: center; gap: 0.75rem; }

.header-search {
    display: flex; align-items: center; gap: 0.5rem;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 8px;
    padding: 0.4rem 0.85rem;
}

.header-search input {
    background: transparent; border: none; outline: none;
    color: white; font-size: 0.8rem; width: 180px;
}

.header-search input::placeholder { color: rgba(255,255,255,0.45); }
.header-search i { color: rgba(255,255,255,0.45); font-size: 0.8rem; }

.header-user {
    display: flex; align-items: center; gap: 0.6rem;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 8px;
    padding: 0.35rem 0.85rem;
}

.header-user-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: #0f172a;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 0.8rem;
}

.header-user-name  { font-size: 0.82rem; font-weight: 600; color: #e0e7ff; line-height: 1.2; }
.header-user-role  { font-size: 0.7rem; color: rgba(165,180,252,0.75); }

/* Content */
.content-area {
    flex: 1;
    min-height: 0;
    padding: 1.25rem 1.5rem 2rem;
    background: #f1f5f9;
    overflow-y: auto;
    overflow-x: hidden;
}

/* Hover card */
.hover-card { transition: box-shadow 0.2s, transform 0.2s; }
.hover-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.1); transform: translateY(-1px); }

/* Gradientes tarjetas */
.from-blue-500   { --gf: #3b82f6; } .to-blue-600   { --gt: #2563eb; }
.from-green-500  { --gf: #22c55e; } .to-green-600  { --gt: #16a34a; }
.from-purple-500 { --gf: #a855f7; } .to-purple-600 { --gt: #9333ea; }
.from-orange-500 { --gf: #f97316; } .to-orange-600 { --gt: #ea580c; }
.bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--gf), var(--gt)); }

/* Animaciones */
@keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
.animate-fade-in { animation: fadeIn 0.35s ease-out; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
.animate-pulse { animation: pulse 2s cubic-bezier(.4,0,.6,1) infinite; }

/* Responsive */
@media (max-width: 1024px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.show { transform: translateX(0); }
    .main-wrapper { left: 0; }
    .mobile-menu-btn { display: flex; }
    .header-search { display: none; }
}

@media (max-width: 640px) {
    .header-user-role { display: none; }
    .top-header { padding: 0 1rem; }
}
</style>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <div class="sidebar-logo-text">
            <span class="sidebar-logo-name">Inventory</span>
            <span class="sidebar-logo-sub">System</span>
        </div>
    </div>

    <!-- Badge de rol -->
    <div class="sidebar-role-badge">
        <span class="dot"></span>
        <?= htmlspecialchars(ucfirst($rol)) ?>
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">

        <?php if ($rol === 'administrador'): ?>

            <p class="nav-section-label">Principal</p>

            <a href="/inventory/controllers/dashboardadmincontroller.php"
               class="nav-item <?= esActivo('dashboardadmin', $rutaActual) ?>">
                <span class="nav-item-icon icon-dashboard"><i class="fas fa-gauge-high"></i></span>
                Dashboard
            </a>

            <div class="nav-divider"></div>
            <p class="nav-section-label">Gestión</p>

            <a href="/inventory/controllers/adminusuariocontroller.php?accion=index"
               class="nav-item <?= esActivo('adminusuario', $rutaActual) ?>">
                <span class="nav-item-icon icon-usuarios"><i class="fas fa-users"></i></span>
                Usuarios
            </a>

            <a href="/inventory/controllers/admincategoriascontroller.php?accion=index"
               class="nav-item <?= esActivo('admincategorias', $rutaActual) ?>">
                <span class="nav-item-icon icon-categorias"><i class="fas fa-tags"></i></span>
                Categorías
            </a>

            <a href="/inventory/controllers/adminproductoscontroller.php?accion=index"
               class="nav-item <?= esActivo('adminproductos', $rutaActual) ?>">
                <span class="nav-item-icon icon-productos"><i class="fas fa-box-open"></i></span>
                Productos
            </a>

            <div class="nav-divider"></div>
            <p class="nav-section-label">Operaciones</p>

            <a href="/inventory/controllers/admincomprascontroller.php?accion=index"
               class="nav-item <?= esActivo('admincompras', $rutaActual) ?>">
                <span class="nav-item-icon icon-compras"><i class="fas fa-cart-flatbed"></i></span>
                Compras
            </a>

            <a href="/inventory/controllers/adminventacontroller.php?accion=index"
               class="nav-item <?= esActivo('adminventa', $rutaActual) ?>">
                <span class="nav-item-icon icon-ventas"><i class="fas fa-chart-line"></i></span>
                Ventas
            </a>

        <?php elseif ($rol === 'vendedor'): ?>

            <p class="nav-section-label">Principal</p>

            <a href="/inventory/controllers/vendedorcontroller.php"
               class="nav-item <?= esActivo('vendedorcontroller', $rutaActual) ?>">
                <span class="nav-item-icon icon-dashboard"><i class="fas fa-gauge-high"></i></span>
                Dashboard
            </a>

            <div class="nav-divider"></div>
            <p class="nav-section-label">Operaciones</p>

            <a href="/inventory/controllers/vendedorproductoscontroller.php"
               class="nav-item <?= esActivo('vendedorproductos', $rutaActual) ?>">
                <span class="nav-item-icon icon-productos"><i class="fas fa-box-open"></i></span>
                Productos
            </a>

            <a href="/inventory/controllers/vendedorcomprascontroller.php?accion=index"
               class="nav-item <?= esActivo('vendedorcompras', $rutaActual) ?>">
                <span class="nav-item-icon icon-compras"><i class="fas fa-cart-flatbed"></i></span>
                Compras
            </a>

            <a href="/inventory/controllers/vendedorventascontroller.php?accion=index"
               class="nav-item <?= esActivo('vendedorventas', $rutaActual) ?>">
                <span class="nav-item-icon icon-ventas"><i class="fas fa-cash-register"></i></span>
                Ventas
            </a>

        <?php endif; ?>

    </nav>+

    <!-- Usuario -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?= htmlspecialchars($nombre) ?></div>
            <div class="sidebar-user-email"><?= htmlspecialchars($correo) ?></div>
        </div>
        <a href="/inventory/controllers/Authcontroller.php?accion=logout"
           class="sidebar-logout" title="Cerrar sesión">
            <i class="fas fa-right-from-bracket"></i>
        </a>
    </div>

</aside>

<!-- ===== MAIN WRAPPER ===== -->
<div class="main-wrapper">
    <!-- Header -->
    <header class="top-header">
        <div class="top-header-left">
            <button class="mobile-menu-btn" onclick="toggleSidebar()" aria-label="Menú">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <div class="top-header-title"><?= htmlspecialchars($titulo ?? 'Dashboard') ?></div>
                <div class="top-header-sub">Sistema de gestión de inventario</div>
            </div>
        </div>

        <div class="top-header-right">
            <div class="header-user">
                <div class="header-user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="header-user-name"><?= htmlspecialchars($nombre) ?></div>
                    <div class="header-user-role"><?= htmlspecialchars(ucfirst($rol)) ?></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido -->
    <div class="content-area animate-fade-in">

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
}

// Cerrar sidebar al hacer click fuera en móvil
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const btn = document.querySelector('.mobile-menu-btn');
    if (window.innerWidth <= 1024 && sidebar.classList.contains('show')
        && !sidebar.contains(e.target) && e.target !== btn) {
        sidebar.classList.remove('show');
    }
});
</script>







