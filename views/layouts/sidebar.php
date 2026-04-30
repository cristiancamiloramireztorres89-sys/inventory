<?php
// Get variables from globals if not already defined
$nombre = $nombre ?? $GLOBALS['nombre'] ?? 'Usuario';
$rol    = $rol    ?? $GLOBALS['rol']    ?? 'invitado';
$correo = $correo ?? $GLOBALS['correo'] ?? '';
$usuario = $usuario ?? $GLOBALS['usuario'] ?? [];
?>

<style>
/* ===== SIDEBAR ===== */
aside.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px;
    height: 100vh;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    display: flex;
    flex-direction: column;
    z-index: 1000;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
    overflow-y: auto;
}

/* Logo */
.sidebar-logo {
    display: flex;
    align-items: center;
    padding: 1.75rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    gap: 1rem;
    flex-shrink: 0;
}

.sidebar-logo-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.sidebar-logo:hover .sidebar-logo-icon {
    transform: scale(1.1) rotate(5deg);
}

.sidebar-logo-text {
    color: #ffffff;
    font-size: 1.4rem;
    font-weight: 700;
    letter-spacing: -0.5px;
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 1rem 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 0.85rem 1rem;
    color: #b8bcc8;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.25s ease;
    gap: 0.85rem;
    font-size: 0.95rem;
    font-weight: 500;
}

.nav-item i {
    width: 20px;
    text-align: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.nav-item:hover {
    color: #ffffff;
    background: rgba(102, 126, 234, 0.15);
}

.nav-item.active {
    color: #ffffff;
    background: rgba(102, 126, 234, 0.2);
    border-left: 3px solid #667eea;
}

/* User Info */
.sidebar-user {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    gap: 0.85rem;
    flex-shrink: 0;
}

.sidebar-user-avatar {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.sidebar-user-details {
    flex: 1;
    min-width: 0;
}

.sidebar-user-name {
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-user-role {
    color: #8892b0;
    font-size: 0.8rem;
    font-weight: 500;
}

.sidebar-logout {
    width: 36px;
    height: 36px;
    background: rgba(239, 68, 68, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ef4444;
    text-decoration: none;
    transition: all 0.25s ease;
    flex-shrink: 0;
}

.sidebar-logout:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: scale(1.1);
}

/* ===== MAIN LAYOUT ===== */
.main-wrapper {
    margin-left: 280px;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Top Header */
.top-header {
    height: 72px;
    background: linear-gradient(135deg, var(--primary-color, #4f46e5), var(--primary-dark, #4338ca));
    color: white;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    position: sticky;
    top: 0;
    z-index: 100;
    flex-shrink: 0;
}

.top-header-title h1 {
    font-size: 1.5rem;
    font-weight: 300;
    letter-spacing: -0.5px;
}

.top-header-title p {
    font-size: 0.8rem;
    opacity: 0.75;
    margin-top: 2px;
}

.top-header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-search {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
    padding: 0.5rem 1rem;
    gap: 0.5rem;
    border: 1px solid rgba(255,255,255,0.2);
}

.header-search input {
    background: transparent;
    border: none;
    outline: none;
    color: white;
    font-size: 0.875rem;
    width: 200px;
}

.header-search input::placeholder {
    color: rgba(255,255,255,0.6);
}

.header-notif {
    position: relative;
    background: rgba(255,255,255,0.1);
    border: none;
    color: white;
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.header-notif:hover {
    background: rgba(255,255,255,0.2);
}

.header-notif .badge {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 10px;
    height: 10px;
    background: #ef4444;
    border-radius: 50%;
}

.header-user {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
    padding: 0.5rem 1rem;
    border: 1px solid rgba(255,255,255,0.2);
}

.header-user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #9333ea);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.header-user-info .user-role-label {
    font-size: 0.85rem;
    font-weight: 600;
    line-height: 1.2;
}

.header-user-info .user-name-label {
    font-size: 0.75rem;
    opacity: 0.8;
}

/* Mobile toggle */
.mobile-menu-btn {
    display: none;
    background: rgba(255,255,255,0.1);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.1rem;
}

/* Content area */
.content-area {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
}

/* Responsive */
@media (max-width: 1024px) {
    aside.sidebar {
        transform: translateX(-100%);
    }

    aside.sidebar.show {
        transform: translateX(0);
    }

    .main-wrapper {
        margin-left: 0;
    }

    .mobile-menu-btn {
        display: flex;
    }

    .header-search {
        display: none;
    }
}

@media (max-width: 640px) {
    .header-user-info {
        display: none;
    }

    .top-header {
        padding: 0 1rem;
    }
}

/* Hover card effect */
.hover-card {
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.hover-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

/* Gradient backgrounds for stat cards */
.from-blue-500 { --gradient-from: #3b82f6; }
.to-blue-600   { --gradient-to:   #2563eb; }
.from-green-500 { --gradient-from: #22c55e; }
.to-green-600   { --gradient-to:   #16a34a; }
.from-purple-500 { --gradient-from: #a855f7; }
.to-purple-600   { --gradient-to:   #9333ea; }
.from-orange-500 { --gradient-from: #f97316; }
.to-orange-600   { --gradient-to:   #ea580c; }

.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--gradient-from), var(--gradient-to));
}

/* Pulse animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.5; }
}
.animate-pulse { animation: pulse 2s cubic-bezier(0.4,0,0.6,1) infinite; }

/* Fade in */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fadeIn 0.35s ease-out; }
</style>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i class="fas fa-cube"></i>
        </div>
        <span class="sidebar-logo-text">Inventory</span>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <a href="/inventory/views/dashboard/administrador.php" class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <?php if ($rol === 'administrador'): ?>
            <a href="/inventory/views/usuarios/gestion/index.php" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-tags"></i>
                <span>Categorías</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-box"></i>
                <span>Productos</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Compras</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Ventas</span>
            </a>
        <?php endif; ?>

        <?php if ($rol === 'vendedor'): ?>
            <a href="#" class="nav-item">
                <i class="fas fa-cash-register"></i>
                <span>Ventas</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-file-alt"></i>
                <span>Reportes</span>
            </a>
        <?php endif; ?>
    </nav>

    <!-- User Info -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="sidebar-user-details">
            <div class="sidebar-user-name"><?= htmlspecialchars($nombre) ?></div>
            <div class="sidebar-user-role"><?= htmlspecialchars(ucfirst($rol)) ?></div>
        </div>
        <a href="/inventory/controllers/AuthController.php?accion=logout" class="sidebar-logout" title="Cerrar sesión">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</aside>

<!-- ===== MAIN WRAPPER ===== -->
<div class="main-wrapper">
    <!-- Top Header -->
    <header class="top-header">
        <div style="display:flex; align-items:center; gap:1rem;">
            <button class="mobile-menu-btn" onclick="toggleSidebar()" aria-label="Menú">
                <i class="fas fa-bars"></i>
            </button>
            <div class="top-header-title">
                <h1><?= htmlspecialchars($titulo) ?></h1>
                <p>Gestión inteligente de inventario</p>
            </div>
        </div>

        <div class="top-header-actions">
            <!-- Search -->
            <div class="header-search">
                <i class="fas fa-search" style="color:rgba(255,255,255,0.6); font-size:0.85rem;"></i>
                <input type="text" placeholder="Buscar...">
            </div>

            <!-- Notifications -->
            <button class="header-notif" aria-label="Notificaciones">
                <i class="fas fa-bell"></i>
                <span class="badge animate-pulse"></span>
            </button>

            <!-- User -->
            <div class="header-user">
                <div class="header-user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="header-user-info">
                    <div class="user-role-label"><?= htmlspecialchars(ucfirst($rol)) ?></div>
                    <div class="user-name-label"><?= htmlspecialchars($nombre) ?></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <div class="content-area animate-fade-in">

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
}

// Active nav item based on current URL
document.addEventListener('DOMContentLoaded', function () {
    const path = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(function (item) {
        item.classList.remove('active');
        if (item.getAttribute('href') && path.includes(item.getAttribute('href').split('/').pop().replace('.php', ''))) {
            item.classList.add('active');
        }
    });
    // Always mark dashboard active on dashboard page
    if (path.includes('administrador') || path.includes('vendedor')) {
        const dash = document.querySelector('.nav-item[href*="dashboard"], .nav-item[href*="administrador"], .nav-item[href*="vendedor"]');
        if (dash) dash.classList.add('active');
    }
});
</script>
