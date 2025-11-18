<?php
require_once '../config/db.php';
// Podríamos requerir modelos de Citas o Estadísticas aquí

class DashboardController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Iniciamos sesión si no está iniciada para verificar acceso
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        // 1. Verificar Autenticación
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // 2. Redirigir según el ROL
        if ($_SESSION['user_role'] === 'admin') {
            $this->loadAdminDashboard();
        } elseif ($_SESSION['user_role'] === 'medico') {
            $this->loadMedicoDashboard();
        } else {
            $this->loadPatientDashboard();
        }
    }

    private function loadAdminDashboard() {
        // Obtener estadísticas reales de la base de datos
        require_once '../models/Paciente.php';
        require_once '../models/Medico.php';
        require_once '../models/Cita.php';
        
        $pacienteModel = new Paciente($this->db);
        $medicoModel = new Medico($this->db);
        $citaModel = new Cita($this->db);
        
        $stats = [
            'pacientes' => $pacienteModel->contar(),
            'medicos' => count($medicoModel->obtenerTodos()),
            'citas_hoy' => $citaModel->contarHoy()
        ];
        require_once '../views/dashboard/admin_home.php';
    }

    private function loadMedicoDashboard() {
        require_once '../models/Cita.php';
        $citaModel = new Cita($this->db);
        
        $medico_id = $_SESSION['user_id'];
        $citas = $citaModel->obtenerPorMedico($medico_id);
        $citasPendientes = $citaModel->contarPendientesPorMedico($medico_id);
        
        require_once '../views/dashboard/medico_home.php';
    }

    private function loadPatientDashboard() {
        require_once '../models/Cita.php';
        $citaModel = new Cita($this->db);
        
        $paciente_id = $_SESSION['user_id'];
        $citas = $citaModel->obtenerPorPaciente($paciente_id);
        $proximaCita = $citaModel->obtenerProximaCitaPaciente($paciente_id);
        $citasPendientes = $citaModel->contarPendientesPaciente($paciente_id);
        
        require_once '../views/dashboard/patient_home.php';
    }
}
?>