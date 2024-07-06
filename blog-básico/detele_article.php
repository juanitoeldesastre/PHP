<?php
require 'db.php';

// Verificar sesión de administrador (ejemplo básico, implementa tu sistema de autenticación adecuado)
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Obtener el ID del artículo a eliminar
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit();
}
$article_id = $_GET['id'];

// Eliminar el artículo de la base de datos
$stmt = $pdo->prepare('DELETE FROM articles WHERE id = :id');
$stmt->execute(['id' => $article_id]);

// Redirigir a la página de administración
header('Location: admin.php');
exit();
?>
