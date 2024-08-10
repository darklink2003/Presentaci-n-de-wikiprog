<?php
/**
 * eliminar.php
 * Elimina un usuario de la base de datos basándose en el parámetro 'id' proporcionado en la URL.
 *
 * Este script realiza las siguientes acciones:
 * 1. Verifica si se ha enviado un parámetro 'id' en la URL.
 * 2. Obtiene el ID del usuario y valida que sea un entero.
 * 3. Conecta a la base de datos.
 * 4. Prepara y ejecuta una consulta SQL para eliminar el usuario con el ID especificado.
 * 5. Redirige a la página principal de usuarios si la eliminación es exitosa.
 * 6. Muestra un mensaje de error si ocurre algún problema durante la eliminación.
 * @version 1.2
 * @author Pablo Alexander Mondragon Acevedo
 */

// Verificar si se ha enviado el parámetro 'id' por la URL
if (isset($_GET['id'])) {
    // Obtener el id del usuario desde la URL y asegurarse de que sea un entero válido
    $usuario_id = intval($_GET['id']);

    // Conexión a la base de datos
    require_once 'db_config.php'; // Asegúrate de que el archivo de conexión esté incluido correctamente

    // Consulta SQL para eliminar el usuario
    $sql = "DELETE FROM usuario WHERE usuario_id = ?";

    // Preparar la declaración SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página principal de usuarios después de eliminar
        header("Location: ../controller/controlador.php?seccion=seccion6");
        exit; // Finalizar el script después de la redirección
    } else {
        // Mostrar un mensaje de error si la eliminación falla
        echo "Error al intentar eliminar el usuario: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si no se proporcionó el parámetro 'id', mostrar un mensaje de error
    echo "No se ha proporcionado el parámetro 'id' para eliminar el usuario.";
}
?>
