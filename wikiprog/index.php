<?php

/**
 * Autor: Camilo Figueroa ( Crivera )
 * Redirecciona según la existencia de la base de datos.
 *
 */

// Configuración de la base de datos
$servername = "127.0.0.1";
$username = "root"; // Reemplaza con tu usuario de MySQL
$password = ""; // Reemplaza con tu contraseña de MySQL

try {
    // Intenta conectar con la base de datos "wikiprog"
    $conn = new PDO("mysql:host=$servername;dbname=wikiprog", $username, $password);
    // Configura el manejo de errores PDO para que lance excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si la conexión se realiza correctamente, redirige al controlador
    header("location: controller/controlador.php");
    exit;
} catch(PDOException $e) {
    // Si hay un error de conexión, redirige al instalador
    header("location: instalador.php");
    exit;
}
