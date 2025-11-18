<?php
require_once '../config/db.php';
require_once '../models/Paciente.php';

class PacienteController {
    private $db;
    private $pacienteModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pacienteModel = new Paciente($this->db);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Listar todos los pacientes
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        $pacientes = $this->pacienteModel->obtenerTodos();
        require_once '../views/pacientes/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        require_once '../views/pacientes/create.php';
    }

    // Guardar nuevo paciente
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

            if (empty($nombre) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Los campos nombre, email y contraseña son requeridos";
                header("Location: index.php?action=pacientes_create");
                exit();
            }

            if ($this->pacienteModel->crear($nombre, $email, $password, $telefono)) {
                $_SESSION['success'] = "Paciente creado exitosamente";
                header("Location: index.php?action=pacientes");
                exit();
            } else {
                $_SESSION['error'] = "Error al crear el paciente. El email puede estar en uso.";
                header("Location: index.php?action=pacientes_create");
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
        $paciente = $this->pacienteModel->obtenerPorId($id);
        
        if (!$paciente) {
            $_SESSION['error'] = "Paciente no encontrado";
            header("Location: index.php?action=pacientes");
            exit();
        }

        require_once '../views/pacientes/edit.php';
    }

    // Actualizar paciente
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
            $password = $_POST['password'] ?? '';

            if (empty($nombre) || empty($email)) {
                $_SESSION['error'] = "Los campos nombre y email son requeridos";
                header("Location: index.php?action=pacientes_edit&id=" . $id);
                exit();
            }

            $result = $this->pacienteModel->actualizar($id, $nombre, $email, $telefono, $password);

            if ($result) {
                $_SESSION['success'] = "Paciente actualizado exitosamente";
                header("Location: index.php?action=pacientes");
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar el paciente. El email puede estar en uso.";
                header("Location: index.php?action=pacientes_edit&id=" . $id);
                exit();
            }
        }
    }

    // Eliminar paciente
    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $_GET['id'] ?? 0;

        if ($this->pacienteModel->eliminar($id)) {
            $_SESSION['success'] = "Paciente eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el paciente. Puede tener citas asociadas.";
        }

        header("Location: index.php?action=pacientes");
        exit();
    }
}
?>

