<?php
session_start();
include("includes/db.php");

//Agregar producto al carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_producto"])) {
    $id_producto = $_POST["id_producto"];
    $cantidad = $_POST["cantidad"];

    $sql = "SELECT * FROM productos WHERE id = $id_producto";
    $result = $conn->query($sql);
    $producto = $result->fetch_assoc();

    $item = [
        "id" => $producto["id"],
        "nombre" => $producto["nombre"],
        "precio" => $producto["precio"],
        "cantidad" => $cantidad
    ];

    // VARIABLE DE TIPO SESION
    $_SESSION["carrito"][] = $item;
    
}

//Eliminar producto del carrito
if (isset($_POST["eliminar"])) {
    $index = $_POST["eliminar"];
    
    if (isset($_SESSION["carrito"][$index])) {
        unset($_SESSION["carrito"][$index]);
        $_SESSION["carrito"] = array_values($_SESSION["carrito"]); // reordenar índices
    }
}

$total = 0;
?>


