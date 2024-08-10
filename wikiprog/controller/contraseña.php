<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body></body>
<?php
// Incluir PHPMailer y sus dependencias
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php'; // Solo si estás usando SMTP
require '../model/mensajes.php'; // Asegúrate de incluir el archivo con las funciones de alerta

// Obtener los datos del GET y sanitizarlos
$nombre = htmlspecialchars($_GET['nombre']);
$correo = filter_var($_GET['correo'], FILTER_SANITIZE_EMAIL);
$contraseña = htmlspecialchars($_GET['contraseña']);

// Verificar que los datos no estén vacíos y que el correo sea válido
if (empty($nombre) || empty($correo) || empty($contraseña)) {
    die("Datos incompletos o inválidos.");
}

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host = 'smtp.mailersend.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'MS_7TqdMh@trial-z3m5jgr0dk0gdpyo.mlsender.net';
    $mail->Password = 'hEr0zjsAdFm0ZgYz';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Destinatarios
    $mail->setFrom('MS_7TqdMh@trial-z3m5jgr0dk0gdpyo.mlsender.net', 'wikiprog');
    $mail->addAddress($correo);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = "Recuperacion de contrasena para el usuario $nombre";
    $mail->Body = "Su contrasena es: $contraseña";
    // $mail->AltBody = 'Texto alternativo para clientes que no soportan HTML';

    $mail->send();
    alertaCorreoEnviado($nombre);
    exit(); // Asegura que el script se detenga aquí después de redirigir
} catch (Exception $e) {
    echo "Error al enviar el correo: " . $mail->ErrorInfo;
}
?>
</body>
</html>
