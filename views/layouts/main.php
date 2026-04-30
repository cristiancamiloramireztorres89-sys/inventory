<?php
/**
 * Main Layout Template - Inventory System
 * This template integrates header, sidebar, and footer components
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security check - redirect to login if not authenticated
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/usuarios/login.php");
    exit;
}

// Extract user session data
$usuario = $_SESSION['usuario'];
$nombre = $usuario['nombre'] ?? 'Usuario';
$rol = $usuario['rol'] ?? 'invitado';
$correo = $usuario['correo'] ?? '';

// Set page title
$titulo = $titulo ?? 'Dashboard';

// Make variables available for all included files
$GLOBALS['nombre'] = $nombre;
$GLOBALS['rol'] = $rol;
$GLOBALS['correo'] = $correo;
$GLOBALS['usuario'] = $usuario;

// Include header component
require_once __DIR__ . '/header.php';
?>

<!-- Include sidebar component -->
<?php require_once __DIR__ . '/sidebar.php'; ?>

<!-- Main content area -->
<div class="flex-1 flex flex-col">
    <!-- Page content goes here -->
    <!-- This will be replaced by the content of individual pages -->
    
    <!-- Include footer component -->
    <?php require_once __DIR__ . '/footer.php'; ?>
</div>

<!-- JavaScript for common functionality -->
<script>
// Initialize tooltips and popovers
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any Bootstrap components
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Sidebar active state management
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    
    sidebarLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === currentPath || 
            (currentPath.includes('dashboard') && link.textContent.includes('Dashboard')) ||
            (currentPath.includes('usuarios') && link.textContent.includes('Usuarios')) ||
            (currentPath.includes('categorias') && link.textContent.includes('Categorías')) ||
            (currentPath.includes('productos') && link.textContent.includes('Productos')) ||
            (currentPath.includes('compras') && link.textContent.includes('Compras')) ||
            (currentPath.includes('ventas') && link.textContent.includes('Ventas'))) {
            link.classList.add('active');
        }
    });
    
    // Mobile sidebar toggle
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebar = document.querySelector('aside');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
    
    // Search functionality
    const searchInput = document.querySelector('input[placeholder="Buscar..."]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = e.target.value.trim();
                if (searchTerm) {
                    // Implement search functionality
                    console.log('Searching for:', searchTerm);
                }
            }
        });
    }
    
    // Notification dropdown
    const notificationBtn = document.querySelector('[data-notifications]');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            // Toggle notification dropdown
            console.log('Toggle notifications');
        });
    }
    
    // User profile dropdown
    const profileBtn = document.querySelector('[data-profile-toggle]');
    if (profileBtn) {
        profileBtn.addEventListener('click', function() {
            // Toggle profile dropdown
            console.log('Toggle profile menu');
        });
    }
});

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
});

// Performance monitoring
if ('performance' in window) {
    window.addEventListener('load', function() {
        const perfData = performance.getEntriesByType('navigation')[0];
        console.log('Page load time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
    });
}
</script>

<style>
/* Additional utility styles */
.sidebar-transition {
    transition: transform 0.3s ease-in-out;
}

@media (max-width: 1024px) {
    aside {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        z-index: 50;
        transform: translateX(-100%);
    }
    
    aside.show {
        transform: translateX(0);
    }
    
    main {
        margin-left: 0 !important;
    }
}

/* Loading spinner */
.loading-spinner {
    border: 3px solid #f3f3f3;
    border-top: 3px solid var(--primary-color);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Custom scrollbar for main content */
main::-webkit-scrollbar {
    width: 8px;
}

main::-webkit-scrollbar-track {
    background: #f1f5f9;
}

main::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

main::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}
</style>
