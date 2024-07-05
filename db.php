<?php
// db.php

// Verificar si el método de solicitud es POST antes de procesar los formularios
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "blog";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Procesar el formulario para agregar una categoría
    if (isset($_POST['add_category'])) {
        $category_name = $_POST['category_name'];

        // Escapar caracteres especiales para evitar SQL injection
        $category_name = $conn->real_escape_string($category_name);

        // Preparar la consulta SQL para insertar una nueva categoría
        $sql_category = "INSERT INTO categories (name) VALUES ('$category_name')";

        if ($conn->query($sql_category) === TRUE) {
            // Redirigir a admin.php con mensaje de éxito
            header("Location: admin.php?success=true");
            exit;
        } else {
            // Redirigir a admin.php con mensaje de error
            header("Location: admin.php?error=true");
            exit;
        }
    }

    // Procesar el formulario para agregar una etiqueta
    if (isset($_POST['add_tag'])) {
        $tag_name = $_POST['tag_name'];

        // Escapar caracteres especiales para evitar SQL injection
        $tag_name = $conn->real_escape_string($tag_name);

        // Preparar la consulta SQL para insertar una nueva etiqueta
        $sql_tag = "INSERT INTO tags (name) VALUES ('$tag_name')";

        if ($conn->query($sql_tag) === TRUE) {
            // Redirigir a admin.php con mensaje de éxito
            header("Location: admin.php?success=true");
            exit;
        } else {
            // Redirigir a admin.php con mensaje de error
            header("Location: admin.php?error=true");
            exit;
        }
    }

    // Cerrar la conexión al final del script si no se ha cerrado ya
    if ($conn) {
        $conn->close();
    }
}
?>
