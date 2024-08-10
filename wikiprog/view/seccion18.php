<?php
// Incluir la configuración de la base de datos y la clase necesaria
include("../model/clase.php");

// Iniciar la sesión si no está activa
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

// Verificar si se ha proporcionado un curso_id válido
if (!isset($_GET['curso_id']) || !is_numeric($_GET['curso_id'])) {
    echo "<p>ID de curso inválido.</p>";
    exit();
}

$curso_id = intval($_GET['curso_id']);

// Verificar si se ha proporcionado un inscripción_id válido
$inscripcion_id = isset($_GET['inscripción_id']) && is_numeric($_GET['inscripción_id']) ? intval($_GET['inscripción_id']) : 0;

// Verificar si la clase y el método existen antes de llamarlos
if (class_exists('Login') && method_exists('Login', 'verUsuariosInscritos')) {
    $usuarios = Login::verUsuariosInscritos($curso_id);
} else {
    $usuarios = "<p>Error al cargar los usuarios inscritos.</p>";
}
?>

<div class="container" style="margin-top:50px;">
    <h1>Usuarios Inscritos en el Curso</h1>
    <div class="table-responsive">
        <?php echo $usuarios; ?>
    </div>
</div>
