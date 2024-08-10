<?php
/**
 * Procesa los datos del formulario de registro de usuario.
 * 
 * Este script verifica si los datos del formulario han sido enviados correctamente mediante POST.
 * Obtiene los datos de usuario, correo electrónico, contraseña y establece un rango_id predeterminado.
 * Luego, incluye el archivo 'login.php' y llama al método estático 'registrar' de la clase 'login' para
 * registrar al usuario en la base de datos.
 *
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */

// Verificamos si los datos del formulario han sido enviados correctamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // usuario
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $rango_id = 1; // Rango por defecto

    // Incluir el archivo login.php que contiene la clase Login
    include("login.php");

    // Llamar al método estático registrar de la clase Login para insertar el usuario en la base de datos
    Login::registrar($usuario, $correo, $contraseña, $rango_id);
}
?>