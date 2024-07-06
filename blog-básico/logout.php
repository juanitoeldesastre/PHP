<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

echo "Bienvenido, " . $_SESSION['username'] . "!<br>";
echo "<a href='logout.php'>Cerrar Sesión</a>";
?>
