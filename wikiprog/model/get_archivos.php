<?php
/**
 * get_archivos.php
 * Consulta y devuelve los archivos asociados a un usuario específico en formato JSON,
 * incluyendo detalles como el nombre del archivo, tamaño y privacidad.
 * @version 1.2
 * @author Pablo Alexander Mondragon Acevedo
 */

// Incluir el archivo de configuración de la base de datos
include 'db_config.php';
// Incluir el archivo de la clase Fecha
include '../controller/class_fechas.php';
// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el usuario_id desde los parámetros GET
if (isset($_GET['usuario_id'])) {
    $usuario_id = $_GET['usuario_id'];

    // Query para obtener los archivos del usuario
    $sql = "SELECT a.archivo_id, a.nombre_archivo, a.tamaño, a.fecha_registro, a.privacidad_id, a.archivo, p.tipo AS tipo_privacidad
            FROM archivo a
            INNER JOIN privacidad p ON a.privacidad_id = p.privacidad_id
            WHERE a.usuario_id = $usuario_id";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Arreglo para almacenar los archivos
        $archivos = array();

        // Iterar sobre los archivos y construir la ruta completa al archivo
        while ($row = $result->fetch_assoc()) {
            // Construir la ruta completa al archivo (considerando el nombre de la carpeta)
            $ruta_archivo = 'archivos_usuarios/' . $row['archivo'];
            // Formatear la fecha de registro usando la clase Fecha
            $fecha_formateada = Fecha::mostrarFechas($row['fecha_registro']);

            $archivos[] = array(
                'archivo_id' => $row['archivo_id'],
                'nombre_archivo' => $row['nombre_archivo'],
                'tamaño' => $row['tamaño'],
                'fecha_registro' => $fecha_formateada,
                'privacidad' => $row['tipo_privacidad'],
                'enlace_descarga' => $ruta_archivo // Ruta completa al archivo
            );
        }

        // Devolver los archivos como JSON
        header('Content-Type: application/json');
        echo json_encode($archivos);
    } else {
        // Si no se encontraron archivos
        echo json_encode(array()); // Devolver un arreglo vacío
    }
} else {
    // Si no se proporcionó el usuario_id
    echo "Error: No se proporcionó el parámetro usuario_id.";
}

// Cerrar la conexión
$conn->close();
?>