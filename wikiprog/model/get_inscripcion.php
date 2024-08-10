<?php
/**
 * get_inscripcion.php
 * Consulta y devuelve las inscripciones asociadas a un usuario específico en formato JSON,
 * incluyendo detalles como el nombre del curso, nota y fecha de registro.
 * @version 1.2
 * @author Pablo Alexander Mondragon Acevedo
 */

// Incluir el archivo de configuración de la base de datos
include 'db_config.php';

// Incluir la clase Fecha para calcular el tiempo transcurrido
include '../controller/class_fechas.php';

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(array('error' => "Error de conexión: " . $conn->connect_error)));
}

// Obtener el usuario_id desde los parámetros GET y validar
if (isset($_GET['usuario_id']) && is_numeric($_GET['usuario_id'])) {
    $usuario_id = intval($_GET['usuario_id']);

    // Query para obtener las inscripciones del usuario
    $sql = "SELECT i.inscripción_id, i.curso_id, i.nombre, i.correo, i.genero, i.pais, i.cursos_anteriores, i.nota, i.fecha_registro, c.titulo_curso
            FROM inscripción i
            INNER JOIN curso c ON i.curso_id = c.curso_id
            WHERE i.usuario_id = ?";

    // Preparar y ejecutar la declaración
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            // Arreglo para almacenar las inscripciones
            $inscripciones = array();

            // Iterar sobre las inscripciones
            while ($row = $result->fetch_assoc()) {
                // Calcular el tiempo transcurrido usando la clase Fecha
                $tiempo_transcurrido = Fecha::mostrarFechas($row['fecha_registro']);

                $inscripciones[] = array(
                    'inscripción_id' => $row['inscripción_id'],
                    'curso_id' => $row['curso_id'],
                    'nombre' => $row['nombre'],
                    'correo' => $row['correo'],
                    'genero' => $row['genero'],
                    'pais' => $row['pais'],
                    'cursos_anteriores' => $row['cursos_anteriores'],
                    'nota' => $row['nota'],
                    'fecha_registro' => $row['fecha_registro'],
                    'tiempo_transcurrido' => $tiempo_transcurrido, // Tiempo transcurrido
                    'titulo_curso' => $row['titulo_curso']
                );
            }

            // Devolver las inscripciones como JSON
            header('Content-Type: application/json');
            echo json_encode($inscripciones);
        } else {
            // Si no se encontraron inscripciones
            echo json_encode(array()); // Devolver un arreglo vacío
        }

        $stmt->close();
    } else {
        // Error al preparar la declaración
        echo json_encode(array('error' => "Error al preparar la consulta."));
    }
} else {
    // Si no se proporcionó el usuario_id o no es válido
    echo json_encode(array('error' => "Error: No se proporcionó un parámetro usuario_id válido."));
}

// Cerrar la conexión
$conn->close();
?>
