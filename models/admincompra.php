<?php
class Compra {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ── Compras ──────────────────────────────────────────────

    public function obtenerTodas() {
        $stmt = $this->conn->query(
            "SELECT c.*, p.nombre AS proveedor_nombre
             FROM compras c
             LEFT JOIN proveedores p ON c.id_proveedor = p.id_proveedor
             ORDER BY c.fecha DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare(
            "SELECT c.*, p.nombre AS proveedor_nombre, p.telefono AS proveedor_telefono, p.correo AS proveedor_correo FROM compras c LEFT JOIN proveedores p ON c.id_proveedor = p.id_proveedor WHERE c.id_compra = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalle($idCompra) {
        $stmt = $this->conn->prepare(
            "SELECT dc.*, pr.nombre AS producto_nombre
             FROM detalle_compra dc
             LEFT JOIN productos pr ON dc.id_producto = pr.id_producto
             WHERE dc.id_compra = :id"
        );
        $stmt->execute([':id' => $idCompra]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTotal() {
        return (int) $this->conn->query("SELECT COUNT(*) FROM compras")->fetchColumn();
    }

    public function totalMes() {
        $stmt = $this->conn->query(
            "SELECT COALESCE(SUM(total),0) FROM compras
             WHERE MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW())"
        );
        return (float) $stmt->fetchColumn();
    }

    public function crear($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO compras (id_proveedor, total) VALUES (:id_proveedor, :total)"
        );
        $stmt->execute([
            ':id_proveedor' => $datos['id_proveedor'],
            ':total'        => $datos['total'],
        ]);
        return (int) $this->conn->lastInsertId();
    }

    public function insertarDetalle($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO detalle_compra (id_compra, id_producto, cantidad, precio_compra, subtotal)
             VALUES (:id_compra, :id_producto, :cantidad, :precio_compra, :subtotal)"
        );
        return $stmt->execute([
            ':id_compra'     => $datos['id_compra'],
            ':id_producto'   => $datos['id_producto'],
            ':cantidad'      => $datos['cantidad'],
            ':precio_compra' => $datos['precio_compra'],
            ':subtotal'      => $datos['subtotal'],
        ]);
    }

    public function actualizarTotal($idCompra, $total) {
        $stmt = $this->conn->prepare(
            "UPDATE compras SET total = :total WHERE id_compra = :id"
        );
        return $stmt->execute([':total' => $total, ':id' => $idCompra]);
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM compras WHERE id_compra = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Al eliminar compra, descontar el stock que se había sumado
    public function revertirStock($idCompra) {
        $detalle = $this->obtenerDetalle($idCompra);
        foreach ($detalle as $item) {
            $stmt = $this->conn->prepare(
                "UPDATE productos SET stock_actual = stock_actual - :cantidad
                 WHERE id_producto = :id AND stock_actual >= :cantidad"
            );
            $stmt->execute([':cantidad' => $item['cantidad'], ':id' => $item['id_producto']]);
        }
    }

    // Al registrar compra, sumar stock
    public function sumarStock($idProducto, $cantidad) {
        $stmt = $this->conn->prepare(
            "UPDATE productos SET stock_actual = stock_actual + :cantidad WHERE id_producto = :id"
        );
        return $stmt->execute([':cantidad' => $cantidad, ':id' => $idProducto]);
    }

    // ── Proveedores ──────────────────────────────────────────

    public function obtenerProveedores() {
        $stmt = $this->conn->query("SELECT * FROM proveedores ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearProveedor($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO proveedores (nombre, telefono, correo)
             VALUES (:nombre, :telefono, :correo)"
        );
        $stmt->execute([
            ':nombre'   => $datos['nombre'],
            ':telefono' => $datos['telefono'] ?? null,
            ':correo'   => $datos['correo']   ?? null,
        ]);
        return (int) $this->conn->lastInsertId();
    }

    // ── Productos para comprar ───────────────────────────────

    public function obtenerProductos() {
        $stmt = $this->conn->query(
            "SELECT id_producto, nombre, precio_venta FROM productos ORDER BY nombre ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

