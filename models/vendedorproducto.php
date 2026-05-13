<?php
class VendedorProducto {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Todos los productos con categoría
    public function obtenerTodos() {
        $stmt = $this->conn->query(
            "SELECT p.*, c.nombre AS categoria_nombre
             FROM productos p
             LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
             ORDER BY p.nombre ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar totales
    public function contarTotal() {
        return (int) $this->conn->query("SELECT COUNT(*) FROM productos")->fetchColumn();
    }

    public function contarDisponibles() {
        return (int) $this->conn->query(
            "SELECT COUNT(*) FROM productos WHERE stock_actual > 0"
        )->fetchColumn();
    }

    public function contarStockBajo() {
        return (int) $this->conn->query(
            "SELECT COUNT(*) FROM productos WHERE stock_actual <= stock_minimo"
        )->fetchColumn();
    }
}
?>
