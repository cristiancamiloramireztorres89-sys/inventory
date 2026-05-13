<?php
class Producto {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Todos los productos con nombre de categoría
    public function obtenerTodos() {
        $stmt = $this->conn->query(
            "SELECT p.*, c.nombre AS categoria_nombre
             FROM productos p
             LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
             ORDER BY p.id_producto DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.nombre AS categoria_nombre
             FROM productos p
             LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
             WHERE p.id_producto = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Contar total
    public function contarTotal() {
        return (int) $this->conn->query("SELECT COUNT(*) FROM productos")->fetchColumn();
    }

    // Contar con stock bajo
    public function contarStockBajo() {
        return (int) $this->conn->query(
            "SELECT COUNT(*) FROM productos WHERE stock_actual <= stock_minimo"
        )->fetchColumn();
    }

    // Verificar si nombre ya existe (excluyendo un ID)
    public function existeNombre($nombre, $excluirId = null) {
        if ($excluirId) {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) FROM productos WHERE nombre = :nombre AND id_producto != :id"
            );
            $stmt->execute([':nombre' => $nombre, ':id' => $excluirId]);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) FROM productos WHERE nombre = :nombre"
            );
            $stmt->execute([':nombre' => $nombre]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    // Crear producto
    public function crear($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO productos (id_categoria, nombre, marca, stock_actual, stock_minimo, precio_venta, descripcion, imagen)
             VALUES (:id_categoria, :nombre, :marca, :stock_actual, :stock_minimo, :precio_venta, :descripcion, :imagen)"
        );
        return $stmt->execute([
            ':id_categoria' => $datos['id_categoria'],
            ':nombre'       => $datos['nombre'],
            ':marca'        => $datos['marca']        ?? null,
            ':stock_actual' => $datos['stock_actual']  ?? 0,
            ':stock_minimo' => $datos['stock_minimo']  ?? 0,
            ':precio_venta' => $datos['precio_venta'],
            ':descripcion'  => $datos['descripcion']  ?? null,
            ':imagen'       => $datos['imagen']        ?? null,
        ]);
    }

    // Editar producto
    public function editar($id, $datos) {
        $stmt = $this->conn->prepare(
            "UPDATE productos
             SET id_categoria = :id_categoria,
                 nombre       = :nombre,
                 marca        = :marca,
                 stock_actual = :stock_actual,
                 stock_minimo = :stock_minimo,
                 precio_venta = :precio_venta,
                 descripcion  = :descripcion,
                 imagen       = :imagen
             WHERE id_producto = :id"
        );
        return $stmt->execute([
            ':id_categoria' => $datos['id_categoria'],
            ':nombre'       => $datos['nombre'],
            ':marca'        => $datos['marca']        ?? null,
            ':stock_actual' => $datos['stock_actual']  ?? 0,
            ':stock_minimo' => $datos['stock_minimo']  ?? 0,
            ':precio_venta' => $datos['precio_venta'],
            ':descripcion'  => $datos['descripcion']  ?? null,
            ':imagen'       => $datos['imagen']        ?? null,
            ':id'           => $id,
        ]);
    }

    // Verificar si el producto tiene registros en compras o ventas
    public function tieneRegistrosAsociados($id) {
        $stmt = $this->conn->prepare(
            "SELECT 
                (SELECT COUNT(*) FROM detalle_compra WHERE id_producto = :id1) +
                (SELECT COUNT(*) FROM detalle_venta  WHERE id_producto = :id2) AS total"
        );
        $stmt->execute([':id1' => $id, ':id2' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // Eliminar registros relacionados antes de eliminar el producto
    public function eliminarRegistrosAsociados($id) {
        $stmt1 = $this->conn->prepare("DELETE FROM detalle_compra WHERE id_producto = :id");
        $stmt1->execute([':id' => $id]);
        $stmt2 = $this->conn->prepare("DELETE FROM detalle_venta WHERE id_producto = :id");
        $stmt2->execute([':id' => $id]);
    }

    // Eliminar producto
    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM productos WHERE id_producto = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
