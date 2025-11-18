<?php
class User {
    private $conn;
    private $table_name = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($nombre, $email, $password) {
        try {
            // Verificar si el email ya existe
            $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":email", $email);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                return false; // Email ya existe
            }
            
            $query = "INSERT INTO " . $this->table_name . " (nombre_completo, email, password, rol) VALUES (:nombre, :email, :password, 'paciente')";
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar y Hash
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password_hash);

            return $stmt->execute();
        } catch(PDOException $e) {
            // Log del error (opcional)
            error_log("Error en registro: " . $e->getMessage());
            return false;
        }
    }

    public function login($email, $password) {
        $query = "SELECT id, nombre_completo, password, rol FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }
}
?>