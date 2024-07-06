<?php
require 'db.php';

// Número de artículos por página
$articlesPerPage = 12;

// Obtener el número total de artículos
$stmt = $pdo->query('SELECT COUNT(*) FROM articles');
$totalArticles = $stmt->fetchColumn();

// Obtener el número de la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $articlesPerPage;

// Obtener los artículos para la página actual
$stmt = $pdo->prepare('SELECT * FROM articles ORDER BY created_at DESC LIMIT :start, :limit');
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $articlesPerPage, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Blog</h1>
        <div class="articles" id="articles">
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                    <p><?php echo htmlspecialchars(substr($article['content'], 0, 100)); ?></p>
                    <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Article Image">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pagination">
            <?php if ($totalArticles > $page * $articlesPerPage): ?>
                <button id="loadMore" class="button">Cargar más artículos</button>
            <?php endif; ?>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
