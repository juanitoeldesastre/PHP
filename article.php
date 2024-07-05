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
        <div class="container">
            <h1>Mi Blog</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="admin.php">Administrar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <article>
                <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                <p><?php echo htmlspecialchars($article['content']); ?></p>
            </article>

            <section class="comments">
                <h3>Comentarios</h3>
                <?php
                // Incluir el archivo de conexión a la base de datos
                include('db_connection.php');

                // Query para obtener los comentarios asociados al artículo
                $sql_comments = "SELECT * FROM comments WHERE article_id = $article_id ORDER BY created_at DESC";
                $result_comments = $conn->query($sql_comments);

                if ($result_comments->num_rows > 0) {
                    while ($comment = $result_comments->fetch_assoc()) {
                        echo "<div class='comment'>";
                        echo "<p><strong>" . htmlspecialchars($comment['author']) . "</strong> dijo:</p>";
                        echo "<p>" . htmlspecialchars($comment['content']) . "</p>";
                        echo "<p class='meta'>Publicado el " . date('d/m/Y H:i', strtotime($comment['created_at'])) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay comentarios aún.</p>";
                }

                // Liberar el resultado y cerrar la conexión
                $result_comments->free();
                $conn->close();
                ?>
            </section>

            <section class="add-comment">
                <h3>Agregar un comentario</h3>
                <form action="add_comment.php" method="POST">
                    <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                    <div class="form-group">
                        <label for="author">Nombre:</label>
                        <input type="text" id="author" name="author" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Comentario:</label>
                        <textarea id="content" name="content" rows="4" required></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Mi Blog. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
