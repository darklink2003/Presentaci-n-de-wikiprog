<?php
/**
 * Clase Login para manejar operaciones relacionadas con usuarios en la base de datos.
 * clase.php
 * Esta clase permite registrar nuevos usuarios y recuperar datos de usuarios existentes.
 * 
 * @version 1.1
 * @author Pablo Alexander Mondragon Acevedo
 */
class Login
{
    /**
     * Registra un nuevo usuario en la base de datos.
     *
     * @param string $usuario El nombre de usuario.
     * @param string $correo La dirección de correo electrónico del usuario.
     * @param string $contraseña La contraseña del usuario.
     * @param int $rango_id El identificador del rango o rol del usuario.
     * @return void
     */
    public static function registrar($usuario, $correo, $contraseña, $rango_id)
    {
        include 'db_config.php';

        $sql = "INSERT INTO usuario (usuario, correo, contraseña, rango_id) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $usuario, $correo, $contraseña, $rango_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: ../controller/controlador.php?seccion=seccion6");
                exit;
            } else {
                echo "Error al registrar el usuario: " . $stmt->error;
            }

            $stmt->close();
        } else {
        }

        $conn->close();
    }

    /**
     * Recupera y muestra los datos de todos los usuarios registrados en la base de datos.
     *
     * @return string Cadena de texto con los datos de todos los usuarios.
     */
    public static function verusuarios()
    {
        include 'db_config.php';
        include_once '../controller/class_fechas.php';

        $salida = "";

        $sql = "SELECT usuario_id, usuario, img_usuario, correo, biografia, rango_id, fecha_registro, intentos_fallidos, cuenta_bloqueada FROM usuario";

        if ($consulta = $conn->query($sql)) {
            $conn->set_charset("utf8mb4");

            $salida = '<div class="container-fluid" style="max-width:110%;">';
            $salida .= '<div class="table-responsive">';
            $salida .= '<table class="table table-striped table-hover table-bordered">';
            $salida .= '<thead class="table-dark">';
            $salida .= '<tr>';
            $salida .= '<th scope="col">Usuario</th>';
            $salida .= '<th scope="col">Correo</th>';
            $salida .= '<th scope="col">Biografía</th>';
            $salida .= '<th scope="col">Rango</th>';
            $salida .= '<th scope="col">Fecha de Registro</th>';
            $salida .= '<th scope="col">Intentos Fallidos</th>';
            $salida .= '<th scope="col">Cuenta Bloqueada</th>';
            $salida .= '<th scope="col">Editar</th>';
            $salida .= '<th scope="col">Eliminar</th>';
            $salida .= '</tr>';
            $salida .= '</thead>';
            $salida .= '<tbody>';

            while ($fila = $consulta->fetch_assoc()) {
                $usuario_id = $fila['usuario_id'];
                $rango_texto = [
                    1 => "Usuario",
                    2 => "Administrador",
                    3 => "Evaluador"
                ][$fila["rango_id"]] ?? "Desconocido";

                $cuenta_bloqueada_texto = $fila['cuenta_bloqueada'] ? "Sí" : "No";
                $fecha_registro = !empty($fila['fecha_registro']) ? Fecha::mostrarFechas($fila['fecha_registro']) : 'Fecha no disponible';

                $salida .= '<tr>';
                $salida .= '<td>' . htmlspecialchars($fila['usuario'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . htmlspecialchars($fila['correo'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . htmlspecialchars($fila['biografia'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . $rango_texto . '</td>';
                $salida .= '<td>' . htmlspecialchars($fecha_registro, ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . htmlspecialchars($fila['intentos_fallidos'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . $cuenta_bloqueada_texto . '</td>';
                $salida .= '<td><a href="../controller/controlador.php?seccion=seccion13&id=' . $usuario_id . '" class="btn btn-primary btn-sm">Editar</a></td>';
                $salida .= '<td><a href="../model/eliminar.php?id=' . $usuario_id . '" class="btn btn-danger btn-sm">Eliminar</a></td>';
                $salida .= '</tr>';
            }

            $salida .= '</tbody>';
            $salida .= '</table>';
            $salida .= '</div>';
            $salida .= '</div>';
        } else {
        }

        $conn->close();

        return $salida;
    }

    /**
     * Recupera y muestra los datos de todos los cursos registrados en la base de datos.
     *
     * @return string Cadena de texto con los datos de todos los cursos.
     */
    public static function vercursos()
    {
        include 'db_config.php';
        include_once '../controller/class_fechas.php';

        if (!isset($_SESSION['usuario_id'])) {
            die("Usuario no autenticado.");
        }

        $usuario_id = $_SESSION['usuario_id'];
        $salida = "";

        $sql = "SELECT curso_id, titulo_curso, descripcion, categoria_id, usuario_id, bloqueo, fecha_registro FROM curso";

        if ($consulta = $conn->query($sql)) {
            $salida .= '<div class="container-fluid">';
            $salida .= '<div class="table-responsive">';
            $salida .= '<table class="table table-striped table-hover table-bordered">';
            $salida .= '<thead class="table-dark">';
            $salida .= '<tr>';
            $salida .= '<th scope="col">Titulo</th>';
            $salida .= '<th scope="col">Descripcion</th>';
            $salida .= '<th scope="col">Categoria</th>';
            $salida .= '<th scope="col">Usuario</th>';
            $salida .= '<th scope="col">Bloqueo</th>';
            $salida .= '<th scope="col">Fecha Registro</th>';
            $salida .= '<th scope="col">Acciones</th>';
            $salida .= '</tr>';
            $salida .= '</thead>';
            $salida .= '<tbody>';

            while ($fila = $consulta->fetch_assoc()) {
                $curso_id = $fila['curso_id'];
                $categoria_texto = [
                    1 => "Código",
                    2 => "Lógica del programador",
                    3 => "Estilo",
                    4 => "Base de datos",
                    5 => "Otro",
                    6 => "AJAX"
                ][$fila['categoria_id']] ?? "Desconocido";

                $bloqueo_texto = [
                    1 => "Bloqueada",
                    0 => "En línea"
                ][$fila['bloqueo']] ?? "Desconocido";

                $fecha_registro = !empty($fila['fecha_registro']) ? Fecha::mostrarFechas($fila['fecha_registro']) : 'Fecha no disponible';

                // Verificar interacciones del usuario con el curso
                $interaccion_sql = "SELECT tipo_interaccion FROM interaccioncurso WHERE curso_id = ? AND usuario_id = ?";
                $stmt = $conn->prepare($interaccion_sql);
                $stmt->bind_param("ii", $curso_id, $usuario_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $tipo_interaccion = '';
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $tipo_interaccion = $row['tipo_interaccion'];
                }
                $stmt->close();

                // Determinar el texto y la acción del botón basado en la interacción
                $like_button_text = $tipo_interaccion === 'like' ? 'Quitar like' : 'Dar like';
                $dislike_button_text = $tipo_interaccion === 'dislike' ? 'Quitar dislike' : 'Dar dislike';
                $like_action = $tipo_interaccion === 'like' ? 'quitar_like' : 'like';
                $dislike_action = $tipo_interaccion === 'dislike' ? 'quitar_dislike' : 'dislike';

                $salida .= '<tr>';
                $salida .= '<td>' . htmlspecialchars($fila['titulo_curso'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . htmlspecialchars($fila['descripcion'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . $categoria_texto . '</td>';
                $salida .= '<td>' . htmlspecialchars($fila['usuario_id'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . $bloqueo_texto . '</td>';
                $salida .= '<td>' . htmlspecialchars($fecha_registro, ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>';
                $salida .= '<a href="../controller/controlador.php?seccion=seccion14&id=' . $curso_id . '" class="btn btn-primary btn-sm">Editar</a> ';

                $salida .= '<a href="../model/acciones.php?accion=' . $like_action . '&id=' . $curso_id . '" class="btn btn-success btn-sm">' . $like_button_text . '</a> ';
                $salida .= '<a href="../model/acciones.php?accion=' . $dislike_action . '&id=' . $curso_id . '" class="btn btn-danger btn-sm">' . $dislike_button_text . '</a> ';
                $salida .= '</td>';
                $salida .= '</tr>';
            }

            $salida .= '</tbody>';
            $salida .= '</table>';
            $salida .= '</div>';
            $salida .= '</div>';
        } else {
            $conn->close();
        }


        $conn->close();

        return $salida;
    }

    /**
     * Función para obtener y mostrar una tabla HTML con las inscripciones a cursos.
     * 
     * Esta función incluye la configuración de la base de datos, realiza una consulta
     * para obtener todas las inscripciones de la tabla 'inscripción' y genera una 
     * tabla HTML con los resultados. Además, incluye enlaces para editar y eliminar 
     * cada inscripción.
     * 
     * @return string Una cadena con la tabla HTML generada con los datos de las inscripciones.
     */
    public static function verinscripciones()
    {
        // Incluir la configuración de la base de datos
        include 'db_config.php';
        include_once '../controller/class_fechas.php';

        // Variable para almacenar la salida
        $salida = "";

        // Consulta SQL para seleccionar todos los cursos de la tabla 'inscripción'
        $sql = "SELECT inscripción_id, curso_id, usuario_id, nombre, correo, genero, pais, cursos_anteriores, nota, fecha_registro FROM inscripción";

        // Ejecución de la consulta
        $consulta = $conn->query($sql);

        // Verificar si la consulta fue exitosa
        if (!$consulta) {
            die("Error en la consulta: " . $conn->query($sql));
        }

        // Construcción de la tabla HTML con los datos de las inscripciones
        $salida = '<div class="container-fluid" style="max-width:400vh;">';
        $salida .= '<div class="table-responsive">';
        $salida .= '<table class="table table-striped table-hover table-bordered">';
        $salida .= '<thead class="table-dark">';
        $salida .= '<tr>';
        $salida .= '<th scope="col">Inscripción ID</th>';
        $salida .= '<th scope="col">Curso ID</th>';
        $salida .= '<th scope="col">Usuario ID</th>';
        $salida .= '<th scope="col">Nombre</th>';
        $salida .= '<th scope="col">Correo</th>';
        $salida .= '<th scope="col">Género</th>';
        $salida .= '<th scope="col">País</th>';
        $salida .= '<th scope="col">Cursos Anteriores</th>';
        $salida .= '<th scope="col">Nota</th>';
        $salida .= '<th scope="col">Fecha Registro</th>';
        $salida .= '</tr>';
        $salida .= '</thead>';
        $salida .= '<tbody>';

        while ($fila = $consulta->fetch_assoc()) {
            $inscripción_id = htmlspecialchars($fila['inscripción_id'], ENT_QUOTES, 'UTF-8');
            $curso_id = htmlspecialchars($fila['curso_id'], ENT_QUOTES, 'UTF-8');
            $usuario_id = htmlspecialchars($fila['usuario_id'], ENT_QUOTES, 'UTF-8');
            $nombre = htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8');
            $correo = htmlspecialchars($fila['correo'], ENT_QUOTES, 'UTF-8');
            $genero = htmlspecialchars($fila['genero'], ENT_QUOTES, 'UTF-8');
            $pais = htmlspecialchars($fila['pais'], ENT_QUOTES, 'UTF-8');
            $cursos_anteriores = htmlspecialchars($fila['cursos_anteriores'], ENT_QUOTES, 'UTF-8');
            $nota = htmlspecialchars($fila['nota'], ENT_QUOTES, 'UTF-8');
            $fecha_registro = htmlspecialchars($fila['fecha_registro'], ENT_QUOTES, 'UTF-8');

            // Verificar que fecha_registro no es vacío y es una fecha válida
            $fecha_registro = !empty($fila['fecha_registro']) ? Fecha::mostrarFechas($fila['fecha_registro']) : 'Fecha no disponible';


            $salida .= '<tr>';
            $salida .= '<td>' . $inscripción_id . '</td>';
            $salida .= '<td>' . $curso_id . '</td>';
            $salida .= '<td>' . $usuario_id . '</td>';
            $salida .= '<td>' . $nombre . '</td>';
            $salida .= '<td>' . $correo . '</td>';
            $salida .= '<td>' . $genero . '</td>';
            $salida .= '<td>' . $pais . '</td>';
            $salida .= '<td>' . $cursos_anteriores . '</td>';
            $salida .= '<td>' . $nota . '</td>';
            $salida .= '<td>' . htmlspecialchars($fecha_registro, ENT_QUOTES, 'UTF-8') . '</td>';
            $salida .= '</tr>';
        }

        $salida .= '</tbody>';
        $salida .= '</table>';
        $salida .= '</div>'; // Cerrar el div table-responsive
        $salida .= '</div>'; // Cerrar el div container-fluid

        // Cerrar la conexión
        $conn->close();

        // Retornar la salida
        return $salida;
    }
    /**
     * Recupera y muestra los datos de todos los cursos registrados en la base de datos.
     *
     * @return string Cadena de texto con los datos de todos los cursos.
     */
    public static function vercursos2($usuario_id)
    {
        // Incluir la configuración de la base de datos
        include 'db_config.php';
        include_once '../controller/class_fechas.php';

        // Variable para almacenar la salida
        $salida = "";

        // Consulta SQL para seleccionar todos los cursos de la tabla 'curso' creados por el usuario autenticado
        $sql = "SELECT curso_id, titulo_curso, descripcion, categoria_id, usuario_id, bloqueo, fecha_registro 
                FROM curso 
                WHERE usuario_id = ?";

        // Preparación de la consulta
        if ($stmt = $conn->prepare($sql)) {
            // Vincular el parámetro
            $stmt->bind_param("i", $usuario_id);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $result = $stmt->get_result();

            // Construcción de la tabla HTML con los datos de los cursos
            $salida = '<div class="container-fluid" style="max-width:400vh;">';
            $salida .= '<div class="table-responsive">';
            $salida .= '<table class="table table-striped table-hover table-bordered">';
            $salida .= '<thead class="table-dark">';
            $salida .= '<tr>';
            $salida .= '<th scope="col">Titulo</th>';
            $salida .= '<th scope="col">Descripcion</th>';
            $salida .= '<th scope="col">Categoria</th>';
            $salida .= '<th scope="col">Usuario</th>';
            $salida .= '<th scope="col">Bloqueo</th>';
            $salida .= '<th scope="col">Fecha Registro</th>';
            $salida .= '<th scope="col">Alumnos</th>';
            $salida .= '</tr>';
            $salida .= '</thead>';
            $salida .= '<tbody>';

            while ($fila = $result->fetch_assoc()) {
                $curso_id = $fila['curso_id']; // Obtener el id del curso

                // Asignar texto correspondiente al categoria_id
                $categoria_texto = isset($fila["categoria_id"]) ?
                    ($fila["categoria_id"] == 1 ? "Código" :
                        ($fila["categoria_id"] == 2 ? "Lógica del programador" :
                            ($fila["categoria_id"] == 3 ? "Estilo" :
                                ($fila["categoria_id"] == 4 ? "Base de datos" :
                                    ($fila["categoria_id"] == 5 ? "Otro" :
                                        ($fila["categoria_id"] == 6 ? "AJAX" : "Desconocido"))))))
                    : "Desconocido";

                // Convertir el valor de bloqueo a texto
                $bloqueo_texto = isset($fila["bloqueo"]) ?
                    ($fila["bloqueo"] == 1 ? "Bloqueada" :
                        ($fila["bloqueo"] == 0 ? "En línea" : "Desconocido"))
                    : "Desconocido";

                // Verificar que fecha_registro no es vacío y es una fecha válida
                $fecha_registro = !empty($fila['fecha_registro']) ? Fecha::mostrarFechas($fila['fecha_registro']) : 'Fecha no disponible';


                $salida .= '<tr>';
                $salida .= '<td>' . htmlspecialchars($fila['titulo_curso'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . htmlspecialchars($fila['descripcion'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . $categoria_texto . '</td>';
                $salida .= '<td>' . htmlspecialchars($fila['usuario_id'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . $bloqueo_texto . '</td>';
                $salida .= '<td>' . htmlspecialchars($fecha_registro, ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td><a href="../controller/controlador.php?seccion=seccion18&curso_id=' . $curso_id . '" class="btn btn-primary btn-sm">Alumnos</a></td>';
                $salida .= '</tr>';
            }

            $salida .= '</tbody>';
            $salida .= '</table>';
            $salida .= '</div>'; // Cerrar el div table-responsive
            $salida .= '</div>'; // Cerrar el div container-fluid


        }

        // Cerrar la conexión
        $conn->close();

        // Retornar la salida
        return $salida;
    }
    /**
     * Recupera y muestra los datos de los usuarios inscritos en un curso específico.
     *
     * @param int $curso_id El identificador del curso para el cual se recuperan los usuarios inscritos.
     * @return string Una cadena de texto con una tabla HTML que contiene los datos de los usuarios inscritos en el curso.
     */
    public static function verUsuariosInscritos($curso_id)
    {
        // Incluir la configuración de la base de datos
        include 'db_config.php';
        include_once '../controller/class_fechas.php';

        // Variable para almacenar la salida
        $salida = "";

        // Consulta SQL para seleccionar los usuarios inscritos en el curso
        $sql = "SELECT i.usuario_id, i.nombre, i.correo, i.fecha_registro, i.inscripción_id
                FROM inscripción i
                WHERE i.curso_id = ?";

        // Preparación de la consulta
        if ($stmt = $conn->prepare($sql)) {
            // Vincular el parámetro
            $stmt->bind_param("i", $curso_id);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $result = $stmt->get_result();

            // Construcción de la tabla HTML con los datos de los usuarios
            $salida = '<div class="container-fluid" style="max-width:400vh;">';
            $salida .= '<div class="table-responsive">';
            $salida .= '<table class="table table-striped table-hover table-bordered">';
            $salida .= '<thead class="table-dark">';
            $salida .= '<tr>';
            $salida .= '<th scope="col">Nombre</th>';
            $salida .= '<th scope="col">Correo</th>';
            $salida .= '<th scope="col">Fecha Registro</th>';
            $salida .= '<th scope="col">Calificar</th>';
            $salida .= '</tr>';
            $salida .= '</thead>';
            $salida .= '<tbody>';

            while ($fila = $result->fetch_assoc()) {
                $usuario_id = $fila['usuario_id']; // Obtener el id del usuario
                $inscripción_id = $fila['inscripción_id']; // Obtener el id de inscripción


                // Verificar que fecha_registro no es vacío y es una fecha válida
                $fecha_registro = !empty($fila['fecha_registro']) ? Fecha::mostrarFechas($fila['fecha_registro']) : 'Fecha no disponible';


                $salida .= '<tr>';
                $salida .= '<td>' . htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . htmlspecialchars($fila['correo'], ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td>' . htmlspecialchars($fecha_registro, ENT_QUOTES, 'UTF-8') . '</td>';
                $salida .= '<td><a href="../controller/controlador.php?seccion=seccion19&usuario_id=' . urlencode($usuario_id) . '&curso_id=' . urlencode($curso_id) . '&inscripción_id=' . urlencode($inscripción_id) . '" class="btn btn-primary btn-sm">Calificar</a></td>';
                $salida .= '</tr>';
            }

            $salida .= '</tbody>';
            $salida .= '</table>';
            $salida .= '</div>'; // Cerrar el div table-responsive
            $salida .= '</div>'; // Cerrar el div container-fluid

            // Cerrar la declaración preparada
            $stmt->close();
        } else {
            $salida = "<p>Error al preparar la consulta.</p>";
        }

        // Cerrar la conexión
        $conn->close();

        // Retornar la salida
        return $salida;
    }
    /**
     * Recupera la información de inscripción de un usuario en un curso específico.
     *
     * @param int $usuario_id El identificador del usuario.
     * @param int $curso_id El identificador del curso.
     * @return array|null Un arreglo asociativo con la información de inscripción si se encuentra, o null si no se encuentra.
     */
    public static function getInscripcionInfo($usuario_id, $curso_id, $inscripción_id)
    {
        include 'db_config.php'; // Asegúrate de que esta ruta es correcta

        // Preparar la consulta SQL
        $sql = "SELECT i.inscripción_id, i.nombre, i.correo, i.nota, c.titulo_curso,
                       r.archivo_respuesta, r.fec_reg AS fecha_respuesta
                FROM inscripción i
                INNER JOIN curso c ON i.curso_id = c.curso_id
                LEFT JOIN respuesta r ON i.inscripción_id = r.inscripción_id
                WHERE i.usuario_id = ? AND i.curso_id = ?
                ORDER BY r.fec_reg DESC
                LIMIT 1";

        if ($stmt = $conn->prepare($sql)) {
            // Vincular parámetros
            $stmt->bind_param("ii", $usuario_id, $curso_id);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $result = $stmt->get_result();

            // Comprobar si se encontró un registro
            if ($result->num_rows > 0) {
                // Devolver la información del usuario como un arreglo asociativo
                $data = $result->fetch_assoc();
            } else {
                // No se encontraron datos
                $data = null;
            }

            // Cerrar declaración y resultado
            $stmt->close();
            $result->free();
        } else {
            // Error al preparar la consulta
            $data = null;
        }

        // Cerrar la conexión
        $conn->close();

        return $data;
    }
    /**
     * Actualiza la nota de un usuario en un curso específico.
     *
     * @param int $usuario_id El identificador del usuario.
     * @param int $curso_id El identificador del curso.
     * @param int $nueva_nota La nueva nota a asignar.
     * @return string Mensaje indicando el resultado de la operación.
     */
    public static function actualizarNota($usuario_id, $curso_id, $nueva_nota)
    {
        include 'db_config.php'; // Asegúrate de que esta ruta es correcta y el archivo está correctamente configurado

        // Asegúrate de que la nueva nota es un número entero válido
        if (!is_numeric($nueva_nota) || $nueva_nota < 0 || $nueva_nota > 100) {
            return "Nota inválida. Debe ser un número entre 0 y 100.";
        }

        // Preparar la consulta SQL para actualizar la nota
        $sql = "UPDATE inscripción SET nota = ? WHERE usuario_id = ? AND curso_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Vincular parámetros (i: entero)
            $stmt->bind_param("iii", $nueva_nota, $usuario_id, $curso_id);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return "La nota se ha actualizado correctamente.";
            } else {
                return "Error al actualizar la nota.";
            }
        } else {
            // Error al preparar la consulta
            return "Error al preparar la consulta.";
        }
    }

}
?>