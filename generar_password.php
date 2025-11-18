<?php
/**
 * Script simple para generar contraseñas hasheadas
 * Úsalo cuando necesites generar un hash para una contraseña nueva
 */

// Configuración
$password = isset($_GET['password']) ? $_GET['password'] : 'admin123';

// Generar hash
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "<h2>Generador de Contraseñas Hasheadas</h2>";
echo "<hr>";
echo "<p><strong>Contraseña original:</strong> {$password}</p>";
echo "<p><strong>Hash generado:</strong></p>";
echo "<textarea style='width: 100%; height: 100px; font-family: monospace;'>{$hash}</textarea>";
echo "<hr>";
echo "<p><strong>Uso:</strong> Copia el hash y úsalo en tu script SQL o PHP</p>";
echo "<p><strong>Ejemplo SQL:</strong></p>";
echo "<pre style='background: #f4f4f4; padding: 10px;'>";
echo "INSERT INTO usuarios (nombre_completo, email, password, rol) VALUES\n";
echo "('Nombre Usuario', 'email@ejemplo.com', '{$hash}', 'admin');";
echo "</pre>";
echo "<hr>";
echo "<p><strong>Generar otro hash:</strong> Agrega ?password=tu_contraseña a la URL</p>";
?>

