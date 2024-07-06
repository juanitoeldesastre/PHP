<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículo Especial</title>
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
            <?php
            // Incluir el archivo de conexión a la base de datos
            require_once('db_connection.php');

            // Obtener el ID del artículo especial desde la URL
            $special_article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            // Query para obtener el artículo especial por ID
            $sql = "SELECT * FROM special_articles WHERE id = $special_article_id";
            $result = $conn->query($sql);

            // Verificar si se obtuvieron resultados
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<div class='special-article-detail'>";
                if (isset($row['image']) && !empty($row['image'])) {
                    echo "<img src='{$row['image']}' alt='{$row['title']}' class='special-article-image'>";
                }
                echo "<h2>{$row['title']}</h2>"; 
                echo "<p>{$row['content']}</p>";
                echo "</div>";
            } else {
                echo "<p>Artículo especial no encontrado.</p>";
            }

            // Liberar el resultado y cerrar la conexión
            $result->free();
            $conn->close();
            ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Mi Blog. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
