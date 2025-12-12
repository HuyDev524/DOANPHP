-- MySQL dump 10.13  Distrib 8.2.0, for Win64 (x86_64)
--
-- Host: localhost    Database: bookstore
-- ------------------------------------------------------
-- Server version	8.2.0

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
-- Table structure for table `chitiethd`
--

DROP TABLE IF EXISTS `chitiethd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chitiethd` (
  `mahd` int NOT NULL,
  `masach` varchar(15) NOT NULL,
  `soluong` tinyint DEFAULT NULL,
  `gia` float DEFAULT NULL,
  PRIMARY KEY (`mahd`,`masach`),
  KEY `fk_chitiethd_sach` (`masach`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chitiethd`
--

LOCK TABLES `chitiethd` WRITE;
/*!40000 ALTER TABLE `chitiethd` DISABLE KEYS */;
INSERT INTO `chitiethd` VALUES (1,'S001',1,150000),(1,'S003',2,25000),(2,'S005',1,180000),(3,'S002',1,85000),(3,'S008',1,79000),(4,'S004',3,200000),(5,'S006',1,300000),(5,'S007',5,25000),(6,'S009',1,350000),(6,'S010',1,190000),(7,'S012',5,45000),(8,'S006',1,300000),(9,'S015',10,22000),(10,'S013',2,80000),(10,'S002',1,85000),(11,'S011',1,120000),(11,'S014',1,60000),(12,'S009',2,350000);
/*!40000 ALTER TABLE `chitiethd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hoadon`
--

DROP TABLE IF EXISTS `hoadon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hoadon` (
  `mahd` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `ngayhd` datetime NOT NULL,
  `tennguoinhan` varchar(50) NOT NULL,
  `diachinguoinhan` varchar(80) NOT NULL,
  `ngaynhan` date NOT NULL,
  `dienthoainguoinhan` varchar(11) NOT NULL,
  `trangthai` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`mahd`),
  KEY `email` (`email`),
  CONSTRAINT `FK_HD_KH` FOREIGN KEY (`email`) REFERENCES `khachhang` (`email`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hoadon`
--

LOCK TABLES `hoadon` WRITE;
/*!40000 ALTER TABLE `hoadon` DISABLE KEYS */;
INSERT INTO `hoadon` VALUES (1,'nguyenvana@gmail.com','2023-10-01 08:30:00','Nguyễn Văn A','123 Lê Lợi, Q1, TP.HCM','2023-10-03','0901234567',1),(2,'tranthib@gmail.com','2023-10-02 14:15:00','Trần Thị B','45 Nguyễn Huệ, Q1, TP.HCM','2023-10-04','0909888777',1),(3,'nguyenvana@gmail.com','2023-10-05 09:00:00','Nguyễn Văn A','123 Lê Lợi, Q1, TP.HCM','2023-10-07','0901234567',0),(4,'lecc@yahoo.com','2023-10-06 10:30:00','Lê Văn C','100 Cầu Giấy, Hà Nội','2023-10-09','0912345678',2),(5,'phamthid@gmail.com','2023-10-07 16:45:00','Phạm Thị D','12 Hùng Vương, Đà Nẵng','2023-10-10','0987654321',1),(6,'vuthif@gmail.com','2023-10-15 08:00:00','Vũ Thị F','88 Lê Văn Sỹ, Q3, TP.HCM','2023-10-17','0911223344',1),(7,'dangvang@yahoo.com','2023-10-16 10:20:00','Đặng Văn G','15 Trần Hưng Đạo, Vũng Tàu','2023-10-19','0944556677',0),(8,'nguyenvana@gmail.com','2023-10-18 14:45:00','Nguyễn Văn A','123 Lê Lợi, Q1, TP.HCM','2023-10-20','0901234567',1),(9,'buiyi@outlook.com','2023-10-20 09:30:00','Bùi Văn I','99 Tô Hiến Thành, Q10, TP.HCM','2023-10-21','0905556667',2),(10,'khongtenk@gmail.com','2023-10-21 16:15:00','Khổng Văn K','Vinhomes Central Park, TP.HCM','2023-10-23','0933999888',1),(11,'tranthib@gmail.com','2023-10-22 11:00:00','Người nhà chị B','45 Nguyễn Huệ, Q1, TP.HCM','2023-10-24','0909000111',1),(12,'hoange@outlook.com','2023-10-25 13:00:00','Hoàng Văn E','50 CMT8, Cần Thơ','2023-10-28','0933444555',0);
/*!40000 ALTER TABLE `hoadon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `khachhang`
--

DROP TABLE IF EXISTS `khachhang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `khachhang` (
  `email` varchar(50) NOT NULL,
  `matkhau` varchar(32) NOT NULL,
  `tenkh` varchar(50) NOT NULL,
  `diachi` varchar(100) NOT NULL,
  `dienthoai` varchar(11) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `khachhang`
--

LOCK TABLES `khachhang` WRITE;
/*!40000 ALTER TABLE `khachhang` DISABLE KEYS */;
INSERT INTO `khachhang` VALUES ('buiyi@outlook.com','pass888','Bùi Văn I','99 Tô Hiến Thành, Q10, TP.HCM','0905556667'),('dangvang@yahoo.com','123123','Đặng Văn G','15 Trần Hưng Đạo, Vũng Tàu','0944556677'),('hoange@outlook.com','securepass','Hoàng Văn E','50 CMT8, Cần Thơ','0933444555'),('khongtenk@gmail.com','k123456','Khổng Văn K','Vinhomes Central Park, TP.HCM','0933999888'),('lecc@yahoo.com','admin123','Lê Văn C','100 Cầu Giấy, Hà Nội','0912345678'),('nguyenh@gmail.com','abcxyz','Nguyễn Thị H','202 Pasteur, Q3, TP.HCM','0977889900'),('nguyenvana@gmail.com','123456','Nguyễn Văn A','123 Lê Lợi, Q1, TP.HCM','0901234567'),('phamthid@gmail.com','pass1234','Phạm Thị D','12 Hùng Vương, Đà Nẵng','0987654321'),('tranthib@gmail.com','password','Trần Thị B','45 Nguyễn Huệ, Q1, TP.HCM','0909888777'),('vuthif@gmail.com','pass567','Vũ Thị F','88 Lê Văn Sỹ, Q3, TP.HCM','0911223344');
/*!40000 ALTER TABLE `khachhang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loai`
--

DROP TABLE IF EXISTS `loai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loai` (
  `maloai` varchar(5) NOT NULL,
  `tenloai` varchar(50) NOT NULL,
  PRIMARY KEY (`maloai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loai`
--

LOCK TABLES `loai` WRITE;
/*!40000 ALTER TABLE `loai` DISABLE KEYS */;
INSERT INTO `loai` VALUES ('L01','Công nghệ thông tin'),('L02','Kinh tế - Quản trị'),('L03','Văn học nước ngoài'),('L04','Truyện tranh'),('L05','Kỹ năng sống'),('L06','Sách Ngoại ngữ'),('L07','Tâm lý - Giáo dục'),('L08','Sách Thiếu nhi');
/*!40000 ALTER TABLE `loai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nhaxb`
--

DROP TABLE IF EXISTS `nhaxb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nhaxb` (
  `manxb` varchar(5) NOT NULL,
  `tennxb` text NOT NULL,
  PRIMARY KEY (`manxb`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nhaxb`
--

LOCK TABLES `nhaxb` WRITE;
/*!40000 ALTER TABLE `nhaxb` DISABLE KEYS */;
INSERT INTO `nhaxb` VALUES ('NXB01','NXB Trẻ'),('NXB02','NXB Kim Đồng'),('NXB03','NXB Giáo Dục'),('NXB04','NXB Lao Động'),('NXB05','NXB Hội Nhà Văn'),('NXB06','NXB Phụ Nữ'),('NXB07','NXB Thế Giới');
/*!40000 ALTER TABLE `nhaxb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sach`
--

DROP TABLE IF EXISTS `sach`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sach` (
  `masach` varchar(15) NOT NULL,
  `tensach` varchar(250) NOT NULL,
  `mota` text NOT NULL,
  `gia` float NOT NULL,
  `hinh` varchar(50) NOT NULL,
  `manxb` varchar(5) NOT NULL,
  `maloai` varchar(5) NOT NULL,
  PRIMARY KEY (`masach`),
  KEY `manxb` (`manxb`,`maloai`),
  KEY `FK_SACH_LOAI` (`maloai`),
  CONSTRAINT `FK_SACH_LOAI` FOREIGN KEY (`maloai`) REFERENCES `loai` (`maloai`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_SACH_NXB` FOREIGN KEY (`manxb`) REFERENCES `nhaxb` (`manxb`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sach`
--

LOCK TABLES `sach` WRITE;
/*!40000 ALTER TABLE `sach` DISABLE KEYS */;
INSERT INTO `sach` VALUES ('S001','Lập trình Java căn bản','Sách học Java cho người mới bắt đầu',350000,'java.jpg','NXB01','L01'),('S002','Đắc Nhân Tâm','Nghệ thuật ứng xử cơ bản',85000,'dacnhantam.jpg','NXB01','L05'),('S003','Doraemon Tập 1','Truyện tranh thiếu nhi Nhật Bản',25000,'doraemon1.jpg','NXB02','L04'),('S004','Kinh tế học vĩ mô','Giáo trình đại học',200000,'kinhte.jpg','NXB03','L02'),('S005','Harry Potter và Hòn đá phù thủy','Tiểu thuyết giả tưởng',180000,'harrypotter1.jpg','NXB01','L03'),('S006','SQL Server toàn tập','Hướng dẫn quản trị CSDL',300000,'sql.jpg','NXB01','L01'),('S007','Conan Tập 100','Thám tử lừng danh',25000,'conan100.jpg','NXB02','L04'),('S008','Nhà Giả Kim','Tiểu thuyết bán chạy nhất',79000,'nhagiakim.jpg','NXB05','L03'),('S009','Clean Code - Mã sạch','Tư duy lập trình chuyên nghiệp',350000,'cleancode.jpg','NXB01','L01'),('S010','Hack Não 1500 Từ Tiếng Anh','Sách học từ vựng siêu tốc',190000,'hacknao.jpg','NXB07','L06'),('S011','Tâm lý học tội phạm','Phân tích tâm lý',120000,'tamlytoi.jpg','NXB06','L07'),('S012','Dế Mèn Phiêu Lưu Ký','Văn học thiếu nhi kinh điển',45000,'demen.jpg','NXB02','L08'),('S013','Tuổi trẻ đáng giá bao nhiêu','Sách kỹ năng sống hot',80000,'tuoitre.jpg','NXB05','L05'),('S014','Giải tích 1','Toán cao cấp đại học',60000,'giaitich1.jpg','NXB03','L01'),('S015','Shin Cậu bé bút chì Tập 1','Truyện tranh hài hước',22000,'shin1.jpg','NXB02','L04');
/*!40000 ALTER TABLE `sach` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `top_10_ban_chay`
--

DROP TABLE IF EXISTS `top_10_ban_chay`;
/*!50001 DROP VIEW IF EXISTS `top_10_ban_chay`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `top_10_ban_chay` AS SELECT 
 1 AS `masach`,
 1 AS `tensach`,
 1 AS `tongsoluong`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `top_10_books`
--

DROP TABLE IF EXISTS `top_10_books`;
/*!50001 DROP VIEW IF EXISTS `top_10_books`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `top_10_books` AS SELECT 
 1 AS `masach`,
 1 AS `tensach`,
 1 AS `gia`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `top_10_ban_chay`
--

/*!50001 DROP VIEW IF EXISTS `top_10_ban_chay`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `top_10_ban_chay` AS select `s`.`masach` AS `masach`,`s`.`tensach` AS `tensach`,sum(`ct`.`soluong`) AS `tongsoluong` from (`chitiethd` `ct` join `sach` `s` on((`ct`.`masach` = `s`.`masach`))) group by `s`.`masach`,`s`.`tensach` order by `tongsoluong` desc limit 10 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `top_10_books`
--

/*!50001 DROP VIEW IF EXISTS `top_10_books`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `top_10_books` AS select `sach`.`masach` AS `masach`,`sach`.`tensach` AS `tensach`,`sach`.`gia` AS `gia` from `sach` order by `sach`.`gia` desc limit 10 */;
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

-- Dump completed on 2025-12-03 17:00:33
