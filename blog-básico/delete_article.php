<?php
require 'db.php';

// Verificar sesión de administrador (ejemplo básico, implementa tu sistema de autenticación adecuado)
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Obtener el ID del artículo a eliminar
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin.php');
    exit();
}
$article_id = $_GET['id'];

// Verificar si el artículo existe
$stmt = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
$stmt->execute(['id' => $article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    // Si el artículo no existe, redirigir a la página de administración con un mensaje de error
    header('Location: admin.php?error=Artículo no encontrado');
    exit();
}

// Eliminar el artículo de la base de datos
$stmt = $pdo->prepare('DELETE FROM articles WHERE id = :id');
if ($stmt->execute(['id' => $article_id])) {
    // Mostrar un mensaje de confirmación usando JavaScript
    echo '<script>alert("Artículo eliminado correctamente"); window.location.href = "admin.php";</script>';
} else {
    // Mostrar un mensaje de error usando JavaScript y redirigir a la página de administración
    echo '<script>alert("Error al eliminar el artículo"); window.location.href = "admin.php";</script>';
}
exit();
?>
