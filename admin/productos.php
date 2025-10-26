<?php
session_start();
include("../includes/db.php");

/* Verificar si el admin está logueado
if(!isset($_SESSION['admin_usuario'])){
    header("Location: index.php");
    exit();
}*/

// Función para subir imagen
function subirImagen($file){
    $nombreArchivo = time() . "_" . $file['name'];
    $rutaDestino = "../img/" . $nombreArchivo;
    move_uploaded_file($file['tmp_name'], $rutaDestino);
    return $nombreArchivo;
}

// Agregar producto
if(isset($_POST['agregar'])){
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $imagen = subirImagen($_FILES['imagen']);

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $stock, $imagen);
    $stmt->execute();
    $stmt->close();
}

// Actualizar producto
if(isset($_POST['editar'])){
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    if($_FILES['imagen']['name'] != ""){
        $imagen = subirImagen($_FILES['imagen']);
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, imagen=? WHERE id=?");
        $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $stock, $imagen, $id);
    } else {
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id);
    }
    $stmt->execute();
    $stmt->close();
}

// Eliminar producto
if(isset($_GET['eliminar'])){
    $id = $_GET['eliminar'];
    $stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Obtener productos
$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Productos</title>
    <link rel="stylesheet" href="../css/diseño.css">
</head>
<body>
    <h1>Administrar Productos</h1>
    <a href="dashboard.php">Volver al Dashboard</a> | <a href="logout.php">Cerrar sesión</a>

    <h2>Agregar Producto</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <textarea name="descripcion" placeholder="Descripción" required></textarea>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="file" name="imagen" required>
        <button type="submit" name="agregar">Agregar</button>
    </form>

    <h2>Productos Existentes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="POST" enctype="multipart/form-data">
                <td><?php echo $row['id']; ?><input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
                <td><input type="text" name="nombre" value="<?php echo $row['nombre']; ?>"></td>
                <td><textarea name="descripcion"><?php echo $row['descripcion']; ?></textarea></td>
                <td><input type="number" step="0.01" name="precio" value="<?php echo $row['precio']; ?>"></td>
                <td><input type="number" name="stock" value="<?php echo $row['stock']; ?>"></td>
                <td>
                    <img src="../img/<?php echo $row['imagen']; ?>" width="80"><br>
                    <input type="file" name="imagen">
                </td>
                <td>
                    <button type="submit" name="editar">Editar</button>
                    <a href="productos.php?eliminar=<?php echo $row['id']; ?>" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
