<?php
/**
 * ../model/get_pruebas.php
 * Consulta y devuelve las pruebas asociadas a un curso específico en formato JSON,
 * incluyendo detalles como el nombre del curso, nota y fecha de registro.
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

// Obtener el curso_id desde los parámetros GET y validar
if (isset($_GET['curso_id']) && is_numeric($_GET['curso_id'])) {
    $curso_id = intval($_GET['curso_id']);

    // Query para obtener las pruebas del curso
    $sql = "SELECT p.prueba_id, p.curso_id, p.titulo_prueba, p.contenido, p.archivo_prueba, p.fec_reg
            FROM prueba p
            WHERE p.curso_id = ?";

    // Preparar y ejecutar la declaración
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $curso_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            // Arreglo para almacenar las pruebas
            $pruebas = array();

            // Iterar sobre las pruebas
            while ($row = $result->fetch_assoc()) {
                // Formatear la fecha de registro usando la clase Fecha
                $fecha_formateada = Fecha::mostrarFechas($row['fec_reg']);
                
                $pruebas[] = array(
                    'prueba_id' => $row['prueba_id'],
                    'curso_id' => $row['curso_id'],
                    'titulo_prueba' => $row['titulo_prueba'],
                    'contenido' => $row['contenido'],
                    'archivo_prueba' => $row['archivo_prueba'],
                    'fec_reg' => $fecha_formateada, // Usar la fecha formateada
                );
            }

            // Devolver las pruebas como JSON
            header('Content-Type: application/json');
            echo json_encode($pruebas);
        } else {
            // Si no se encontraron pruebas
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
    // Si no se proporcionó el curso_id o no es válido
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(array('error' => "Error: No se proporcionó un parámetro curso_id válido."));
}

// Cerrar la conexión
$conn->close();
?>
