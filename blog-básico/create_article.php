<?php
require 'db.php';

// Verificar sesión de administrador (ejemplo básico, implementa tu sistema de autenticación adecuado)
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Procesar el formulario de creación de artículo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = 'uploads/' . $_FILES['image']['name']; // Guarda la imagen en una carpeta 'uploads'
    
    // Subir archivo de imagen
    move_uploaded_file($_FILES['image']['tmp_name'], $image);

    // Insertar artículo en la base de datos
    $stmt = $pdo->prepare('INSERT INTO articles (title, content, image) VALUES (:title, :content, :image)');
    $stmt->execute(['title' => $title, 'content' => $content, 'image' => $image]);

    // Redirigir a la página de administración
    header('Location: admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Artículo</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        /* Estilos específicos para esta página si es necesario */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
        }

        input[type="text"], textarea, input[type="file"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Artículo</h1>
        <form action="create_article.php" method="POST" enctype="multipart/form-data">
            <label for="title">Título:</label><br>
            <input type="text" id="title" name="title" required><br><br>
            
            <label for="content">Contenido:</label><br>
            <textarea id="content" name="content" rows="5" required></textarea><br><br>
            
            <label for="image">Imagen:</label><br>
            <input type="file" id="image" name="image" accept="image/*" required><br><br>
            
            <button type="submit">Publicar Artículo</button>
        </form>
    </div>
</body>
</html>
