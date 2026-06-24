-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: projetoloja
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `id_categoria` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES (1,'Fone de Ouvido Bluetooth',89.90,'JBL',1),(2,'Carregador USB-C 20W',35.90,'Anker',1),(3,'Cabo HDMI 2 metros',29.90,'Multilaser',1),(4,'Caixa de Som Portátil',149.90,'Sony',1),(5,'Suporte para Celular Veicular',24.90,'Baseus',1),(6,'Camiseta Básica Algodão',39.90,'Hering',2),(7,'Meia Kit com 3 Pares',24.90,'Lupo',2),(8,'Boné Aba Curva',49.90,'Nike',2),(9,'Calça Moletom',79.90,'Fila',2),(10,'Biscoito Recheado 144g',4.50,'Oreo',3),(11,'Achocolatado em Pó 400g',12.90,'Nescau',3),(12,'Macarrão Instantâneo',3.99,'Nissin',3),(13,'Granola Natural 300g',18.90,'Native',3),(14,'Café Torrado e Moído 250g',14.90,'Pilão',3),(15,'Shampoo 400ml',19.90,'Pantene',4),(16,'Sabonete Líquido 250ml',8.90,'Dove',4),(17,'Creme Dental 90g',7.50,'Colgate',4),(18,'Desodorante Aerossol 150ml',16.90,'Rexona',4),(19,'Porta-Copos Inox Set 6 un',35.90,'Tramontina',5),(20,'Vela Aromática 180g',29.90,'Villa Natura',5),(21,'Toalha de Rosto 45x70cm',19.90,'Buddemeyer',5),(22,'Organizador de Gaveta 6 div',22.90,'Ordene',5),(23,'Quebra-Cabeça 500 Peças',49.90,'Grow',6),(24,'Massinha de Modelar 8 cores',15.90,'Play-Doh',6),(25,'Carrinho de Controle Remoto',89.90,'Multilaser',6),(26,'Caderno Universitário 200fls',28.90,'Tilibra',7),(27,'Caneta Esferográfica Kit 10',12.90,'BIC',7),(28,'Mochila Escolar 30L',89.90,'Sestini',7),(29,'Estojo Duplo',19.90,'Tilibra',7),(30,'Bola de Futebol Campo',79.90,'Penalty',8),(31,'Squeeze 600ml',29.90,'Hidroway',8),(32,'Corda de Pular Speed',34.90,'Muvin',8);
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-23 23:29:31
