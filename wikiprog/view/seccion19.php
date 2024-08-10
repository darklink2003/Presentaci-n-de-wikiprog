<?php
// Incluir la configuración de la base de datos y la clase necesaria
include ("../model/clase.php");

// Iniciar la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../controller/controlador.php?seccion=seccion2&error=not_logged_in");
    exit();
}

// Verificar si se han proporcionado usuario_id y curso_id válidos
if (
    !isset($_GET['usuario_id']) || !is_numeric($_GET['usuario_id'])
    || !isset($_GET['curso_id']) || !is_numeric($_GET['curso_id'])
    || !isset($_GET['inscripción_id']) || !is_numeric($_GET['inscripción_id'])
) {
    echo "<p>ID de usuario o curso inválido.</p>";
    exit();
}

$usuario_id = intval($_GET['usuario_id']);
$curso_id = intval($_GET['curso_id']);
$inscripción_id = intval($_GET['inscripción_id']);

// Obtener la información del usuario inscrito
if (class_exists('Login') && method_exists('Login', 'getInscripcionInfo')) {
    $usuario_info = Login::getInscripcionInfo($usuario_id, $curso_id, $inscripción_id);
} else {
    $usuario_info = "<p>Error al obtener la información del usuario.</p>";
}

// Procesar el formulario de calificación si se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar y sanitizar la entrada
    $nota = isset($_POST['nota']) ? intval($_POST['nota']) : 0;
    $nota = max(0, min(100, $nota)); // Asegurarse de que la nota esté entre 0 y 100

    // Actualizar la calificación en la base de datos
    if (class_exists('Login') && method_exists('Login', 'actualizarNota')) {
        $actualizacion = Login::actualizarNota($usuario_id, $curso_id, $nota);
        if ($actualizacion) {
            echo "<p>Calificación actualizada con éxito.</p>";
        } else {
            echo "<p>Error al actualizar la calificación.</p>";
        }
    }
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-dark text-light p-4 rounded-3">
            <h1>Calificar Usuario</h1>
            <?php if (is_array($usuario_info) && count($usuario_info) > 0): ?>
                <div class="row" style="border: 1px solid white; padding:10px; border-radius:15px;">
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Nombre:</strong></div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($usuario_info['nombre'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Correo:</strong></div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($usuario_info['correo'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Curso:</strong></div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($usuario_info['titulo_curso'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Respuesta de usuario:</strong></div>
                        <div class="col-sm-8">
                            <?php if (isset($usuario_info['archivo_respuesta'])): ?>
                                <?php
                                $archivo_respuesta = htmlspecialchars($usuario_info['archivo_respuesta'], ENT_QUOTES, 'UTF-8');
                                $enlace_descarga = "../archivos_respuesta/" . $archivo_respuesta; // Ajusta esta ruta según sea necesario
                                ?>
                                <a href="<?php echo $enlace_descarga; ?>" download="<?php echo $archivo_respuesta; ?>">
                                    Descargar <?php echo $archivo_respuesta; ?>
                                </a>
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="nota" class="form-label">Nota</label>
                        <input type="number" class="form-control" id="nota" name="nota" min="0" max="100" required
                            value="<?php echo isset($usuario_info['nota']) ? htmlspecialchars($usuario_info['nota'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Calificación</button>
                    <a href="../controller/controlador.php?seccion=seccion18&curso_id=<?php echo urlencode($curso_id); ?>" class="btn btn-primary btn-md">Regresar</a>
                </form>
            <?php else: ?>
                <p>No se encontró la información del usuario.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
