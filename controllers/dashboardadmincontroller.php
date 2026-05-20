<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../models/Dashboard.php';

// Solo administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: " . BASE_URL . "/views/usuarios/login.php");
    exit;
}

$database      = new Database();
$db            = $database->conectar();
$usuarioModel  = new Usuario($db);
$dashModel     = new Dashboard($db);

// ── Datos de sesión ──────────────────────────────────────────
$usuario = $_SESSION['usuario'];
$nombre  = $usuario['nombre'] ?? 'Administrador';
$rol     = $usuario['rol']    ?? 'administrador';
$correo  = $usuario['correo'] ?? '';

$GLOBALS['nombre']  = $nombre;
$GLOBALS['rol']     = $rol;
$GLOBALS['correo']  = $correo;
$GLOBALS['usuario'] = $usuario;

// ── Datos preparados por los modelos ────────────────────────
$totalUsuarios   = $usuarioModel->contarTotal();
$totalAdmins     = $usuarioModel->contarPorRol('administrador');
$totalVendedores = $usuarioModel->contarPorRol('vendedor');
$ultimosUsuarios = $usuarioModel->obtenerUltimos(5);

$totalProductos  = $dashModel->contarProductos();
$stockBajo       = $dashModel->contarStockBajo();
$totalCategorias = $dashModel->contarCategorias();

$titulo = "Dashboard";

// ── Cargar vista ─────────────────────────────────────────────
require_once __DIR__ . '/../views/dashboard/administrador.php';
?>
