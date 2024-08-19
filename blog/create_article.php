<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Inicializar variables de error y éxito
$error = '';
$success = '';

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $publication_date = $_POST['publication_date'] ? $_POST['publication_date'] : date('Y-m-d');
    $author_id = $_POST['author_id'] ? $_POST['author_id'] : 1; // Asumir un ID de autor predeterminado
    $description = $_POST['description'];
    $content = isset($_POST['content']) ? $_POST['content'] : ''; // Manejar el caso en que content no está definido

    // Validar los datos del formulario
    if (empty($title) || empty($_FILES['image']['name']) || empty($description)) {
        $error = "El título, la imagen y la descripción son obligatorios. La fecha de publicación y el ID del autor son opcionales.";
    } else {
        // Procesar la imagen
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validar que el archivo es una imagen
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insertar el nuevo artículo en la base de datos
                $sql = "INSERT INTO articles (title, image, publication_date, author_id, description, content) 
                        VALUES (:title, :image, :publication_date, :author_id, :description, :content)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':title' => $title,
                    ':image' => $target_file,
                    ':publication_date' => $publication_date,
                    ':author_id' => $author_id,
                    ':description' => $description,
                    ':content' => $content // Agregar content
                ]);

                $success = "El artículo ha sido creado exitosamente.";
            } else {
                $error = "Hubo un error al subir la imagen.";
            }
        } else {
            $error = "El archivo seleccionado no es una imagen.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Artículo | Admin</title>
    <style>
        /* Estilos para la página de creación de artículos */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007BFF;
            color: #fff;
            padding: 15px 0;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin: 0 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .admin-section {
            max-width: 600px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-section h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: #FF0000;
            margin-bottom: 20px;
        }

        .success {
            color: #28A745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Crear Nuevo Artículo</h1>
        <nav>
            <ul>
                <li><a href="admin.php">Panel de Control</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="admin-section">
        <h2>Ingrese la información del artículo</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="create_article.php" method="POST" enctype="multipart/form-data">
            <label for="title">Título:</label>
            <input type="text" id="title" name="title" value="<?php echo isset($title) ? $title : ''; ?>" required>

            <label for="image">Seleccionar Imagen:</label>
            <input type="file" id="image" name="image" required>

            <label for="publication_date">Fecha de Publicación (Opcional):</label>
            <input type="date" id="publication_date" name="publication_date" value="<?php echo isset($publication_date) ? $publication_date : ''; ?>">

            <label for="author_id">ID del Autor (Opcional):</label>
            <input type="number" id="author_id" name="author_id" value="<?php echo isset($author_id) ? $author_id : ''; ?>">

            <label for="description">Descripción:</label>
            <textarea id="description" name="description" rows="5" required><?php echo isset($description) ? $description : ''; ?></textarea>

            <button type="submit">Crear Artículo</button>
        </form>
    </section>
</body>
</html>
