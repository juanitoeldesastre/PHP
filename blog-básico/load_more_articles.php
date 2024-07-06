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

// Devolver los artículos en formato JSON
header('Content-Type: application/json');
echo json_encode([
    'articles' => $articles,
    'totalArticles' => $totalArticles
]);
?>
