<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el número de página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$articles_per_page = 12;
$offset = ($page - 1) * $articles_per_page;

// Consulta para obtener los artículos
$sql = "SELECT * FROM articulos ORDER BY fecha_publicacion DESC LIMIT $offset, $articles_per_page";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Básico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .article-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
        }
        .article {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .article h2 {
            font-size: 20px;
            margin: 0 0 10px;
        }
        .article p {
            font-size: 14px;
            color: #666;
        }
        .article a {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .load-more {
            text-align: center;
            margin: 30px 0;
        }
        .load-more a {
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Blog Básico</h1>
        <div class="article-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="article">';
                    echo '<h2>' . $row['titulo'] . '</h2>';
                    echo '<p>' . substr($row['descripcion'], 0, 100) . '...</p>';
                    echo '<p><small>Publicado el: ' . date("d/m/Y", strtotime($row['fecha_publicacion'])) . '</small></p>';
                    echo '<a href="articulo.php?id=' . $row['id'] . '">Leer artículo</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay artículos disponibles.</p>';
            }
            ?>
        </div>

        <?php
        // Contar el número total de artículos
        $sql_total = "SELECT COUNT(*) as total FROM articulos";
        $result_total = $conn->query($sql_total);
        $row_total = $result_total->fetch_assoc();
        $total_articles = $row_total['total'];
        
        // Mostrar botón de "Cargar más" si hay más artículos
        if ($total_articles > $articles_per_page * $page) {
            echo '<div class="load-more">';
            echo '<a href="index.php?page=' . ($page + 1) . '">Cargar más artículos</a>';
            echo '</div>';
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
