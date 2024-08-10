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
