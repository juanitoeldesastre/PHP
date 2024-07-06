<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simulamos la verificación de las credenciales
    if ($username == 'admin' && $password == 'password') {
        $_SESSION['username'] = $username;
        header("Location: profile.php");
        exit;
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
</head>
<body>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post" action="login.php">
        <label for="username">Nombre de usuario:</label>
        <input type="text" id="username" name="username">
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password">
        <br>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
