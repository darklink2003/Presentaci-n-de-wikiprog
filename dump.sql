-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: wikiprog
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.20-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `archivo`
--

DROP TABLE IF EXISTS `archivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivo` (
  `archivo_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nombre_archivo` varchar(45) DEFAULT NULL,
  `tamaño` varchar(45) DEFAULT NULL,
  `privacidad_id` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `archivo` longtext NOT NULL,
  PRIMARY KEY (`archivo_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `privacidad_id` (`privacidad_id`),
  CONSTRAINT `archivo_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`),
  CONSTRAINT `archivo_ibfk_2` FOREIGN KEY (`privacidad_id`) REFERENCES `privacidad` (`privacidad_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archivo`
--

LOCK TABLES `archivo` WRITE;
/*!40000 ALTER TABLE `archivo` DISABLE KEYS */;
/*!40000 ALTER TABLE `archivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `categoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Codigo','2024-05-31 21:36:38'),(2,'Logica del programador','2024-05-31 21:36:38'),(3,'Estilo','2024-05-31 21:36:38'),(4,'Base de datos','2024-05-31 21:36:38'),(5,'otro','2024-05-31 21:36:38'),(6,'jax','2024-06-12 17:21:57');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentario`
--

DROP TABLE IF EXISTS `comentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentario` (
  `comentario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`comentario_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario`
--

LOCK TABLES `comentario` WRITE;
/*!40000 ALTER TABLE `comentario` DISABLE KEYS */;
/*!40000 ALTER TABLE `comentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `curso`
--

DROP TABLE IF EXISTS `curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `curso` (
  `curso_id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_curso` varchar(30) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `bloqueo` int(11) NOT NULL DEFAULT 0,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`curso_id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `curso_ibfk_usuario` (`usuario_id`),
  CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  CONSTRAINT `curso_ibfk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso`
--

LOCK TABLES `curso` WRITE;
/*!40000 ALTER TABLE `curso` DISABLE KEYS */;
/*!40000 ALTER TABLE `curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscripción`
--

DROP TABLE IF EXISTS `inscripción`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscripción` (
  `inscripción_id` int(11) NOT NULL AUTO_INCREMENT,
  `curso_id` int(11) DEFAULT 0,
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `genero` varchar(20) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `cursos_anteriores` enum('si','no') NOT NULL,
  `nota` int(45) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`inscripción_id`),
  KEY `curso_id` (`curso_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `inscripción_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`) ON DELETE SET NULL,
  CONSTRAINT `inscripción_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscripción`
--

LOCK TABLES `inscripción` WRITE;
/*!40000 ALTER TABLE `inscripción` DISABLE KEYS */;
/*!40000 ALTER TABLE `inscripción` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interaccioncurso`
--

DROP TABLE IF EXISTS `interaccioncurso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `interaccioncurso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curso_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo_interaccion` enum('like','dislike') NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_interaction` (`curso_id`,`usuario_id`,`tipo_interaccion`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `interaccioncurso_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`) ON DELETE CASCADE,
  CONSTRAINT `interaccioncurso_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interaccioncurso`
--

LOCK TABLES `interaccioncurso` WRITE;
/*!40000 ALTER TABLE `interaccioncurso` DISABLE KEYS */;
/*!40000 ALTER TABLE `interaccioncurso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leccion`
--

DROP TABLE IF EXISTS `leccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leccion` (
  `leccion_id` int(11) NOT NULL AUTO_INCREMENT,
  `curso_id` int(11) NOT NULL,
  `titulo_leccion` varchar(255) NOT NULL,
  `contenido` varchar(255) NOT NULL,
  `archivo_leccion` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`leccion_id`),
  KEY `curso_id` (`curso_id`),
  CONSTRAINT `leccion_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leccion`
--

LOCK TABLES `leccion` WRITE;
/*!40000 ALTER TABLE `leccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `leccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privacidad`
--

DROP TABLE IF EXISTS `privacidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `privacidad` (
  `privacidad_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(45) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`privacidad_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privacidad`
--

LOCK TABLES `privacidad` WRITE;
/*!40000 ALTER TABLE `privacidad` DISABLE KEYS */;
INSERT INTO `privacidad` VALUES (1,'publica','2024-07-10 15:31:03'),(2,'privada','2024-07-10 15:31:03');
/*!40000 ALTER TABLE `privacidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prueba`
--

DROP TABLE IF EXISTS `prueba`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prueba` (
  `prueba_id` int(11) NOT NULL AUTO_INCREMENT,
  `curso_id` int(11) DEFAULT NULL,
  `titulo_prueba` varchar(45) NOT NULL,
  `contenido` varchar(45) DEFAULT NULL,
  `archivo_prueba` varchar(100) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fec_reg` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`prueba_id`),
  KEY `fk_prueba_usuario` (`usuario_id`),
  KEY `fk_prueba_curso` (`curso_id`),
  CONSTRAINT `fk_prueba_curso` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`curso_id`),
  CONSTRAINT `fk_prueba_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prueba`
--

LOCK TABLES `prueba` WRITE;
/*!40000 ALTER TABLE `prueba` DISABLE KEYS */;
/*!40000 ALTER TABLE `prueba` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rango`
--

DROP TABLE IF EXISTS `rango`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rango` (
  `rango_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`rango_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rango`
--

LOCK TABLES `rango` WRITE;
/*!40000 ALTER TABLE `rango` DISABLE KEYS */;
INSERT INTO `rango` VALUES (1,'usuario','2024-06-04 12:05:53'),(2,'administrador','2024-06-04 12:05:53'),(3,'evaluador','2024-06-20 16:45:05');
/*!40000 ALTER TABLE `rango` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_creacion_archivo`
--

DROP TABLE IF EXISTS `registro_creacion_archivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_creacion_archivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `archivo_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_creacion_archivo`
--

LOCK TABLES `registro_creacion_archivo` WRITE;
/*!40000 ALTER TABLE `registro_creacion_archivo` DISABLE KEYS */;
INSERT INTO `registro_creacion_archivo` VALUES (1,43,'2024-07-31 15:59:14'),(2,46,'2024-08-01 12:59:13'),(3,47,'2024-08-01 12:59:26'),(4,48,'2024-08-01 12:59:46'),(5,49,'2024-08-01 15:03:45'),(6,50,'2024-08-01 16:31:00'),(7,51,'2024-08-02 13:00:31');
/*!40000 ALTER TABLE `registro_creacion_archivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_creacion_curso`
--

DROP TABLE IF EXISTS `registro_creacion_curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_creacion_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curso_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_creacion_curso`
--

LOCK TABLES `registro_creacion_curso` WRITE;
/*!40000 ALTER TABLE `registro_creacion_curso` DISABLE KEYS */;
INSERT INTO `registro_creacion_curso` VALUES (1,73,'2024-08-01 15:07:41'),(2,74,'2024-08-02 16:20:19');
/*!40000 ALTER TABLE `registro_creacion_curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_creacion_usuario`
--

DROP TABLE IF EXISTS `registro_creacion_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_creacion_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_creacion_usuario`
--

LOCK TABLES `registro_creacion_usuario` WRITE;
/*!40000 ALTER TABLE `registro_creacion_usuario` DISABLE KEYS */;
INSERT INTO `registro_creacion_usuario` VALUES (1,39,'2024-07-31 15:58:21'),(2,40,'2024-08-01 13:12:42'),(3,41,'2024-08-01 13:16:40'),(4,42,'2024-08-01 14:06:35'),(5,43,'2024-08-02 16:17:33');
/*!40000 ALTER TABLE `registro_creacion_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respuesta`
--

DROP TABLE IF EXISTS `respuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `respuesta` (
  `respuesta_id` int(11) NOT NULL AUTO_INCREMENT,
  `prueba_id` int(11) DEFAULT NULL,
  `archivo_respuesta` varchar(150) NOT NULL,
  `inscripción_id` int(11) NOT NULL,
  `fec_reg` datetime NOT NULL,
  PRIMARY KEY (`respuesta_id`),
  KEY `fk_respuesta_prueba` (`prueba_id`),
  KEY `fk_respuesta_inscripcion` (`inscripción_id`),
  CONSTRAINT `fk_respuesta_inscripcion` FOREIGN KEY (`inscripción_id`) REFERENCES `inscripción` (`inscripción_id`),
  CONSTRAINT `fk_respuesta_prueba` FOREIGN KEY (`prueba_id`) REFERENCES `prueba` (`prueba_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respuesta`
--

LOCK TABLES `respuesta` WRITE;
/*!40000 ALTER TABLE `respuesta` DISABLE KEYS */;
/*!40000 ALTER TABLE `respuesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) NOT NULL,
  `img_usuario` varchar(45) NOT NULL DEFAULT 'null',
  `correo` varchar(50) NOT NULL,
  `biografia` varchar(45) NOT NULL,
  `contraseña` varchar(45) NOT NULL,
  `rango_id` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `intentos_fallidos` int(11) DEFAULT 0,
  `cuenta_bloqueada` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`usuario_id`),
  KEY `rango_id` (`rango_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rango_id`) REFERENCES `rango` (`rango_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (40,'admin','../img_usuario/perfil.png','admini@gmail.com','administrador','Administrador!',2,'2024-08-01 13:12:42',0,0),(41,'evaluador','../img_usuario/perfil.png','evaluador@gmil.com','evaluador','Evaluador$',3,'2024-08-01 13:16:40',0,0),(43,'usuario','../img_usuario/perfil.png','usuario@gmail.com','usuario','Usuario&',1,'2024-08-02 16:17:33',0,0);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-12 22:17:43
