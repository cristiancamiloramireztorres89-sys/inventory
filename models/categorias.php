<?php
class Categoria {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las categorías con conteo de productos
    public function obtenerTodas() {
        $stmt = $this->conn->query(
            "SELECT c.*, COUNT(p.id_producto) AS total_productos
             FROM categorias c
             LEFT JOIN productos p ON p.id_categoria = c.id_categoria
             GROUP BY c.id_categoria
             ORDER BY c.id_categoria DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categorias WHERE id_categoria = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Contar total
    public function contarTotal() {
        return (int) $this->conn->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
    }

    // Verificar si nombre ya existe (excluyendo un ID)
    public function existeNombre($nombre, $excluirId = null) {
        if ($excluirId) {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) FROM categorias WHERE nombre = :nombre AND id_categoria != :id"
            );
            $stmt->execute([':nombre' => $nombre, ':id' => $excluirId]);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) FROM categorias WHERE nombre = :nombre"
            );
            $stmt->execute([':nombre' => $nombre]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    // Crear categoría
    public function crear($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)"
        );
        return $stmt->execute([
            ':nombre'      => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] ?? null,
        ]);
    }

    // Editar categoría
    public function editar($id, $datos) {
        $stmt = $this->conn->prepare(
            "UPDATE categorias SET nombre = :nombre, descripcion = :descripcion
             WHERE id_categoria = :id"
        );
        return $stmt->execute([
            ':nombre'      => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] ?? null,
            ':id'          => $id,
        ]);
    }

    // Eliminar categoría
    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM categorias WHERE id_categoria = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Verificar si tiene productos asociados
    public function tieneProductos($id) {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM productos WHERE id_categoria = :id"
        );
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
?>
