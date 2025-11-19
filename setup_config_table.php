<?php
require_once 'config/db.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    $sql = "CREATE TABLE IF NOT EXISTS configuracion (
        id INT AUTO_INCREMENT PRIMARY KEY,
        clave VARCHAR(50) NOT NULL UNIQUE,
        valor TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $conn->exec($sql);
    echo "Tabla 'configuracion' creada exitosamente.<br>";

    // Insertar datos por defecto si no existen
    $defaults = [
        'nombre_clinica' => 'Policlínico San Gabriel',
        'direccion' => 'Av. Principal 123, Ciudad',
        'telefono' => '(01) 555-1234',
        'email_contacto' => 'contacto@sangabriel.com'
    ];

    foreach ($defaults as $key => $value) {
        $check = $conn->prepare("SELECT id FROM configuracion WHERE clave = :clave");
        $check->execute([':clave' => $key]);
        
        if ($check->rowCount() == 0) {
            $stmt = $conn->prepare("INSERT INTO configuracion (clave, valor) VALUES (:clave, :valor)");
            $stmt->execute([':clave' => $key, ':valor' => $value]);
            echo "Configuración '$key' insertada.<br>";
        }
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
