<?php
require_once '../config/db.php';
require_once '../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if ($this->db === null) {
            die("No se pudo establecer conexión con la base de datos.");
        }
        $this->user = new User($this->db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $loggedInUser = $this->user->login($email, $password);
            
            if ($loggedInUser) {
                session_start();
                $_SESSION['user_id'] = $loggedInUser['id'];
                $_SESSION['user_name'] = $loggedInUser['nombre_completo'];
                $_SESSION['user_role'] = $loggedInUser['rol'];
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $error = "Credenciales incorrectas";
                require_once '../views/auth/login.php';
            }
        } else {
            require_once '../views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar que los campos estén presentes
            if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['password'])) {
                $error = "Todos los campos son requeridos.";
                require_once '../views/auth/register.php';
                return;
            }
            
            // Lógica simple de registro
            $result = $this->user->register($_POST['nombre'], $_POST['email'], $_POST['password']);
            
            if($result){
                // Limpiar buffer y redirigir al login con mensaje de éxito
                ob_clean();
                header("Location: index.php?action=login&success=1");
                exit();
            } else {
                $error = "Error al registrar. El email puede estar en uso o hubo un problema con la base de datos.";
                require_once '../views/auth/register.php';
            }
        } else {
            require_once '../views/auth/register.php';
        }
    }
    
    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}
?>