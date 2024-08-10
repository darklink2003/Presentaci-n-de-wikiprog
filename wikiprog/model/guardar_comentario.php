<?php
/**
 * guardar_comentario.php
 * Procesa el formulario para guardar comentarios asociados a un curso.
 * 
 * Este script recibe datos del formulario POST para agregar un nuevo comentario
 * a la base de datos 'wikiprog'. Verifica la existencia del curso asociado,
 * inicia una conexión con la base de datos y realiza una inserción en la tabla 'comentario'.
 * Si ocurre algún error durante la inserción, muestra un mensaje de error.
 *
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */

// Obtener los datos del formulario
$curso_id = $_POST['curso_id'];
$comentario = $_POST['comentario'];

// Validar que el curso_id sea un número entero válido
if (!is_numeric($curso_id)) {
    die("Error: El ID del curso no es válido.");
}

// Función para obtener el usuario_id del usuario actual (simulada)
function obtenerUsuarioId()
{
    // Iniciar o continuar la sesión
    session_start();

    // Verificar si hay una sesión de usuario activa y obtener el usuario_id
    if (isset($_SESSION['usuario_id'])) {
        return $_SESSION['usuario_id'];
    } else {
        // Redirigir al usuario a la página de inicio de sesión si no hay sesión activa
        header("Location: seccion2.php");
        exit(); // Finalizar la ejecución del script después de redirigir
    }
}

// Obtener el usuario_id del usuario actual
$usuario_id = obtenerUsuarioId();

// Conexión a la base de datos
include 'db_config.php';

// Iniciar una transacción
$conn->begin_transaction();

try {
    // Insertar el comentario en la tabla comentario
    $sql_comentario = "INSERT INTO comentario (curso_id, usuario_id, comentario) VALUES (?, ?, ?)";
    $stmt_comentario = $conn->prepare($sql_comentario);
    if (!$stmt_comentario) {
        throw new Exception("Error en la preparación de la consulta de comentario: " . $conn->error);
    }
    $stmt_comentario->bind_param("iis", $curso_id, $usuario_id, $comentario);
    $stmt_comentario->execute();
    if ($stmt_comentario->errno) {
        throw new Exception("Error en la ejecución de la consulta de comentario: " . $stmt_comentario->error);
    }

    // Confirmar la transacción
    $conn->commit();
    // Redirigir a otra página si es necesario
    header("Location: ../controller/controlador.php?seccion=seccion7&curso_id=$curso_id");
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    echo "Error al guardar el comentario: " . $e->getMessage();
}

// Cerrar las conexiones
if (isset($stmt_comentario)) {
    $stmt_comentario->close();
}
$conn->close();
?>
