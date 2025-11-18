<?php
// Script de prueba para verificar la conexión a la base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Prueba de Conexión a Base de Datos</h2>";

require_once 'config/db.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✓ Conexión exitosa a la base de datos</p>";
        
        // Verificar si la tabla usuarios existe
        $query = "SHOW TABLES LIKE 'usuarios'";
        $stmt = $conn->query($query);
        
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ La tabla 'usuarios' existe</p>";
            
            // Contar usuarios
            $countQuery = "SELECT COUNT(*) as total FROM usuarios";
            $countStmt = $conn->query($countQuery);
            $count = $countStmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>Total de usuarios en la base de datos: <strong>" . $count['total'] . "</strong></p>";
            
            // Mostrar estructura de la tabla
            echo "<h3>Estructura de la tabla usuarios:</h3>";
            $descQuery = "DESCRIBE usuarios";
            $descStmt = $conn->query($descQuery);
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $descStmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . $row['Default'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>✗ La tabla 'usuarios' NO existe</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ No se pudo establecer conexión</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>

