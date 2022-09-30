CREATE DATABASE  IF NOT EXISTS `api-tickets` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `api-tickets`;
-- MySQL dump 10.13  Distrib 8.0.28, for macos11 (x86_64)
--
-- Host: localhost    Database: api-tickets
-- ------------------------------------------------------
-- Server version	8.0.29

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
-- Table structure for table `historial_detalles`
--

DROP TABLE IF EXISTS `historial_detalles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_detalles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `historial_incidencias_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('A','E') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`),
  KEY `historial_detalles_historial_incidencias_id_foreign` (`historial_incidencias_id`),
  CONSTRAINT `historial_detalles_historial_incidencias_id_foreign` FOREIGN KEY (`historial_incidencias_id`) REFERENCES `historial_incidencias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_detalles`
--

LOCK TABLES `historial_detalles` WRITE;
/*!40000 ALTER TABLE `historial_detalles` DISABLE KEYS */;
INSERT INTO `historial_detalles` VALUES (1,'Se esta verificando el proceso.',17,'2022-09-30 10:20:59','2022-09-30 10:20:59','A'),(2,'Pruebas validas.',17,'2022-09-30 10:20:59','2022-09-30 10:20:59','A'),(3,'Se ha verificado las pruebas.',18,'2022-09-30 10:23:04','2022-09-30 10:23:04','A'),(4,'Se esta verificando los documentos solicitados.',19,'2022-09-30 10:24:57','2022-09-30 10:24:57','A'),(6,'Se ha corregido el problema.',17,'2022-09-30 10:26:25','2022-09-30 10:26:25','A');
/*!40000 ALTER TABLE `historial_detalles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_incidencias`
--

DROP TABLE IF EXISTS `historial_incidencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_incidencias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_soporte` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comentario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_atencion` datetime NOT NULL DEFAULT '2022-09-29 19:37:08',
  `tickets_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historial_incidencias_tickets_id_foreign` (`tickets_id`),
  CONSTRAINT `historial_incidencias_tickets_id_foreign` FOREIGN KEY (`tickets_id`) REFERENCES `tickets` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_incidencias`
--

LOCK TABLES `historial_incidencias` WRITE;
/*!40000 ALTER TABLE `historial_incidencias` DISABLE KEYS */;
INSERT INTO `historial_incidencias` VALUES (17,'Elian','N/A','2022-09-30 05:20:59',46,'2022-09-30 10:20:59','2022-09-30 10:20:59'),(18,'Luis','Se esta culminando el proceso.','2022-09-30 05:23:04',47,'2022-09-30 10:23:04','2022-09-30 10:23:04'),(19,'Daniel Pincay','Verificando.','2022-09-30 05:24:57',48,'2022-09-30 10:24:57','2022-09-30 10:24:57');
/*!40000 ALTER TABLE `historial_incidencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `persona_solicitante` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `asunto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('P','C','E') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'P',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (5,'asd','2022-09-29 00:00:00','sad','asd','E','2022-09-30 03:02:51','2022-09-30 03:44:08'),(39,'Beta','2022-09-29 00:00:00','No se subio','No se subio correctamente','E','2022-09-30 03:23:07','2022-09-30 03:53:47'),(46,'Bryan','2022-09-29 00:00:00','Sanción erronea.','Se sancionó sin verificaciones de datos.','P','2022-09-30 10:20:59','2022-09-30 10:26:03'),(47,'Matias Cedeño','2022-09-30 00:00:00','Infracción','Infracción por incumplimiento de normas.','C','2022-09-30 10:23:04','2022-09-30 10:25:07'),(48,'Arianna Segovia','2022-10-29 00:00:00','Falta de documentos.','Se solicita los documentos pertinenentes.','P','2022-09-30 10:24:57','2022-09-30 10:24:57');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','Bryan','admin@gmail.com','$2y$10$u2LL6fU98I/I0C5wDhhxWumLzzNpIyd6kPDfFzhidMOy2GxvcoE66','2022-09-30 09:43:25','2022-09-30 09:43:25');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-30  0:37:48
