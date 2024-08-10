<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include '../model/mensajes.php'; ?> <!-- Incluir el archivo de mensajes -->
</head>

<body>
    <?php
    /**
     * ../model/delete_archivo.php
     * Script para eliminar un archivo de la base de datos y del sistema de archivos.
     * 
     * Este script recibe el ID del archivo a eliminar mediante una solicitud DELETE.
     * Se conecta a la base de datos y ejecuta la eliminación del archivo.
     * @version 1.2
     * @author Pablo Alexander Mondragon Acevedo
     */

    // Verificar si se recibió el ID del archivo a eliminar
    if (!isset($_GET['archivo_id'])) {
        http_response_code(400); // Bad request
        die("Error: No se proporcionó el ID del archivo.");
    }

    $archivo_id = $_GET['archivo_id'];

    // Incluir el archivo de configuración de la base de datos
    require_once '../model/db_config.php';  // Asegúrate de que la ruta es correcta
    
    // Consulta SQL para obtener la información del archivo antes de eliminarlo
    $sql_select = "SELECT nombre_archivo FROM archivo WHERE archivo_id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $archivo_id);

    if (!$stmt_select->execute()) {
        alertaErrorEliminarArchivo("Error al ejecutar la consulta para obtener el nombre del archivo: " . $stmt_select->error);
        http_response_code(500); // Internal server error
        exit();
    }

    $stmt_select->bind_result($nombre_archivo);

    if (!$stmt_select->fetch()) {
        alertaArchivoNoEncontrado($archivo_id);
        http_response_code(404); // Not found
        exit();
    }

    $stmt_select->close();

    // Consulta SQL para eliminar el archivo por su ID
    $sql_delete = "DELETE FROM archivo WHERE archivo_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $archivo_id);

    if (!$stmt_delete->execute()) {
        alertaErrorEliminarArchivo("Error al eliminar el archivo de la base de datos: " . $stmt_delete->error);
        http_response_code(500); // Internal server error
        exit();
    }

    // Éxito al eliminar el archivo de la base de datos
    http_response_code(204); // No content
    
    // Eliminar el archivo físico de la carpeta archivos_usuarios
    $archivo_path = '../archivos_usuarios/' . $nombre_archivo;
    if (file_exists($archivo_path)) {
        if (!unlink($archivo_path)) {
            alertaErrorEliminarArchivo("Error al eliminar el archivo físico: No se pudo eliminar el archivo.");
            http_response_code(500); // Internal server error
            exit();
        }
    }

    // Mostrar alerta de éxito y redirigir
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Archivo eliminado',
                text: 'El archivo ha sido eliminado exitosamente.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = '../controller/controlador.php?seccion12&usuario_id=" . htmlspecialchars($usuario_id) . "';
            });
          </script>";

    // Cerrar las declaraciones y la conexión
    $stmt_delete->close();
    $conn->close();
    ?>
</body>

</html>