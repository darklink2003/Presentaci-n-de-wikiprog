<?php
/**
 * get_lessosns.php
 * Consulta y devuelve las lecciones asociadas a un curso específico en formato JSON.
 * 
 * Este script realiza una conexión a la base de datos MySQL ('wikiprog'), verifica la conexión,
 * y obtiene el ID del curso desde la solicitud GET. Luego, prepara una consulta para seleccionar
 * todas las lecciones asociadas al curso_id proporcionado. Si la preparación de la consulta es
 * exitosa, ejecuta la consulta, obtiene los resultados y los devuelve en formato JSON.
 * En caso de error, devuelve un mensaje de error en formato JSON.
 *
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */

    // Incluir el archivo de configuración de la base de datos
    include 'db_config.php';

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

// Obtener el curso_id de la solicitud GET
$curso_id = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;

// Preparar la consulta para obtener las lecciones asociadas a un curso_id específico
$query = "SELECT * FROM leccion WHERE curso_id = ?";
$stmt = mysqli_prepare($conn, $query);

// Verificar si la preparación fue exitosa
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $curso_id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    $lecciones = array();
    while ($leccion = mysqli_fetch_assoc($resultado)) {
        $lecciones[] = $leccion;
    }

    // Establecer encabezado para indicar que se devolverá JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($lecciones);

    // Cerrar el statement
    mysqli_stmt_close($stmt);
} else {
    // Devolver un mensaje de error en formato JSON si la preparación de la consulta falla
    echo json_encode(array('error' => 'Error en la preparación de la consulta: ' . mysqli_error($conn)));
}

// Cerrar conexión
mysqli_close($conn);
?>