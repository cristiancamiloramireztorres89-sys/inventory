<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/usuario.php';

class Authcontroller {

    public function login() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // VALIDAR CAMPOS
        if ($correo === '' || $password === '') {
            $_SESSION['alert'] = [
                'text' => "Debe ingresar correo y contraseña",
                'type' => "danger"
            ];
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        // CONEXIÓN
        $database = new Database();
        $db = $database->conectar();

        $usuarioModel = new Usuario($db);
        $usuario = $usuarioModel->obtenerPorCorreo($correo);

        // USUARIO NO EXISTE
        if (!$usuario) {
            $_SESSION['alert'] = [
                'text' => "Usuario no encontrado",
                'type' => "danger"
            ];
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        // CONTRASEÑA
        if ($password !== $usuario['contrasena']) {
            $_SESSION['alert'] = [
                'text' => "Contraseña incorrecta",
                'type' => "danger"
            ];
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        // ACTIVO
        if (isset($usuario['activo']) && (int)$usuario['activo'] === 0) {
            $_SESSION['alert'] = [
                'text' => "Usuario desactivado",
                'type' => "danger"
            ];
            header("Location: ../views/usuarios/login.php");
            exit;
        }

        // LOGIN OK
        $_SESSION['usuario'] = $usuario;

        $rol = strtolower(trim($usuario['rol']));

        // REDIRECCIÓN
        if ($rol === 'administrador') {
            header("Location: ../views/dashboard/administrador.php");
        } else {
            header("Location: ../views/dashboard/vendedor.php");
        }

        exit;
    }
}

// EJECUCIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new Authcontroller();
    $controller->login();
}