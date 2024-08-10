<?php
/**
 * ../model/get_respuestas.php
 * Consulta y devuelve las respuestas asociadas a una inscripción específica en formato JSON,
 * incluyendo detalles como el archivo de la respuesta y la fecha de registro.
 */

// Incluir el archivo de configuración de la base de datos
include 'db_config.php';
// Incluir el archivo de la clase Fecha
include '../controller/class_fechas.php';
// Verificar la conexión
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(array('error' => "Error de conexión: " . $conn->connect_error)));
}

// Obtener el inscripción_id desde los parámetros GET y validar
if (isset($_GET['inscripción_id']) && is_numeric($_GET['inscripción_id'])) {
    $inscripción_id = intval($_GET['inscripción_id']);

    // Query para obtener las respuestas asociadas a la inscripción
    $sql = "SELECT r.respuesta_id, r.prueba_id, r.archivo_respuesta, r.inscripción_id, r.fec_reg
            FROM respuesta r
            WHERE r.inscripción_id = ?";

    // Preparar y ejecutar la declaración
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $inscripción_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            // Arreglo para almacenar las respuestas
            $respuestas = array();

            // Iterar sobre las respuestas
            while ($row = $result->fetch_assoc()) {
                // Formatear la fecha de registro usando la clase Fecha
                $fecha_formateada = Fecha::mostrarFechas($row['fec_reg']);
                $respuestas[] = array(
                    'respuesta_id' => $row['respuesta_id'],
                    'prueba_id' => $row['prueba_id'],
                    'archivo_respuesta' => $row['archivo_respuesta'],
                    'inscripción_id' => $row['inscripción_id'],
                    'fec_reg' => $fecha_formateada, // Usar la fecha formateada
                );
            }

            // Devolver las respuestas como JSON
            header('Content-Type: application/json');
            echo json_encode($respuestas);
        } else {
            // Si no se encontraron respuestas
            http_response_code(204); // Sin contenido
            echo json_encode(array());
        }

        $stmt->close();
    } else {
        // Error al preparar la declaración
        http_response_code(500);
        echo json_encode(array('error' => "Error al preparar la consulta."));
    }
} else {
    // Si no se proporcionó el inscripción_id o no es válido
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(array('error' => "Error: No se proporcionó un parámetro inscripción_id válido."));
}

// Cerrar la conexión
$conn->close();
?>