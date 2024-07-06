<?php
session_start();

// Verificar si el usuario ya está autenticado
if (isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

// Verificar si se envió el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar las credenciales (ejemplo básico, ajusta según tu sistema de usuarios)
    $username = 'admin'; // Usuario de ejemplo
    $password = 'admin'; // Contraseña de ejemplo

    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        // Credenciales válidas, iniciar sesión
        $_SESSION['admin'] = $username;
        header('Location: admin.php');
        exit();
    } else {
        // Credenciales incorrectas
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="username">Usuario:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
