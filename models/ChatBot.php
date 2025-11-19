<?php

class ChatBot {
    
    private $knowledgeBase = [
        // Saludos y Cortesía
        'hola' => '¡Hola! Es un gusto saludarte. Soy tu asistente virtual de la Clínica San Gabriel. ¿En qué puedo ayudarte hoy?',
        'buenos dias' => '¡Muy buenos días! Espero que se encuentre bien. Dígame, ¿en qué le puedo colaborar?',
        'buenas tardes' => '¡Buenas tardes! Estoy aquí para ayudarle con lo que necesite del sistema.',
        'buenas noches' => '¡Buenas noches! Si tiene alguna duda antes de descansar, aquí estoy para resolverla.',
        'gracias' => '¡Es un placer ayudarle! No dude en preguntarme si necesita algo más.',
        'adios' => '¡Hasta luego! Cuídese mucho y que tenga un excelente día.',
        'quien eres' => 'Soy el asistente virtual de la Clínica San Gabriel. Mi trabajo es guiarle paso a paso para que use el sistema sin problemas.',

        // Gestión de Citas (Pacientes)
        'agendar' => 'Para pedir una cita, es muy sencillo: 1. Busque en el menú la opción "Nueva Cita". 2. Elija el médico que prefiere y la fecha. 3. El sistema le mostrará si el doctor está disponible. ¡Y listo!',
        'cita' => 'Si desea sacar una cita, vaya al botón "Nueva Cita". Recuerde que ahora el sistema revisa automáticamente si el doctor está libre para que no haya confusiones.',
        'no puedo agendar' => 'Si no puede agendar, puede ser que el médico no trabaje ese día o ya tenga otra cita a esa hora. Por favor, intente probar con otro horario o día.',
        'confirmar' => 'Para confirmar que asistirá a su cita: Vaya a "Mis Citas" o "Inicio", busque la cita que dice "Pendiente" y presione el botón verde que dice "Confirmar". Así el doctor sabrá que usted irá.',
        'historial' => 'En la opción "Historial" o "Mis Citas" puede ver todas las consultas que ha tenido anteriormente y las que vienen a futuro.',

        // Gestión de Médicos (Doctores)
        'horario' => 'Doctor, para configurar cuándo trabaja: 1. Vaya a "Gestionar Horarios" en el menú de la izquierda. 2. Marque los días que vendrá. 3. Ponga la hora de entrada y salida. ¡No olvide guardar!',
        'disponibilidad' => 'Usted decide su tiempo. En "Gestionar Horarios" puede activar o desactivar los días que va a trabajar. Los pacientes solo podrán agendarle en las horas que usted defina.',
        'resultados' => 'Para anotar lo que pasó en la consulta: 1. Vaya a su "Inicio". 2. Busque la cita del paciente y dele clic al botón azul "Ver". 3. Abajo verá un espacio para escribir los "Resultados" y guardarlos.',
        'pacientes' => 'En la sección "Pacientes" puede ver la lista de todas las personas registradas en la clínica.',

        // Administración y General
        'login' => 'Para entrar al sistema, necesita su correo y su contraseña. Si se le olvidó o no puede entrar, por favor pídale ayuda al administrador de la clínica.',
        'registro' => 'Si es un paciente nuevo, puede registrarse en la pantalla de inicio donde dice "Registrarse". Si es médico, el administrador debe crearle su cuenta.',
        'contraseña' => 'Si olvidó su clave, comuníquese con la recepción o el administrador para que le ayuden a recuperarla.',
        'salir' => 'Para salir del sistema de forma segura, busque el botón rojo que dice "Salir" o "Cerrar Sesión" en el menú de la izquierda.',
        'ayuda' => 'Estoy aquí para servirle. Pregúnteme cosas como: "¿Cómo agendo una cita?", "¿Cómo pongo mi horario?" (si es médico), o "¿Cómo veo mis citas?".',
        'reporte' => 'Los reportes son para los administradores. Pueden sacar listas de citas, médicos y pacientes en PDF desde el menú "Reporte-PDF".'
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
