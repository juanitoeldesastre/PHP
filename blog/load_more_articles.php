<?php
// Incluir el archivo de conexión a la base de datos
require_once('db_connection.php');

// Obtener el número de página de la solicitud AJAX
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$articles_per_page = 12;
$offset = ($page - 1) * $articles_per_page;

// Query para obtener los artículos paginados
$sql = "SELECT articles.*, categories.name AS category_name 
        FROM articles 
        INNER JOIN categories ON articles.category_id = categories.id 
        ORDER BY articles.created_at DESC 
        LIMIT $articles_per_page OFFSET $offset";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<a href='article.php?id={$row['id']}' class='article-link'>";
        echo "<div class='article-item'>";
        echo "<h3>{$row['title']}</h3>";
        echo "<p class='meta'>";
        echo "Publicado el " . date('d/m/Y', strtotime($row['created_at']));
        echo "<span class='category-tag'>{$row['category_name']}</span>";
        echo "</p>";
        echo "<p>" . substr($row['content'], 0, 200) . "...</p>"; // Mostrar solo un extracto del contenido
        echo "</div>";
        echo "</a>";
    }
} else {
    echo "<p>No hay más artículos disponibles.</p>";
}

// Liberar el resultado y cerrar la conexión
$result->free();
$conn->close();
?>
