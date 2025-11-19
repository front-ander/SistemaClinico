<?php
// Habilitar mostrar errores (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar output buffering para evitar problemas con headers
ob_start();

// SEGURIDAD: Definir ruta base del proyecto
define('BASE_PATH', realpath(__DIR__ . '/..'));

// 1. Cargar Controladores
require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/DashboardController.php';
require_once BASE_PATH . '/controllers/CitaController.php';
require_once BASE_PATH . '/controllers/MedicoController.php';
require_once BASE_PATH . '/controllers/PacienteController.php';
require_once BASE_PATH . '/controllers/ChatController.php';
require_once BASE_PATH . '/controllers/ConfigController.php';
require_once BASE_PATH . '/controllers/ReporteController.php';

// 2. Lista de acciones permitidas (whitelist) - PROTECCIÓN CONTRA PATH TRAVERSAL
$allowedActions = [
    'login', 'register', 'logout', 'dashboard', 'agendar', 'guardar_cita',
    'medicos', 'medicos_create', 'medicos_store', 'medicos_edit', 'medicos_update', 'medicos_delete',
    'pacientes', 'pacientes_create', 'pacientes_store', 'pacientes_edit', 'pacientes_update', 'pacientes_delete',
    'citas', 'citas_edit', 'citas_update', 'citas_delete', 'citas_confirmar', 'citas_ver', 'citas_agregar_resultados',
    'chat_response', 'settings', 'settings_update', 'reportes', 'reportes_ver'
];

// 3. Capturar y validar la acción de la URL (por defecto 'login')
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// SEGURIDAD: Validar que la acción esté en la whitelist - PREVIENE PATH TRAVERSAL
if (!in_array($action, $allowedActions, true)) {
    error_log("Intento de acceso a acción no permitida: " . $action);
    $action = 'login';
}

// SEGURIDAD: Sanitizar IDs numéricos en GET
if (isset($_GET['id']) && !is_numeric($_GET['id'])) {
    $_GET['id'] = 0;
}

// 3. Instanciar Controladores
$auth = new AuthController();
$dashboard = new DashboardController();
$citaController = new CitaController();
$medicoController = new MedicoController();
$pacienteController = new PacienteController();
$chatController = new ChatController();
$configController = new ConfigController();
$reporteController = new ReporteController();

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
    case 'chat_response':
        $chatController->handleRequest();
        break;
    // Configuración
    case 'settings':
        $configController->index();
        break;
    case 'settings_update':
        $configController->update();
        break;
    // Reportes
    case 'reportes':
        $reporteController->index();
        break;
    case 'reportes_ver':
        $reporteController->generate();
        break;
    default:
        // Página 404 o redirigir a login
        header("Location: index.php?action=login");
        exit();
        break;
}
?>