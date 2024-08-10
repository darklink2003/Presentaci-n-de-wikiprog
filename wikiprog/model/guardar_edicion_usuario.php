<?php
/**
 * guardar_edicion_usuario.php
 * Este script procesa un formulario enviado por POST para actualizar la información de un usuario en la base de datos.
 * Verifica que los datos sean válidos antes de realizar la actualización en la base de datos.
 * 
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */

// Verificación de petición POST para asegurar que los datos se están enviando correctamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificación básica para asegurar que se ha proporcionado un id de usuario válido
    if (!isset($_POST['usuario_id']) || !is_numeric($_POST['usuario_id'])) {
        die('Parámetro de ID inválido.');
    }

    // Obtener y sanitizar los datos del formulario
    $usuario_id = $_POST['usuario_id']; // ID del usuario a actualizar
    $usuario_nombre = htmlspecialchars($_POST['usuario'], ENT_QUOTES, 'UTF-8'); // Nombre del usuario, sanitizado para evitar XSS
    $usuario_correo = htmlspecialchars($_POST['correo'], ENT_QUOTES, 'UTF-8'); // Correo del usuario, sanitizado para evitar XSS
    $biografia = htmlspecialchars($_POST['biografia'], ENT_QUOTES, 'UTF-8'); // Biografía del usuario, sanitizada para evitar XSS
    $usuario_rango_id = $_POST['rango']; // ID del rango del usuario
    $intentos_fallidos = $_POST['intentos_fallidos']; // Número de intentos fallidos de inicio de sesión
    $cuenta_bloqueada = $_POST['cuenta_bloqueada']; // Estado de bloqueo de la cuenta

    // Incluir el archivo de configuración de la base de datos
    include 'db_config.php';

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error); // Finalizar si hay un error en la conexión
    }

    // Consulta SQL para actualizar los datos del usuario usando declaraciones preparadas
    $sql = "UPDATE usuario SET usuario = ?, correo = ?, biografia = ?, rango_id = ?, intentos_fallidos = ?, cuenta_bloqueada = ? WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Error en la preparación de la consulta: ' . $conn->error); // Finalizar si hay un error en la preparación
    }
    
    // Vincular los parámetros a la consulta preparada
    $stmt->bind_param("sssiiii", $usuario_nombre, $usuario_correo, $biografia, $usuario_rango_id, $intentos_fallidos, $cuenta_bloqueada, $usuario_id);
    
    // Ejecución de la consulta
    if ($stmt->execute() === TRUE) {
        // Redirigir de vuelta a la página principal o a donde sea apropiado después de editar
        header("Location: ../controller/controlador.php?seccion=seccion6");
        exit();
    } else {
        // Mostrar un mensaje de error si la ejecución falla
        echo "Error al actualizar usuario: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si no es una petición POST, redirigir a alguna página de error o a donde sea apropiado
    header("Location: ../controller/controlador.php?seccion=seccion6");
    exit();
}
?>
