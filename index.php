<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Blog</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Estilos específicos para la carga de más artículos */
        #load-more {
            text-align: center;
            margin-top: 20px;
            display: none; /* Ocultar inicialmente el botón de cargar más */
        }

        /* Estilos para el artículo especial */
        .special-article-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .special-article-item img {
            width: 200px;
            height: auto;
            border-radius: 5px;
            margin-right: 20px;
        }

        .special-article-content h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .special-article-content p {
            line-height: 1.6;
        }
    </style>
    <script>
        $(document).ready(function() {
            var page = 1; // Página inicial
            var loading = false; // Bandera para evitar múltiples solicitudes simultáneas

            function loadArticles() {
                if (!loading) {
                    loading = true;
                    $('#load-more').text('Cargando...').show();
                    $.ajax({
                        url: 'load_more_articles.php',
                        type: 'GET',
                        data: { page: page },
                        success: function(response) {
                            if (response.trim() === "<p>No hay más artículos disponibles.</p>") {
                                $('#load-more').text('No hay más artículos disponibles');
                            } else {
                                $('#load-more').hide();
                                $('#articles-container').append(response);
                                loading = false;
                                page++;
                            }
                        },
                        error: function() {
                            $('#load-more').text('Error al cargar').show();
                            loading = false;
                        }
                    });
                }
            }

            // Cargar más artículos al hacer clic en el botón
            $('#load-more-btn').on('click', function() {
                loadArticles();
            });

            // Cargar más artículos al llegar al final de la página
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    loadArticles();
                }
            });
        });
    </script>
</head>
<body>
    <header>
        <div class="container">
            <h1>Mi Blog</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="admin.php">Administrar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="articles">
            <div class="container articles-container" id="articles-container">
                <?php
                // Incluir el artículo especial
                include('especial_article.php');

                // Incluir el archivo de conexión a la base de datos
                include('db_connection.php');

                // Definir el número de artículos por página y la página actual
                $articles_per_page = 11; // Uno menos porque el artículo especial ocupa un lugar
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($current_page - 1) * $articles_per_page;

                // Query para obtener los artículos paginados
                $sql = "SELECT articles.*, categories.name AS category_name 
                        FROM articles 
                        INNER JOIN categories ON articles.category_id = categories.id 
                        ORDER BY articles.created_at DESC 
                        LIMIT $articles_per_page OFFSET $offset";
                $result = $conn->query($sql);

                // Obtener el total de artículos (para la paginación)
                $sql_total = "SELECT COUNT(*) AS total FROM articles";
                $result_total = $conn->query($sql_total);
                $row_total = $result_total->fetch_assoc();
                $total_articles = $row_total['total'];

                // Calcular el total de páginas
                $total_pages = ceil($total_articles / $articles_per_page);

                // Verificar si se obtuvieron resultados
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<a href='article.php?id={$row['id']}' class='article-link'>";
                        echo "<div class='article-item'>";
                        echo "<h3>{$row['title']}</h3>";
                        echo "<p class='meta'>";
                        echo "Publicado el " . date('d/m/Y', strtotime($row['created_at']));
                        echo "<span class='category-tag'>{$row['category_name']}</span>";
                        echo "</p>";
                        echo "<p>" . substr($row['content'], 0, 200) . "...</p>"; // Mostrar solo un extracto del contenido
                        echo "</div>";
                        echo "</a>";
                    }
                } else {
                    echo "<p>No hay artículos disponibles.</p>";
                }

                // Liberar el resultado y cerrar la conexión
                $result->free();
                $result_total->free();
                $conn->close();
                ?>
            </div>

            <!-- Botón de cargar más -->
            <div id="load-more">
                <button id="load-more-btn">Cargar más</button>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Mi Blog. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
