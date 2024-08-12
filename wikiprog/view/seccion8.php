<?php
// Verificar si la sesión ya ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificación básica para asegurar que se ha proporcionado un id válido
if (!isset($_GET['curso_id']) || !is_numeric($_GET['curso_id'])) {
    die('Parámetro de ID inválido.');
}

$curso_id = $_GET['curso_id'];

// Incluir el archivo de configuración de la base de datos
include '../model/db_config.php';

// Consulta SQL para obtener los datos del curso por su ID usando declaraciones preparadas
$sql = "SELECT curso_id, titulo_curso, descripcion, categoria_id, fecha_registro FROM curso WHERE curso_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado) {
    die('Error en la ejecución de la consulta: ' . $stmt->error);
}

// Verificar si se encontró el curso
if ($resultado->num_rows == 1) {
    // Obtener los datos del curso
    $fila = $resultado->fetch_assoc();
    $curso_id = $fila['curso_id'];
    $titulo_curso = htmlspecialchars($fila['titulo_curso']);
    $descripcion = htmlspecialchars($fila['descripcion']);
    $categoria_id = htmlspecialchars($fila['categoria_id']);
    $fecha_registro = $fila['fecha_registro'];
}
?>
<style>
    .card {
        background-color: #495057;
        color: #ffffff;
    }

    .form-control {
        background-color: #6c757d;
        border: none;
        color: #ffffff;
    }

    .form-control::placeholder {
        color: #ced4da;
    }

    .form-check-label {
        color: #ffffff;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }
</style>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="row no-gutters">
                    <!-- Primera columna: Título -->
                    <div class="col-md-4">
                        <div class="row" style="font-size:25px; font-family:Arial; text-align: center; padding:25px;">
                            <h1>
                                Formulario de Inscripción al curso de <p>" <?php echo $titulo_curso; ?> "</p>
                            </h1>
                        </div>
                    </div>
                    <!-- Segunda columna: Formulario -->
                    <div class="col-md-8">
                        <div class="card-body">
                            <form action="../model/guardar_inscripcion.php" method="POST">
                                <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
                                <input type="hidden" name="usuario_id" value="<?php echo $_SESSION['usuario_id']; ?>">
                                <div class="form-group">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="form-group">
                                    <label for="correo">Correo Electrónico:</label>
                                    <input type="email" class="form-control" id="correo" name="correo" required>
                                </div>
                                <div class="form-group">
                                    <label for="genero">Género:</label>
                                    <select class="form-control" id="genero" name="genero" required>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="otro">Otro</option>
                                        <option value="prefiero_no_decirlo">Prefiero no decirlo</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pais">País:</label>
                                    <input type="text" class="form-control" id="pais" name="pais" required>
                                </div>
                                <div class="form-group">
                                    <label for="cursos_anteriores">¿Ha recibido otros cursos anteriormente?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cursos_anteriores"
                                            id="cursos_anteriores_si" value="si" required>
                                        <label class="form-check-label" for="cursos_anteriores_si">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cursos_anteriores"
                                            id="cursos_anteriores_no" value="no" required>
                                        <label class="form-check-label" for="cursos_anteriores_no">No</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar Inscripción</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
