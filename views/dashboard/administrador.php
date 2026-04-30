<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Usuario.php';

$database = new Database();
$db = $database->conectar();
$usuarioModel = new Usuario($db);

$usuario = $_SESSION['usuario'];
$nombre  = $usuario['nombre'] ?? 'Usuario';
$rol     = $usuario['rol']    ?? 'administrador';
$correo  = $usuario['correo'] ?? '';

$GLOBALS['nombre']  = $nombre;
$GLOBALS['rol']     = $rol;
$GLOBALS['correo']  = $correo;
$GLOBALS['usuario'] = $usuario;

$titulo = "Dashboard Administrador";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<!-- Dashboard Content -->
<div class="space-y-6 animate-fade-in">
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-card">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Bienvenido al <span class="text-blue-600">Dashboard</span></h2>
                <p class="text-gray-600 mt-2">Panel principal de gestión del sistema de inventario</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-500">Sistema en línea</span>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
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
                    <p class="text-green-100 text-sm">Ventas del Mes</p>
                    <p class="text-3xl font-bold mt-2">$45,678</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-green-100">
                <i class="fas fa-arrow-up mr-1"></i> 8% desde el mes anterior
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
            <a href="/inventory/views/usuarios/gestion/index.php" class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                <i class="fas fa-users text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm text-gray-700">Gestión Usuarios</span>
            </a>
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
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actividad Reciente del Sistema</h3>
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
            <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-purple-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">Nuevo usuario registrado</p>
                    <p class="text-xs text-gray-500">Ana Martínez - hace 2 horas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ventas Mensuales</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center text-gray-500">
                    <i class="fas fa-chart-bar text-4xl mb-2"></i>
                    <p>Gráfico de ventas</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos por Categoría</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center text-gray-500">
                    <i class="fas fa-chart-pie text-4xl mb-2"></i>
                    <p>Gráfico de categorías</p>
                </div>
            </div>
        </div>
    </div>

</div><!-- end space-y-6 -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
