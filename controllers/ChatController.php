<?php
require_once '../models/ChatBot.php';

class ChatController {
    private $chatBot;

    public function __construct() {
        $this->chatBot = new ChatBot();
    }

    public function handleRequest() {
        // Establecer cabecera JSON
        header('Content-Type: application/json');

        // Obtener el cuerpo de la solicitud (JSON)
        $input = json_decode(file_get_contents('php://input'), true);
        
        $message = isset($input['message']) ? $input['message'] : '';
        
        if (empty($message)) {
            echo json_encode(['response' => 'Por favor escribe algo.']);
            return;
        }

        // Obtener respuesta del modelo
        $response = $this->chatBot->getResponse($message);
        
        echo json_encode(['response' => $response]);
        exit;
    }
}
?>
