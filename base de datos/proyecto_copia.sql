-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: sis_venta
-- ------------------------------------------------------
-- Server version	8.0.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `idcategoria` int NOT NULL AUTO_INCREMENT,
  `categoria` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Bebidas'),(2,'Snacks');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `idcliente` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `razonSocial` varchar(45) DEFAULT NULL,
  `NIT_CI` varchar(255) DEFAULT NULL,
  `Direccion` varchar(100) NOT NULL,
  `Referencia` varchar(1000) NOT NULL,
  `telf` int DEFAULT NULL,
  `codigo` varchar(45) NOT NULL,
  `imagen_tienda` varchar(1000) DEFAULT NULL,
  `activo` tinyint DEFAULT NULL,
  `prevendedor_idprevendedor` int NOT NULL,
  PRIMARY KEY (`idcliente`),
  KEY `fk_cliente_prevendedor1_idx` (`prevendedor_idprevendedor`),
  CONSTRAINT `fk_cliente_prevendedor1` FOREIGN KEY (`prevendedor_idprevendedor`) REFERENCES `prevendedor` (`idprevendedor`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,'CLIENTE','A','123456','741852','Calle Pachamama','al lado de...',78417511,'0001','../../imagenes_tienda/0001.jpeg',0,1),(2,'CLIENTE ','2','1233456','1234567','Calle Pachamama','al lado de...',78417511,'0002','../../imagenes_tienda/',0,1),(3,'CLIENTE ','3','1233456','1234567','Calle Pachamama','al lado de...',78417511,'0003','../../imagenes_tienda/',0,2),(7,'CLIENTE ','4','1233456','1234567','Calle Pachamama','al lado de...',78417511,'0004','../../imagenes_tienda/0001.jpeg',0,1),(8,'CLIENTE R','R','1233456','1234567','Calle Pachamama','al lado de...',78417511,'0003','../../imagenes_tienda/0001.jpeg',0,1),(9,'cliente',' ','1233456','1234567','Calle Pachamama','al lado de...',78417511,'000005','../../imagenes_tienda/0001.jpeg',1,3);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribuidor`
--

DROP TABLE IF EXISTS `distribuidor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distribuidor` (
  `iddistribuidor` int NOT NULL AUTO_INCREMENT,
  `fecha_asignacion` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `ruta` int NOT NULL,
  `datos` int NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`iddistribuidor`),
  KEY `fk_distribuidor_rutas1_idx` (`ruta`),
  KEY `fk_distribuidor_empleados1_idx` (`datos`),
  CONSTRAINT `fk_distribuidor_empleados1` FOREIGN KEY (`datos`) REFERENCES `empleados` (`id_empleado`),
  CONSTRAINT `fk_distribuidor_rutas1` FOREIGN KEY (`ruta`) REFERENCES `rutas` (`idruta`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribuidor`
--

LOCK TABLES `distribuidor` WRITE;
/*!40000 ALTER TABLE `distribuidor` DISABLE KEYS */;
INSERT INTO `distribuidor` VALUES (2,'2024-06-03','2024-06-30',1,2,0),(3,'2024-06-03','2024-06-30',1,2,0),(4,'2024-06-09','2024-06-10',2,5,0),(5,'2024-06-16','2024-06-17',1,8,0),(6,'2024-06-17','2024-06-17',1,8,1);
/*!40000 ALTER TABLE `distribuidor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribuidor_has_pedido`
--

DROP TABLE IF EXISTS `distribuidor_has_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distribuidor_has_pedido` (
  `distribuidor_iddistribuidor` int NOT NULL,
  `pedido_idPedido` int NOT NULL,
  PRIMARY KEY (`distribuidor_iddistribuidor`,`pedido_idPedido`),
  KEY `fk_distribuidor_has_pedido_pedido1_idx` (`pedido_idPedido`),
  KEY `fk_distribuidor_has_pedido_distribuidor1_idx` (`distribuidor_iddistribuidor`),
  CONSTRAINT `fk_distribuidor_has_pedido_distribuidor1` FOREIGN KEY (`distribuidor_iddistribuidor`) REFERENCES `distribuidor` (`iddistribuidor`),
  CONSTRAINT `fk_distribuidor_has_pedido_pedido1` FOREIGN KEY (`pedido_idPedido`) REFERENCES `pedido` (`idPedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribuidor_has_pedido`
--

LOCK TABLES `distribuidor_has_pedido` WRITE;
/*!40000 ALTER TABLE `distribuidor_has_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `distribuidor_has_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `id_empleado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `CI` varchar(100) DEFAULT NULL,
  `direccion` varchar(1000) DEFAULT NULL,
  `telefono` int DEFAULT NULL,
  `garantia` decimal(10,2) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `correo` varchar(1000) DEFAULT NULL,
  `activo` tinyint DEFAULT NULL,
  `rol` int NOT NULL,
  PRIMARY KEY (`id_empleado`),
  KEY `fk_empleados_rol1_idx` (`rol`),
  CONSTRAINT `fk_empleados_rol1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES (1,'Matias','Paniagua','62146348','av. america',62146348,5235.00,'2005-03-17','2024-06-02','matias@gmail.com',0,1),(2,'DISTRIBUIDOR','P','123456','av. america',147258,963852.00,'1998-03-29','2024-06-01','nose@gmail.com',0,2),(3,'PREVENDEDOR R','REAL','963852','Calle Pachamama',78417511,856265.00,'2024-06-01','2024-06-29','nose@gmail.com',0,3),(4,'PREV','2','147554','NOSE',68468,6514.00,'2021-02-10','2024-06-09','nose@gmail.com',0,3),(5,'DISTRIBUIDOR','2','147554','NOSE',68468,6514.00,'2017-02-01','2024-06-09','nose@gmail.com',0,2),(6,'PRUEBA','A','147554','NOSE',68468,6514.00,'2019-06-08','2024-06-09','nose@gmail.com',0,4),(7,'prevendedor','A','147554','NOSE',68468,6514.00,'2017-07-06','2018-03-16','nose@gmail.com',1,3),(8,'distribuidor',' ','147554','NOSE',68468,6514.00,'2021-06-08','2024-06-16','nose@gmail.com',1,2),(9,'Matias','Paniagua','147554','NOSE',68468,6514.00,'2005-05-17','2024-06-16','nose@gmail.com',1,1);
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forma_pago`
--

DROP TABLE IF EXISTS `forma_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forma_pago` (
  `idforma_pago` int NOT NULL AUTO_INCREMENT,
  `pago` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idforma_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forma_pago`
--

LOCK TABLES `forma_pago` WRITE;
/*!40000 ALTER TABLE `forma_pago` DISABLE KEYS */;
INSERT INTO `forma_pago` VALUES (1,'Tarjeta'),(2,'Qr'),(3,'Efectivo');
/*!40000 ALTER TABLE `forma_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `idPedido` int NOT NULL AUTO_INCREMENT,
  `monto_total` double DEFAULT NULL,
  `fecha_pedido` date DEFAULT NULL,
  `cliente_idcliente` int NOT NULL,
  PRIMARY KEY (`idPedido`),
  KEY `fk_pedido_cliente1_idx` (`cliente_idcliente`),
  CONSTRAINT `fk_pedido_cliente1` FOREIGN KEY (`cliente_idcliente`) REFERENCES `cliente` (`idcliente`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (16,10,'2024-06-17',9);
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prevendedor`
--

DROP TABLE IF EXISTS `prevendedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prevendedor` (
  `idprevendedor` int NOT NULL AUTO_INCREMENT,
  `fecha_asignacion` date DEFAULT NULL,
  `fecha_visita` date DEFAULT NULL,
  `ruta` int NOT NULL,
  `datos` int NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`idprevendedor`),
  KEY `fk_prevendedor_rutas1_idx` (`ruta`),
  KEY `fk_prevendedor_empleados1_idx` (`datos`),
  CONSTRAINT `fk_prevendedor_empleados1` FOREIGN KEY (`datos`) REFERENCES `empleados` (`id_empleado`),
  CONSTRAINT `fk_prevendedor_rutas1` FOREIGN KEY (`ruta`) REFERENCES `rutas` (`idruta`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prevendedor`
--

LOCK TABLES `prevendedor` WRITE;
/*!40000 ALTER TABLE `prevendedor` DISABLE KEYS */;
INSERT INTO `prevendedor` VALUES (1,'2024-06-02','2024-06-15',1,3,0),(2,'2024-06-09','2024-06-10',2,4,0),(3,'2024-06-16','2024-06-17',1,7,1);
/*!40000 ALTER TABLE `prevendedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_compra_lote` date DEFAULT NULL,
  `Activo` tinyint DEFAULT NULL,
  `nombre_proveedor` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria` int NOT NULL,
  `unidades_idunidades` int NOT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `fk_productos_categorias1_idx` (`categoria`),
  KEY `fk_productos_unidades1_idx` (`unidades_idunidades`),
  CONSTRAINT `fk_productos_categorias1` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`idcategoria`),
  CONSTRAINT `fk_productos_unidades1` FOREIGN KEY (`unidades_idunidades`) REFERENCES `unidades` (`idunidades`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'coca cola',9.00,11.00,281,'2025-12-19','2024-06-02',0,'COCA COLA COMPANY','../../images/coca-cola.jpg',1,2),(2,'GALLETA',8.00,10.00,385,'2025-07-02','2024-06-02',0,'Mabel','../../images/GALLETAS.jpeg',2,1),(3,'COCA-COLA',8.00,10.00,5623,'2026-08-16','2024-06-16',0,'COCA COLA COMPANY','../../images/coca-cola.jpg',1,1),(4,'coca cola',8.00,10.00,473,'2025-11-16','2024-06-16',1,NULL,'../../images/coca-cola.jpg',1,2),(5,'GALLETA',4.00,5.00,500,'2026-02-14','2024-06-16',1,NULL,'../../images/0001.jpeg',2,2);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos_has_pedido`
--

DROP TABLE IF EXISTS `productos_has_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos_has_pedido` (
  `productos_id_producto` int NOT NULL,
  `Pedido_idPedido` int NOT NULL,
  `cantidad` int DEFAULT NULL,
  PRIMARY KEY (`productos_id_producto`,`Pedido_idPedido`),
  KEY `fk_productos_has_Pedido_Pedido1_idx` (`Pedido_idPedido`),
  KEY `fk_productos_has_Pedido_productos1_idx` (`productos_id_producto`),
  CONSTRAINT `fk_productos_has_Pedido_Pedido1` FOREIGN KEY (`Pedido_idPedido`) REFERENCES `pedido` (`idPedido`),
  CONSTRAINT `fk_productos_has_Pedido_productos1` FOREIGN KEY (`productos_id_producto`) REFERENCES `productos` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos_has_pedido`
--

LOCK TABLES `productos_has_pedido` WRITE;
/*!40000 ALTER TABLE `productos_has_pedido` DISABLE KEYS */;
INSERT INTO `productos_has_pedido` VALUES (4,16,1);
/*!40000 ALTER TABLE `productos_has_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos_has_proveedor`
--

DROP TABLE IF EXISTS `productos_has_proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos_has_proveedor` (
  `productos_id_producto` int NOT NULL,
  `proveedor_id` int NOT NULL,
  PRIMARY KEY (`productos_id_producto`,`proveedor_id`),
  KEY `fk_productos_has_proveedor_proveedor1_idx` (`proveedor_id`),
  KEY `fk_productos_has_proveedor_productos1_idx` (`productos_id_producto`),
  CONSTRAINT `fk_productos_has_proveedor_productos1` FOREIGN KEY (`productos_id_producto`) REFERENCES `productos` (`id_producto`),
  CONSTRAINT `fk_productos_has_proveedor_proveedor1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos_has_proveedor`
--

LOCK TABLES `productos_has_proveedor` WRITE;
/*!40000 ALTER TABLE `productos_has_proveedor` DISABLE KEYS */;
INSERT INTO `productos_has_proveedor` VALUES (4,1),(5,2);
/*!40000 ALTER TABLE `productos_has_proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `telefono` int DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `nombre_referencia` varchar(45) DEFAULT NULL,
  `NIT` varchar(45) DEFAULT NULL,
  `razon_social` varchar(45) DEFAULT NULL,
  `activo` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedor`
--

LOCK TABLES `proveedor` WRITE;
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
INSERT INTO `proveedor` VALUES (1,'COCA COLA COMPANY',3613651,'KM.10','BOB','78520216','COCA-COLA S.R.L',1),(2,'Mabel',754123,'Km.8','Alan','984681651','MABEL S.R.L',1),(3,'Delicia',6514685,'Km.8','Ester','984681651','Delicia S.R.L',1);
/*!40000 ALTER TABLE `proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `idrol` int NOT NULL AUTO_INCREMENT,
  `rol` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador'),(2,'Distribuidor'),(3,'Prevendedor'),(4,'Almacenista'),(5,'Limpieza');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rutas`
--

DROP TABLE IF EXISTS `rutas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rutas` (
  `idruta` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(1000) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `imagen` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`idruta`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rutas`
--

LOCK TABLES `rutas` WRITE;
/*!40000 ALTER TABLE `rutas` DISABLE KEYS */;
INSERT INTO `rutas` VALUES (1,'001','Sacaba','rutas/R.png'),(2,'002','Quillacollo','rutas/Quillacollo.png'),(3,'004','Cercado ','../../rutas/stack.jpg');
/*!40000 ALTER TABLE `rutas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidades`
--

DROP TABLE IF EXISTS `unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidades` (
  `idunidades` int NOT NULL AUTO_INCREMENT,
  `unidad` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idunidades`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidades`
--

LOCK TABLES `unidades` WRITE;
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;
INSERT INTO `unidades` VALUES (1,'paquete x6'),(2,'unidad x1');
/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `idusuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `n_usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `perfil` varchar(255) DEFAULT NULL,
  `empleado` int NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`idusuario`),
  KEY `fk_usuario_empleados1_idx` (`empleado`),
  CONSTRAINT `fk_usuario_empleados1` FOREIGN KEY (`empleado`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Matias','matias@gmail.com','matias','$2y$10$CS5RJRanJvWNNCjAdu9xAeuVgybtTrOvEvX/dWvMwD2H/IoL34A8a','../../perfiles/l_matias.jpg',1,1),(2,'DISTRIBUIDOR','distribuidor@gmail.com','distribuidor','$2y$10$ComZO7Zp3gncomKB8yZFMe9wpiMgQl4NA0rmFXz2wGukZnVSzEEY6','../../perfiles/emplazamiento.drawio (1).png',2,0),(3,'DISTRIBUIDOR 2','distribuidor@gmail.com','distribuidor2','$2y$10$FcRbd9Hh53YE69k6a3DuyOGBcKFVvb4qhdb1awyWyijBTe260jGpG','../../perfiles/pajarito.jpg',5,0),(4,'prev 1','prevendedor@gmail.com','prevendedor','$2y$10$H3pd6G1pJq/JwyeLBUJz7uZayhnA2I09btja/F52N1RuJy4V9YvBS','../../perfiles/papa.jpeg',3,0),(5,'distribuidor','nose@gmail.com','distribuidor','$2y$10$WpqcacgOyQSErdE1C8gpJu/4xZSd/cB3TwqBrY8JoTLLDb.hGc8SK','../../perfiles/stack.jpg',8,0),(6,'prevendedor','nose@gmail.com','prevendedor','$2y$10$n2vC5cY9YgtejunjzSSgA.ilKYtzmPjvdH0ayCsHs9dt0ghFOtcZu','../../perfiles/No cuentes tus años, haz que tus años cuenten.png',7,1),(7,'distribuidor','nose@gmail.com','distribuidor','$2y$10$25gkeYYrBwTOlYKRI2dbNOJnCQVvwhj9wKtRnge3ODRLa9nC2s1Re','../../perfiles/Imagen de WhatsApp 2024-05-25 a las 06.57.24_873120c9.jpg',8,1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `idventas` int NOT NULL AUTO_INCREMENT,
  `fecha_entrega` date DEFAULT NULL,
  `estado_entrega` tinyint DEFAULT NULL,
  `distribuidor_iddistribuidor` int NOT NULL,
  `forma_pago_idforma_pago` int NOT NULL,
  `pedido` int NOT NULL,
  PRIMARY KEY (`idventas`),
  KEY `fk_ventas_distribuidor1_idx` (`distribuidor_iddistribuidor`),
  KEY `fk_ventas_forma_pago1_idx` (`forma_pago_idforma_pago`),
  KEY `fk_venta_pedido` (`pedido`),
  CONSTRAINT `fk_venta_pedido` FOREIGN KEY (`pedido`) REFERENCES `pedido` (`idPedido`),
  CONSTRAINT `fk_ventas_distribuidor1` FOREIGN KEY (`distribuidor_iddistribuidor`) REFERENCES `distribuidor` (`iddistribuidor`),
  CONSTRAINT `fk_ventas_forma_pago1` FOREIGN KEY (`forma_pago_idforma_pago`) REFERENCES `forma_pago` (`idforma_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES (1,'2024-06-09',1,2,3,1),(2,'2024-06-10',1,4,3,2),(3,'2024-06-10',1,3,3,5);
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vista_clientes`
--

DROP TABLE IF EXISTS `vista_clientes`;
/*!50001 DROP VIEW IF EXISTS `vista_clientes`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vista_clientes` AS SELECT 
 1 AS `idcliente`,
 1 AS `nombre_completo`,
 1 AS `razonSocial`,
 1 AS `NIT_CI`,
 1 AS `Direccion`,
 1 AS `Referencia`,
 1 AS `telf`,
 1 AS `codigo`,
 1 AS `nombre_prevendedor`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vista_productos`
--

DROP TABLE IF EXISTS `vista_productos`;
/*!50001 DROP VIEW IF EXISTS `vista_productos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vista_productos` AS SELECT 
 1 AS `id_producto`,
 1 AS `precio_venta`,
 1 AS `stock`,
 1 AS `fecha_vencimiento`,
 1 AS `categoria`,
 1 AS `unidad`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vista_clientes`
--

/*!50001 DROP VIEW IF EXISTS `vista_clientes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_clientes` AS select `c`.`idcliente` AS `idcliente`,concat(`c`.`nombre`,' ',`c`.`apellido`) AS `nombre_completo`,`c`.`razonSocial` AS `razonSocial`,`c`.`NIT_CI` AS `NIT_CI`,`c`.`Direccion` AS `Direccion`,`c`.`Referencia` AS `Referencia`,`c`.`telf` AS `telf`,`c`.`codigo` AS `codigo`,`e`.`nombre` AS `nombre_prevendedor` from ((`cliente` `c` join `prevendedor` `p` on((`c`.`prevendedor_idprevendedor` = `p`.`idprevendedor`))) join `empleados` `e` on((`p`.`datos` = `e`.`id_empleado`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_productos`
--

/*!50001 DROP VIEW IF EXISTS `vista_productos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_productos` AS select `p`.`id_producto` AS `id_producto`,`p`.`precio_venta` AS `precio_venta`,`p`.`stock` AS `stock`,`p`.`fecha_vencimiento` AS `fecha_vencimiento`,`c`.`categoria` AS `categoria`,`u`.`unidad` AS `unidad` from ((`productos` `p` join `unidades` `u` on((`p`.`unidades_idunidades` = `u`.`idunidades`))) join `categorias` `c` on((`p`.`categoria` = `c`.`categoria`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-07-06 22:42:48
