<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/vendedorproducto.php';
require_once __DIR__ . '/../models/vendedorventa.php';

// Solo vendedor
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'vendedor') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

$database      = new Database();
$db            = $database->conectar();
$productoModel = new VendedorProducto($db);
$ventaModel    = new VendedorVenta($db);

$idUsuario = (int)$_SESSION['usuario']['id_usuario'];

// ── Datos de sesión ──────────────────────────────────────────
$usuario = $_SESSION['usuario'];
$nombre  = $usuario['nombre'] ?? 'Vendedor';
$rol     = $usuario['rol']    ?? 'vendedor';
$correo  = $usuario['correo'] ?? '';

$GLOBALS['nombre']  = $nombre;
$GLOBALS['rol']     = $rol;
$GLOBALS['correo']  = $correo;
$GLOBALS['usuario'] = $usuario;

// ── Productos para el dashboard ──────────────────────────────
$productosDisponibles = $productoModel->obtenerTodos();
$ventasHoy            = $ventaModel->totalHoy($idUsuario);

$titulo = "Dashboard Vendedor";

// ── Cargar vista ─────────────────────────────────────────────
require_once __DIR__ . '/../views/dashboard/vendedor.php';
?>
