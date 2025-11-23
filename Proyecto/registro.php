<?php
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $password);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}
?>
