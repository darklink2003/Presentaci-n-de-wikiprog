-- Eliminación y creación de funciones
-- funciones.sql

-- Función `dolar`
DROP FUNCTION IF EXISTS `dolar`;
CREATE FUNCTION `dolar`() RETURNS INT
BEGIN
  RETURN contar_usuarios() * 4000;
END;

-- Función `sumar`
DROP FUNCTION IF EXISTS `sumar`;
CREATE FUNCTION `sumar`(n1 FLOAT, n2 FLOAT) RETURNS FLOAT
BEGIN
  RETURN n1 + n2;
END;

-- Función `eliminar_perfil`
DROP FUNCTION IF EXISTS `eliminar_perfil`;
CREATE FUNCTION `eliminar_perfil`(usuario_id INT) RETURNS INT
BEGIN
    DECLARE exit_code INT DEFAULT 0;

    -- Desactivar las restricciones de claves foráneas temporalmente
    SET foreign_key_checks = 0;

    -- Eliminar datos de la tabla `respuesta` asociados a las inscripciones del usuario
    DELETE FROM respuesta
    WHERE inscripción_id IN (SELECT inscripción_id FROM inscripción WHERE usuario_id = usuario_id);

    -- Eliminar datos de la tabla `prueba` asociados al usuario
    DELETE FROM prueba WHERE usuario_id = usuario_id;
    
    -- Eliminar datos de la tabla `leccion` asociados a los cursos del usuario
    DELETE FROM leccion
    WHERE curso_id IN (SELECT curso_id FROM curso WHERE usuario_id = usuario_id);
    
    -- Eliminar datos de la tabla `inscripción` asociados al usuario
    DELETE FROM inscripción WHERE usuario_id = usuario_id;
    
    -- Eliminar datos de la tabla `curso` creados por el usuario
    DELETE FROM curso WHERE usuario_id = usuario_id;
    
    -- Eliminar datos de la tabla `interaccioncurso` asociados al usuario
    DELETE FROM interaccioncurso WHERE usuario_id = usuario_id;
    
    -- Eliminar datos de la tabla `comentario` hechos por el usuario
    DELETE FROM comentario WHERE usuario_id = usuario_id;
    
    -- Eliminar datos de la tabla `archivo` subidos por el usuario
    DELETE FROM archivo WHERE usuario_id = usuario_id;
    
    -- Finalmente, eliminar el usuario de la tabla `usuario`
    DELETE FROM usuario WHERE usuario_id = usuario_id;

    -- Volver a activar las restricciones de claves foráneas
    SET foreign_key_checks = 1;

    -- Si todo fue exitoso
    SET exit_code = 1;

    RETURN exit_code;
END;

-- Trigger `after_archivo_insert`
DROP TRIGGER IF EXISTS `after_archivo_insert`;
CREATE TRIGGER `after_archivo_insert`
AFTER INSERT ON `archivo`
FOR EACH ROW
BEGIN
  INSERT INTO `registro_creacion_archivo` (archivo_id, fecha_creacion)
  VALUES (NEW.archivo_id, NEW.fecha_registro);
END;

-- Trigger `after_curso_insert`
DROP TRIGGER IF EXISTS `after_curso_insert`;
CREATE TRIGGER `after_curso_insert`
AFTER INSERT ON `curso`
FOR EACH ROW
BEGIN
  INSERT INTO `registro_creacion_curso` (curso_id, fecha_creacion)
  VALUES (NEW.curso_id, NEW.fecha_registro);
END;

-- Trigger `after_usuario_insert`
DROP TRIGGER IF EXISTS `after_usuario_insert`;
CREATE TRIGGER `after_usuario_insert`
AFTER INSERT ON `usuario`
FOR EACH ROW
BEGIN
  INSERT INTO `registro_creacion_usuario` (usuario_id, fecha_creacion)
  VALUES (NEW.usuario_id, NEW.fecha_registro);
END;
