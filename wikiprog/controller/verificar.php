<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
// Incluir el archivo de configuración de la base de datos y las funciones de alerta
include('../model/db_config.php');
include('../model/mensajes.php');

// Verificar si se recibieron los datos a través del método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    // Verificar que no estén vacíos
    if (!empty($nombre) && !empty($correo)) {
        // Conexión a la base de datos
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Preparar y ejecutar la consulta SQL
        $stmt = $conn->prepare("SELECT contraseña FROM usuario WHERE usuario = ? AND correo = ?");
        $stmt->bind_param("ss", $nombre, $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró el usuario
        if ($result->num_rows > 0) {
            // Obtener la fila de resultados
            $row = $result->fetch_assoc();
            $contraseña = $row['contraseña'];

            // Redirigir a otro archivo PHP con los datos (reemplazar con manejo seguro)
            alertaCorreoEnviado($nombre);
            header("Location: ../controller/contraseña.php?nombre=" . urlencode($nombre) . "&correo=" . urlencode($correo) . "&contraseña=" . urlencode($contraseña));
            exit(); // Asegúrate de terminar el script después de redirigir
        } else {
            alertaUsuarioNoEncontrado2($nombre);
        }

        // Cerrar la declaración y la conexión
        $stmt->close();
        $conn->close();
    } else {
        alertaCamposVacios();
    }
} else {
    echo "Datos no recibidos correctamente.";
}
?>
</body>
</html>
