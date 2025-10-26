<?php
session_start();
include("../includes/db.php");

if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $pass = $_POST['pass'];

    $sql = "SELECT * FROM administradores WHERE usuario = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $_SESSION['admin'] = $usuario;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrecta";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
</head>
<body>
    <h2>Login Administrador</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Usuario:</label><br>
        <input type="text" name="usuario" required><br>
        <label>Contraseña:</label><br>
        <input type="password" name="pass" required><br><br>
        <button type="submit" name="login">Ingresar</button>
    </form>
</body>
</html>
