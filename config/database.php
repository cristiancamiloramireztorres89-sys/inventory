<?php
class Database {
    private $host = "127.0.0.1"; //permiso kost
    private $port = "3306";   // ← ESTE ES EL PUERTO DE TU MYSQL
    private $db_name = "inventory"; // permiso base de datos
    private $username = "root"; //permiso usuario
    private $password = "";//permiso contrasena
    public $conn;//variable de coneccion

    public function conectar() {

        $this->conn = null;

        try {

            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {

            die("Error de conexión: " . $e->getMessage());

        }

        return $this->conn;
    }
}
?>