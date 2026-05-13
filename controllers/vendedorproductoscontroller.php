<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/vendedorproducto.php';

// Solo vendedor
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'vendedor') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

$database       = new Database();
$db             = $database->conectar();
$productoModel  = new VendedorProducto($db);

// Solo acción index — el vendedor solo puede ver
$productos    = $productoModel->obtenerTodos();
$total        = $productoModel->contarTotal();
$disponibles  = $productoModel->contarDisponibles();
$stockBajo    = $productoModel->contarStockBajo();

require_once __DIR__ . '/../views/dashboard/vendedorproductos.php';
?>
