<?php
// Incluir el archivo de conexión a la base de datos
include('db_connection.php');

// Definir el número de artículos por página y la página actual
$articles_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calcular el índice inicial para la consulta SQL según la página actual
$offset = ($current_page - 1) * $articles_per_page;

// Query para obtener los artículos paginados
$sql = "SELECT articles.*, categories.name AS category_name 
        FROM articles 
        INNER JOIN categories ON articles.category_id = categories.id 
        ORDER BY articles.created_at DESC 
        LIMIT $articles_per_page OFFSET $offset";
$result = $conn->query($sql);

// Obtener el total de artículos (para la paginación)
$sql_total = "SELECT COUNT(*) AS total FROM articles";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_articles = $row_total['total'];

// Calcular el total de páginas
$total_pages = ceil($total_articles / $articles_per_page);

// Verificar si se obtuvieron resultados
$articles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Blog</title>
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
        <section class="articles">
            <div class="container">
                <h2>Últimos Artículos</h2>

                <?php
                if (!empty($articles)) {
                    foreach ($articles as $article) {
                        echo "<article>";
                        echo "<a href='article.php?id={$article['id']}' class='article-link'>";
                        echo "<h3>{$article['title']}</h3>";
                        
                        // Mostrar las categorías como etiquetas junto a la fecha de publicación
                        echo "<p class='meta'>Publicado el " . date('d/m/Y', strtotime($article['created_at'])) . " ";

                        // Mostrar las categorías como etiquetas
                        $categories = explode(',', $article['category_name']);
                        foreach ($categories as $category) {
                            echo "<span class='category-tag'>$category</span>";
                        }

                        echo "</p>";
                        echo "<p>" . substr($article['content'], 0, 200) . "...</p>"; // Mostrar solo un extracto del contenido
                        echo "</a>";
                        echo "</article>";
                    }
                } else {
                    echo "<p>No hay artículos disponibles.</p>";
                }
                ?>

                <!-- Paginación -->
                <div class="pagination">
                    <?php if ($total_pages > 1): ?>
                        <?php if ($current_page > 1): ?>
                            <a href="index.php?page=<?php echo $current_page - 1; ?>">Anterior</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $current_page): ?>
                                <span class="current-page"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($current_page < $total_pages): ?>
                            <a href="index.php?page=<?php echo $current_page + 1; ?>">Siguiente</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Mi Blog. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>

<?php
// Liberar el resultado y cerrar la conexión
$result->free();
$result_total->free();
$conn->close();
?>
