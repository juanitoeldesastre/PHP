<?php
// Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación básica (puedes expandirla según tus necesidades)
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    // Validación simple para asegurar que los campos no estén vacíos
    if (empty($title) || empty($content) || empty($category_id)) {
        $error_message = "Por favor, completa todos los campos.";
    } else {
        // Incluir el archivo de conexión a la base de datos
        include('db_connection.php');

        // Preparar la consulta SQL para insertar el nuevo artículo
        $title = $conn->real_escape_string($title);
        $content = $conn->real_escape_string($content);
        $category_id = (int)$category_id;

        $sql = "INSERT INTO articles (title, content, category_id) VALUES ('$title', '$content', $category_id)";

        // Ejecutar la consulta y verificar si fue exitosa
        if ($conn->query($sql) === TRUE) {
            $success_message = "El artículo se creó correctamente.";
            // Opcional: Redirigir a una página de confirmación o al inicio
            // header("Location: index.php");
            // exit();
        } else {
            $error_message = "Error al crear el artículo: " . $conn->error;
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Artículo - Mi Blog</title>
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
        <section class="create-article">
            <h2>Crear Nuevo Artículo</h2>

            <?php
            // Mostrar mensajes de éxito o error si existen
            if (isset($success_message)) {
                echo "<p class='success'>$success_message</p>";
            } elseif (isset($error_message)) {
                echo "<p class='error'>$error_message</p>";
            }
            ?>

            <!-- Formulario para crear un nuevo artículo -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="title">Título:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Contenido:</label>
                    <textarea id="content" name="content" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="category_id">Categoría ID:</label>
                    <input type="number" id="category_id" name="category_id" required>
                </div>
                <button type="submit">Crear Artículo</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Mi Blog. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
