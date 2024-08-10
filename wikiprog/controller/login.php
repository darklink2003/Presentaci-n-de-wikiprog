<?php
/**
 * Clase Login para gestionar operaciones relacionadas con usuarios y cursos en la base de datos.
 * 
 * Esta clase facilita la gestión de operaciones CRUD relacionadas con usuarios y cursos en la base de datos.
 * Utiliza métodos para registrar nuevos usuarios y cursos, utilizando consultas preparadas para prevenir
 * inyecciones SQL. También maneja la conexión y desconexión de la base de datos.
 * 
 * Requiere una conexión válida a la base de datos al ser instanciada.
 * 
 * @property mysqli $conexion Conexión activa a la base de datos.
 * 
 * @version 1.0
 * @package Login
 * @author Pablo Alexander Mondragon Acevedo
 */
class Login
{
    /**
     * @var mysqli $conexion Conexión a la base de datos.
     */
    private $conexion;

    /**
     * Constructor de la clase Login.
     * 
     * Establece la conexión a la base de datos al ser instanciada la clase.
     * Si hay un error en la conexión, muestra un mensaje de error y termina la ejecución.
     */
    public function __construct()
    {
        // Conexión a la base de datos
        $this->conexion = new mysqli('localhost', 'root', '', 'wikiprog');

        // Verificar la conexión
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    /**
     * Registra un nuevo usuario en la base de datos.
     * 
     * @param string $usuario Nombre de usuario.
     * @param string $correo Correo electrónico del usuario.
     * @param string $contraseña Contraseña del usuario (se almacenará hasheada en la base de datos).
     * @param int $rango_id ID del rango del usuario.
     * 
     * @return void
     */
    public function registrarUsuario($usuario, $correo, $contraseña, $rango_id)
    {
        // Preparar la consulta para evitar inyecciones SQL
        $stmt = $this->conexion->prepare("INSERT INTO usuario (usuario, correo, contraseña, rango_id) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        // Hashear la contraseña antes de guardarla en la base de datos
        $contraseña_hashed = password_hash($contraseña, PASSWORD_DEFAULT);

        // Vincular los parámetros
        $stmt->bind_param('sssi', $usuario, $correo, $contraseña_hashed, $rango_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            header('Location: controlador.php?seccion=seccion5');
            exit(); // Detener la ejecución después de redirigir
        } else {
            die("Error en la ejecución de la consulta: " . $stmt->error);
        }

        // Cerrar la declaración
       // $stmt->close();
    }

    /**
     * Registra un nuevo curso en la base de datos.
     * 
     * @param int $curso_id ID del curso.
     * @param string $titulo_curso Título del curso.
     * @param string $descripcion Descripción del curso.
     * @param int $categoria_id ID de la categoría del curso.
     * 
     * @return void
     */
    public function registrarCurso($curso_id, $titulo_curso, $descripcion, $categoria_id)
    {
        // Preparar la consulta para evitar inyecciones SQL
        $stmt = $this->conexion->prepare("INSERT INTO curso (curso_id, titulo_curso, descripcion, categoria_id) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        // Vincular los parámetros
        $stmt->bind_param('isss', $curso_id, $titulo_curso, $descripcion, $categoria_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            header('Location: controlador.php?seccion=seccion4');
            exit(); // Detener la ejecución después de redirigir
        } else {
            die("Error en la ejecución de la consulta: " . $stmt->error);
        }

        // Cerrar la declaración
      //  $stmt->close();
    }

    /**
     * Destructor de la clase Login.
     * 
     * Cierra la conexión a la base de datos al destruirse la instancia de la clase.
     * 
     * @return void
     */
    public function __destruct()
    {
        // Cerrar la conexión
        $this->conexion->close();
    }
}
?>