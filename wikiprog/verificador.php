<?php
// verificador.php

$host = '127.0.0.1'; // Host de la base de datos
$db_name = 'wikiprog'; // Nombre de la base de datos
$username = 'root'; // Usuario de la base de datos
$password = ''; // Contraseña de la base de datos

// Conexión a la base de datos
$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si la base de datos existe
$sql = "SHOW TABLES FROM $db_name";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "La base de datos $db_name está instalada correctamente.<br>";
} else {
    echo "La base de datos $db_name no está instalada.<br>";
}

$conn->close();
?>
