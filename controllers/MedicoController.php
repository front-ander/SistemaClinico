<?php
require_once '../config/db.php';
require_once '../models/Medico.php';

class MedicoController {
    private $db;
    private $medicoModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->medicoModel = new Medico($this->db);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Listar todos los médicos
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        $medicos = $this->medicoModel->obtenerTodos();
        require_once '../views/medicos/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        $especialidades = $this->medicoModel->obtenerEspecialidades();
        require_once '../views/medicos/create.php';
    }

    // Guardar nuevo médico
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $especialidad_id = $_POST['especialidad_id'] ?? '';

            if (empty($nombre) || empty($email) || empty($password) || empty($especialidad_id)) {
                $_SESSION['error'] = "Todos los campos son requeridos";
                header("Location: index.php?action=medicos_create");
                exit();
            }

            if ($this->medicoModel->crear($nombre, $email, $password, $telefono, $especialidad_id)) {
                $_SESSION['success'] = "Médico creado exitosamente";
                header("Location: index.php?action=medicos");
                exit();
            } else {
                $_SESSION['error'] = "Error al crear el médico. El email puede estar en uso.";
                header("Location: index.php?action=medicos_create");
                exit();
            }
        }
    }

    // Mostrar formulario de edición
    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $_GET['id'] ?? 0;
        $medico = $this->medicoModel->obtenerPorId($id);
        
        if (!$medico) {
            $_SESSION['error'] = "Médico no encontrado";
            header("Location: index.php?action=medicos");
            exit();
        }

        $especialidades = $this->medicoModel->obtenerEspecialidades();
        require_once '../views/medicos/edit.php';
    }

    // Actualizar médico
    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $especialidad_id = $_POST['especialidad_id'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($nombre) || empty($email) || empty($especialidad_id)) {
                $_SESSION['error'] = "Los campos nombre, email y especialidad son requeridos";
                header("Location: index.php?action=medicos_edit&id=" . $id);
                exit();
            }

            $result = $this->medicoModel->actualizar($id, $nombre, $email, $telefono, $especialidad_id, $password);

            if ($result) {
                $_SESSION['success'] = "Médico actualizado exitosamente";
                header("Location: index.php?action=medicos");
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar el médico. El email puede estar en uso.";
                header("Location: index.php?action=medicos_edit&id=" . $id);
                exit();
            }
        }
    }

    // Eliminar médico
    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $_GET['id'] ?? 0;

        if ($this->medicoModel->eliminar($id)) {
            $_SESSION['success'] = "Médico eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el médico. Puede tener citas asociadas.";
        }

        header("Location: index.php?action=medicos");
        exit();
    }

    // Gestionar horarios (Vista)
    public function horarios() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'medico') {
            header("Location: index.php?action=login");
            exit();
        }

        $horarios = $this->medicoModel->obtenerHorarios($_SESSION['user_id']);
        // Organizar horarios por día para fácil acceso en la vista
        $horarios_map = [];
        foreach ($horarios as $h) {
            $horarios_map[$h['dia_semana']] = $h;
        }
        
        require_once '../views/medicos/horarios.php';
    }

    // Guardar horarios
    public function guardar_horarios() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'medico') {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $success = true;

            foreach ($dias as $dia) {
                // Verificar si el día fue marcado como activo
                $activo = isset($_POST['activo'][$dia]) ? 1 : 0;
                $inicio = $_POST['inicio'][$dia] ?? '09:00';
                $fin = $_POST['fin'][$dia] ?? '17:00';

                if (!$this->medicoModel->guardarHorario($_SESSION['user_id'], $dia, $inicio, $fin, $activo)) {
                    $success = false;
                }
            }

            if ($success) {
                $_SESSION['success'] = "Horarios actualizados exitosamente";
            } else {
                $_SESSION['error'] = "Hubo un problema al guardar algunos horarios";
            }

            header("Location: index.php?action=horarios");
            exit();
        }
    }
}
?>

