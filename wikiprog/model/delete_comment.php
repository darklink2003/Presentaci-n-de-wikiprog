<?php
/**
 * ../model/delete_comment.php
 * Elimina un comentario específico si el usuario actual tiene permiso para hacerlo.
 *
 * Este script realiza las siguientes acciones:
 * 1. Incluye el archivo de configuración para la conexión a la base de datos.
 * 2. Inicia la sesión para acceder al ID del usuario actual.
 * 3. Obtiene el ID del comentario y del usuario actual desde la solicitud.
 * 4. Verifica que el comentario pertenece al usuario actual.
 * 5. Elimina el comentario si pertenece al usuario y devuelve una respuesta en formato JSON.
 * @author Pablo Alexander Mondragon Acevedo
 */

// Incluir el archivo de configuración para la conexión a la base de datos
include '../model/db_config.php';

// Iniciar la sesión para acceder al ID del usuario actual
session_start();

// Obtener el ID del usuario actual desde la sesión
$usuario_id_actual = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;

// Obtener el ID del comentario desde la solicitud POST en formato JSON
$data = json_decode(file_get_contents('php://input'), true);
$comentario_id = isset($data['id']) ? intval($data['id']) : 0;

// Inicializar la respuesta
$response = ['success' => false, 'message' => 'Error desconocido.'];

// Verificar que se proporcionaron IDs válidos
if ($comentario_id > 0 && $usuario_id_actual > 0) {
    // Preparar la consulta para verificar que el comentario pertenece al usuario actual
    $stmt = $conn->prepare("SELECT usuario_id FROM comentario WHERE comentario_id = ?");
    $stmt->bind_param("i", $comentario_id);
    $stmt->execute();
    $stmt->bind_result($usuario_id);
    $stmt->fetch();
    $stmt->close();

    // Comprobar si el comentario pertenece al usuario actual
    if ($usuario_id === $usuario_id_actual) {
        // El comentario pertenece al usuario actual, proceder con la eliminación
        $stmt = $conn->prepare("DELETE FROM comentario WHERE comentario_id = ?");
        $stmt->bind_param("i", $comentario_id);

        // Ejecutar la consulta de eliminación
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Comentario eliminado exitosamente.';
            } else {
                $response['message'] = 'No se encontró el comentario para eliminar.';
            }
        } else {
            $response['message'] = 'Error al ejecutar la consulta: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = 'No tienes permiso para eliminar este comentario.';
    }
} else {
    $response['message'] = 'ID del comentario o del usuario no válido.';
}

// Cerrar la conexión a la base de datos
$conn->close();

// Enviar la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>