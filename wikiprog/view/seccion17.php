<?php
include ("../model/clase.php");

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../controller/controlador.php?seccion=seccion2&error=not_logged_in");
    exit();
}

// Obtener el usuario_id de la sesión
$usuario_id = $_SESSION['usuario_id'];

// Verificar si la clase y el método existen antes de llamarlos
if (class_exists('Login') && method_exists('Login', 'vercursos2')) {
    // Llamar al método estático vercursos2 de la clase Login
    $cursos = Login::vercursos2($usuario_id);
} else {
    $cursos = "<p>Error al cargar los cursos.</p>";
}
?>

<h1>Vista del Evaluador</h1>

<div class="row">
    <?php
    echo $cursos;
    ?>
</div>
