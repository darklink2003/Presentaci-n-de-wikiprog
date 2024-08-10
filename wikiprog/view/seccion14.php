<!-- seccion14.php -->
<?php
// Incluir la configuración de la base de datos
include '../model/db_config.php';

// Verificar si se recibió un ID de curso válido y no está vacío
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $curso_id = $_GET['id'];

    // Consulta SQL para obtener los datos del curso específico (mejor usar consulta preparada)
    $sql = "SELECT * FROM curso WHERE curso_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $curso_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar si se encontró el curso
        if ($resultado->num_rows > 0) {
            $curso = $resultado->fetch_assoc();
        } else {
            // Si no se encuentra el curso, redirigir o mostrar un mensaje de error
            die("Curso no encontrado.");
        }

        // Consulta SQL para obtener las lecciones del curso
        $sql_lecciones = "SELECT * FROM leccion WHERE curso_id = ?";
        $stmt_lecciones = $conn->prepare($sql_lecciones);
        $stmt_lecciones->bind_param('i', $curso_id);
        $stmt_lecciones->execute();
        $resultado_lecciones = $stmt_lecciones->get_result();

        // Verificar si hay lecciones asociadas al curso
        $lecciones = [];
        if ($resultado_lecciones->num_rows > 0) {
            while ($row = $resultado_lecciones->fetch_assoc()) {
                $lecciones[] = $row;
            }
        }

        $stmt_lecciones->close();
        $stmt->close();
    } else {
        die("Error en la preparación de la consulta.");
    }
} else {
    // Si no se proporciona un ID de curso válido, redirigir o mostrar un mensaje de error
    die("ID de curso no válido.");
}

// Si se recibe un POST con datos de curso y lecciones, actualizar la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar los datos del curso
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria'];

    // Actualizar el curso en la base de datos
    $sql_update_curso = "UPDATE curso SET titulo_curso = ?, descripcion = ?, categoria_id = ? WHERE curso_id = ?";
    $stmt_update_curso = $conn->prepare($sql_update_curso);
    $stmt_update_curso->bind_param('ssii', $titulo, $descripcion, $categoria_id, $curso_id);
    $stmt_update_curso->execute();
    $stmt_update_curso->close();

    // Procesar los datos de las lecciones
    if (isset($_POST['lecciones'])) {
        foreach ($_POST['lecciones'] as $leccion_id => $leccion_data) {
            $titulo_leccion = $leccion_data['titulo_leccion'];
            $contenido = $leccion_data['contenido'];

            // Si se subió un archivo para la lección, procesarlo
            if ($_FILES['lecciones']['error'][$leccion_id]['archivo_leccion'] === UPLOAD_ERR_OK) {
                $archivo_nombre = $_FILES['lecciones']['name'][$leccion_id]['archivo_leccion'];
                $archivo_tmp = $_FILES['lecciones']['tmp_name'][$leccion_id]['archivo_leccion'];
                $archivo_tipo = $_FILES['lecciones']['type'][$leccion_id]['archivo_leccion'];
                $archivo_tamano = $_FILES['lecciones']['size'][$leccion_id]['archivo_leccion'];

                // Guardar el archivo en una ubicación deseada y actualizar el registro en la base de datos
                $ruta_archivo = '../uploads/' . $archivo_nombre;
                move_uploaded_file($archivo_tmp, $ruta_archivo);

                // Actualizar el nombre del archivo en la base de datos
                $sql_update_archivo = "UPDATE leccion SET archivo_leccion = ? WHERE leccion_id = ?";
                $stmt_update_archivo = $conn->prepare($sql_update_archivo);
                $stmt_update_archivo->bind_param('si', $archivo_nombre, $leccion_id);
                $stmt_update_archivo->execute();
                $stmt_update_archivo->close();
            }

            // Actualizar el título y contenido de la lección en la base de datos
            $sql_update_leccion = "UPDATE leccion SET titulo_leccion = ?, contenido = ? WHERE leccion_id = ?";
            $stmt_update_leccion = $conn->prepare($sql_update_leccion);
            $stmt_update_leccion->bind_param('ssi', $titulo_leccion, $contenido, $leccion_id);
            $stmt_update_leccion->execute();
            $stmt_update_leccion->close();
        }
    }

    // Redirigir después de guardar los cambios
    header("Location: ../controller/controlador.php?seccion=seccion6");
    exit();
}

// Cerrar la conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #222;
            color: #fff;
            padding-top: 20px;
        }

        .container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            background-color: #444;
            color: #fff;
            border: 1px solid #555;
        }

        .form-control:focus {
            background-color: #555;
            color: #fff;
            border-color: #888;
            box-shadow: none;
        }

        label {
            color: #aaa;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>

<body>
    <div class="container1">
        <div class="row">
            <div class="col-md-2">
                <h2 class="text-center">Editar Curso</h2>
                <form action="../model/guardar_edicion_curso.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="curso_id" value="<?php echo htmlspecialchars($curso['curso_id']); ?>">
                    <div class="form-group">
                        <label for="titulo">Título del Curso:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo"
                            value="<?php echo htmlspecialchars($curso['titulo_curso']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5"
                            required><?php echo htmlspecialchars($curso['descripcion']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select class="form-control" id="categoria" name="categoria" required>
                            <option value="1" <?php echo ($curso['categoria_id'] == 1) ? 'selected' : ''; ?>>Código
                            </option>
                            <option value="2" <?php echo ($curso['categoria_id'] == 2) ? 'selected' : ''; ?>>Lógica del
                                programador</option>
                            <option value="3" <?php echo ($curso['categoria_id'] == 3) ? 'selected' : ''; ?>>Estilo
                            </option>
                            <option value="4" <?php echo ($curso['categoria_id'] == 4) ? 'selected' : ''; ?>>Base de datos
                            </option>
                            <option value="5" <?php echo ($curso['categoria_id'] == 5) ? 'selected' : ''; ?>>Otro</option>
                            <option value="6" <?php echo ($curso['categoria_id'] == 6) ? 'selected' : ''; ?>>AJAX</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bloqueo">Estado:</label>
                        <select class="form-control" id="bloqueo" name="bloqueo" required>
                            <option value="0" <?php echo ($curso['bloqueo'] == 0) ? 'selected' : ''; ?>>En línea</option>
                            <option value="1" <?php echo ($curso['bloqueo'] == 1) ? 'selected' : ''; ?>>Bloqueado</option>
                        </select>
                    </div>
            </div>
            <div class="col-md-10">
                <h2 class="text-center">Lecciones</h2>
                <div class="row">
                    <?php foreach ($lecciones as $leccion): ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" name="lecciones[<?php echo $leccion['leccion_id']; ?>][leccion_id]"
                                    value="<?php echo $leccion['leccion_id']; ?>">
                                <label for="titulo_leccion_<?php echo $leccion['leccion_id']; ?>">Título de la
                                    Lección:</label>
                                <input type="text" class="form-control"
                                    id="titulo_leccion_<?php echo $leccion['leccion_id']; ?>"
                                    name="lecciones[<?php echo $leccion['leccion_id']; ?>][titulo_leccion]"
                                    value="<?php echo htmlspecialchars($leccion['titulo_leccion']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contenido_<?php echo $leccion['leccion_id']; ?>">Contenido de la
                                    Lección:</label>
                                <textarea class="form-control" id="contenido_<?php echo $leccion['leccion_id']; ?>"
                                    name="lecciones[<?php echo $leccion['leccion_id']; ?>][contenido]" rows="5"
                                    required><?php echo htmlspecialchars($leccion['contenido']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="archivo_leccion_<?php echo $leccion['leccion_id']; ?>">Archivo:</label>
                                <input type="file" class="form-control-file"
                                    id="archivo_leccion_<?php echo $leccion['leccion_id']; ?>"
                                    name="lecciones[<?php echo $leccion['leccion_id']; ?>][archivo_leccion]">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
                <a href="../controller/controlador.php?seccion=seccion6"
                    class="btn btn-secondary btn-block">Cancelar</a>
            </div>
        </div>
        </form>
    </div>
</body>

</html>