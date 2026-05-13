<?php
class VendedorVenta {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ventas del vendedor actual
    public function obtenerPorVendedor($idUsuario) {
        $stmt = $this->conn->prepare(
            "SELECT v.*, c.nombre AS cliente_nombre
             FROM ventas v
             LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
             WHERE v.id_usuario = :id
             ORDER BY v.fecha DESC"
        );
        $stmt->execute([':id' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    // Venta por ID (solo si pertenece al vendedor)
    public function obtenerPorId($idVenta, $idUsuario) {
        $stmt = $this->conn->prepare(
            "SELECT v.*, c.nombre AS cliente_nombre, c.telefono AS cliente_telefono,
                    c.correo AS cliente_correo
             FROM ventas v
             LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
             WHERE v.id_venta = :id AND v.id_usuario = :uid"
        );
        $stmt->execute([':id' => $idVenta, ':uid' => $idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Total vendido hoy por el vendedor
    public function totalHoy($idUsuario) {
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(total),0) FROM ventas
             WHERE id_usuario = :id
             AND DATE(fecha) = CURDATE()"
        );
        $stmt->execute([':id' => $idUsuario]);
        return (float) $stmt->fetchColumn();
    }

    // Total vendido este mes por el vendedor
    public function totalMes($idUsuario) {
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(total),0) FROM ventas
             WHERE id_usuario = :id
             AND MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW())"
        );
        $stmt->execute([':id' => $idUsuario]);
        return (float) $stmt->fetchColumn();
    }

    // Contar ventas del vendedor
    public function contarPorVendedor($idUsuario) {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM ventas WHERE id_usuario = :id"
        );
        $stmt->execute([':id' => $idUsuario]);
        return (int) $stmt->fetchColumn();
    }

    // Crear venta
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

    // Insertar detalle
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

    // Actualizar total
    public function actualizarTotal($idVenta, $total) {
        $stmt = $this->conn->prepare(
            "UPDATE ventas SET total = :total WHERE id_venta = :id"
        );
        return $stmt->execute([':total' => $total, ':id' => $idVenta]);
    }

    // Descontar stock
    public function descontarStock($idProducto, $cantidad) {
        $stmt = $this->conn->prepare(
            "UPDATE productos SET stock_actual = stock_actual - :cantidad
             WHERE id_producto = :id AND stock_actual >= :cantidad"
        );
        $stmt->execute([':cantidad' => $cantidad, ':id' => $idProducto]);
        return $stmt->rowCount() > 0;
    }

    // Clientes
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

    // Productos disponibles
    public function obtenerProductosDisponibles() {
        $stmt = $this->conn->query(
            "SELECT id_producto, nombre, precio_venta, stock_actual, imagen
             FROM productos WHERE stock_actual > 0 ORDER BY nombre ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
