# Sistema Clínica San Gabriel - Manual de Usuario y Documentación Técnica

Bienvenido al **Sistema de Gestión Clínica San Gabriel**. Este sistema integral permite la administración eficiente de pacientes, médicos, citas y reportes, facilitando la interacción entre el personal médico y los pacientes.

## 📋 Características Principales

El sistema cuenta con tres roles principales, cada uno con funcionalidades específicas:

### 1. 👨‍💼 Administrador
El administrador tiene control total sobre el sistema.
*   **Gestión de Usuarios**:
    *   **Médicos**: Registrar, editar y eliminar médicos. Asignar especialidades.
    *   **Pacientes**: Registrar, editar y eliminar pacientes.
*   **Gestión de Citas**: Ver todas las citas, agendar nuevas citas para pacientes, editar y cancelar citas.
*   **Reportes**: Generar reportes en PDF de:
    *   Listado de Citas.
    *   Listado de Pacientes.
    *   Listado de Médicos.
*   **Configuración**: Modificar la información de la clínica (Nombre, Dirección, Teléfono, Logo) que aparece en el sistema y reportes.

### 2. 👨‍⚕️ Médico
El médico gestiona su propia agenda y atención.
*   **Dashboard**: Vista rápida de citas pendientes y estadísticas del día.
*   **Gestión de Citas**:
    *   Ver listado de citas asignadas.
    *   Ver detalles del paciente.
    *   **Agregar Resultados**: Registrar notas y resultados de la consulta.
*   **Gestión de Horarios**:
    *   Configurar disponibilidad semanal (días y horas de atención).
    *   Activar/Desactivar días específicos.

### 3. 👤 Paciente
El paciente puede autogestionar sus citas.
*   **Registro e Inicio de Sesión**: Creación de cuenta personal.
*   **Agendar Citas**:
    *   Seleccionar médico y especialidad.
    *   Verificar disponibilidad en tiempo real (evita conflictos de horario).
*   **Mis Citas**:
    *   Ver historial de citas.
    *   Ver próximas citas.
    *   Confirmar asistencia.
*   **Chatbot IA**: Asistente virtual para resolver dudas frecuentes.

---

## 🚀 Instalación y Configuración

### Requisitos Previos
*   Servidor Web (Apache/Nginx).
*   PHP 7.4 o superior.
*   MySQL / MariaDB.
*   Composer (para dependencias de PDF, si aplica).

### Pasos de Instalación
1.  **Base de Datos**:
    *   Crear una base de datos llamada `san_gabriel_db`.
    *   Importar el archivo SQL de estructura inicial (si existe) o asegurarse de que las migraciones se hayan ejecutado.
    *   Tablas principales: `usuarios`, `medicos_info`, `especialidades`, `citas`, `horarios_medicos`, `configuracion`.

2.  **Configuración de Conexión**:
    *   Verificar el archivo `config/db.php` y ajustar las credenciales de la base de datos:
        ```php
        private $host = "localhost";
        private $db_name = "san_gabriel_db";
        private $username = "root";
        private $password = "";
        ```

3.  **Usuarios Iniciales (Seeders)**:
    *   Puede usar el script `insert_admin_doctores.sql` para poblar la base de datos con administradores y médicos de prueba.
    *   **Admin por defecto**: `admin@sangabriel.com` / `admin123`
    *   **Médico por defecto**: `carlos.ramirez@sangabriel.com` / `doctor123`

---

## 📖 Guía de Uso Rápida

### Para el Administrador
1.  **Configurar la Clínica**: Vaya a "Configuración" en el menú lateral para establecer el nombre y logo.
2.  **Registrar Personal**: En "Gestión de Médicos", agregue a los doctores y sus especialidades.
3.  **Ver Reportes**: Use la sección "Reportes" para descargar resúmenes en PDF.

### Para el Médico
1.  **Configurar Horario**: Es **CRUCIAL** que lo primero que haga sea ir a "Gestionar Horarios" y definir sus horas de trabajo. Sin esto, los pacientes no podrán agendarle citas.
2.  **Atender Citas**: En su Dashboard, haga clic en "Ver" en una cita para revisar los detalles y "Agregar Resultados" al finalizar la consulta.

### Para el Paciente
1.  **Agendar**: Haga clic en "Nueva Cita", seleccione el médico y la hora. El sistema le avisará si el médico no está disponible.
2.  **Confirmar**: Si ve una cita en estado "Pendiente", puede confirmarla para asegurar su asistencia.

---

## 📂 Estructura del Proyecto

*   `config/`: Archivos de configuración (Base de datos).
*   `controllers/`: Lógica de negocio (CitaController, MedicoController, etc.).
*   `models/`: Acceso a datos y reglas de negocio (Cita, Usuario, Medico).
*   `views/`: Interfaz de usuario (HTML/PHP).
    *   `layouts/`: Cabecera, pie de página, barras laterales.
    *   `citas/`, `medicos/`, `pacientes/`, `dashboard/`: Vistas específicas.
*   `public/`: Archivos accesibles vía web (CSS, JS, index.php).
*   `helpers/`: Funciones auxiliares (Seguridad, Formato).

---

## 🔒 Seguridad

*   **Protección de Rutas**: Sistema de "Whitelist" en `index.php` para prevenir Path Traversal.
*   **Contraseñas**: Almacenamiento seguro usando `password_hash` (Bcrypt).
*   **Sesiones**: Control de acceso basado en roles (Admin, Médico, Paciente).
*   **Validación**: Verificación de disponibilidad de horarios en el servidor para evitar sobrecupos.

---

© 2025 Clínica San Gabriel - Todos los derechos reservados.
