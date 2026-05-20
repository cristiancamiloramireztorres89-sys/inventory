<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';

header('Content-Type: application/json');

// Solo admin autenticado
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    echo json_encode(['error' => true]);
    exit;
}

$correo    = trim($_GET['correo']    ?? '');
$excluirId = (int)($_GET['excluir'] ?? 0);

if ($correo === '') {
    echo json_encode(['existe' => false]);
    exit;
}

$database     = new Database();
$db           = $database->conectar();
$usuarioModel = new Usuario($db);

$existe = $usuarioModel->existeCorreo($correo, $excluirId ?: null);

echo json_encode(['existe' => $existe]);
?>
