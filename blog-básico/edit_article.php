<?php
require 'db.php';

// Verificar sesión de administrador (ejemplo básico, implementa tu sistema de autenticación adecuado)
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Obtener el ID del artículo a editar
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit();
}
$article_id = $_GET['id'];

// Obtener los datos del artículo
$stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
$stmt->execute([$article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// Función para actualizar un artículo
function updateArticle($pdo, $id, $title, $content, $image) {
    $stmt = $pdo->prepare('UPDATE articles SET title = ?, content = ?, image = ? WHERE id = ?');
    return $stmt->execute([$title, $content, $image, $id]);
}

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_title = $_POST['title'];
    $new_content = $_POST['content'];
    $new_image = $_FILES['image'];

    // Manejar la carga de la imagen si se seleccionó una nueva
    if ($new_image['size'] > 0) {
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($new_image['name']);

        // Mover el archivo cargado al directorio de uploads
        if (move_uploaded_file($new_image['tmp_name'], $upload_file)) {
            $new_image_path = $upload_file;
        } else {
            // Error al subir la imagen
            $error_message = "Error al subir la imagen.";
        }
    } else {
        // Conservar la imagen existente si no se seleccionó una nueva
        $new_image_path = $article['image'];
    }

    // Actualizar el artículo en la base de datos
    if (updateArticle($pdo, $article_id, $new_title, $new_content, $new_image_path)) {
        $success_message = "Artículo actualizado correctamente.";
        // Redirigir a la página de administración después de actualizar
        header('Location: admin.php');
        exit();
    } else {
        $error_message = "Error al actualizar el artículo.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        /* Estilos adicionales específicos para esta página si es necesario */
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 10px;
        }

        .form-container input[type="text"], .form-container textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-container input[type="file"] {
            margin-bottom: 15px;
        }

        .form-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Artículo</h1>

        <div class="form-container">
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if (isset($success_message)): ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>

                <label for="content">Contenido:</label>
                <textarea id="content" name="content" rows="6" required><?php echo htmlspecialchars($article['content']); ?></textarea>

                <label for="image">Cambiar Imagen:</label>
                <input type="file" id="image" name="image">

                <input type="submit" value="Actualizar Artículo">
            </form>
        </div>
    </div>
</body>
</html>
