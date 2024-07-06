<?php
require 'db.php';

// Verificar sesión de administrador (ejemplo básico, implementa tu sistema de autenticación adecuado)
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Función para obtener todos los artículos
function getAllArticles($pdo) {
    $stmt = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener todos los artículos
$articles = getAllArticles($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <div class="container">
        <h1>Panel de Administración</h1>

        <h2>Crear Nuevo Artículo</h2>
        <a href="create_article.php" class="button">Crear Artículo</a>

        <h2>Editar o Eliminar Artículos</h2>
        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                <p><?php echo htmlspecialchars(substr($article['content'], 0, 100)) ; ?></p>
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Imagen del artículo">
                <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="edit">Editar</a>
                <a href="delete_article.php?id=<?php echo $article['id']; ?>" class="delete">Eliminar</a>
            </div>
        <?php endforeach; ?>
        <div style="clear:both;"></div> <!-- Limpiar el float al final de los artículos -->
    </div>
</body>
</html>
