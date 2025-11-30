<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}
?>

<h2>Bienvenido, <?php echo $_SESSION['admin']; ?></h2>
<nav>
    <a href="productos.php">Gestionar Productos</a> |
    <a href="ventas.php">Ver Ventas</a> |
    <a href="logout.php">Cerrar sesión</a>
</nav>
<p>Panel de administración principal</p>
