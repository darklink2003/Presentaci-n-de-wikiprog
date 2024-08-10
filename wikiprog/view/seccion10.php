<?php
// Inicia la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: controlador.php?seccion=seccion2&error=not_logged_in");
    exit();
}

// Incluir el archivo de configuración de la base de datos
include '../model/db_config.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el usuario_id de la sesión
$usuario_id = $_SESSION['usuario_id'];

// Consulta SQL para obtener los datos del usuario
$sql = "SELECT usuario, correo, biografia, contraseña FROM usuario WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    // Manejar el caso en que no se encuentren datos del usuario
    $user_data = [
        'usuario' => '',
        'correo' => '',
        'biografia' => '',
        'contraseña' => ''
    ];
}

$stmt->close();
$conn->close();
?>


<div class="container" style="background-color: #292835; padding:30px; color:white; border-radius:15px;">
    <div class="row">
        <div class="col-md-12">
            <div class="py-4 text-center">
                <h2>Perfil</h2>
                <hr>
            </div>
            <div class="row" style="background-color: #1c1b24; padding:20px; color:white; border-radius:15px;>
                <div class=" col-md-12">
                <form action="../model/actualizar_datos.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="formGroupExampleInput">Nombre</label>
                        <input type="text" class="form-control" id="formGroupExampleInput" name="nombre"
                            value="<?php echo htmlspecialchars($user_data['usuario']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Correo Público</label>
                        <input type="email" class="form-control" id="formGroupExampleInput2" name="correo"
                            value="<?php echo htmlspecialchars($user_data['correo']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="biografia">Biografía</label>
                        <textarea class="form-control" id="biografia" name="biografia"
                            rows="3"><?php echo htmlspecialchars($user_data['biografia']); ?></textarea>
                    </div><br>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Contraseña</label>
                        <input type="password" class="form-control" id="formGroupExampleInput2" name="contraseña"
                            value="<?php echo htmlspecialchars($user_data['contraseña']); ?>" required>
                    </div><br>
                    <center>
                        <div class="form-group">
                            <div class="btn btn-primary">
                                <label for="perfil_imagen">Imagen de Perfil</label>
                                <input type="file" class="form-control-file" id="perfil_imagen" name="perfil_imagen">

                            </div>
                        </div><br>

                        <button type="submit" class="btn btn-primary" style="width:50%;">Actualizar Perfil</button>


                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

