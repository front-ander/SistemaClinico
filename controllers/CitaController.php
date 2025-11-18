<?php
require_once '../config/db.php';
require_once '../models/Cita.php';

class CitaController {
    private $db;
    private $citaModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->citaModel = new Cita($this->db);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Muestra el formulario
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        // Obtener la lista de médicos para el desplegable
        $medicos = $this->citaModel->obtenerMedicos();
        require_once '../views/citas/create.php';
    }

    // Guarda la cita en la BD
    public function store() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $paciente_id = $_SESSION['user_role'] === 'admin' ? ($_POST['paciente_id'] ?? $_SESSION['user_id']) : $_SESSION['user_id'];
            $medico_id = $_POST['medico_id'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $motivo = $_POST['motivo'] ?? '';

            if (empty($medico_id) || empty($fecha) || empty($hora)) {
                $_SESSION['error'] = "Todos los campos son requeridos";
                header("Location: index.php?action=agendar");
                exit();
            }

            if ($this->citaModel->crear($paciente_id, $medico_id, $fecha, $hora, $motivo)) {
                $_SESSION['success'] = "Cita creada exitosamente";
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $_SESSION['error'] = "Error al agendar la cita";
                header("Location: index.php?action=agendar");
                exit();
            }
        }
    }

    // Listar todas las citas (admin)
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        $citas = $this->citaModel->obtenerTodas();
        require_once '../views/citas/index.php';
    }

    // Mostrar formulario de edición
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $_GET['id'] ?? 0;
        $cita = $this->citaModel->obtenerPorId($id);
        
        if (!$cita) {
            $_SESSION['error'] = "Cita no encontrada";
            header("Location: index.php?action=citas");
            exit();
        }

        // Verificar permisos: admin puede editar todas, paciente solo las suyas
        if ($_SESSION['user_role'] !== 'admin' && $cita['paciente_id'] != $_SESSION['user_id']) {
            header("Location: index.php?action=dashboard");
            exit();
        }

        $medicos = $this->citaModel->obtenerMedicos();
        require_once '../views/citas/edit.php';
    }

    // Actualizar cita
    public function update() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $medico_id = $_POST['medico_id'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $motivo = $_POST['motivo'] ?? '';
            $estado = $_POST['estado'] ?? 'pendiente';

            if (empty($medico_id) || empty($fecha) || empty($hora)) {
                $_SESSION['error'] = "Todos los campos son requeridos";
                header("Location: index.php?action=citas_edit&id=" . $id);
                exit();
            }

            if ($this->citaModel->actualizar($id, $medico_id, $fecha, $hora, $motivo, $estado)) {
                $_SESSION['success'] = "Cita actualizada exitosamente";
                header("Location: index.php?action=citas");
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar la cita";
                header("Location: index.php?action=citas_edit&id=" . $id);
                exit();
            }
        }
    }

    // Eliminar cita
    public function delete() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $_GET['id'] ?? 0;

        // Verificar permisos
        if ($_SESSION['user_role'] !== 'admin') {
            $cita = $this->citaModel->obtenerPorId($id);
            if ($cita && $cita['paciente_id'] != $_SESSION['user_id']) {
                header("Location: index.php?action=dashboard");
                exit();
            }
        }

        if ($this->citaModel->eliminar($id)) {
            $_SESSION['success'] = "Cita eliminada exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar la cita";
        }

        header("Location: index.php?action=citas");
        exit();
    }

    // Confirmar cita (paciente)
    public function confirmar() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $_GET['id'] ?? 0;
        $cita = $this->citaModel->obtenerPorId($id);

        // Verificar que la cita pertenece al paciente
        if (!$cita || $cita['paciente_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "No tiene permiso para confirmar esta cita";
            header("Location: index.php?action=dashboard");
            exit();
        }

        if ($this->citaModel->confirmar($id)) {
            $_SESSION['success'] = "Cita confirmada exitosamente";
        } else {
            $_SESSION['error'] = "Error al confirmar la cita";
        }

        header("Location: index.php?action=dashboard");
        exit();
    }

    // Ver detalles de cita y resultados
    public function ver() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $_GET['id'] ?? 0;
        $cita = $this->citaModel->obtenerPorId($id);

        if (!$cita) {
            $_SESSION['error'] = "Cita no encontrada";
            header("Location: index.php?action=dashboard");
            exit();
        }

        // Verificar permisos: paciente solo puede ver sus citas, médico las suyas, admin todas
        if ($_SESSION['user_role'] === 'paciente' && $cita['paciente_id'] != $_SESSION['user_id']) {
            header("Location: index.php?action=dashboard");
            exit();
        }
        if ($_SESSION['user_role'] === 'medico' && $cita['medico_id'] != $_SESSION['user_id']) {
            header("Location: index.php?action=dashboard");
            exit();
        }

        require_once '../views/citas/ver.php';
    }

    // Agregar resultados (médico)
    public function agregarResultados() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'medico') {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $resultados = $_POST['resultados'] ?? '';

            $cita = $this->citaModel->obtenerPorId($id);
            if (!$cita || $cita['medico_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = "No tiene permiso para agregar resultados a esta cita";
                header("Location: index.php?action=dashboard");
                exit();
            }

            if ($this->citaModel->agregarResultados($id, $resultados)) {
                $_SESSION['success'] = "Resultados agregados exitosamente";
            } else {
                $_SESSION['error'] = "Error al agregar resultados. Verifique que la columna 'resultados' existe en la tabla citas.";
            }

            header("Location: index.php?action=dashboard");
            exit();
        }
    }
}
?>