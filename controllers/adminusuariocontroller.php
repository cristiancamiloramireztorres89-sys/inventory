<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';

// Solo administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: " . BASE_URL . "/views/usuarios/login.php");
    exit;
}

$database     = new Database();
$db           = $database->conectar();
$usuarioModel = new Usuario($db);

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    // ── INDEX ────────────────────────────────────────────────
    case 'index':
        $usuarios   = $usuarioModel->obtenerTodos();
        $total      = count($usuarios);
        $admins     = count(array_filter($usuarios, fn($u) => $u['rol'] === 'administrador'));
        $vendedores = count(array_filter($usuarios, fn($u) => $u['rol'] === 'vendedor'));
        $alert      = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);

        require_once __DIR__ . '/../views/dashboard/adminusuarios.php';
        break;

    // ── CREAR ────────────────────────────────────────────────
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: adminusuariocontroller.php?accion=index"); exit;
        }

        $nombre   = trim($_POST['nombre']   ?? '');
        $correo   = trim($_POST['correo']   ?? '');
        $password = trim($_POST['password'] ?? '');
        $rol      = trim($_POST['rol']      ?? '');

        if (!$nombre || !$correo || !$password || !$rol) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Completa todos los campos obligatorios.'];
            header("Location: adminusuariocontroller.php?accion=index"); exit;
        }

        if ($usuarioModel->existeCorreo($correo)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'El correo ya est&aacute; registrado.'];
            header("Location: adminusuariocontroller.php?accion=index"); exit;
        }

        $ok = $usuarioModel->crear([
            'nombre'     => $nombre,
            'correo'     => $correo,
            'contrasena' => $password,
            'rol'        => $rol,
        ]);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Usuario creado correctamente.']
            : ['type' => 'danger',  'text' => 'No se pudo crear el usuario.'];

        header("Location: adminusuariocontroller.php?accion=index"); exit;

    // ── EDITAR ───────────────────────────────────────────────
    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: adminusuariocontroller.php?accion=index"); exit;
        }

        $id       = (int)($_POST['id_usuario'] ?? 0);
        $nombre   = trim($_POST['nombre']   ?? '');
        $correo   = trim($_POST['correo']   ?? '');
        $rol      = trim($_POST['rol']      ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$id || !$nombre || !$correo || !$rol) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Completa todos los campos obligatorios.'];
            header("Location: adminusuariocontroller.php?accion=index"); exit;
        }

        if ($usuarioModel->existeCorreo($correo, $id)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'El correo ya est&aacute; en uso por otro usuario.'];
            header("Location: adminusuariocontroller.php?accion=index"); exit;
        }

        $datos = ['nombre' => $nombre, 'correo' => $correo, 'rol' => $rol];
        if ($password !== '') $datos['contrasena'] = $password;

        $ok = $usuarioModel->editar($id, $datos);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Usuario actualizado correctamente.']
            : ['type' => 'danger',  'text' => 'No se pudo actualizar el usuario.'];

        header("Location: adminusuariocontroller.php?accion=index"); exit;

    // ── TOGGLE ESTADO ────────────────────────────────────────
    case 'toggleEstado':
        $id = (int)($_GET['id'] ?? 0);

        // No puede desactivarse a sí mismo
        if ($id === (int)($_SESSION['usuario']['id_usuario'] ?? 0)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'No puedes desactivar tu propia cuenta.'];
            header("Location: adminusuariocontroller.php?accion=index"); exit;
        }

        $u      = $usuarioModel->obtenerPorId($id);
        $nuevo  = (int)($u['activo'] ?? 1) === 1 ? 0 : 1;
        $ok     = $usuarioModel->cambiarEstado($id, $nuevo);
        $estado = $nuevo === 1 ? 'activado' : 'desactivado';

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => "Usuario $estado correctamente."]
            : ['type' => 'danger',  'text' => 'No se pudo cambiar el estado.'];

        header("Location: adminusuariocontroller.php?accion=index"); exit;

    default:
        header("Location: adminusuariocontroller.php?accion=index"); exit;
}
?>
