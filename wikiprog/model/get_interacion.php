<?php
/**
 * ../model/get_interacion.php
 * Consulta y devuelve información de interacciones de un curso específico en formato JSON.
 * 
 * Este script realiza una conexión a la base de datos MySQL, consulta la tabla 'interaccioncurso'
 * para obtener el número de likes y dislikes de un curso específico.
 *
 * @version 1.2
 * @author Pablo Alexander Mondragon Acevedo
 */

// Incluir el archivo de configuración de la base de datos
include 'db_config.php';

// Reportar errores de MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Obtener el curso_id de la solicitud GET
$curso_id = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;

// Validar el curso_id
if ($curso_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID de curso inválido']);
    exit;
}

try {
    // Consulta SQL para obtener interacciones del curso específico
    $stmt = $conn->prepare("SELECT tipo_interaccion, COUNT(*) as count FROM interaccioncurso WHERE curso_id = ? GROUP BY tipo_interaccion");
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Inicializar conteos de likes y dislikes
    $interacciones = [
        'like' => 0,
        'dislike' => 0
    ];

    // Contar los likes y dislikes
    while ($row = $result->fetch_assoc()) {
        if ($row['tipo_interaccion'] === 'like') {
            $interacciones['like'] = $row['count'];
        } elseif ($row['tipo_interaccion'] === 'dislike') {
            $interacciones['dislike'] = $row['count'];
        }
    }

    // Convertir el array a formato JSON
    header('Content-Type: application/json');
    echo json_encode($interacciones);
} catch (Exception $e) {
    // Manejar errores
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
    error_log("Error en get_interacion.php: " . $e->getMessage()); // Registrar el error en el archivo de log
} finally {
    $conn->close();
}
?>
