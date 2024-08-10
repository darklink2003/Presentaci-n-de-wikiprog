<?php
/**
 * eliminar_perfil.php
 * Elimina el perfil del usuario actual y todos los datos asociados a él en la base de datos.
 *
 * Este script realiza las siguientes acciones:
 * 1. Verifica si el usuario ha iniciado sesión.
 * 2. Conecta a la base de datos.
 * 3. Inicia una transacción para asegurar que todas las eliminaciones se realicen de manera atómica.
 * 4. Elimina los datos asociados al usuario en varias tablas.
 * 5. Elimina el usuario de la tabla `usuario`.
 * 6. Maneja posibles errores y realiza un rollback en caso de fallo.
 * 7. Redirige al usuario a la página principal con un mensaje de éxito o error.
 * @version 1.2
 * @author Pablo Alexander Mondragon Acevedo
 */

// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al usuario a la página de inicio de sesión si no está logueado
    header("Location: ../controller/controlador.php?seccion=seccion2&error=not_logged_in");
    exit();
}

// Incluir el archivo de configuración de la base de datos
include '../model/db_config.php';

// Verificar la conexión a la base de datos
if ($conn->connect_error) {
    error_log("Error de conexión: " . $conn->connect_error);
    die("Error de conexión. Por favor, intente nuevamente más tarde.");
}

// Obtener el ID del usuario desde la sesión
$usuario_id = (int)$_SESSION['usuario_id'];

// Iniciar una transacción para asegurar la atomicidad de las operaciones
$conn->begin_transaction();

try {
    // Desactivar las restricciones de claves foráneas temporalmente para evitar problemas de integridad referencial
    $conn->query("SET foreign_key_checks = 0");

    // Eliminar datos de la tabla `respuesta` asociados a las inscripciones del usuario
    $sql = "DELETE FROM respuesta WHERE inscripción_id IN (SELECT inscripción_id FROM inscripción WHERE usuario_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Eliminar datos de la tabla `prueba` asociados al usuario
    $sql = "DELETE FROM prueba WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Eliminar datos de la tabla `leccion` asociados a los cursos del usuario
    $sql = "DELETE FROM leccion WHERE curso_id IN (SELECT curso_id FROM curso WHERE usuario_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Eliminar datos de la tabla `inscripción` asociados al usuario
    $sql = "DELETE FROM inscripción WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Eliminar datos de la tabla `curso` creados por el usuario
    $sql = "DELETE FROM curso WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Eliminar datos de la tabla `interaccioncurso` asociados al usuario
    $sql = "DELETE FROM interaccioncurso WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Eliminar datos de la tabla `comentario` hechos por el usuario
    $sql = "DELETE FROM comentario WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Eliminar datos de la tabla `archivo` subidos por el usuario
    $sql = "DELETE FROM archivo WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Finalmente, eliminar el usuario de la tabla `usuario`
    $sql = "DELETE FROM usuario WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Confirmar la transacción si todas las eliminaciones se realizaron correctamente
    $conn->commit();

    // Volver a activar las restricciones de claves foráneas
    $conn->query("SET foreign_key_checks = 1");

    // Destruir la sesión y redirigir al usuario con un mensaje de éxito
    session_destroy();
    header("Location: ../controller/controlador.php?seccion=seccion1&message=account_deleted");
    exit();
} catch (Exception $e) {
    // Revertir la transacción si ocurre un error
    $conn->rollback();
    error_log("Error al eliminar el usuario: " . $e->getMessage());
    echo "Error al eliminar la cuenta. Por favor, intente nuevamente más tarde.";

    // Volver a activar las restricciones de claves foráneas
    $conn->query("SET foreign_key_checks = 1");
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
