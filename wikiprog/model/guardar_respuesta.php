<?php
/**
 * guardar_respuesta.php
 * Procesa el formulario para guardar la respuesta de un usuario a una prueba, incluyendo un archivo adjunto.
 * 
 * Este script recibe datos a través de un formulario POST, maneja la carga de un archivo y guarda los detalles en la base de datos.
 * Si la carga o el guardado fallan, se muestran mensajes de error específicos.
 *
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */

// Incluir la configuración de la base de datos
include '../model/db_config.php';

// Verificar si se han recibido los datos del formulario y si se han enviado correctamente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_respuesta']) && isset($_POST['prueba_id']) && isset($_POST['inscripción_id'])) {

    // Obtener y validar los datos del formulario
    $prueba_id = intval($_POST['prueba_id']); // ID de la prueba
    $inscripción_id = intval($_POST['inscripción_id']); // ID de la inscripción
    
    // Manejar el archivo subido
    $archivo_respuesta = $_FILES['archivo_respuesta'];
    $archivo_nombre = basename($archivo_respuesta['name']); // Obtener el nombre del archivo
    $archivo_temporal = $archivo_respuesta['tmp_name']; // Ruta temporal del archivo en el servidor
    $archivo_destino = "../archivos_respuesta/" . $archivo_nombre; // Ruta de destino donde se guardará el archivo

    // Verificar si el archivo es válido y pertenece a los tipos permitidos
    $archivo_tipo = mime_content_type($archivo_temporal); // Tipo MIME del archivo
    $tipos_permitidos = ['application/pdf', 'image/jpeg', 'image/png']; // Tipos de archivo permitidos

    // Validar el archivo y moverlo a la ubicación final
    if (is_uploaded_file($archivo_temporal) && in_array($archivo_tipo, $tipos_permitidos)) {
        if (move_uploaded_file($archivo_temporal, $archivo_destino)) {
            // Preparar la consulta SQL para insertar los datos en la base de datos
            $query = "INSERT INTO respuesta (prueba_id, archivo_respuesta, inscripción_id, fec_reg) VALUES (?, ?, ?, NOW())";
            if ($stmt = $conn->prepare($query)) {
                // Vincular parámetros y ejecutar la consulta
                $stmt->bind_param("isi", $prueba_id, $archivo_nombre, $inscripción_id);
                if ($stmt->execute()) {
                    // Redirigir a la página deseada después de la ejecución exitosa
                    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'default_id'; // Ajustar según tu lógica
                    header("Location: ../controller/controlador.php?seccion=seccion1");
                    exit();
                } else {
                    // Registrar y mostrar un error si la consulta falla
                    error_log("Error al guardar la respuesta en la base de datos: " . $stmt->error);
                    echo "Error al guardar la respuesta.";
                }
                $stmt->close(); // Cerrar la declaración
            } else {
                // Registrar y mostrar un error si la preparación de la consulta falla
                error_log("Error en la preparación de la consulta: " . $conn->error);
                echo "Error en la preparación de la consulta.";
            }
        } else {
            // Mostrar un error si no se pudo mover el archivo subido
            echo "Error al mover el archivo subido.";
        }
    } else {
        // Mostrar un mensaje si el archivo no es válido o permitido
        echo "Archivo no válido o no permitido.";
    }
} else {
    // Mostrar un mensaje si los datos del formulario están incompletos
    echo "Datos del formulario incompletos.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
