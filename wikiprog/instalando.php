<?php
// instalando.php

$host = $_POST['host'];
$db_name = $_POST['db_name'];
$username = $_POST['username'];
$password = $_POST['password'];

// Validar y sanitizar entradas
$host = htmlspecialchars($host);
$db_name = htmlspecialchars($db_name);
$username = htmlspecialchars($username);
$password = htmlspecialchars($password);

// Conexión a la base de datos
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Error de conexión: " . htmlspecialchars($conn->connect_error));
}

// Crear la base de datos
$sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos $db_name creada con éxito.<br>";
} else {
    die("Error creando la base de datos: " . htmlspecialchars($conn->error));
}

// Seleccionar la base de datos
$conn->select_db($db_name);

// Leer y ejecutar el archivo dump.sql
$dump_file = 'dump.sql'; // Asegúrate de tener el archivo dump.sql en el mismo directorio
if (file_exists($dump_file)) {
    $sql = file_get_contents($dump_file);
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        echo "Tablas y estructuras instaladas con éxito.<br>";
    } else {
        die("Error instalando las tablas: " . htmlspecialchars($conn->error));
    }
} else {
    die("Archivo dump.sql no encontrado.<br>");
}

// Leer y ejecutar el archivo funciones.sql
$functions_file = 'funciones.sql'; // Asegúrate de tener el archivo funciones.sql en el mismo directorio
if (file_exists($functions_file)) {
    $sql = file_get_contents($functions_file);

    // Reemplazar delimitadores para manejar correctamente los triggers
    $sql = str_replace('DELIMITER ;;', '', $sql);
    $sql = str_replace(';;', ';', $sql);

    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        echo "Funciones y triggers instalados con éxito.<br>";
    } else {
        die("Error instalando funciones y triggers: " . htmlspecialchars($conn->error));
    }
} else {
    die("Archivo funciones.sql no encontrado.<br>");
}

$conn->close();

echo '<a href="index.php">Volver a la página principal</a>';
?>
