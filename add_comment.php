<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Incluir el archivo de conexión a la base de datos
    include('db_connection.php');

    // Obtener los datos del formulario
    $article_id = $_POST['article_id'];
    $author = $_POST['author'];
    $content = $_POST['content'];

    // Query para insertar el nuevo comentario
    $sql = "INSERT INTO comments (article_id, author, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $article_id, $author, $content);

    if ($stmt->execute()) {
        // Redirigir de vuelta al artículo después de agregar el comentario
        header("Location: article.php?id=$article_id");
    } else {
        echo "Error al agregar el comentario: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si el formulario no fue enviado correctamente, redirigir al índice
    header("Location: index.php");
}
?>
