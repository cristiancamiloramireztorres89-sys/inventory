<?php
class Dashboard {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function contarProductos() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM productos");
        return $stmt ? (int)$stmt->fetchColumn() : 0;
    }

    public function contarStockBajo() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM productos WHERE stock_actual <= stock_minimo");
        return $stmt ? (int)$stmt->fetchColumn() : 0;
    }

    public function contarCategorias() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM categorias");
        return $stmt ? (int)$stmt->fetchColumn() : 0;
    }

    public function totalVentasMes() {
        $stmt = $this->conn->query(
            "SELECT COALESCE(SUM(total), 0) FROM ventas
             WHERE MONTH(fecha) = MONTH(NOW()) AND YEAR(fecha) = YEAR(NOW())"
        );
        return $stmt ? (float)$stmt->fetchColumn() : 0;
    }

    public function totalComprasMes() {
        $stmt = $this->conn->query(
            "SELECT COALESCE(SUM(total), 0) FROM compras
             WHERE MONTH(fecha) = MONTH(NOW()) AND YEAR(fecha) = YEAR(NOW())"
        );
        return $stmt ? (float)$stmt->fetchColumn() : 0;
    }
}
?>
