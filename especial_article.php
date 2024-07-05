<?php
// Incluir el archivo de conexión a la base de datos
require_once('db_connection.php');

// Query para obtener los artículos especiales
$sql = "SELECT * FROM special_articles ORDER BY created_at DESC";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='special-article-item'>";
        echo "<img src='{$row['image']}' alt='{$row['title']}'>";
        echo "<div class='special-article-content'>";
        echo "<h2>{$row['title']}</h2>";
        echo "<p>" . substr($row['summary'], 0, 300) . "...</p>"; // Mostrar solo un extracto del resumen
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>No hay artículos especiales disponibles.</p>";
}

// Liberar el resultado y cerrar la conexión
$result->free();
$conn->close();
?>


