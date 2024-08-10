<?php
// Inicia la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../controller/controlador.php?seccion=seccion2&error=not_logged_in");
    exit();
}

// Configuración de la base de datos
// Incluir el archivo de configuración de la base de datos
include '../model/db_config.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el usuario_id de la sesión
$usuario_id = $_SESSION['usuario_id'];

// Consulta SQL para obtener los datos del usuario incluyendo el rango de usuario
$sql = "SELECT usuario, correo, biografia, img_usuario, rango_id FROM usuario WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Mostrar los datos del usuario
    while ($row = $result->fetch_assoc()) {
        // Obtener el texto del rango
        $rango_texto = "Desconocido";
        if (isset($row["rango_id"])) {
            switch ($row["rango_id"]) {
                case 1:
                    $rango_texto = "Usuario";
                    break;
                case 2:
                    $rango_texto = "Administrador";
                    break;
                case 3:
                    $rango_texto = "Evaluador";
                    break;
            }
        }

        ?>
        <div class="container">
            <div class="container_titulo">
                <div class="row">
                    <div class="col-md-3">
                        <div class="circulo">
                            <img src="../img_usuario/<?php echo htmlspecialchars($row["img_usuario"]); ?>"
                                 alt="Imagen de perfil" width="100%" height="100%" class="circulo">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <p>Nombre de usuario: <?php echo htmlspecialchars($row["usuario"]); ?></p><br>
                        <p>Rango: <?php echo htmlspecialchars($rango_texto); ?></p>
                    </div>
                    <div class="col-md-3">
                        <!-- Columna vacía -->
                    </div>
                    <div class="col-md-3" style="display:flex;">
                        <button type="button" id="eliminarCuentaBtn" onclick="abrirModal()">Eliminar Cuenta</button>
                        <button type="button" style="margin-left: 5px;">
                            <a href="controlador.php?seccion=seccion10" style="text-decoration: none; color: white;">Editar Perfil</a>
                        </button>
                    </div>
                </div>

                <!-- Modal -->
                <div id="myModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" onclick="cerrarModal()">&times;</span>
                        <p>¿Seguro que quieres eliminar la cuenta? Todos los archivos relacionados al usuario serán eliminados.</p>
                        <form method="POST" action="../model/eliminar_perfil.php">
                            <button type="submit" id="siBtn" class="btn btn-danger" style="width:100%;">Sí</button>
                        </form>
                        <button id="noBtn" onclick="cerrarModal()" class="btn btn-secondary" style="width:100%;">No</button>
                    </div>
                </div>
            </div>


            <div class="container_usuario">
                <div class="row">
                    <div class="col-md-3">
                        <h5 style="color: white;">Información del usuario</h5>
                    </div>
                    <div class="col-md-9">
                        <!-- Columna vacía -->
                    </div>
                </div>
            </div>

            <div class="container_proyecto">
                <div class="row">
                    <div class="col-md-5 d-flex flex-column align-items-start">
                        <div id="columna1" class="kj" style="padding:10px">
                            <h5 style="color: white; text-align: center; font-size: 30px;">Datos</h5>
                            <p style="color: white; font-size: 20px;">Biografía: <?php echo htmlspecialchars($row["biografia"]); ?></p>
                            <p style="color: white; font-size: 20px;">Correo: <?php echo htmlspecialchars($row["correo"]); ?></p>
                        </div>
                    </div>
                    <div class="col-md-7 d-flex flex-column align-items-center">
                        <div id="columna2" class="div">
                            <?php if ($row["rango_id"] == 1) { ?>
                                <h5 style="color: white; text-align: center;">Bienvenido a Wikirpog</h5>
                                <p style="color: white; text-align: center;">
                                    Bienvenido a Wikiprog, una plataforma gratuita de tutoriales interactivos. Aquí puedes explorar
                                    una variedad de cursos y aprender nuevas habilidades. ¡Disfruta interactuando con nuestros
                                    cursos y descubre todo lo que tenemos para ofrecer!
                                </p>
                                <ul style="list-style-type: none; padding: 0;">
                                    <li>
                                        <a class="btn btn-primary"
                                           href="controlador.php?seccion=seccion3&usuario_id=<?php echo htmlspecialchars($usuario_id); ?>"
                                           style="color: white; text-decoration: none; display: block; padding: 10px;">
                                            Tus Cursos
                                        </a>
                                    </li><br>
                                </ul>
                            <?php } elseif ($row["rango_id"] == 3) { ?>
                                <h5 style="color: white; text-align: center;">Opciones Especiales</h5>
                                <ul style="list-style-type: none; padding: 0;">
                                    <li>
                                        <a class="btn btn-primary"
                                           href="controlador.php?seccion=seccion3&usuario_id=<?php echo htmlspecialchars($usuario_id); ?>"
                                           style="color: white; text-decoration: none; display: block; padding: 10px;">
                                            Tus Cursos
                                        </a>
                                    </li><br>
                                    <li>
                                        <a class="btn btn-primary"
                                           href="controlador.php?seccion=seccion17&usuario_id=<?php echo htmlspecialchars($usuario_id); ?>"
                                           style="color: white; text-decoration: none; display: block; padding: 10px;">
                                            Evaluar
                                        </a>
                                    </li><br>
                                    <li>
                                        <a class="btn btn-primary"
                                           href="controlador.php?seccion=seccion4"
                                           style="color: white; text-decoration: none; display: block; padding: 10px;">
                                            Cursos
                                        </a>
                                    </li>
                                </ul>
                            <?php } else { ?>
                                <h5 style="color: white; text-align: center;">Acciones</h5>
                                <ul style="list-style-type: none; padding: 0;">
                                    <li>
                                        <a class="btn btn-primary"
                                           href="controlador.php?seccion=seccion6"
                                           style="color: white; text-decoration: none; display: block; padding: 10px;">
                                            Administrador
                                        </a>
                                    </li><br>
                                    <li>
                                        <a class="btn btn-primary"
                                           href="controlador.php?seccion=seccion3&usuario_id=<?php echo htmlspecialchars($usuario_id); ?>"
                                           style="color: white; text-decoration: none; display: block; padding: 10px;">
                                            Tus Cursos
                                        </a>
                                    </li><br>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
} else {
    echo "0 resultados";
}

$stmt->close();
$conn->close();
?>

<script>
    function abrirModal() {
        document.getElementById('myModal').style.display = "block";
    }

    function cerrarModal() {
        document.getElementById('myModal').style.display = "none";
    }
</script>
