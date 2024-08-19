<?php
// db.php - Manejo de la conexión a la base de datos

$host = 'localhost';  // Cambia esto si tu base de datos está en otro servidor
$dbname = 'blog_db';  // Nombre de la base de datos
$username = 'root';  // Tu usuario de MySQL
$password = '';  // Tu contraseña de MySQL

try {
    // Crear la conexión usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurar PDO para que lance excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Manejo de errores en caso de fallo de conexión
    die("Error de conexión: " . $e->getMessage());
}
?>
