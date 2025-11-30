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

// Procesar agregado al carrito
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

    $_SESSION["carrito"][] = $item;
}

// Eliminar producto del carrito
if (isset($_POST["eliminar"])) {
    $index = $_POST["eliminar"];

    if (isset($_SESSION["carrito"][$index])) {
        unset($_SESSION["carrito"][$index]);
        $_SESSION["carrito"] = array_values($_SESSION["carrito"]); // Reindexar
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

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body>
    
    <header>
        <h1 class="titulo">Panadería Ronroneos<span></span></h1>

    </header>

    <!-- Barra de navegación -->
    <div class="nav-bg">
        <nav class="navegacion-principal contenedor">
            <a href="#" data-section="inicio">Inicio</a>
            <a href="#" data-section="nosotros">Nosotros</a>
            <a href="#" data-section="productos">Productos</a>
            <a href="#" data-section="carrito">Carrito</a>
            <a href="#" data-section="contacto">Contacto</a>
            <a href="#" data-section="perfil">Perfil</a>
        </nav>
    </div>

    <!-- Contenido dinámico -->
    <main class="contenedor sombra">

        <!-- Sección: SESIÓN -->
        <section id="perfil" class="seccion">
    <h2>Iniciar Sesión</h2>

    <?php if(isset($_SESSION['cliente_id'])): ?>
        <div style="text-align:center;">
            <p>Has iniciado sesión como <strong><?php echo htmlspecialchars($_SESSION['cliente_nombre']); ?></strong>.</p>
            <a class="boton" href="logout.php">Cerrar sesión</a>
        </div>
    <?php else: ?>
        <!-- Login -->
        <form id="loginForm" method="POST" action="" class="formulario" style="max-width:400px; margin:auto;">
            <fieldset>
                <legend>Accede a tu cuenta</legend>
                <label>Correo electrónico</label>
                <input type="email" name="email" placeholder="Email" required>

                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Contraseña" required>

                <button type="submit" name="login" class="boton">Iniciar sesión</button>

                <?php if(!empty($login_error)) echo "<p style='color:red; text-align:center;'>$login_error</p>"; ?>
            </fieldset>
        </form>

        <!-- Botón para mostrar formulario de registro -->
        <div style="text-align:center; margin-top:10px;">
            <button id="btnMostrarRegistro" class="boton">Registrarse</button>
        </div>

        <!-- Formulario de registro oculto -->
        <form id="registroForm" method="POST" action="registro.php" class="formulario" style="max-width:400px; margin:auto; display:none; margin-top:10px;" onsubmit="return validarFormulario('registroForm')">
            <fieldset>
                <legend>Registro de usuario</legend>
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Tu nombre" required>

                <label>Email</label>
                <input type="email" name="email" placeholder="Email" required>

                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Contraseña" required>

                <button type="submit" class="boton">Registrarse</button>
            </fieldset>
        </form>
    <?php endif; ?>
</section>


        <!-- Sección: INICIO -->
        <section id="inicio" class="seccion activa">
            <h2>Pan fresco y artesanal todos los días <span>con amor</span></h2>
            <p>Disfruta del mejor pan recién hecho, directo desde nuestro horno a tu mesa.</p>
            <div class="slider">
            <div><img src="img/Carrusel1.jpg" alt="Pan recién horneado"></div>
            <div><img src="img/Carrusel2.jpg" alt="Pasteles artesanales"></div>
            <div><img src="img/Carrusel3.jpg" alt="Repostería dulce"></div>
            </div>

            <div class="hero">
                <a class="boton" href="#" data-section="productos">Ver Productos</a>
            </div>
        </section>

        <!-- Sección: NOSOTROS -->
        <section id="nosotros" class="seccion">
            <h2>Sobre Nosotros</h2>
            <p>Somos una panadería familiar con más de 15 años de experiencia. Creemos en el pan hecho con amor, ingredientes frescos y un toque artesanal.</p>
            <p>Nuestra misión es endulzar tus días con productos de calidad.</p>

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

        </section>

        <!-- Sección: PRODUCTOS -->
        <section id="productos" class="seccion">
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
                        
                        if ($row["stock"] > 0) {
                            echo '<p style="color:green;">Disponibles: '. $row["stock"] .' unidades</p>';
                            echo '
                            <form method="POST" action="index.php">
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
        </section>

        <!-- Sección: CARRITO -->
        <section id="carrito" class="seccion">
    <h2>Tu carrito</h2>

    <?php
    $total = 0;
    if (!empty($_SESSION["carrito"])) {
        foreach ($_SESSION["carrito"] as $index => $item) {
            echo "
                <p>
                    <strong>{$item['nombre']}</strong> 
                    x {$item['cantidad']} 
                    – $" . ($item['precio'] * $item['cantidad']) . "
                </p>

                <form method='POST' action='index.php' style='display:inline;'>
                    <input type='hidden' name='eliminar' value='$index'>
                    <button class='boton' style='background:#c0392b;'>Eliminar</button>
                </form>
                <hr>
            ";

            $total += $item['precio'] * $item['cantidad'];
        }

        echo "<h3>Total: $" . number_format($total, 2) . "</h3>";
        ?>
        <form action="checkout.php" method="post" target="_blank">
            <label for="metodo_pago">Selecciona el método de pago:</label>
            <select name="metodo_pago" required>
                <option value="">--Seleccione--</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="PayPal">PayPal</option>
            </select>
            <br><br>
            <input type="submit" value="Finalizar compra" class="boton">
        </form>
        <?php
    } else {
        echo "<p>El carrito está vacío.</p>";
    }
    ?>
</section>



        <!-- Sección: CONTACTO -->
        <section id="contacto" class="seccion">
            <h2>Contáctanos</h2>
            <form class="formulario">
                <fieldset>
                    <legend>Envíanos un mensaje</legend>
                    <label>Nombre</label>
                    <input type="text" placeholder="Tu nombre" required>

                    <label>Correo</label>
                    <input type="email" placeholder="Tu correo" required>

                    <label>Mensaje</label>
                    <textarea placeholder="Escribe tu mensaje aquí..." required></textarea>

                    <input type="submit" value="Enviar" class="boton">
                </fieldset>
            </form>

            <div class="testimonios">
            <h3>Lo que dicen nuestros clientes</h3>
            <blockquote>
            "Las conchas más deliciosas que he probado. Siempre calientitas y el servicio excelente."
            <footer>- Mariana G.</footer>
            </blockquote>
        </div>
        </section>
    </main>


            <!-- JavaScript -->
    <script src="js/secciones.js"></script>
    <script src="js/efectos.js"></script>
    <script src="js/validaciones.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="js/slider.js"></script>


</body>

    <footer class="footer">
        <p>Todos los derechos reservados. Panadería Ronroneos © 2025</p>
    </footer>

</html>
