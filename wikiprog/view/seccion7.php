<?php
// Obtener el curso_id desde la URL y sanitizarlo
if (isset($_GET['curso_id']) && is_numeric($_GET['curso_id'])) {
    $curso_id = intval($_GET['curso_id']);
} else {
    // Manejar el caso donde no se proporciona un curso_id válido
    die("Error: No se ha proporcionado un ID de curso válido.");
}

?>

<style>
    /* Estilos CSS personalizados */
    .leccion-container,
    .comentario-container {
        background-color: #171717;
        color: white;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }

    .col {
        flex: 0 0 calc(33.33% - 30px);
        max-width: calc(33.33% - 30px);
        padding: 0 15px;
    }

    @media (max-width: 768px) {
        .col {
            flex: 0 0 calc(50% - 30px);
            max-width: calc(50% - 30px);
        }
    }

    @media (max-width: 576px) {
        .col {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }


</style>

<div class="container">
    <div class="container" style="background-color:#3c3d3f; padding:15px; border-radius:15px;">
        <!-- Contenedor de información del curso -->
        <div id="info-curso" class="mt-4 bg-dark p-3 text-white rounded">
            <!-- Aquí se cargará dinámicamente la información del curso y el enlace de inscripción -->
             
        </div>
        <br>
        <!-- Contenedor de lecciones del curso -->
        <div id="lecciones-container" class="mt-12 row">
            <!-- Aquí se cargarán dinámicamente las lecciones del curso -->
        </div>
    </div>


    <!-- Formulario para enviar comentarios -->
    <div id="formulario-comentario-container" class="mt-4 row bg-dark p-2" style="border-radius:15px; color:white;">
        <form action="../model/guardar_comentario.php" method="POST">
            <!-- Campo oculto con el ID del curso -->
            <input type="hidden" name="curso_id"
                value="<?php echo htmlspecialchars($curso_id, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <h2><b>Comentario</b></h2>
                <textarea class="form-control" id="comentario" name="comentario" rows="4" required></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

    <!-- Contenedor de comentarios del curso -->
    <div id="comentario-container" class="mt-4 row">
        <!-- Aquí se cargarán dinámicamente los comentarios del curso -->
    </div>
</div>