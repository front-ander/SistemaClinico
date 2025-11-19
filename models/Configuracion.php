<?php
class Configuracion {
    private $conn;
    private $table_name = "configuracion";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT clave, valor FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['clave']] = $row['valor'];
        }
        return $settings;
    }

    public function update($clave, $valor) {
        $query = "UPDATE " . $this->table_name . " SET valor = :valor WHERE clave = :clave";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':valor' => $valor, ':clave' => $clave]);
    }
}
?>
