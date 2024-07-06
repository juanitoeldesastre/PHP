<?php
// Aquí deberías incluir la lógica de autenticación y permisos de administrador
// Suponemos que ya se ha iniciado sesión como administrador

// Incluir el archivo de conexión a la base de datos
require_once('db_connection.php');

// Variables para almacenar los datos del formulario
$title = $content = $image_url = '';
$category_id = 1; // ID de la categoría del artículo especial (ajustar según tu estructura)

// Mensajes de error y éxito
$errors = [];
$success = '';

// Manejo del formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validaciones básicas (puedes extenderlas según necesites)
    if (empty($_POST['title'])) {
        $errors[] = 'El título es requerido.';
    } else {
        $title = $_POST['title'];
    }

    if (empty($_POST['content'])) {
        $errors[] = 'El contenido es requerido.';
    } else {
        $content = $_POST['content'];
    }

    // Procesar la imagen si se carga (puedes extender esta funcionalidad según tus necesidades)
    if (!empty($_FILES['image']['tmp_name'])) {
        $image_name = $_FILES['image']['name'];
        $target_dir = 'uploads/';
        $target_file = $target_dir . basename($image_name);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = $target_file;
        } else {
            $errors[] = 'Error al subir la imagen.';
        }
    } else {
        $errors[] = 'La imagen es requerida.';
    }

    // Insertar el artículo en la base de datos si no hay errores
    if (empty($errors)) {
        $sql = "INSERT INTO special_articles (title, summary, image, created_at) 
                VALUES ('$title', '$content', '$image_url', CURRENT_TIMESTAMP)";

        if ($conn->query($sql) === TRUE) {
            $success = 'Artículo especial creado exitosamente.';
            // Limpiar los campos después de la inserción (opcional)
            $title = $content = $image_url = '';
        } else {
            $errors[] = 'Error al crear el artículo especial: ' . $conn->error;
        }
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Artículo Especial - Panel de Administración</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Crear Artículo Especial</h1>
        <nav>
            <ul>
                <li><a href="index.php">Ver Blog</a></li>
                <li><a href="admin.php">Volver al Panel de Administración</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="create-special-article">
            <h2>Formulario para Artículo Especial</h2>
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <label for="title">Título:</label><br>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br><br>
                
                <label for="content">Resumen:</label><br>
                <textarea id="content" name="content"><?php echo htmlspecialchars($content); ?></textarea><br><br>
                
                <label for="image">Imagen:</label><br>
                <input type="file" id="image" name="image"><br><br>
                
                <button type="submit">Crear Artículo Especial</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Mi Blog. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
