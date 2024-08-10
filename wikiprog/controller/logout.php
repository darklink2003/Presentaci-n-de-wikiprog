<?php
/**
 * Cerrar sesión de usuario.
 * 
 * Este script PHP cierra la sesión de usuario activa, desactivando y eliminando todas las variables de sesión.
 * Luego redirige al usuario a la sección especificada del controlador.
 * 
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 *          Keiner Yamith Tarache Parra
 */

session_start(); // Inicia la sesión si no está activa

session_unset(); // Desactiva todas las variables de sesión
session_destroy(); // Destruye las sesiones

// Redirigir a la sección correspondiente del controlador
header("Location: controlador.php?seccion=seccion2");
exit();
?>