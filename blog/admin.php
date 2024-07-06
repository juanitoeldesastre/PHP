<?php
// Aquí deberías incluir la lógica de autenticación y permisos de administrador
// Suponemos que ya se ha iniciado sesión como administrador

// Conexión a la base de datos (ejemplo básico)
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "blog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para obtener todos los artículos
function getArticles($conn) {
    $sql = "SELECT * FROM articles";
    $result = $conn->query($sql);
    $articles = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
    }
    return $articles;
}

// Función para obtener todos los comentarios
function getComments($conn) {
    $sql = "SELECT * FROM comments";
    $result = $conn->query($sql);
    $comments = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
    }
    return $comments;
}

// Función para obtener todas las categorías
function getCategories($conn) {
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);
    $categories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    return $categories;
}

// Función para obtener todas las etiquetas
function getTags($conn) {
    $sql = "SELECT * FROM tags";
    $result = $conn->query($sql);
    $tags = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tags[] = $row;
        }
    }
    return $tags;
}

// Ejemplo de uso de las funciones para obtener datos
$articles = getArticles($conn);
$comments = getComments($conn);
$categories = getCategories($conn);
$tags = getTags($conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Mi Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
        <nav>
            <ul>
                <li><a href="index.php">Ver Blog</a></li>
                <!-- Enlaces adicionales según las funciones que implementes -->
            </ul>
        </nav>
    </header>

    <main>
        <section class="manage-articles">
            <h2>Artículos</h2>
            <ul>
                <?php foreach ($articles as $article): ?>
                    <li>
                        <?php echo $article['title']; ?> -
                        <a href="edit_article.php?id=<?php echo $article['id']; ?>">Editar</a> |
                        <a href="delete_article.php?id=<?php echo $article['id']; ?>">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="create_article.php">Crear Nuevo Artículo</a>
            <a href="create_special_article.php">Crear Artículo Especial</a> <!-- Nueva opción para crear artículo especial -->
        </section>

        <section class="manage-comments">
            <h2>Comentarios</h2>
            <ul>
                <?php foreach ($comments as $comment): ?>
                    <li>
                        <?php echo $comment['content']; ?> -
                        <a href="edit_comment.php?id=<?php echo $comment['id']; ?>">Editar</a> |
                        <a href="delete_comment.php?id=<?php echo $comment['id']; ?>">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="manage-categories">
            <h2>Categorías</h2>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <?php echo $category['name']; ?> -
                        <a href="edit_category.php?id=<?php echo $category['id']; ?>">Editar</a> |
                        <a href="delete_category.php?id=<?php echo $category['id']; ?>">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="create_category.php">Crear Nueva Categoría</a>
        </section>

        <section class="manage-tags">
            <h2>Etiquetas</h2>
            <ul>
                <?php foreach ($tags as $tag): ?>
                    <li>
                        <?php echo $tag['name']; ?> -
                        <a href="edit_tag.php?id=<?php echo $tag['id']; ?>">Editar</a> |
                        <a href="delete_tag.php?id=<?php echo $tag['id']; ?>">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="create_tag.php">Crear Nueva Etiqueta</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Mi Blog. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
