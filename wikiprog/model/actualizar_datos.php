<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include 'mensajes.php'; ?> <!-- Incluir el archivo de mensajes -->
</head>
<body>
    <?php
    // Inicia la sesión si no está activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['usuario_id'])) {
        alertaErrorSesion();
        exit();
    }

    // Incluir archivo de configuración de la base de datos
    include 'db_config.php';

    // Obtener el usuario_id de la sesión
    $usuario_id = $_SESSION['usuario_id'];

    // Obtener y validar datos del formulario
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $biografia = isset($_POST['biografia']) ? trim($_POST['biografia']) : '';
    $contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';

    $errores = [];

    // Validar nombre
    if (empty($nombre)) {
        $errores[] = 'El nombre es obligatorio.';
    }

    // Validar correo
    if (empty($correo)) {
        $errores[] = 'El correo es obligatorio.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no es válido.';
    }

    // Permitir biografía vacía
    if (empty($biografia)) {
        $biografia = null;
    }

    // Validar contraseña
    if (empty($contraseña)) {
        $errores[] = 'La contraseña es obligatoria.';
    }

    // Manejo de la imagen de perfil
    $directorio_destino = '../img_usuario/';
    $nombre_archivo = '';

    if (!empty($_FILES['perfil_imagen']['name'])) {
        $nombre_archivo = basename($_FILES['perfil_imagen']['name']);
        $ruta_archivo = $directorio_destino . $nombre_archivo;

        // Validar tipo de archivo e imagen
        $tipo_archivo = pathinfo($ruta_archivo, PATHINFO_EXTENSION);
        $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($tipo_archivo, $tipos_permitidos)) {
            $errores[] = 'Tipo de archivo no permitido. Solo se permiten JPG, JPEG, PNG y GIF.';
        }

        // Validar tamaño del archivo
        if ($_FILES['perfil_imagen']['size'] > 500000) { // 500 KB
            $errores[] = 'El tamaño del archivo es demasiado grande. Máximo 500 KB.';
        }

        // Mover el archivo de la carpeta temporal al destino final
        if (count($errores) === 0 && !move_uploaded_file($_FILES['perfil_imagen']['tmp_name'], $ruta_archivo)) {
            $errores[] = 'Error al subir la imagen.';
        }
    }

    // Si hay errores, mostrarlos con SweetAlert y redirigir
    if (count($errores) > 0) {
        alertaError($errores, '../controller/controlador.php?seccion=seccion10');
        exit();
    }

    // Consulta SQL para actualizar datos en la base de datos
    if (!empty($nombre_archivo)) {
        $sql = "UPDATE usuario SET usuario = ?, correo = ?, biografia = ?, contraseña = ?, img_usuario = ? WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $correo, $biografia, $contraseña, $nombre_archivo, $usuario_id);
    } else {
        $sql = "UPDATE usuario SET usuario = ?, correo = ?, biografia = ?, contraseña = ? WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $correo, $biografia, $contraseña, $usuario_id);
    }

    // Ejecutar la actualización y verificar si tuvo éxito
    if ($stmt->execute()) {
        alertaExitoActualizacion('../controller/controlador.php?seccion=seccion9');
    } else {
        $errores[] = 'Error al actualizar los datos.';
        alertaError($errores, '../controller/controlador.php?seccion=seccion10');
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
