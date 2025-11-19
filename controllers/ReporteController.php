<?php
require_once '../models/Cita.php';
require_once '../models/Paciente.php';
require_once '../models/Medico.php';

class ReporteController {
    private $db;
    private $cita;
    private $paciente;
    private $medico;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->cita = new Cita($this->db);
        $this->paciente = new Paciente($this->db);
        $this->medico = new Medico($this->db);
    }

    public function index() {
        require_once '../views/reportes/index.php';
    }

    public function generate() {
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $data = [];
        $title = "";
        $columns = [];

        switch ($type) {
            case 'citas':
                $title = "Reporte de Citas";
                $query = "SELECT c.id, p.nombre_completo as paciente, m.nombre_completo as medico, 
                                 CONCAT(c.fecha_cita, ' ', c.hora_cita) as fecha_hora, c.estado, c.motivo 
                          FROM citas c 
                          JOIN usuarios p ON c.paciente_id = p.id 
                          JOIN usuarios m ON c.medico_id = m.id 
                          ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $columns = ['ID', 'Paciente', 'Médico', 'Fecha/Hora', 'Estado', 'Motivo'];
                break;

            case 'pacientes':
                $title = "Reporte de Pacientes";
                $query = "SELECT id, nombre_completo, telefono, email, fecha_registro 
                          FROM usuarios 
                          WHERE rol = 'paciente' 
                          ORDER BY nombre_completo ASC";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $columns = ['ID', 'Nombre', 'Teléfono', 'Email', 'Fecha Registro'];
                break;

            case 'medicos':
                $title = "Reporte de Médicos";
                $query = "SELECT u.id, u.nombre_completo, e.nombre as especialidad, u.telefono, u.email 
                          FROM usuarios u 
                          LEFT JOIN medicos_info m ON u.id = m.usuario_id
                          LEFT JOIN especialidades e ON m.especialidad_id = e.id
                          WHERE u.rol = 'medico' 
                          ORDER BY u.nombre_completo ASC";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $columns = ['ID', 'Nombre', 'Especialidad', 'Teléfono', 'Email'];
                break;
            
            default:
                die("Tipo de reporte no válido");
        }

        require_once '../views/reportes/print_view.php';
    }
}
?>
