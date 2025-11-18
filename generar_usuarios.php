<?php
/**
 * Script para generar usuarios Administradores y Doctores
 * Ejecuta este archivo una vez para insertar los usuarios en la base de datos
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/db.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    die("Error: No se pudo conectar a la base de datos.");
}

echo "<h2>Generando Usuarios Administradores y Doctores</h2>";
echo "<hr>";

try {
    // ============================================
    // 1. INSERTAR ADMINISTRADORES
    // ============================================
    echo "<h3>1. Insertando Administradores...</h3>";
    
    $admins = [
        [
            'nombre' => 'Administrador Principal',
            'email' => 'admin@sangabriel.com',
            'password' => 'admin123',
            'telefono' => '999-888-7777'
        ],
        [
            'nombre' => 'María González',
            'email' => 'maria.admin@sangabriel.com',
            'password' => 'admin123',
            'telefono' => '999-888-7778'
        ]
    ];
    
    foreach ($admins as $admin) {
        // Verificar si ya existe
        $checkQuery = "SELECT id FROM usuarios WHERE email = :email";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(":email", $admin['email']);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            echo "<p style='color: orange;'>⚠ El administrador {$admin['email']} ya existe. Omitiendo...</p>";
            continue;
        }
        
        $password_hash = password_hash($admin['password'], PASSWORD_BCRYPT);
        $query = "INSERT INTO usuarios (nombre_completo, email, password, rol, telefono) 
                  VALUES (:nombre, :email, :password, 'admin', :telefono)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":nombre", $admin['nombre']);
        $stmt->bindParam(":email", $admin['email']);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":telefono", $admin['telefono']);
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✓ Administrador {$admin['nombre']} creado exitosamente</p>";
        } else {
            echo "<p style='color: red;'>✗ Error al crear {$admin['nombre']}</p>";
        }
    }
    
    // ============================================
    // 2. INSERTAR DOCTORES
    // ============================================
    echo "<hr><h3>2. Insertando Doctores...</h3>";
    
    $doctores = [
        // Medicina General
        [
            'nombre' => 'Dr. Carlos Ramírez',
            'email' => 'carlos.ramirez@sangabriel.com',
            'password' => 'doctor123',
            'telefono' => '999-111-0001',
            'especialidad' => 'Medicina General'
        ],
        [
            'nombre' => 'Dra. Ana Martínez',
            'email' => 'ana.martinez@sangabriel.com',
            'password' => 'doctor123',
            'telefono' => '999-111-0002',
            'especialidad' => 'Medicina General'
        ],
        // Pediatría
        [
            'nombre' => 'Dr. Luis Fernández',
            'email' => 'luis.fernandez@sangabriel.com',
            'password' => 'doctor123',
            'telefono' => '999-111-0003',
            'especialidad' => 'Pediatría'
        ],
        [
            'nombre' => 'Dra. Sofía Herrera',
            'email' => 'sofia.herrera@sangabriel.com',
            'password' => 'doctor123',
            'telefono' => '999-111-0004',
            'especialidad' => 'Pediatría'
        ],
        // Odontología
        [
            'nombre' => 'Dr. Roberto Sánchez',
            'email' => 'roberto.sanchez@sangabriel.com',
            'password' => 'doctor123',
            'telefono' => '999-111-0005',
            'especialidad' => 'Odontología'
        ],
        [
            'nombre' => 'Dra. Carmen López',
            'email' => 'carmen.lopez@sangabriel.com',
            'password' => 'doctor123',
            'telefono' => '999-111-0006',
            'especialidad' => 'Odontología'
        ]
    ];
    
    foreach ($doctores as $doctor) {
        // Verificar si ya existe
        $checkQuery = "SELECT id FROM usuarios WHERE email = :email";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(":email", $doctor['email']);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            echo "<p style='color: orange;'>⚠ El doctor {$doctor['email']} ya existe. Omitiendo...</p>";
            continue;
        }
        
        // Insertar el usuario médico
        $password_hash = password_hash($doctor['password'], PASSWORD_BCRYPT);
        $query = "INSERT INTO usuarios (nombre_completo, email, password, rol, telefono) 
                  VALUES (:nombre, :email, :password, 'medico', :telefono)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":nombre", $doctor['nombre']);
        $stmt->bindParam(":email", $doctor['email']);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":telefono", $doctor['telefono']);
        
        if ($stmt->execute()) {
            $usuario_id = $conn->lastInsertId();
            
            // Obtener el ID de la especialidad
            $espQuery = "SELECT id FROM especialidades WHERE nombre = :nombre LIMIT 1";
            $espStmt = $conn->prepare($espQuery);
            $espStmt->bindParam(":nombre", $doctor['especialidad']);
            $espStmt->execute();
            
            if ($espStmt->rowCount() > 0) {
                $especialidad = $espStmt->fetch(PDO::FETCH_ASSOC);
                $especialidad_id = $especialidad['id'];
                
                // Insertar en medicos_info
                $infoQuery = "INSERT INTO medicos_info (usuario_id, especialidad_id) 
                              VALUES (:usuario_id, :especialidad_id)";
                $infoStmt = $conn->prepare($infoQuery);
                $infoStmt->bindParam(":usuario_id", $usuario_id);
                $infoStmt->bindParam(":especialidad_id", $especialidad_id);
                
                if ($infoStmt->execute()) {
                    echo "<p style='color: green;'>✓ Doctor {$doctor['nombre']} ({$doctor['especialidad']}) creado exitosamente</p>";
                } else {
                    echo "<p style='color: red;'>✗ Error al asignar especialidad a {$doctor['nombre']}</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ Error: Especialidad '{$doctor['especialidad']}' no encontrada</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Error al crear {$doctor['nombre']}</p>";
        }
    }
    
    // ============================================
    // 3. RESUMEN
    // ============================================
    echo "<hr><h3>3. Resumen</h3>";
    
    // Contar administradores
    $countAdmin = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'admin'");
    $adminCount = $countAdmin->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total Administradores:</strong> {$adminCount['total']}</p>";
    
    // Contar médicos
    $countMedico = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'medico'");
    $medicoCount = $countMedico->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total Médicos:</strong> {$medicoCount['total']}</p>";
    
    // Listar médicos con especialidades
    echo "<h4>Médicos y sus Especialidades:</h4>";
    $medicosQuery = "SELECT u.nombre_completo, e.nombre as especialidad 
                     FROM usuarios u
                     JOIN medicos_info m ON u.id = m.usuario_id
                     JOIN especialidades e ON m.especialidad_id = e.id
                     WHERE u.rol = 'medico'
                     ORDER BY e.nombre, u.nombre_completo";
    $medicosStmt = $conn->query($medicosQuery);
    echo "<ul>";
    while ($medico = $medicosStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>{$medico['nombre_completo']} - {$medico['especialidad']}</li>";
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✓ Proceso completado exitosamente</h3>";
    echo "<p><strong>Credenciales por defecto:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Administradores:</strong> Email = admin@sangabriel.com, Password = admin123</li>";
    echo "<li><strong>Doctores:</strong> Email = [email del doctor], Password = doctor123</li>";
    echo "</ul>";
    echo "<p style='color: red;'><strong>⚠ IMPORTANTE:</strong> Cambia las contraseñas después del primer inicio de sesión.</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

