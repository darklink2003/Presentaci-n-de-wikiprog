<?php
// Verificar si la sesión no ha sido iniciada previamente y comenzarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * guardar_inscripcion.php
 * Procesa el formulario para agregar una inscripción de un usuario a un nuevo curso.
 * 
 * Este script recibe datos del formulario POST para inscribir a un usuario en un curso.
 * Verifica si el usuario ya está inscrito en el curso antes de realizar la inserción
 * en la tabla 'inscripción'. Si ocurre algún error durante el proceso, muestra un mensaje de error.
 *
 * @version 1.0
 * @author Pablo Alexander Mondragon Acevedo
 */

// Incluir el archivo de configuración de la base de datos
include 'db_config.php';

// Validar y sanitizar las entradas del formulario
$curso_id = filter_input(INPUT_POST, 'curso_id', FILTER_SANITIZE_NUMBER_INT); // ID del curso
$usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_SANITIZE_NUMBER_INT); // ID del usuario
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING); // Nombre del usuario
$correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL); // Correo electrónico del usuario
$genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING); // Género del usuario
$pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_STRING); // País del usuario
$cursos_anteriores = filter_input(INPUT_POST, 'cursos_anteriores', FILTER_SANITIZE_STRING); // Cursos anteriores tomados por el usuario

// Verificar que todas las variables requeridas tienen valores válidos
if (!$curso_id || !$usuario_id || !$nombre || !$correo || !$genero || !$pais || !$cursos_anteriores) {
    die('Error: Datos del formulario inválidos.'); // Finalizar si algún dato es inválido
}

// Verificar si el usuario ya está inscrito en el curso
$sql_verificar = "SELECT COUNT(*) AS num_inscripciones FROM inscripción WHERE curso_id = ? AND usuario_id = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
if (!$stmt_verificar) {
    die('Error en la preparación de la consulta de verificación: ' . $conn->error); // Finalizar si hay un error en la preparación de la consulta
}
$stmt_verificar->bind_param("ii", $curso_id, $usuario_id); // Vincular parámetros para la consulta
$stmt_verificar->execute(); // Ejecutar la consulta
$resultado_verificar = $stmt_verificar->get_result();

if (!$resultado_verificar) {
    die('Error en la ejecución de la consulta de verificación: ' . $stmt_verificar->error); // Finalizar si hay un error en la ejecución
}

$fila_verificar = $resultado_verificar->fetch_assoc(); // Obtener los resultados de la consulta
$num_inscripciones = $fila_verificar['num_inscripciones']; // Número de inscripciones encontradas

if ($num_inscripciones > 0) {
    die('Error: El usuario ya está inscrito en este curso.'); // Finalizar si el usuario ya está inscrito
}

// Preparar la consulta SQL para insertar los datos en la base de datos
$sql = "INSERT INTO inscripción (curso_id, usuario_id, nombre, correo, genero, pais, cursos_anteriores, fecha_registro) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Error en la preparación de la consulta de inserción: ' . $conn->error); // Finalizar si hay un error en la preparación de la consulta
}
$stmt->bind_param("iisssss", $curso_id, $usuario_id, $nombre, $correo, $genero, $pais, $cursos_anteriores); // Vincular los parámetros
$ejecucion = $stmt->execute(); // Ejecutar la consulta de inserción

// Verificar si la inserción fue exitosa y redirigir o mostrar error
if ($ejecucion) {
    header("Location: ../controller/controlador.php?seccion=seccion3&usuario_id=${usuario_id}");
    exit(); // Asegura que el script se detenga después de la redirección
} else {
    die('Error en la ejecución de la consulta de inserción: ' . $stmt->error); // Finalizar si hay un error en la ejecución
}
?>
