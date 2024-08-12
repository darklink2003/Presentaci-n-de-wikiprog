<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #222;
            color: #fff;
            padding-top: 20px;
        }

        .container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            background-color: #444;
            color: #fff;
            border: 1px solid #555;
        }

        .form-control:focus {
            background-color: #555;
            color: #fff;
            border-color: #888;
            box-shadow: none;
        }

        label {
            color: #aaa;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>

<body>
    <div class="container2">
        <h2 class="mb-4">Editar Usuario</h2>
        <?php
        // Verificación básica para asegurar que se ha proporcionado un id válido
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            die('Parámetro de ID inválido.');
        }

        $id_usuario = $_GET['id'];

        // Incluir el archivo de configuración de la base de datos
        include '../model/db_config.php';

        // Consulta SQL para obtener los datos del usuario por su ID usando declaraciones preparadas
        $sql = "SELECT usuario_id, usuario, correo, biografia, rango_id, intentos_fallidos, cuenta_bloqueada FROM usuario WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die('Error en la preparación de la consulta: ' . $conn->error);
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if (!$resultado) {
            die('Error en la ejecución de la consulta: ' . $stmt->error);
        }

        // Verificar si se encontró el usuario
        if ($resultado->num_rows == 1) {
            // Obtener los datos del usuario
            $fila = $resultado->fetch_assoc();
            $usuario_id = $fila['usuario_id'];
            $usuario_nombre = htmlspecialchars($fila['usuario']);
            $usuario_correo = htmlspecialchars($fila['correo']);
            $biografia = htmlspecialchars($fila['biografia']);
            $usuario_rango_id = $fila['rango_id'];
            $intentos_fallidos = $fila['intentos_fallidos'];
            $cuenta_bloqueada = $fila['cuenta_bloqueada'];

            // Formulario de edición con dos columnas
            ?>
            <form action="../model/guardar_edicion_usuario.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>" >
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="usuario">Nombre de Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario"
                                value="<?php echo $usuario_nombre; ?>" readonly style="background-color:#444444;">
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo Electrónico:</label>
                            <input type="email" class="form-control" id="correo" name="correo"
                                value="<?php echo $usuario_correo; ?>" readonly style="background-color:#444444;">
                        </div>
                        <div class="form-group">
                            <label for="rango">Rango:</label>
                            <select class="form-control" id="rango" name="rango" required>
                                <option value="1" <?php if ($usuario_rango_id == 1)
                                    echo "selected"; ?>>Usuario</option>
                                <option value="2" <?php if ($usuario_rango_id == 2)
                                    echo "selected"; ?>>Administrador</option>
                                <option value="3" <?php if ($usuario_rango_id == 3)
                                    echo "selected"; ?>>Evaluador</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="biografia">Biografía:</label>
                            <input type="text" class="form-control" id="biografia" name="biografia"
                                value="<?php echo $biografia; ?>" readonly style="background-color:#444444;">
                        </div>
                        <div class="form-group">
                            <label for="intentos_fallidos">Intentos Fallidos:</label>
                            <input type="number" class="form-control" id="intentos_fallidos" name="intentos_fallidos"
                                value="<?php echo $intentos_fallidos; ?>"  style="background-color:#444444;">
                        </div>
                        <div class="form-group">
                            <label for="cuenta_bloqueada">Cuenta Bloqueada:</label>
                            <select class="form-control" id="cuenta_bloqueada" name="cuenta_bloqueada" required>
                                <option value="0" <?php if ($cuenta_bloqueada == 0)
                                    echo "selected"; ?>>No</option>
                                <option value="1" <?php if ($cuenta_bloqueada == 1)
                                    echo "selected"; ?>>Sí</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="../controller/controlador.php?seccion=seccion6" class="btn btn-secondary">Cancelar</a>
            </form>
            <?php
        } else {
            echo "Usuario no encontrado.";
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>

</html>