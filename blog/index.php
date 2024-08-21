<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Obtener los 12 primeros artículos desde la base de datos
$sql = "SELECT articles.*, users.username AS author FROM articles 
        JOIN users ON articles.author_id = users.id 
        ORDER BY publication_date DESC 
        LIMIT 12";
$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Blog</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de crear un archivo CSS -->
</head>
<body>
    <!-- Banner -->
    <section id="banner">
    <img src="all_tomorrows.png" alt="Banner del blog">
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/jS5fTzMP_mg?autoplay=1&controls=0&modestbranding=1&showinfo=0&fs=0&playsinline=1"
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
        </iframe>
    </div>
</section>




    <!-- Menú de navegación -->
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="#">Sobre Nosotros</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <!-- Artículos del blog -->
    <section id="articles">
        <div class="container">
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                    <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                    <p class="meta">
                        Publicado el <?php echo htmlspecialchars($article['publication_date']); ?> por <?php echo htmlspecialchars($article['author']); ?>
                    </p>
                    <p><?php echo htmlspecialchars($article['description']); ?></p>
                    <a href="article.php?id=<?php echo $article['id']; ?>">Leer más</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Botón para cargar más artículos -->
    <div class="load-more">
        <button id="loadMore">Cargar más artículos</button>
    </div>

    <script src="scripts.js"></script> <!-- Este será el archivo JS para manejar AJAX -->
</body>
</html>




