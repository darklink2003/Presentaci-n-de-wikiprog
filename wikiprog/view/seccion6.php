<!--
/**
 * Incluye y muestra el resultado del método estático "ver" de la clase "login".
 *
 * Este fragmento de código PHP incluye el archivo "clase.php", que contiene la definición
 * de la clase "login". Luego, llama al método estático "ver" de la clase "login" mediante
 * la sintaxis de PHP echo(), que muestra el resultado devuelto por dicho método en esta sección
 * del código HTML.
 * 
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo

 */
-->
<?php
include ("../model/clase.php");
// Inicia la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../controller/controlador.php?seccion=seccion2&error=not_logged_in");
    exit();
}
?>
<div class="container">
    <div class="row">
        <h1>Administrador</h1>
    </div>
    <h1>
        Usuarios
    </h1>
    <div class="row">
        <?php
        echo (login::verusuarios());
        ?>
    </div>
    <h1>
        Cursos
    </h1>
    <div class="row">
        <?php
        echo (login::vercursos());
        ?>
    </div>
    <h1>
        Inscripciones
    </h1>
    <div class="row">
        <?php
        echo (login::verinscripciones());
        ?>
    </div>
    <a href="controlador.php?seccion=seccion11" class="btn btn-primary">Ver estadísticas</a>
</div>