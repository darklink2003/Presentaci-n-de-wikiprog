<?php
/**
 * registro.php
 * Procesamiento del formulario de registro de usuarios.
 *
 * Este archivo se encarga de gestionar la conexión a la base de datos,
 * validar los datos del formulario de registro, proteger contra inyecciones SQL,
 * verificar la existencia del usuario o correo en la base de datos, y realizar
 * la inserción del nuevo usuario si no existe previamente, además de validar
 * la aceptación de términos y condiciones.
 *
 * PHP version 7.4
 *
 * @category Procesamiento
 * @package  WikiProg
 * @version  1.0
 * @autor    Pablo Alexander Mondragon Acevedo
 *           Keiner Yamith Tarache Parra
 */

// Incluir el archivo de configuración de la base de datos
include '../model/db_config.php';

// Obtener los datos del formulario de registro
$user = trim($_POST['username']);
$email = trim($_POST['email']);
$bio = trim($_POST['biografia']);
$pass = trim($_POST['password']);
$terminos = isset($_POST['terminos']);

// Validación básica de los datos de entrada
if (empty($user) || empty($email) || empty($bio) || empty($pass)) {
    echo "Todos los campos son obligatorios.";
    exit;
}

// Verificar si se aceptaron los términos y condiciones
if (!$terminos) {
    echo "Debes aceptar los términos y condiciones para registrarte.";
    exit;
}

// Proteger contra inyecciones SQL y preparar la consulta para verificar existencia
$stmt = $conn->prepare("SELECT * FROM usuario WHERE usuario = ? OR correo = ?");
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

$stmt->bind_param("ss", $user, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "El nombre de usuario o el correo ya están en uso";
    $stmt->close();
    $conn->close();
    exit;
} else {
    // Insertar el nuevo usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuario (usuario, img_usuario, correo, biografia, contraseña, rango_id) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }

    $img_usuario = '../img_usuario/perfil.png';
    $rango_id = 1; // Asignar el rango de usuario básico
    $stmt->bind_param("sssssi", $user, $img_usuario, $email, $bio, $pass, $rango_id);

    if ($stmt->execute()) {
        // Registro exitoso, redirigir a la página de inicio de sesión
        header("Location: ../index.php");
        exit;
    } else {
        // Error al insertar en la base de datos
        echo "Error: " . $stmt->error;
    }
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();
?>
