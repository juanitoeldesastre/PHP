<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Aquí iría la lógica para manejar la creación, edición y eliminación de artículos

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Panel de Control</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Panel de Control del Administrador</h1>
        <nav>
            <ul>
                <li><a href="index.php">Ver Blog</a></li>
                <li><a href="create_article.php">Crear Nuevo Artículo</a></li> <!-- Enlace agregado -->
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="admin-section">
        <h2>Gestión de Artículos</h2>
        <p>Esta sección está en desarrollo.</p>
    </section>
</body>
</html>
