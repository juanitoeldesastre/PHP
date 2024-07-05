<?php
// Suponemos que recibimos el ID del artículo a mostrar a través de GET
if (isset($_GET['id'])) {
    // Aquí deberías incluir tu lógica de conexión a la base de datos y consulta
    // Por simplicidad, simulamos la obtención de un artículo por su ID
    $article_id = $_GET['id'];

    // Incluir el archivo de conexión a la base de datos
    include('db_connection.php');

    // Query para obtener el artículo específico
    $sql = "SELECT * FROM articles WHERE id = $article_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
    } else {
        // Si no se encuentra el artículo, redirigir al índice o mostrar un mensaje de error
        header("Location: index.php");
        exit();
    }

    // Liberar el resultado y cerrar la conexión
    $result->free();
    $conn->close();
} else {
    // Si no se proporciona un ID válido, redirigir al índice o mostrar un mensaje de error
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Mi Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Mi Blog</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="admin.php">Administrar</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <article>
            <h2><?php echo htmlspecialchars($article['title']); ?></h2>
            <p><?php echo htmlspecialchars($article['content']); ?></p>
        </article>

        <section class="comments">
            <h3>Comentarios</h3>
            <!-- Aquí podrías incluir la lógica para mostrar los comentarios del artículo -->
            <!-- Esto dependerá de cómo estructures y guardes los comentarios en la base de datos -->
            <!-- Por ejemplo, podrías hacer una consulta para obtener los comentarios asociados a este artículo -->
            <?php
            // Aquí se debería incluir la lógica para mostrar los comentarios del artículo
            // Por simplicidad, simulamos que no hay comentarios en este ejemplo
            ?>
            <p>No hay comentarios aún.</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Mi Blog. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
