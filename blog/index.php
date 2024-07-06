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

        /* Estilo para el artículo especial */
        .special-article-container {
            display: flex;
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            align-items: center;
        }

        .special-article-container img {
            max-width: 650px;
            height: auto;
            border-radius: 5px;
            margin-right: 20px;
        }

        .special-article-content {
            max-width: calc(100% - 170px); /* Ajustar para dejar espacio para la imagen y el margen */
        }

        .special-article-container h3 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .special-article-container .meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .special-article-container p {
            line-height: 1.8;
        }

        .articles-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Tres columnas iguales */
            gap: 20px; /* Espacio entre los artículos */
        }

        .article-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            width: 350px;
            height: 150px;
        }

        .article-item h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .article-item p.meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .article-item p.meta .category-tag {
            display: inline-block;
            background-color: #ffffff;
            color: #000000;
            font-size: .70rem;
            padding: 0px 10px;
            border-radius: 15px;
            margin-left: 7px;
            border: 1px solid #afafaf;
        }

        .article-item p {
            line-height: 1.8;
            flex-grow: 1;
        }

        .article-link {
            text-decoration: none;
            color: inherit;
        }

        .article-item {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .article-item:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a, .pagination .current-page {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            background-color: #f7f7f7;
            color: #333;
            text-decoration: none;
            border-radius: 3px;
        }

        .pagination a:hover {
            background-color: #e0e0e0;
        }

        .pagination .current-page {
            font-weight: bold;
            color: #fff;
            background-color: #333;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        footer p {
            font-size: 0.9rem;
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
                            $('#load-more').hide();
                            $('#articles-container').append(response);
                            loading = false;
                            page++;
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
            <div class="container">
                <?php
                // Incluir el archivo de conexión a la base de datos
                include('db_connection.php');

                // Query para obtener el artículo especial
                $special_article_sql = "SELECT * FROM special_articles LIMIT 1";
                $special_article_result = $conn->query($special_article_sql);

                // Mostrar el artículo especial si existe
                if ($special_article_result->num_rows > 0) {
                    $special_article = $special_article_result->fetch_assoc();
                    echo "<div class='special-article-container'>";
                    if (isset($special_article['image']) && !empty($special_article['image'])) {
                        echo "<img src='{$special_article['image']}' alt='{$special_article['title']}' />";
                    }
                    echo "<div class='special-article-content'>";
                    echo "<a href='special_article.php?id={$special_article['id']}' class='article-link'>";
                    echo "<h3>{$special_article['title']}</h3>";
                    echo "<p class='meta'>";
                    echo "Publicado el " . date('d/m/Y', strtotime($special_article['created_at']));
                    echo "<span class='category-tag'>Artículo Especial</span>";
                    echo "</p>";
                    echo "<p>" . substr($special_article['summary'], 0, 200) . "...</p>"; // Mostrar solo un extracto del resumen
                    echo "</a>";
                    echo "</div>";
                    echo "</div>";
                }

                // Liberar el resultado del artículo especial
                $special_article_result->free();

                // Definir el número de artículos por página y la página actual
                $articles_per_page = 12;
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($current_page - 1) * $articles_per_page;

                // Query para obtener los artículos paginados
                $regular_articles_sql = "SELECT articles.*, categories.name AS category_name 
                                        FROM articles 
                                        INNER JOIN categories ON articles.category_id = categories.id 
                                        ORDER BY articles.created_at DESC 
                                        LIMIT $articles_per_page OFFSET $offset";
                $regular_articles_result = $conn->query($regular_articles_sql);

                // Verificar si se obtuvieron resultados
                if ($regular_articles_result->num_rows > 0) {
                    echo "<div class='articles-container' id='articles-container'>";
                    while ($row = $regular_articles_result->fetch_assoc()) {
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
                    echo "</div>";
                } else {
                    echo "<p>No hay artículos disponibles.</p>";
                }

                // Liberar el resultado de los artículos regulares y cerrar la conexión
                $regular_articles_result->free();
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
