<?php
/**
 * guardar_edicion_curso.php
 * Este script permite a un usuario autenticado actualizar la información de un curso y sus lecciones.
 * Procesa los datos enviados mediante un formulario POST y actualiza la base de datos.
 * Si se suben archivos, estos son movidos a la carpeta correspondiente.
 * 
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */

// Iniciar sesión para verificar la autenticación del usuario
session_start();

// Incluir la configuración de la conexión a la base de datos
include '../model/db_config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "Acceso denegado.";
    exit; // Terminar la ejecución si no se ha iniciado sesión
}

// Verificar si los datos han sido enviados por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar los datos enviados
    $curso_id = $_POST['curso_id']; // ID del curso a actualizar
    $titulo_curso = !empty($_POST['titulo']) ? trim(mysqli_real_escape_string($conn, $_POST['titulo'])) : null; // Título del curso
    $descripcion = !empty($_POST['descripcion']) ? trim(mysqli_real_escape_string($conn, $_POST['descripcion'])) : null; // Descripción del curso
    $categoria_id = !empty($_POST['categoria']) ? $_POST['categoria'] : null; // ID de la categoría del curso
    $bloqueo = isset($_POST['bloqueo']) ? $_POST['bloqueo'] : null; // Estado de bloqueo del curso

    // Validar que al menos uno de los campos del curso no esté vacío
    if (is_null($titulo_curso) && is_null($descripcion) && is_null($categoria_id) && is_null($bloqueo)) {
        echo "Debe proporcionar al menos un campo para actualizar.";
        exit; // Terminar la ejecución si no hay datos para actualizar
    }

    // Iniciar la consulta de actualización
    $updates = []; // Almacenar los campos que se actualizarán
    $params = []; // Almacenar los valores correspondientes a los campos
    $types = ''; // Almacenar los tipos de datos de los valores

    // Añadir cada campo a la lista de actualización si no está vacío
    if (!is_null($titulo_curso)) {
        $updates[] = 'titulo_curso = ?';
        $params[] = $titulo_curso;
        $types .= 's';
    }
    if (!is_null($descripcion)) {
        $updates[] = 'descripcion = ?';
        $params[] = $descripcion;
        $types .= 's';
    }
    if (!is_null($categoria_id)) {
        $updates[] = 'categoria_id = ?';
        $params[] = $categoria_id;
        $types .= 'i';
    }
    if (!is_null($bloqueo)) {
        $updates[] = 'bloqueo = ?';
        $params[] = $bloqueo;
        $types .= 'i';
    }

    // Añadir el ID del curso a los parámetros y tipos
    $params[] = $curso_id;
    $types .= 'i';

    // Preparar la consulta SQL para actualizar el curso
    $sql = "UPDATE curso SET " . implode(', ', $updates) . " WHERE curso_id = ?";
    $stmt = $conn->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if ($stmt) {
        // Vincular los parámetros a la consulta
        $stmt->bind_param($types, ...$params);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página principal si la actualización fue exitosa
            header("Location: ../index.php");
        } else {
            // Mostrar un mensaje de error si la ejecución falló
            echo "Error al actualizar el curso: " . $stmt->error;
        }

        // Cerrar la declaración preparada
        $stmt->close();
    } else {
        // Mostrar un mensaje de error si la preparación de la consulta falló
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    // Verificar y mover cada archivo de lección si se ha subido alguno
    if (isset($_FILES['lecciones'])) {
        $uploadDir = '../archivos_leccion/'; // Directorio donde se guardarán los archivos de las lecciones

        // Recorrer cada lección enviada
        foreach ($_FILES['lecciones']['tmp_name'] as $index => $tmpNames) {
            foreach ($tmpNames as $field => $tmpName) {
                $leccion_id = $_POST['lecciones'][$index]['leccion_id']; // ID de la lección
                $titulo_leccion = !empty($_POST['lecciones'][$index]['titulo_leccion']) ? $_POST['lecciones'][$index]['titulo_leccion'] : null; // Título de la lección
                $contenido_leccion = !empty($_POST['lecciones'][$index]['contenido']) ? $_POST['lecciones'][$index]['contenido'] : null; // Contenido de la lección

                // Generar un nombre único para el archivo si se ha subido uno
                if ($field === 'archivo_leccion' && is_uploaded_file($tmpName)) {
                    $archivo_name = $_FILES['lecciones']['name'][$index][$field]; // Nombre original del archivo
                    $archivo_tmp_name = $tmpName; // Ruta temporal del archivo
                    $archivo_path = $uploadDir . uniqid() . '_' . $archivo_name; // Ruta final del archivo

                    // Mover el archivo a la carpeta de archivos de lección
                    if (move_uploaded_file($archivo_tmp_name, $archivo_path)) {
                        // Actualizar la ruta del archivo en la base de datos
                        $sql_update_archivo = "UPDATE leccion SET archivo_leccion = ? WHERE leccion_id = ?";
                        $stmt_update_archivo = $conn->prepare($sql_update_archivo);
                        $stmt_update_archivo->bind_param('si', $archivo_path, $leccion_id);
                        $stmt_update_archivo->execute();
                        $stmt_update_archivo->close();
                    } else {
                        // Mostrar un mensaje de error si el archivo no se pudo mover
                        echo "Error al subir el archivo '$archivo_name'.";
                    }
                }

                // Actualizar el título y contenido de la lección en la base de datos
                if (!is_null($titulo_leccion) || !is_null($contenido_leccion)) {
                    $leccion_updates = []; // Almacenar los campos de la lección que se actualizarán
                    $leccion_params = []; // Almacenar los valores correspondientes a los campos de la lección
                    $leccion_types = ''; // Almacenar los tipos de datos de los valores de la lección

                    // Añadir cada campo de la lección a la lista de actualización si no está vacío
                    if (!is_null($titulo_leccion)) {
                        $leccion_updates[] = 'titulo_leccion = ?';
                        $leccion_params[] = $titulo_leccion;
                        $leccion_types .= 's';
                    }
                    if (!is_null($contenido_leccion)) {
                        $leccion_updates[] = 'contenido = ?';
                        $leccion_params[] = $contenido_leccion;
                        $leccion_types .= 's';
                    }

                    // Añadir el ID de la lección a los parámetros y tipos
                    $leccion_params[] = $leccion_id;
                    $leccion_types .= 'i';

                    // Preparar la consulta SQL para actualizar la lección
                    $sql_update_leccion = "UPDATE leccion SET " . implode(', ', $leccion_updates) . " WHERE leccion_id = ?";
                    $stmt_update_leccion = $conn->prepare($sql_update_leccion);
                    $stmt_update_leccion->bind_param($leccion_types, ...$leccion_params);
                    $stmt_update_leccion->execute();
                    $stmt_update_leccion->close();
                }
            }
        }
    }

} else {
    // Mostrar un mensaje de error si el método de solicitud no es POST
    echo "Método de solicitud no permitido.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
