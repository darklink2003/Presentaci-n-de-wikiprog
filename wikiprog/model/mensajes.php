<?php
// mensajes.php

/**
 * Muestra una alerta utilizando SweetAlert.
 *
 * @param string $tipo Tipo de la alerta (e.g., 'success', 'error', 'warning').
 * @param string $titulo Título de la alerta.
 * @param string $texto Texto de la alerta.
 * @param string $redireccion URL a la que redirigir después de cerrar la alerta.
 */
function mostrarAlerta($tipo, $titulo, $texto, $redireccion)
{
    echo "<script>
        Swal.fire({
            icon: '$tipo',
            title: '$titulo',
            text: '$texto',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '$redireccion';
            }
        });
    </script>";
}

/**
 * Muestra una alerta indicando que la cuenta está bloqueada.
 */
function alertaCuentaBloqueada()
{
    mostrarAlerta('error', 'Cuenta bloqueada', 'Tu cuenta ha sido bloqueada debido a múltiples intentos fallidos. Por favor, contacta al administrador.', 'controlador.php?seccion=seccion20');
}

/**
 * Muestra una alerta indicando que el inicio de sesión fue exitoso.
 */
function alertaInicioExitoso()
{
    mostrarAlerta('success', 'Inicio de sesión exitoso', 'Has iniciado sesión correctamente.', 'controlador.php?seccion=seccion1');
}

/**
 * Muestra una alerta indicando que la contraseña ingresada es incorrecta.
 */
function alertaContrasenaIncorrecta()
{
    mostrarAlerta('error', 'Contraseña incorrecta', 'La contraseña ingresada es incorrecta.', 'controlador.php?seccion=seccion2');
}

/**
 * Muestra una alerta indicando que el usuario no fue encontrado.
 *
 * @param string $usuario El nombre de usuario que no fue encontrado.
 */
function alertaUsuarioNoEncontrado($usuario)
{
    mostrarAlerta('error', 'Usuario no encontrado', "El usuario ingresado no existe: $usuario", 'controlador.php?seccion=seccion3');
}

/**
 * Muestra una alerta indicando que hay campos vacíos.
 */
function alertaCamposVacios()
{
    mostrarAlerta('warning', 'Campos vacíos', 'Por favor, ingresa nombre de usuario y contraseña.', 'controlador.php?seccion=seccion4');
}

/**
 * Muestra una alerta indicando que un archivo se ha subido correctamente.
 *
 * @param int $usuario_id ID del usuario relacionado con el archivo subido.
 */
function alertaArchivoSubido($usuario_id)
{
    $url = '../controller/controlador.php?seccion=seccion12&usuario_id=' . urlencode($usuario_id);
    mostrarAlerta('success', 'Archivo subido correctamente', 'El archivo se ha subido exitosamente.', $url);
}

/**
 * Muestra una alerta indicando un error al subir un archivo.
 *
 * @param string $mensaje Mensaje que describe el error.
 */
function alertaErrorArchivo($mensaje)
{
    mostrarAlerta('error', 'Error al subir el archivo', $mensaje, 'javascript:history.back()');
}

/**
 * Muestra una alerta indicando que un archivo se ha eliminado correctamente.
 *
 * @param string $redirectUrl URL a la que redirigir después de eliminar el archivo.
 */
function alertaArchivoEliminado($redirectUrl)
{
    mostrarAlerta('success', 'Archivo eliminado', 'El archivo se ha eliminado exitosamente.', $redirectUrl);
}

/**
 * Muestra una alerta indicando un error al eliminar un archivo.
 *
 * @param string $mensaje Mensaje que describe el error.
 */
function alertaErrorEliminarArchivo($mensaje)
{
    mostrarAlerta('error', 'Error al eliminar el archivo', $mensaje, 'javascript:history.back()');
}

/**
 * Muestra una alerta indicando que un archivo no fue encontrado.
 *
 * @param int $archivo_id ID del archivo que no fue encontrado.
 */
function alertaArchivoNoEncontrado($archivo_id)
{
    mostrarAlerta('error', 'Archivo no encontrado', "No se encontró el archivo con ID $archivo_id.", 'javascript:history.back()');
}

/**
 * Muestra una alerta indicando que la actualización de datos fue exitosa.
 *
 * @param string $redirectUrl URL a la que redirigir después de la actualización.
 */
function alertaExitoActualizacion($redirectUrl)
{
    mostrarAlerta('success', 'Actualización exitosa', 'Datos actualizados con éxito.', $redirectUrl);
}

/**
 * Muestra una alerta indicando un error durante la actualización de datos.
 *
 * @param array<string> $errores Lista de mensajes de error.
 * @param string $redirectUrl URL a la que redirigir después del error.
 */
function alertaError($errores, $redirectUrl)
{
    $mensajeErrores = implode(' ', $errores);
    mostrarAlerta('error', 'Error en la actualización', $mensajeErrores, $redirectUrl);
}

/**
 * Muestra una alerta indicando que la sesión no está iniciada.
 */
function alertaErrorSesion()
{
    mostrarAlerta('error', 'Sesión no iniciada', 'Debe iniciar sesión para continuar.', '../controller/controlador.php?seccion=seccion2&error=not_logged_in');
}
/**
 * Muestra una alerta indicando que el usuario no fue encontrado.
 *
 * @param string $usuario El nombre de usuario que no fue encontrado.
 */
function alertaUsuarioNoEncontrado2($usuario)
{
    $mensaje = "El usuario ingresado no existe: " . htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8');
    mostrarAlerta('error', 'Usuario no encontrado', $mensaje, 'controlador.php?seccion=seccion20');
}
/**
 * Muestra una alerta indicando que el correo fue enviado.
 *
 * @param string $nombre El nombre de usuario al que se envió el correo.
 */
function alertaCorreoEnviado($nombre)
{
    $mensaje = "Correo de recuperación enviado correctamente al usuario: " . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
    mostrarAlerta('success', 'Correo enviado', $mensaje, 'controlador.php?seccion=seccion1');
}
?>
