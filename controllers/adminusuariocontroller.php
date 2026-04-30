<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

// 🔐 SOLO ADMIN
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

class AdminUsuarioController {

    private $usuarioModel;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->usuarioModel = new Usuario($db);
    }

    // 🔵 CREAR USUARIO
    public function crear() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../views/usuarios/usuarios.php");
            exit;
        }

        $nombres = trim($_POST['nombres'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rol = trim($_POST['rol'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        if (empty($nombres) || empty($apellidos) || empty($correo) || empty($password) || empty($rol)) {
            $this->setAlert('warning', 'Campos incompletos', 'Complete todos los campos');
            header("Location: ../views/usuarios/usuarios.php");
            exit;
        }

        if ($this->usuarioModel->existeCorreo($correo)) {
            $this->setAlert('error', 'Error', 'El correo ya existe');
            header("Location: ../views/usuarios/usuarios.php");
            exit;
        }

        $datos = [
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'correo' => $correo,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $rol,
            'telefono' => $telefono
        ];

        $resultado = $this->usuarioModel->crear($datos);

        if ($resultado === true) {
            $this->setAlert('success', 'Éxito', 'Usuario creado correctamente');
        } else {
            $this->setAlert('error', 'Error', 'No se pudo crear el usuario');
        }

        header("Location: ../views/usuarios/usuarios.php");
        exit;
    }

    // 🟡 EDITAR USUARIO
    public function editar() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../views/usuarios/usuarios.php");
            exit;
        }

        $id = $_POST['id_usuario'] ?? null;

        if (!$id) {
            $this->setAlert('error', 'Error', 'ID inválido');
            header("Location: ../views/usuarios/usuarios.php");
            exit;
        }

        $datos = [
            'nombres' => trim($_POST['nombres'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'rol' => trim($_POST['rol'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? '')
        ];

        if (!empty($_POST['password'])) {
            $datos['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $resultado = $this->usuarioModel->editar($id, $datos);

        if ($resultado === true) {
            $this->setAlert('success', 'Actualizado', 'Usuario actualizado');
        } else {
            $this->setAlert('error', 'Error', 'No se pudo actualizar');
        }

        header("Location: ../views/usuarios/usuarios.php");
        exit;
    }

    // 🔴 ACTIVAR / DESACTIVAR
    public function toggleEstado() {

        $id = $_GET['id'] ?? null;
        $estado = $_GET['estado'] ?? null;

        if ($id !== null && $estado !== null) {

            $nuevo = $estado == 1 ? 0 : 1;
            $this->usuarioModel->cambiarEstado($id, $nuevo);

            $msg = $nuevo == 1 ? 'activado' : 'desactivado';

            $this->setAlert('success', 'Estado', "Usuario $msg correctamente");

        } else {
            $this->setAlert('error', 'Error', 'Datos inválidos');
        }

        header("Location: ../views/usuarios/usuarios.php");
        exit;
    }

    // 🔔 ALERTAS
    private function setAlert($icon, $title, $text) {
        $_SESSION['alert'] = [
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ];
    }
}

// 🔥 EJECUCIÓN
$controller = new adminUsuarioController();

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        $controller->crear();
        break;
    case 'editar':
        $controller->editar();
        break;
    case 'toggleEstado':
        $controller->toggleEstado();
        break;
    default:
        header("Location: ../views/usuarios/usuarios.php");
        exit;
}