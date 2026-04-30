<?php
// Example Dashboard Page - Using the new layout system
// This file demonstrates how to use the main layout template

// Set page title
$titulo = "Dashboard Principal";

// Include the main layout which contains header, sidebar, and footer
require_once __DIR__ . '/../layouts/main.php';
?>

<!-- Page Content -->
<div class="space-y-6 animate-fade-in">
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-card">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Bienvenido, <?= htmlspecialchars($nombre) ?>!</h2>
                <p class="text-gray-600 mt-1">Rol: <span class="font-semibold capitalize"><?= htmlspecialchars($rol) ?></span></p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-500">En línea</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Productos</p>
                    <p class="text-3xl font-bold mt-2">1,234</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-blue-100">
                <i class="fas fa-arrow-up mr-1"></i> 12% desde el mes pasado
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Ventas Hoy</p>
                    <p class="text-3xl font-bold mt-2">$45,678</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-green-100">
                <i class="fas fa-arrow-up mr-1"></i> 8% desde ayer
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Usuarios Activos</p>
                    <p class="text-3xl font-bold mt-2">89</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-purple-100">
                <i class="fas fa-arrow-up mr-1"></i> 5% nuevos esta semana
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Compras Mes</p>
                    <p class="text-3xl font-bold mt-2">$23,456</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-orange-100">
                <i class="fas fa-arrow-down mr-1"></i> 3% desde el mes pasado
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php if ($rol === 'administrador'): ?>
                <button class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                    <i class="fas fa-user-plus text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm text-gray-700">Nuevo Usuario</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                    <i class="fas fa-plus-circle text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm text-gray-700">Agregar Producto</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                    <i class="fas fa-tag text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm text-gray-700">Nueva Categoría</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors group">
                    <i class="fas fa-file-invoice text-orange-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm text-gray-700">Nueva Compra</span>
                </button>
            <?php else: ?>
                <button class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                    <i class="fas fa-cash-register text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm text-gray-700">Nueva Venta</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                    <i class="fas fa-search text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm text-gray-700">Buscar Producto</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                    <i class="fas fa-barcode text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm text-gray-700">Escanear</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors group">
                    <i class="fas fa-file-alt text-orange-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm text-gray-700">Reportes</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actividad Reciente</h3>
        <div class="space-y-4">
            <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">Nuevo producto agregado</p>
                    <p class="text-xs text-gray-500">Laptop Dell XPS 13 - hace 5 minutos</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">Venta completada</p>
                    <p class="text-xs text-gray-500">Orden #1234 - $1,234.56 - hace 15 minutos</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-orange-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">Compra registrada</p>
                    <p class="text-xs text-gray-500">Proveedor TechCorp - $5,678.90 - hace 1 hora</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Specific JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate numbers on page load
    const animateValue = (element, start, end, duration) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.textContent = Math.floor(progress * (end - start) + start).toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };

    // Animate stat cards
    const statNumbers = document.querySelectorAll('.text-3xl');
    statNumbers.forEach(element => {
        const finalValue = parseInt(element.textContent.replace(/[^0-9]/g, ''));
        if (!isNaN(finalValue)) {
            animateValue(element, 0, finalValue, 2000);
        }
    });

    // Quick action buttons
    const actionButtons = document.querySelectorAll('.grid button');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.querySelector('span').textContent;
            console.log('Acción seleccionada:', action);
            // Here you would typically open a modal or navigate to a page
        });
    });
});
</script>
