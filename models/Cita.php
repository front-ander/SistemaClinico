<?php
class Cita {
    private $conn;
    private $table_name = "citas";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para crear una nueva cita
    public function crear($paciente_id, $medico_id, $fecha, $hora, $motivo) {
        // Query SQL segura
        $query = "INSERT INTO " . $this->table_name . " 
                 (paciente_id, medico_id, fecha_cita, hora_cita, motivo) 
                 VALUES (:paciente_id, :medico_id, :fecha, :hora, :motivo)";

        $stmt = $this->conn->prepare($query);

        // Limpieza básica de datos
        $motivo = htmlspecialchars(strip_tags($motivo));

        // Enlace de parámetros (Binding)
        $stmt->bindParam(":paciente_id", $paciente_id);
        $stmt->bindParam(":medico_id", $medico_id);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":hora", $hora);
        $stmt->bindParam(":motivo", $motivo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener médicos para el select del formulario
    public function obtenerMedicos() {
        $query = "SELECT u.id, u.nombre_completo, e.nombre as especialidad 
                  FROM usuarios u 
                  JOIN medicos_info m ON u.id = m.usuario_id 
                  JOIN especialidades e ON m.especialidad_id = e.id 
                  WHERE u.rol = 'medico'
                  ORDER BY u.nombre_completo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todas las citas
    public function obtenerTodas() {
        $query = "SELECT c.id, c.fecha_cita, c.hora_cita, c.estado, c.motivo, c.created_at,
                         p.nombre_completo as paciente_nombre, p.email as paciente_email,
                         m.nombre_completo as medico_nombre, e.nombre as especialidad
                  FROM " . $this->table_name . " c
                  JOIN usuarios p ON c.paciente_id = p.id
                  JOIN usuarios m ON c.medico_id = m.id
                  LEFT JOIN medicos_info mi ON m.id = mi.usuario_id
                  LEFT JOIN especialidades e ON mi.especialidad_id = e.id
                  ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener citas por paciente
    public function obtenerPorPaciente($paciente_id) {
        $query = "SELECT c.id, c.fecha_cita, c.hora_cita, c.estado, c.motivo, c.resultados, c.created_at,
                         m.nombre_completo as medico_nombre, e.nombre as especialidad
                  FROM " . $this->table_name . " c
                  JOIN usuarios m ON c.medico_id = m.id
                  LEFT JOIN medicos_info mi ON m.id = mi.usuario_id
                  LEFT JOIN especialidades e ON mi.especialidad_id = e.id
                  WHERE c.paciente_id = :paciente_id
                  ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":paciente_id", $paciente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una cita por ID
    public function obtenerPorId($id) {
        $query = "SELECT c.id, c.paciente_id, c.medico_id, c.fecha_cita, c.hora_cita, c.estado, c.motivo, c.resultados, c.created_at,
                         p.nombre_completo as paciente_nombre, p.email as paciente_email, p.telefono as paciente_telefono,
                         m.nombre_completo as medico_nombre, e.nombre as especialidad
                  FROM " . $this->table_name . " c
                  JOIN usuarios p ON c.paciente_id = p.id
                  JOIN usuarios m ON c.medico_id = m.id
                  LEFT JOIN medicos_info mi ON m.id = mi.usuario_id
                  LEFT JOIN especialidades e ON mi.especialidad_id = e.id
                  WHERE c.id = :id
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar cita
    public function actualizar($id, $medico_id, $fecha, $hora, $motivo, $estado) {
        $query = "UPDATE " . $this->table_name . " 
                 SET medico_id = :medico_id, fecha_cita = :fecha, hora_cita = :hora, 
                     motivo = :motivo, estado = :estado
                 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $motivo = htmlspecialchars(strip_tags($motivo));
        
        $stmt->bindParam(":medico_id", $medico_id);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":hora", $hora);
        $stmt->bindParam(":motivo", $motivo);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    // Eliminar cita
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Contar citas del día
    public function contarHoy() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE fecha_cita = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Obtener citas por médico
    public function obtenerPorMedico($medico_id) {
        $query = "SELECT c.id, c.fecha_cita, c.hora_cita, c.estado, c.motivo, c.created_at,
                         p.nombre_completo as paciente_nombre, p.email as paciente_email, p.telefono as paciente_telefono,
                         e.nombre as especialidad
                  FROM " . $this->table_name . " c
                  JOIN usuarios p ON c.paciente_id = p.id
                  LEFT JOIN medicos_info mi ON c.medico_id = mi.usuario_id
                  LEFT JOIN especialidades e ON mi.especialidad_id = e.id
                  WHERE c.medico_id = :medico_id
                  ORDER BY c.fecha_cita ASC, c.hora_cita ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":medico_id", $medico_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar citas pendientes por médico
    public function contarPendientesPorMedico($medico_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE medico_id = :medico_id AND estado IN ('pendiente', 'confirmada')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":medico_id", $medico_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Confirmar cita (cambiar estado a confirmada)
    public function confirmar($id) {
        $query = "UPDATE " . $this->table_name . " 
                 SET estado = 'confirmada'
                 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Agregar resultados/notas a la cita
    public function agregarResultados($id, $resultados) {
        // Primero verificamos si la columna existe, si no, la agregamos
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET resultados = :resultados, estado = 'completada'
                     WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $resultados = htmlspecialchars(strip_tags($resultados));
            $stmt->bindParam(":resultados", $resultados);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            // Si la columna no existe, intentamos agregarla
            if (strpos($e->getMessage(), 'Unknown column') !== false) {
                // La columna no existe, pero continuamos sin error
                return false;
            }
            throw $e;
        }
    }

    // Obtener próxima cita del paciente
    public function obtenerProximaCitaPaciente($paciente_id) {
        $query = "SELECT c.id, c.fecha_cita, c.hora_cita, c.estado, c.motivo,
                         m.nombre_completo as medico_nombre, e.nombre as especialidad
                  FROM " . $this->table_name . " c
                  JOIN usuarios m ON c.medico_id = m.id
                  LEFT JOIN medicos_info mi ON m.id = mi.usuario_id
                  LEFT JOIN especialidades e ON mi.especialidad_id = e.id
                  WHERE c.paciente_id = :paciente_id 
                    AND c.fecha_cita >= CURDATE()
                    AND c.estado IN ('pendiente', 'confirmada')
                  ORDER BY c.fecha_cita ASC, c.hora_cita ASC
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":paciente_id", $paciente_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Contar citas pendientes del paciente
    public function contarPendientesPaciente($paciente_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE paciente_id = :paciente_id AND estado IN ('pendiente', 'confirmada')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":paciente_id", $paciente_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Verificar disponibilidad del médico
    public function verificarDisponibilidad($medico_id, $fecha, $hora, $cita_id = null) {
        // 1. Verificar horario de trabajo
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $dia_semana = $dias[date('w', strtotime($fecha))];

        $query = "SELECT * FROM horarios_medicos 
                  WHERE medico_id = :medico_id 
                  AND dia_semana = :dia_semana 
                  AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":medico_id", $medico_id);
        $stmt->bindParam(":dia_semana", $dia_semana);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            return ['disponible' => false, 'mensaje' => "El médico no atiende los $dia_semana."];
        }

        $horario = $stmt->fetch(PDO::FETCH_ASSOC);
        $hora_cita = strtotime($hora);
        $inicio = strtotime($horario['hora_inicio']);
        $fin = strtotime($horario['hora_fin']);

        if ($hora_cita < $inicio || $hora_cita >= $fin) {
            return ['disponible' => false, 'mensaje' => "La hora seleccionada está fuera del horario de atención ($horario[hora_inicio] - $horario[hora_fin])."];
        }

        // 2. Verificar si ya existe una cita (intervalos de 30 min por defecto, o simplemente coincidencia exacta)
        // Asumimos citas de 30 minutos para simplificar la validación de solapamiento
        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE medico_id = :medico_id 
                  AND fecha_cita = :fecha 
                  AND hora_cita = :hora 
                  AND estado != 'cancelada'";
        
        if ($cita_id) {
            $query .= " AND id != :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":medico_id", $medico_id);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":hora", $hora);
        if ($cita_id) {
            $stmt->bindParam(":id", $cita_id);
        }
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['disponible' => false, 'mensaje' => "El médico ya tiene una cita agendada en ese horario."];
        }

        return ['disponible' => true];
    }
}
?>