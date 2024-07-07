<?php
require 'db.php';

// Verificar sesión de administrador (ejemplo básico, implementa tu sistema de autenticación adecuado)
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Función para obtener los artículos paginados
function getArticlesPaginated($pdo, $page, $perPage) {
    $start = ($page - 1) * $perPage;
    $stmt = $pdo->prepare('SELECT * FROM articles ORDER BY created_at DESC LIMIT :start, :limit');
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Número de artículos por página
$articlesPerPage = 12;

// Obtener la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Obtener los primeros 12 artículos para la página actual
$articles = getArticlesPaginated($pdo, $page, $articlesPerPage);

// Mensajes de éxito o error
$successMessage = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
$errorMessage = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var page = 1; // Página inicial
            var loading = false;

            function loadMoreArticles() {
                if (loading) return;
                loading = true;
                page++;
                $.ajax({
                    url: 'load_more_articles.php',
                    type: 'GET',
                    data: { page: page },
                    dataType: 'json',
                    success: function(data) {
                        if (data.articles.length > 0) {
                            data.articles.forEach(function(article) {
                                var articleHtml = `
                                    <div class="article">
                                        <h3>${article.title}</h3>
                                        <p>${article.content.substring(0, 100)}</p>
                                        <img src="${article.image}" alt="Imagen del artículo">
                                        <a href="edit_article.php?id=${article.id}" class="edit">Editar</a>
                                        <a href="delete_article.php?id=${article.id}" class="delete" onclick="return confirm('¿Estás seguro de eliminar este artículo?');">Eliminar</a>
                                    </div>
                                `;
                                $('.articles').append(articleHtml);
                            });
                        } else {
                            $('.pagination').hide(); // Ocultar botón de carga si no hay más artículos
                        }
                        loading = false;
                    },
                    error: function() {
                        alert('Error al cargar más artículos');
                        loading = false;
                    }
                });
            }

            $('.load-more').click(function(e) {
                e.preventDefault();
                loadMoreArticles();
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Panel de Administración</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <h2>Crear Nuevo Artículo</h2>
        <a href="create_article.php" class="button">Crear Artículo</a>

        <h2>Editar o Eliminar Artículos</h2>
        <div class="articles">
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($article['content'], 0, 100)); ?></p>
                    <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Imagen del artículo">
                    <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="edit">Editar</a>
                    <a href="delete_article.php?id=<?php echo $article['id']; ?>" class="delete" onclick="return confirm('¿Estás seguro de eliminar este artículo?');">Eliminar</a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($articles) >= $articlesPerPage): ?>
            <div class="pagination">
                <a href="#" class="button load-more">Cargar más artículos</a>
            </div>
        <?php endif; ?>
        <div style="clear:both;"></div> <!-- Limpiar el float al final de los artículos -->
    </div>
    <script src="js/script.js"></script>
</body>
</html>
