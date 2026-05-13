<?php
class Venta {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ── Ventas ───────────────────────────────────────────────

    // Todas las ventas con nombre de cliente y usuario
    public function obtenerTodas() {
        $stmt = $this->conn->query(
            "SELECT v.*, c.nombre AS cliente_nombre, u.nombre AS usuario_nombre
             FROM ventas v
             LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
             LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
             ORDER BY v.fecha DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Venta por ID con detalle
    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare(
            "SELECT v.*, c.nombre AS cliente_nombre, c.telefono AS cliente_telefono,
                    c.correo AS cliente_correo, u.nombre AS usuario_nombre
             FROM ventas v
             LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
             LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
             WHERE v.id_venta = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Detalle de una venta
    public function obtenerDetalle($idVenta) {
        $stmt = $this->conn->prepare(
            "SELECT dv.*, p.nombre AS producto_nombre, p.imagen
             FROM detalle_venta dv
             LEFT JOIN productos p ON dv.id_producto = p.id_producto
             WHERE dv.id_venta = :id"
        );
        $stmt->execute([':id' => $idVenta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar total de ventas
    public function contarTotal() {
        return (int) $this->conn->query("SELECT COUNT(*) FROM ventas")->fetchColumn();
    }

    // Total vendido este mes
    public function totalMes() {
        $stmt = $this->conn->query(
            "SELECT COALESCE(SUM(total),0) FROM ventas
             WHERE MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW())"
        );
        return (float) $stmt->fetchColumn();
    }

    // Crear venta (encabezado)
    public function crear($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO ventas (id_usuario, id_cliente, total)
             VALUES (:id_usuario, :id_cliente, :total)"
        );
        $stmt->execute([
            ':id_usuario' => $datos['id_usuario'],
            ':id_cliente' => $datos['id_cliente'],
            ':total'      => $datos['total'],
        ]);
        return (int) $this->conn->lastInsertId();
    }

    // Insertar línea de detalle
    public function insertarDetalle($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal)
             VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :subtotal)"
        );
        return $stmt->execute([
            ':id_venta'        => $datos['id_venta'],
            ':id_producto'     => $datos['id_producto'],
            ':cantidad'        => $datos['cantidad'],
            ':precio_unitario' => $datos['precio_unitario'],
            ':subtotal'        => $datos['subtotal'],
        ]);
    }

    // Actualizar total de la venta
    public function actualizarTotal($idVenta, $total) {
        $stmt = $this->conn->prepare(
            "UPDATE ventas SET total = :total WHERE id_venta = :id"
        );
        return $stmt->execute([':total' => $total, ':id' => $idVenta]);
    }

    // Eliminar venta (el detalle se borra en cascada)
    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM ventas WHERE id_venta = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Descontar stock al vender
    public function descontarStock($idProducto, $cantidad) {
        $stmt = $this->conn->prepare(
            "UPDATE productos SET stock_actual = stock_actual - :cantidad
             WHERE id_producto = :id AND stock_actual >= :cantidad"
        );
        $stmt->execute([':cantidad' => $cantidad, ':id' => $idProducto]);
        return $stmt->rowCount() > 0;
    }

    // Restaurar stock al eliminar venta
    public function restaurarStock($idVenta) {
        $detalle = $this->obtenerDetalle($idVenta);
        foreach ($detalle as $item) {
            $stmt = $this->conn->prepare(
                "UPDATE productos SET stock_actual = stock_actual + :cantidad
                 WHERE id_producto = :id"
            );
            $stmt->execute([':cantidad' => $item['cantidad'], ':id' => $item['id_producto']]);
        }
    }

    // ── Clientes ─────────────────────────────────────────────

    public function obtenerClientes() {
        $stmt = $this->conn->query("SELECT * FROM clientes ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearCliente($datos) {
        $stmt = $this->conn->prepare(
            "INSERT INTO clientes (nombre, telefono, correo)
             VALUES (:nombre, :telefono, :correo)"
        );
        $stmt->execute([
            ':nombre'   => $datos['nombre'],
            ':telefono' => $datos['telefono'] ?? null,
            ':correo'   => $datos['correo']   ?? null,
        ]);
        return (int) $this->conn->lastInsertId();
    }

    // ── Productos disponibles para vender ────────────────────

    public function obtenerProductosDisponibles() {
        $stmt = $this->conn->query(
            "SELECT id_producto, nombre, precio_venta, stock_actual, imagen
             FROM productos
             WHERE stock_actual > 0
             ORDER BY nombre ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
