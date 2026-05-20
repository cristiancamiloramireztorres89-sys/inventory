<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/categorias.php';

// Solo administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: " . BASE_URL . "/views/usuarios/login.php");
    exit;
}

$database       = new Database();
$db             = $database->conectar();
$categoriaModel = new Categoria($db);

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    // ── INDEX ────────────────────────────────────────────────
    case 'index':
        $categorias = $categoriaModel->obtenerTodas();
        $total      = count($categorias);
        $alert      = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);

        require_once __DIR__ . '/../views/dashboard/admincategorias.php';
        break;

    // ── CREAR ────────────────────────────────────────────────
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admincategoriascontroller.php?accion=index"); exit;
        }

        $nombre      = trim($_POST['nombre']      ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (!$nombre) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'El nombre es obligatorio.'];
            header("Location: admincategoriascontroller.php?accion=index"); exit;
        }

        if ($categoriaModel->existeNombre($nombre)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Ya existe una categoría con ese nombre.'];
            header("Location: admincategoriascontroller.php?accion=index"); exit;
        }

        $ok = $categoriaModel->crear([
            'nombre'      => $nombre,
            'descripcion' => $descripcion ?: null,
        ]);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Categoría creada correctamente.']
            : ['type' => 'danger',  'text' => 'No se pudo crear la categoría.'];

        header("Location: admincategoriascontroller.php?accion=index"); exit;

    // ── EDITAR ───────────────────────────────────────────────
    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admincategoriascontroller.php?accion=index"); exit;
        }

        $id          = (int)($_POST['id_categoria'] ?? 0);
        $nombre      = trim($_POST['nombre']        ?? '');
        $descripcion = trim($_POST['descripcion']   ?? '');

        if (!$id || !$nombre) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Completa todos los campos obligatorios.'];
            header("Location: admincategoriascontroller.php?accion=index"); exit;
        }

        if ($categoriaModel->existeNombre($nombre, $id)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Ya existe otra categoría con ese nombre.'];
            header("Location: admincategoriascontroller.php?accion=index"); exit;
        }

        $ok = $categoriaModel->editar($id, [
            'nombre'      => $nombre,
            'descripcion' => $descripcion ?: null,
        ]);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Categoría actualizada correctamente.']
            : ['type' => 'danger',  'text' => 'No se pudo actualizar la categoría.'];

        header("Location: admincategoriascontroller.php?accion=index"); exit;

    // ── ELIMINAR ─────────────────────────────────────────────
    case 'eliminar':
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'ID inválido.'];
            header("Location: admincategoriascontroller.php?accion=index"); exit;
        }

        if ($categoriaModel->tieneProductos($id)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'No se puede eliminar: la categoría tiene productos asociados.'];
            header("Location: admincategoriascontroller.php?accion=index"); exit;
        }

        $ok = $categoriaModel->eliminar($id);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Categoría eliminada correctamente.']
            : ['type' => 'danger',  'text' => 'No se pudo eliminar la categoría.'];

        header("Location: admincategoriascontroller.php?accion=index"); exit;

    default:
        header("Location: admincategoriascontroller.php?accion=index"); exit;
}
?>
