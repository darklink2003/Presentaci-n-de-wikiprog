<?php
/**
 * ../model/db_config.php
 * Configuración de la base de datos.
 * 
 * Este script establece la conexión con la base de datos MySQL utilizando las credenciales proporcionadas.
 * Si la conexión falla, muestra un mensaje de error y termina la ejecución del script.
 *
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */

// Variables de configuración de la base de datos
$servername = "127.0.0.1";  // Servidor de la base de datos
$username = "root";         // Usuario de la base de datos
$password = "";             // Contraseña de la base de datos
$dbname = "wikiprog";       // Nombre de la base de datos

// Establecer la conexión con la base de datos usando mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión ha fallado
if ($conn->connect_error) {
    // Mostrar mensaje de error y terminar la ejecución del script
    die("Connection failed: " . $conn->connect_error);
}
?>