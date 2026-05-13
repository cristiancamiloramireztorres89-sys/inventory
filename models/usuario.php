<?php
class Usuario {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Login
    public function obtenerPorCorreo($correo) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Todos los usuarios
    public function obtenerTodos() {
        $stmt = $this->conn->query("SELECT * FROM usuarios ORDER BY id_usuario DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Últimos N usuarios
    public function obtenerUltimos($limite = 5) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios ORDER BY id_usuario DESC LIMIT :limite");
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar total
    public function contarTotal() {
        return (int) $this->conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    }

    // Contar por rol
    public function contarPorRol($rol) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE rol = :rol");
        $stmt->execute([':rol' => $rol]);
        return (int) $stmt->fetchColumn();
    }

    // Verificar si correo ya existe (excluyendo un ID)
    public function existeCorreo($correo, $excluirId = null) {
        if ($excluirId) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo AND id_usuario != :id");
            $stmt->execute([':correo' => $correo, ':id' => $excluirId]);
        } else {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
            $stmt->execute([':correo' => $correo]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    // Crear usuario — columnas reales: nombre, correo, contrasena, rol, activo
    public function crear($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO usuarios (nombre, correo, contrasena, rol, activo)
             VALUES (:nombre, :correo, :contrasena, :rol, 1)"
        );
        return $stmt->execute([
            ':nombre'     => $datos['nombre'],
            ':correo'     => $datos['correo'],
            ':contrasena' => $datos['contrasena'],
            ':rol'        => $datos['rol'],
        ]);
    }

    // Editar usuario
    public function editar($id, $datos) {
        if (!empty($datos['contrasena'])) {
            $stmt = $this->conn->prepare(
                "UPDATE usuarios SET nombre=:nombre, correo=:correo,
                 contrasena=:contrasena, rol=:rol WHERE id_usuario=:id"
            );
            return $stmt->execute([
                ':nombre'     => $datos['nombre'],
                ':correo'     => $datos['correo'],
                ':contrasena' => $datos['contrasena'],
                ':rol'        => $datos['rol'],
                ':id'         => $id,
            ]);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE usuarios SET nombre=:nombre, correo=:correo,
                 rol=:rol WHERE id_usuario=:id"
            );
            return $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':correo' => $datos['correo'],
                ':rol'    => $datos['rol'],
                ':id'     => $id,
            ]);
        }
    }

    // Cambiar estado activo/inactivo
    public function cambiarEstado($id, $activo) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET activo=:activo WHERE id_usuario=:id");
        return $stmt->execute([':activo' => $activo, ':id' => $id]);
    }
}
?>
