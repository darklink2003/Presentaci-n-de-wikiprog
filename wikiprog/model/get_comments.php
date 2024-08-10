<?php
/**
 * get_comments.php
 * Consulta y devuelve los comentarios asociados a un curso específico en formato JSON.
 *
 * @version 1.3
 * author Pablo Alexander Mondragon Acevedo
 */

// Incluir el archivo de configuración de la base de datos
include 'db_config.php';
// Incluir la clase Fecha para calcular el tiempo transcurrido
include '../controller/class_fechas.php';
// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el curso_id de la solicitud GET
$curso_id = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;

// Preparar la consulta para obtener los comentarios asociados a un curso_id específico
$query = "SELECT c.comentario_id, c.usuario_id, c.curso_id, c.comentario, c.fecha_registro, u.usuario AS nombre_usuario
          FROM comentario c
          INNER JOIN usuario u ON c.usuario_id = u.usuario_id
          WHERE c.curso_id = ?
          ORDER BY c.fecha_registro DESC";

$stmt = $conn->prepare($query);

// Verificar si la preparación fue exitosa
if ($stmt) {
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $comentarios = array();
    while ($comentario = $resultado->fetch_assoc()) {
        // Calcular el tiempo transcurrido usando la clase Fecha
        $tiempo_transcurrido = Fecha::mostrarFechas($comentario['fecha_registro']);

        $comentarios[] = array(
            'comentario_id' => $comentario['comentario_id'],
            'usuario_id' => $comentario['usuario_id'],
            'curso_id' => $comentario['curso_id'],
            'comentario' => $comentario['comentario'],
            'fecha_registro' => $comentario['fecha_registro'],
            'tiempo_transcurrido' => $tiempo_transcurrido, // Tiempo transcurrido
            'nombre_usuario' => $comentario['nombre_usuario']
        );
    }

    // Establecer encabezado para indicar que se devolverá JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($comentarios);

    // Cerrar el statement
    $stmt->close();
} else {
    // Devolver un mensaje de error en formato JSON si la preparación de la consulta falla
    echo json_encode(array('error' => 'Error en la preparación de la consulta: ' . $conn->error));
}

// Cerrar conexión
$conn->close();
?>
