<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/db.php");


// Procesar login

$login_error = "";

if(isset($_POST['login'])) {
    $correo = $_POST['email'];
    $password = $_POST['password'];

    // Consulta simple sin hash
    $query = "SELECT * FROM clientes WHERE email='$correo' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        //VARIABLE DE TIPO SESION
        $_SESSION['cliente_id'] = $row['id'];
        $_SESSION['cliente_nombre'] = $row['nombre'];
        header("Location: index.php");
        exit;
    } else {
        $login_error = "Correo o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería Dulce Hogar</title>
    <link rel="preload" href="css/normalize.css" as="diseño">
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preload" href="css/diseño.css" as="diseño">
    <link href="css/diseño.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1 class="titulo">Panadería <span>Dulce Hogar</span></h1>

        <?php if(isset($_SESSION['cliente_id'])): ?>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['cliente_nombre']); ?> | <a href="logout.php">Cerrar sesión</a></p>
        <?php else: ?>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="login">Iniciar sesión</button>
            </form>
            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php endif; ?> 
    </header>

    <div class="nav-bg">
        <?php if (session_status() == PHP_SESSION_NONE) {  session_start();} ?>
        <nav class="navegacion-principal contenedor">
             <a href="index.php">Inicio</a>
             <a href="#">Nosotros</a>
             <a href="#">Productos</a>
             <a href="#">Contacto</a>

        <?php if(isset($_SESSION['cliente_id'])): ?>
             <a href="logout.php">Cerrar sesión (<?php echo $_SESSION['cliente_nombre']; ?>)</a>
        <?php else: ?>
             <a href="login.php">Iniciar sesión</a>
        <?php endif; ?>
        </nav>

    </div>

    
    <section class="hero">
        <div class="contenido-hero">
            <h2>Pan fresco y artesanal todos los días <span>con amor</span></h2> 
            <div class="ubicacion">
                <!-- Ícono de ubicación -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin" width="88" height="88" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFC107" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z"/>
                    <circle cx="12" cy="11" r="3" />
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 0 1 -2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z" />
                </svg>
                <p>Torreón, Coahuila</p>
            </div>
            <a class="boton" href="#">Ordenar Ahora</a>
        </div>
    </section>
    
    <main class="contenedor sombra">
        <h2>Nuestros Productos</h2>

        
        

        <div class="servicios">
<?php
include("includes/db.php");

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        echo '
        <section class="servicio">
            <h3>'. htmlspecialchars($row["nombre"]) .'</h3>
            <img src="img/'. htmlspecialchars($row["imagen"]) .'" alt="'. htmlspecialchars($row["nombre"]) .'" width="150">
            <p>'. htmlspecialchars($row["descripcion"]) .'</p>
            <p><strong>Precio: $'. number_format($row["precio"], 2) .'</strong></p>';

        // Mostrar cantidad disponible
        if ($row["stock"] > 0) {
            echo '<p style="color:green;">Disponibles: '. $row["stock"] .' unidades</p>';

            echo '
            <form method="POST" action="carrito.php">
                <input type="hidden" name="id_producto" value="'. $row["id"] .'">
                <input type="number" name="cantidad" value="1" min="1" max="'. $row["stock"] .'">
                <button type="submit" class="boton">Agregar al carrito</button>
            </form>';
        } else {
            echo '<p style="color:red; font-weight:bold;">Agotado</p>';
        }

        echo '</section>';
    }
} else {
    echo "<p>No hay productos registrados.</p>";
}
?>
</div>



        <!--<section>
            <h2>Contacto</h2>

            <form class="formulario">
                <fieldset>
                    <legend>Contáctanos llenando todos los campos</legend>

                    <div class="contenedor-campos">
                        <div class="campo">
                            <label>Nombre</label>
                            <input class="input-text" type="text" placeholder="Tu Nombre">
                        </div>

                        <div class="campo">
                            <label>Teléfono</label>
                            <input class="input-text" type="tel" placeholder="Tu Teléfono">
                        </div>

                        <div class="campo">
                            <label>Correo</label>
                            <input class="input-text" type="email" placeholder="Tu Email">
                        </div>
                
                        <div class="campo">
                            <label>Mensaje</label>
                            <textarea class="input-text" placeholder="¿Qué deseas ordenar o consultar?"></textarea>
                        </div>
                    </div>

                    <div class="alinear-derecha flex">
                        <input class="boton w-sm-100" type="submit" value="Enviar">
                    </div>
                </fieldset>
            </form>
        </section> -->


        <h2>Conocenos</h2>

        <div class="conocenos contenedor">
        <section class="bloque-conocenos">
            <div class="horario">
            <h3>Horario de Atención</h3>
            <p>Lunes a Viernes: 7:00 am - 8:00 pm</p>
            <p>Sábados y Domingos: 8:00 am - 6:00 pm</p>
            </div>
        </section>

        <section class="bloque-conocenos">
            <div class="contacto-directo">
            <a class="boton whatsapp" href="https://wa.me/521XXXXXXXXXX" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" class="icono" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.05 11.2c0 5.17-4.3 9.38-9.6 9.38-1.65 0-3.21-.44-4.55-1.2L3 21l1.53-4.31c-.8-1.29-1.28-2.8-1.28-4.43 0-5.18 4.3-9.38 9.6-9.38s9.6 4.2 9.6 9.38z"/>
            <path d="M8.24 7.75c.13-.32.68-.64 1.08-.69.39-.06.88-.06 1.29.44.41.49 1.35 2.01 1.59 2.29.24.28.47.61.08 1.1-.39.49-.59.8-.83 1.06-.24.27-.49.59-.22.98.27.39 1.19 1.92 2.41 2.61 1.22.69 1.5.38 1.88.06.38-.32 1.13-1.05 1.17-1.53.04-.48.04-.87-.12-1.05-.16-.18-.39-.27-.63-.33-.24-.06-.88-.15-1.31-.31-.43-.16-.85-.42-1.28-.81-.43-.39-.76-.76-1.2-1.35-.44-.59-.61-.94-.73-1.35-.12-.41-.05-.74.08-1.06z"/>
            </svg>
            Enviar mensaje por WhatsApp
            </a>

            <a class="boton telefono" href="tel:XXXXXXXXXX">
            <svg xmlns="http://www.w3.org/2000/svg" class="icono" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92V21a2 2 0 0 1-2.18 2A19.87 19.87 0 0 1 3 5.18 2 2 0 0 1 5 3h4.09a2 2 0 0 1 2 1.72c.12.81.37 1.61.72 2.35a2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 6.28 6.28l1.27-1.27a2 2 0 0 1 2.11-.45c.74.35 1.54.6 2.35.72a2 2 0 0 1 1.72 2z"/>
            </svg>
            Llamar ahora
            </a>
            </div>
        </section>


        <section class="bloque-conocenos">
            <div class="redes-sociales">
            <h3>Síguenos</h3>
            <a href="https://facebook.com/tupanaderia" target="_blank">Facebook</a><br>
            <a href="https://instagram.com/tupanaderia" target="_blank">Instagram</a>
            </div>
        </section>
        </div>


        <div class="mapa">
            <h3>¿Dónde estamos?</h3>
            <iframe src="https://maps.app.goo.gl/BuFb79zHQQPhoS5R8" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <div class="testimonios">
            <h3>Lo que dicen nuestros clientes</h3>
            <blockquote>
            "Las conchas más deliciosas que he probado. Siempre calientitas y el servicio excelente."
            <footer>- Mariana G.</footer>
            </blockquote>
        </div>



    </main>
    
    <footer class="footer">
        <p>Todos los derechos reservados. Panadería Dulce Hogar © 2025</p>
    </footer>
</body>
</html>
