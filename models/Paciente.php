<?php
class Paciente {
    private $conn;
    private $table_name = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los pacientes
    public function obtenerTodos() {
        $query = "SELECT id, nombre_completo, email, telefono, fecha_registro
                  FROM " . $this->table_name . "
                  WHERE rol = 'paciente'
                  ORDER BY nombre_completo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un paciente por ID
    public function obtenerPorId($id) {
        $query = "SELECT id, nombre_completo, email, telefono, fecha_registro
                  FROM " . $this->table_name . "
                  WHERE id = :id AND rol = 'paciente'
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo paciente
    public function crear($nombre, $email, $password, $telefono) {
        try {
            // Verificar si el email ya existe
            $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":email", $email);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                return false; // Email ya existe
            }

            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO " . $this->table_name . " 
                     (nombre_completo, email, password, rol, telefono) 
                     VALUES (:nombre, :email, :password, 'paciente', :telefono)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password_hash);
            $stmt->bindParam(":telefono", $telefono);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error al crear paciente: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar paciente
    public function actualizar($id, $nombre, $email, $telefono, $password = null) {
        try {
            // Verificar si el email ya existe en otro usuario
            $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE email = :email AND id != :id LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":email", $email);
            $checkStmt->bindParam(":id", $id);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                return false; // Email ya existe en otro usuario
            }

            if ($password) {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $query = "UPDATE " . $this->table_name . " 
                         SET nombre_completo = :nombre, email = :email, telefono = :telefono, password = :password
                         WHERE id = :id AND rol = 'paciente'";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":password", $password_hash);
            } else {
                $query = "UPDATE " . $this->table_name . " 
                         SET nombre_completo = :nombre, email = :email, telefono = :telefono
                         WHERE id = :id AND rol = 'paciente'";
                $stmt = $this->conn->prepare($query);
            }
            
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":telefono", $telefono);
            $stmt->bindParam(":id", $id);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error al actualizar paciente: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar paciente
    public function eliminar($id) {
        try {
            // Verificar si tiene citas asociadas
            $checkQuery = "SELECT COUNT(*) as total FROM citas WHERE paciente_id = :id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":id", $id);
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['total'] > 0) {
                return false; // No se puede eliminar si tiene citas
            }

            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND rol = 'paciente'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error al eliminar paciente: " . $e->getMessage());
            return false;
        }
    }

    // Contar total de pacientes
    public function contar() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE rol = 'paciente'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>

