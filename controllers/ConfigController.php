<?php
require_once '../models/Configuracion.php';

class ConfigController {
    private $db;
    private $config;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->config = new Configuracion($this->db);
    }

    public function index() {
        $settings = $this->config->getAll();
        require_once '../views/configuracion/index.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($_POST as $key => $value) {
                if ($key != 'action') { // Ignorar el parámetro action
                    $this->config->update($key, $value);
                }
            }
            header("Location: index.php?action=settings&success=1");
            exit();
        }
    }
}
?>
