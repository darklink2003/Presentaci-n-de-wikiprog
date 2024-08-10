<?php
/**
 * ../model/eliminar_respuesta.php
 * Elimina una respuesta de la base de datos basado en el respuesta_id proporcionado.
 * @version 1.2
 * @author Pablo Alexander Mondragon Acevedo
 */

// Incluir el archivo de configuración de la base de datos
include 'db_config.php';

// Verificar la conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => "Error de conexión: " . $conn->connect_error));
    exit();
}

// Obtener el respuesta_id desde el cuerpo de la solicitud y validar
$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['respuesta_id']) && is_numeric($input['respuesta_id'])) {
    $respuesta_id = intval($input['respuesta_id']);

    // Query para eliminar la respuesta
    $sql = "DELETE FROM respuesta WHERE respuesta_id = ?";

    // Preparar y ejecutar la declaración
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $respuesta_id);
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->affected_rows > 0) {
                // Éxito
                echo json_encode(array('success' => true));
            } else {
                // No se encontró el registro para eliminar
                echo json_encode(array('success' => false, 'error' => "No se encontró la respuesta para eliminar."));
            }
        } else {
            // Error al ejecutar la consulta
            http_response_code(500);
            echo json_encode(array('success' => false, 'error' => "Error al ejecutar la consulta."));
        }
        $stmt->close();
    } else {
        // Error al preparar la declaración
        http_response_code(500);
        echo json_encode(array('success' => false, 'error' => "Error al preparar la consulta."));
    }
} else {
    // Parámetro respuesta_id inválido
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => "Parámetro respuesta_id inválido."));
}

// Cerrar la conexión
$conn->close();
?>
