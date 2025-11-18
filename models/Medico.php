<?php
class Medico {
    private $conn;
    private $table_name = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los médicos con sus especialidades
    public function obtenerTodos() {
        $query = "SELECT u.id, u.nombre_completo, u.email, u.telefono, u.fecha_registro,
                         e.nombre as especialidad, e.id as especialidad_id
                  FROM " . $this->table_name . " u
                  LEFT JOIN medicos_info m ON u.id = m.usuario_id
                  LEFT JOIN especialidades e ON m.especialidad_id = e.id
                  WHERE u.rol = 'medico'
                  ORDER BY u.nombre_completo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un médico por ID
    public function obtenerPorId($id) {
        $query = "SELECT u.id, u.nombre_completo, u.email, u.telefono, u.fecha_registro,
                         e.nombre as especialidad, e.id as especialidad_id
                  FROM " . $this->table_name . " u
                  LEFT JOIN medicos_info m ON u.id = m.usuario_id
                  LEFT JOIN especialidades e ON m.especialidad_id = e.id
                  WHERE u.id = :id AND u.rol = 'medico'
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo médico
    public function crear($nombre, $email, $password, $telefono, $especialidad_id) {
        try {
            $this->conn->beginTransaction();

            // Verificar si el email ya existe
            $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":email", $email);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                $this->conn->rollBack();
                return false; // Email ya existe
            }

            // Insertar usuario médico
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO " . $this->table_name . " 
                     (nombre_completo, email, password, rol, telefono) 
                     VALUES (:nombre, :email, :password, 'medico', :telefono)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password_hash);
            $stmt->bindParam(":telefono", $telefono);
            
            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            $usuario_id = $this->conn->lastInsertId();

            // Insertar en medicos_info
            $infoQuery = "INSERT INTO medicos_info (usuario_id, especialidad_id) 
                         VALUES (:usuario_id, :especialidad_id)";
            $infoStmt = $this->conn->prepare($infoQuery);
            $infoStmt->bindParam(":usuario_id", $usuario_id);
            $infoStmt->bindParam(":especialidad_id", $especialidad_id);
            
            if (!$infoStmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al crear médico: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar médico
    public function actualizar($id, $nombre, $email, $telefono, $especialidad_id, $password = null) {
        try {
            $this->conn->beginTransaction();

            // Verificar si el email ya existe en otro usuario
            $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE email = :email AND id != :id LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":email", $email);
            $checkStmt->bindParam(":id", $id);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                $this->conn->rollBack();
                return false; // Email ya existe en otro usuario
            }

            // Actualizar usuario
            if ($password) {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $query = "UPDATE " . $this->table_name . " 
                         SET nombre_completo = :nombre, email = :email, telefono = :telefono, password = :password
                         WHERE id = :id AND rol = 'medico'";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":password", $password_hash);
            } else {
                $query = "UPDATE " . $this->table_name . " 
                         SET nombre_completo = :nombre, email = :email, telefono = :telefono
                         WHERE id = :id AND rol = 'medico'";
                $stmt = $this->conn->prepare($query);
            }
            
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":telefono", $telefono);
            $stmt->bindParam(":id", $id);
            
            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            // Actualizar especialidad
            $infoQuery = "UPDATE medicos_info 
                         SET especialidad_id = :especialidad_id 
                         WHERE usuario_id = :usuario_id";
            $infoStmt = $this->conn->prepare($infoQuery);
            $infoStmt->bindParam(":especialidad_id", $especialidad_id);
            $infoStmt->bindParam(":usuario_id", $id);
            
            if (!$infoStmt->execute()) {
                // Si no existe, crear el registro
                $insertQuery = "INSERT INTO medicos_info (usuario_id, especialidad_id) 
                               VALUES (:usuario_id, :especialidad_id)";
                $insertStmt = $this->conn->prepare($insertQuery);
                $insertStmt->bindParam(":usuario_id", $id);
                $insertStmt->bindParam(":especialidad_id", $especialidad_id);
                $insertStmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al actualizar médico: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar médico
    public function eliminar($id) {
        try {
            // Verificar si tiene citas asociadas
            $checkQuery = "SELECT COUNT(*) as total FROM citas WHERE medico_id = :id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":id", $id);
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['total'] > 0) {
                return false; // No se puede eliminar si tiene citas
            }

            $this->conn->beginTransaction();

            // Eliminar de medicos_info
            $infoQuery = "DELETE FROM medicos_info WHERE usuario_id = :id";
            $infoStmt = $this->conn->prepare($infoQuery);
            $infoStmt->bindParam(":id", $id);
            $infoStmt->execute();

            // Eliminar usuario
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND rol = 'medico'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            
            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar médico: " . $e->getMessage());
            return false;
        }
    }

    // Obtener todas las especialidades
    public function obtenerEspecialidades() {
        $query = "SELECT id, nombre FROM especialidades ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

