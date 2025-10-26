<?php
session_start();
include("includes/db.php");

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
    header("Location: carrito.php");
    exit;
}

$total = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Carrito</title></head>
<body>
<h2>Tu carrito</h2>
<?php
if (!empty($_SESSION["carrito"])) {
    foreach ($_SESSION["carrito"] as $item) {
        echo "<p>{$item['nombre']} x {$item['cantidad']} - $" . ($item['precio'] * $item['cantidad']) . "</p>";
        $total += $item['precio'] * $item['cantidad'];
    }
    echo "<h3>Total: $" . number_format($total, 2) . "</h3>";
    // <-- Aquí reemplazamos el link por un formulario -->
    ?>
    <form action="checkout.php" method="post">
        <label for="metodo_pago">Selecciona el método de pago:</label>
        <select name="metodo_pago" required>
            <option value="">--Seleccione--</option>
            <option value="Tarjeta">Tarjeta</option>
            <option value="PayPal">PayPal</option>
        </select>
        <br><br>
        <input type="submit" value="Finalizar compra">
    </form>
    <?php
} else {
    echo "<p>El carrito está vacío.</p>";
}
?>
</body>
</html>

