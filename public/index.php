<?php
// Habilitar mostrar errores (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar output buffering para evitar problemas con headers
ob_start();

// 1. Cargar Controladores
require_once '../controllers/AuthController.php';
require_once '../controllers/DashboardController.php';
require_once '../controllers/CitaController.php';
require_once '../controllers/MedicoController.php';
require_once '../controllers/PacienteController.php';

// 2. Capturar la acción de la URL (por defecto 'login')
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// 3. Instanciar Controladores
$auth = new AuthController();
$dashboard = new DashboardController();
$citaController = new CitaController();
$medicoController = new MedicoController();
$pacienteController = new PacienteController();

// 4. Enrutamiento (Switch)
switch ($action) {
    case 'login':
        $auth->login();
        break;
    case 'register':
        $auth->register();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'dashboard':
        // Delegamos la lógica de qué dashboard mostrar al controlador
        $dashboard->index(); 
        break;
    case 'agendar':
        $citaController->create();
        break;
    case 'guardar_cita':
        $citaController->store();
        break;
    // CRUD Médicos
    case 'medicos':
        $medicoController->index();
        break;
    case 'medicos_create':
        $medicoController->create();
        break;
    case 'medicos_store':
        $medicoController->store();
        break;
    case 'medicos_edit':
        $medicoController->edit();
        break;
    case 'medicos_update':
        $medicoController->update();
        break;
    case 'medicos_delete':
        $medicoController->delete();
        break;
    // CRUD Pacientes
    case 'pacientes':
        $pacienteController->index();
        break;
    case 'pacientes_create':
        $pacienteController->create();
        break;
    case 'pacientes_store':
        $pacienteController->store();
        break;
    case 'pacientes_edit':
        $pacienteController->edit();
        break;
    case 'pacientes_update':
        $pacienteController->update();
        break;
    case 'pacientes_delete':
        $pacienteController->delete();
        break;
    // CRUD Citas
    case 'citas':
        $citaController->index();
        break;
    case 'citas_edit':
        $citaController->edit();
        break;
    case 'citas_update':
        $citaController->update();
        break;
    case 'citas_delete':
        $citaController->delete();
        break;
    case 'citas_confirmar':
        $citaController->confirmar();
        break;
    case 'citas_ver':
        $citaController->ver();
        break;
    case 'citas_agregar_resultados':
        $citaController->agregarResultados();
        break;
    default:
        // Página 404 o redirigir a login
        header("Location: index.php?action=login");
        exit();
        break;
}
?>