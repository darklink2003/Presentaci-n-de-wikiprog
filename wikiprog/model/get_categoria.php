<?php
/**
 * ../model/get_categoria.php
 * Consulta y devuelve la descripción de una categoría en formato JSON.
 * 
 * Este script realiza una conexión a la base de datos MySQL, consulta la tabla 'categoria' para obtener
 * la descripción de la categoría con base en el ID proporcionado.
 * Luego, cierra la conexión y devuelve los datos obtenidos en formato JSON.
 *
 * @version 1.0
 * author Pablo Alexander Mondragon Acevedo
 */

// Incluir el archivo de configuración de la base de datos
include 'db_config.php';

// Obtener el ID de la categoría desde los parámetros de la solicitud
$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

if ($categoria_id > 0) {
    // Consulta SQL para obtener la descripción de la categoría
    $sql = "SELECT descripcion FROM categoria WHERE categoria_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $categoria_id);
        $stmt->execute();
        $stmt->bind_result($descripcion);
        
        $categoria = array();
        if ($stmt->fetch()) {
            $categoria['descripcion'] = $descripcion;
        } else {
            $categoria['descripcion'] = null;
        }
        
        $stmt->close();
    } else {
        $categoria['descripcion'] = null;
    }
} else {
    $categoria['descripcion'] = null;
}

// Cerrar la conexión a la base de datos
$conn->close();

// Establecer encabezado para indicar que se devolverá JSON
header('Content-Type: application/json');

// Devolver los datos en formato JSON
echo json_encode($categoria);
?>
