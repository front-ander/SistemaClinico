<?php

class ChatBot {
    
    private $knowledgeBase = [
        // Sistema / Manual
        'login' => 'Para iniciar sesión, ingresa tu correo y contraseña en la página principal. Si no tienes cuenta, contacta al administrador.',
        'entrar' => 'Para entrar al sistema, usa tus credenciales en la pantalla de Login.',
        'registro' => 'El registro de nuevos usuarios (médicos/pacientes) debe ser realizado por un administrador o desde el panel de gestión correspondiente.',
        'cita' => 'Para agendar una cita: 1. Ve al menú "Citas". 2. Haz clic en "Nueva Cita". 3. Selecciona el paciente, médico y fecha.',
        'agendar' => 'Puedes agendar citas desde el módulo de Citas en el menú principal.',
        'medico' => 'En el módulo "Médicos" puedes ver, agregar, editar o eliminar la información de los doctores de la clínica.',
        'doctor' => 'La gestión de doctores se realiza en la sección "Médicos".',
        'paciente' => 'En el módulo "Pacientes" puedes gestionar toda la información clínica y personal de los pacientes.',
        'dashboard' => 'El Dashboard te muestra un resumen de las citas del día y estadísticas rápidas.',
        'salir' => 'Para cerrar sesión, haz clic en el botón "Cerrar Sesión" o "Logout" en la esquina superior derecha.',
        'logout' => 'Puedes cerrar tu sesión actual desde el menú de usuario.',
        'ayuda' => 'Soy el asistente virtual del sistema. Puedo ayudarte a navegar por las opciones. Pregúntame sobre "citas", "médicos", "pacientes" o "login".',
        
        // Conversación
        'hola' => '¡Hola! ¿En qué puedo ayudarte hoy con el sistema?',
        'buenos dias' => '¡Buenos días! Espero que tengas una excelente jornada. ¿Qué necesitas?',
        'buenas tardes' => '¡Buenas tardes! ¿Cómo puedo asistirte?',
        'buenas noches' => '¡Buenas noches! ¿Necesitas ayuda antes de irte?',
        'gracias' => '¡De nada! Estoy aquí para ayudar.',
        'adios' => '¡Hasta luego! Que tengas un buen día.',
        'quien eres' => 'Soy el asistente virtual de la Clínica San Gabriel. Estoy aquí para guiarte en el uso del sistema.',
    ];

    public function getResponse($message) {
        $message = mb_strtolower(trim($message));
        
        // 1. Búsqueda directa por palabras clave
        foreach ($this->knowledgeBase as $key => $response) {
            if (strpos($message, $key) !== false) {
                return $response;
            }
        }

        // 2. Respuestas por defecto / Fallback
        return "Lo siento, no entendí tu pregunta. Intenta usar palabras clave como 'cita', 'médico', 'paciente' o 'login'.";
    }
}
?>
