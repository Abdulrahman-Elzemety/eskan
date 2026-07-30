CREATE DATABASE  IF NOT EXISTS `my_database` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `my_database`;
-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: my_database
-- ------------------------------------------------------
-- Server version	8.0.46

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
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `PersonID` int NOT NULL,
  PRIMARY KEY (`PersonID`),
  CONSTRAINT `FK_Customer_Person` FOREIGN KEY (`PersonID`) REFERENCES `person` (`PersonID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1),(2),(3),(4),(5),(6),(7),(8),(9),(10),(11),(12),(13),(14),(15),(16),(17),(18),(19),(20),(27);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customeremploymentinformation`
--

DROP TABLE IF EXISTS `customeremploymentinformation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customeremploymentinformation` (
  `CustomerEmploymentID` int NOT NULL AUTO_INCREMENT,
  `PersonID` int NOT NULL,
  `Employer` varchar(100) DEFAULT NULL,
  `JobTitle` varchar(100) DEFAULT NULL,
  `YearsOfService` int DEFAULT NULL,
  `EmploymentStatus` enum('Employed','Unemployed','Self-Employed','Retired','Student') DEFAULT NULL,
  PRIMARY KEY (`CustomerEmploymentID`),
  UNIQUE KEY `PersonID` (`PersonID`),
  CONSTRAINT `FK_CustomerEmploymentInformation_Customer` FOREIGN KEY (`PersonID`) REFERENCES `customer` (`PersonID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customeremploymentinformation`
--

LOCK TABLES `customeremploymentinformation` WRITE;
/*!40000 ALTER TABLE `customeremploymentinformation` DISABLE KEYS */;
INSERT INTO `customeremploymentinformation` VALUES (1,1,'Dubai Police','Officer',12,'Employed'),(2,2,'Dubai Municipality','Engineer',8,'Employed'),(3,3,NULL,NULL,0,'Unemployed'),(4,4,'DEWA','Administrator',10,'Employed'),(5,5,'RTA','Supervisor',15,'Employed'),(6,6,NULL,NULL,0,'Student'),(7,7,'Emirates Airline','Technician',7,'Employed'),(8,8,NULL,NULL,0,'Unemployed'),(9,9,'Dubai Courts','Lawyer',13,'Employed'),(10,10,NULL,NULL,0,'Retired'),(11,11,'Dubai Customs','Inspector',11,'Employed'),(12,12,'Private Company','HR Officer',6,'Employed'),(13,13,'Ministry of Education','Teacher',9,'Employed'),(14,14,NULL,NULL,0,'Student'),(15,15,'Dubai Health','Nurse',14,'Employed'),(16,16,NULL,NULL,0,'Unemployed'),(17,17,'Dubai Police','Captain',20,'Employed'),(18,18,'Private Company','Accountant',5,'Employed'),(19,19,'RTA','Driver',9,'Employed'),(20,20,NULL,NULL,0,'Student'),(24,27,'','Eng',1,'Employed');
/*!40000 ALTER TABLE `customeremploymentinformation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customerfamilyinformation`
--

DROP TABLE IF EXISTS `customerfamilyinformation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customerfamilyinformation` (
  `CustomerFamilyID` int NOT NULL AUTO_INCREMENT,
  `PersonID` int NOT NULL,
  `MaritalStatus` enum('Single','Married','Divorced','Widowed') DEFAULT NULL,
  `FamilyMembers` int DEFAULT NULL,
  `Dependents` int DEFAULT NULL,
  PRIMARY KEY (`CustomerFamilyID`),
  UNIQUE KEY `PersonID` (`PersonID`),
  CONSTRAINT `FK_CustomerFamilyInformation_Customer` FOREIGN KEY (`PersonID`) REFERENCES `customer` (`PersonID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customerfamilyinformation`
--

LOCK TABLES `customerfamilyinformation` WRITE;
/*!40000 ALTER TABLE `customerfamilyinformation` DISABLE KEYS */;
INSERT INTO `customerfamilyinformation` VALUES (1,1,'Married',5,3),(2,2,'Married',4,2),(3,3,'Single',1,0),(4,4,'Married',6,4),(5,5,'Married',7,5),(6,6,'Single',1,0),(7,7,'Married',3,1),(8,8,'Single',1,0),(9,9,'Married',5,3),(10,10,'Widowed',2,1),(11,11,'Married',4,2),(12,12,'Divorced',2,1),(13,13,'Married',6,4),(14,14,'Single',1,0),(15,15,'Married',5,2),(16,16,'Single',1,0),(17,17,'Married',8,6),(18,18,'Married',4,2),(19,19,'Married',3,1),(20,20,'Single',1,0),(25,27,'Single',5,0);
/*!40000 ALTER TABLE `customerfamilyinformation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customerfinancialinformation`
--

DROP TABLE IF EXISTS `customerfinancialinformation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customerfinancialinformation` (
  `CustomerFinancialID` int NOT NULL AUTO_INCREMENT,
  `PersonID` int NOT NULL,
  `MonthlyIncome` decimal(10,2) DEFAULT NULL,
  `MonthlyExpenses` decimal(10,2) DEFAULT NULL,
  `Liabilities` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`CustomerFinancialID`),
  UNIQUE KEY `PersonID` (`PersonID`),
  CONSTRAINT `FK_CustomerFinancialInformation_Customer` FOREIGN KEY (`PersonID`) REFERENCES `customer` (`PersonID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customerfinancialinformation`
--

LOCK TABLES `customerfinancialinformation` WRITE;
/*!40000 ALTER TABLE `customerfinancialinformation` DISABLE KEYS */;
INSERT INTO `customerfinancialinformation` VALUES (1,1,18000.00,9000.00,120000.00),(2,2,14000.00,7000.00,80000.00),(3,3,0.00,1500.00,0.00),(4,4,22000.00,12000.00,150000.00),(5,5,35000.00,18000.00,250000.00),(6,6,0.00,1000.00,0.00),(7,7,16000.00,8000.00,60000.00),(8,8,0.00,1200.00,0.00),(9,9,45000.00,20000.00,300000.00),(10,10,9000.00,4000.00,10000.00),(11,11,20000.00,9000.00,90000.00),(12,12,17000.00,8500.00,70000.00),(13,13,15000.00,7000.00,50000.00),(14,14,0.00,900.00,0.00),(15,15,28000.00,13000.00,170000.00),(16,16,0.00,1000.00,0.00),(17,17,52000.00,25000.00,400000.00),(18,18,13000.00,6500.00,30000.00),(19,19,16000.00,7500.00,45000.00),(20,20,0.00,800.00,0.00),(21,27,10000.00,0.00,0.00);
/*!40000 ALTER TABLE `customerfinancialinformation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documenttype`
--

DROP TABLE IF EXISTS `documenttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documenttype` (
  `DocumentTypeID` int NOT NULL AUTO_INCREMENT,
  `DocumentName` varchar(100) NOT NULL,
  PRIMARY KEY (`DocumentTypeID`),
  UNIQUE KEY `DocumentName` (`DocumentName`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documenttype`
--

LOCK TABLES `documenttype` WRITE;
/*!40000 ALTER TABLE `documenttype` DISABLE KEYS */;
INSERT INTO `documenttype` VALUES (13,'Bank Statement'),(18,'Certificate of Child Custody or Financial Support'),(15,'Certificate of Continuity of Marriage'),(17,'Certificate of Social Status'),(2,'Construction Undertaking Form'),(4,'Difference Payment Undertaking Form'),(6,'Emirates ID'),(11,'Employment Contract'),(8,'Family Book'),(19,'House Completion Certificate'),(1,'Housing Service Application Form'),(5,'Land Disposal Freeze Form'),(14,'Marriage Certificate'),(7,'Passport'),(12,'Proof of Income'),(3,'Residential Land Mortgage Form'),(9,'Residential Land Plot Plan'),(10,'Salary Certificate'),(16,'Sharia Inheritance Certificate');
/*!40000 ALTER TABLE `documenttype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person` (
  `PersonID` int NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Gender` enum('Male','Female') NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `RegistrationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PersonID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (1,'Ahmed','Al Mansoori','ahmed.almansoori@email.com','HASH123','Male','+971501000001','Dubai, UAE','1989-04-15','2026-07-27 09:13:21'),(2,'Fatima','Al Suwaidi','fatima.alsuwaidi@email.com','HASH123','Female','+971501000002','Dubai, UAE','1992-08-21','2026-07-27 09:13:21'),(3,'Omar','Al Marri','omar.almarri@email.com','HASH123','Male','+971501000003','Dubai, UAE','1985-01-11','2026-07-27 09:13:21'),(4,'Maryam','Al Mazrouei','maryam.almazrouei@email.com','HASH123','Female','+971501000004','Dubai, UAE','1994-03-18','2026-07-27 09:13:21'),(5,'Khalid','Al Shamsi','khalid.alshamsi@email.com','HASH123','Male','+971501000005','Dubai, UAE','1983-07-09','2026-07-27 09:13:21'),(6,'Aisha','Al Kaabi','aisha.alkaabi@email.com','HASH123','Female','+971501000006','Dubai, UAE','1995-10-05','2026-07-27 09:13:21'),(7,'Saeed','Al Hammadi','saeed.alhammadi@email.com','HASH123','Male','+971501000007','Dubai, UAE','1988-12-17','2026-07-27 09:13:21'),(8,'Noora','Al Dhaheri','noora.aldhaheri@email.com','HASH123','Female','+971501000008','Dubai, UAE','1996-06-30','2026-07-27 09:13:21'),(9,'Mohammed','Al Ameri','mohammed.alameri@email.com','HASH123','Male','+971501000009','Dubai, UAE','1981-09-13','2026-07-27 09:13:21'),(10,'Hessa','Al Falasi','hessa.alfalasi@email.com','HASH123','Female','+971501000010','Dubai, UAE','1990-11-27','2026-07-27 09:13:21'),(11,'Rashid','Al Nuaimi','rashid.alnuaimi@email.com','HASH123','Male','+971501000011','Dubai, UAE','1987-05-24','2026-07-27 09:13:21'),(12,'Sara','Al Shehhi','sara.alshehhi@email.com','HASH123','Female','+971501000012','Dubai, UAE','1993-01-06','2026-07-27 09:13:21'),(13,'Yousef','Al Muhairi','yousef.almuhairi@email.com','HASH123','Male','+971501000013','Dubai, UAE','1986-02-19','2026-07-27 09:13:21'),(14,'Latifa','Al Ketbi','latifa.alketbi@email.com','HASH123','Female','+971501000014','Dubai, UAE','1991-07-22','2026-07-27 09:13:21'),(15,'Hamad','Al Balushi','hamad.albalushi@email.com','HASH123','Male','+971501000015','Dubai, UAE','1984-08-10','2026-07-27 09:13:21'),(16,'Shamma','Al Rumaithi','shamma.alrumaithi@email.com','HASH123','Female','+971501000016','Dubai, UAE','1997-09-15','2026-07-27 09:13:21'),(17,'Abdullah','Al Qubaisi','abdullah.alqubaisi@email.com','HASH123','Male','+971501000017','Dubai, UAE','1982-10-28','2026-07-27 09:13:21'),(18,'Maha','Al Mehairi','maha.almehairi@email.com','HASH123','Female','+971501000018','Dubai, UAE','1998-04-03','2026-07-27 09:13:21'),(19,'Salem','Al Zaabi','salem.alzaabi@email.com','HASH123','Male','+971501000019','Dubai, UAE','1980-12-01','2026-07-27 09:13:21'),(20,'Reem','Al Yammahi','reem.alyammahi@email.com','HASH123','Female','+971501000020','Dubai, UAE','1999-05-12','2026-07-27 09:13:21'),(21,'Nasser','Al Mansoori','nasser.specialist@email.com','HASH123','Male','+971501000021','Dubai, UAE','1980-06-18','2026-07-27 09:13:21'),(22,'Amal','Al Suwaidi','amal.specialist@email.com','HASH123','Female','+971501000022','Dubai, UAE','1984-02-14','2026-07-27 09:13:21'),(23,'Majid','Al Marri','majid.specialist@email.com','HASH123','Male','+971501000023','Dubai, UAE','1978-11-08','2026-07-27 09:13:21'),(24,'Hind','Al Mazrouei','hind.specialist@email.com','HASH123','Female','+971501000024','Dubai, UAE','1986-09-20','2026-07-27 09:13:21'),(25,'Sultan','Al Shamsi','sultan.specialist@email.com','HASH123','Male','+971501000025','Dubai, UAE','1981-03-04','2026-07-27 09:13:21'),(27,'Abdulrahman','Elzemety','Abdulrahman@gmail.com','$2y$10$yHR05cZcKJXI4Q2csNY6o.7qTzpXb.uoRTRk73J2GlCkeOHx5yjiq','Male','+971000000000','dat','2004-07-07','2026-07-27 12:38:15'),(28,'System','Specialist','specialist@mail.com','$2y$10$yHR05cZcKJXI4Q2csNY6o.7qTzpXb.uoRTRk73J2GlCkeOHx5yjiq','Male','+971501234567','Dubai','1990-01-01','2026-07-28 13:12:55');
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requestdocument`
--

DROP TABLE IF EXISTS `requestdocument`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requestdocument` (
  `RequestDocumentID` int NOT NULL AUTO_INCREMENT,
  `RequestID` int NOT NULL,
  `DocumentTypeID` int NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FilePath` varchar(500) NOT NULL,
  `UploadDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`RequestDocumentID`),
  KEY `RequestID` (`RequestID`),
  KEY `DocumentTypeID` (`DocumentTypeID`),
  CONSTRAINT `requestdocument_ibfk_1` FOREIGN KEY (`RequestID`) REFERENCES `servicerequest` (`RequestID`),
  CONSTRAINT `requestdocument_ibfk_2` FOREIGN KEY (`DocumentTypeID`) REFERENCES `documenttype` (`DocumentTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requestdocument`
--

LOCK TABLES `requestdocument` WRITE;
/*!40000 ALTER TABLE `requestdocument` DISABLE KEYS */;
INSERT INTO `requestdocument` VALUES (1,1,1,'HousingServiceApplicationForm.pdf','/uploads/request_1/HousingServiceApplicationForm.pdf','2025-01-14 10:15:00'),(2,1,2,'ConstructionUndertakingForm.pdf','/uploads/request_1/ConstructionUndertakingForm.pdf','2025-01-14 10:17:00'),(3,1,3,'ResidentialLandMortgageForm.pdf','/uploads/request_1/ResidentialLandMortgageForm.pdf','2025-01-14 10:20:00'),(4,1,6,'EmiratesID.pdf','/uploads/request_1/EmiratesID.pdf','2025-01-14 10:22:00'),(5,1,7,'Passport.pdf','/uploads/request_1/Passport.pdf','2025-01-14 10:23:00'),(6,1,8,'FamilyBook.pdf','/uploads/request_1/FamilyBook.pdf','2025-01-14 10:24:00'),(7,1,9,'ResidentialLandPlotPlan.pdf','/uploads/request_1/ResidentialLandPlotPlan.pdf','2025-01-14 10:25:00'),(8,1,10,'SalaryCertificate.pdf','/uploads/request_1/SalaryCertificate.pdf','2025-01-14 10:27:00'),(9,1,11,'EmploymentContract.pdf','/uploads/request_1/EmploymentContract.pdf','2025-01-14 10:28:00'),(10,1,12,'ProofOfIncome.pdf','/uploads/request_1/ProofOfIncome.pdf','2025-01-14 10:29:00'),(11,1,13,'BankStatement.pdf','/uploads/request_1/BankStatement.pdf','2025-01-14 10:30:00'),(12,1,14,'MarriageCertificate.pdf','/uploads/request_1/MarriageCertificate.pdf','2025-01-14 10:31:00'),(13,2,1,'HousingServiceApplicationForm.pdf','/uploads/request_2/HousingServiceApplicationForm.pdf','2026-03-11 09:00:00'),(14,2,6,'EmiratesID.pdf','/uploads/request_2/EmiratesID.pdf','2026-03-11 09:02:00'),(15,2,7,'Passport.pdf','/uploads/request_2/Passport.pdf','2026-03-11 09:03:00'),(16,2,8,'FamilyBook.pdf','/uploads/request_2/FamilyBook.pdf','2026-03-11 09:04:00'),(17,2,10,'SalaryCertificate.pdf','/uploads/request_2/SalaryCertificate.pdf','2026-03-11 09:05:00'),(18,2,11,'EmploymentContract.pdf','/uploads/request_2/EmploymentContract.pdf','2026-03-11 09:06:00'),(19,2,12,'ProofOfIncome.pdf','/uploads/request_2/ProofOfIncome.pdf','2026-03-11 09:07:00'),(20,2,13,'BankStatement.pdf','/uploads/request_2/BankStatement.pdf','2026-03-11 09:08:00'),(21,2,19,'HouseCompletionCertificate.pdf','/uploads/request_2/HouseCompletionCertificate.pdf','2026-03-11 09:09:00'),(22,2,14,'MarriageCertificate.pdf','/uploads/request_2/MarriageCertificate.pdf','2026-03-11 09:10:00'),(23,3,1,'HousingServiceApplicationForm.pdf','/uploads/request_3/HousingServiceApplicationForm.pdf','2025-02-09 08:00:00'),(24,3,2,'ConstructionUndertakingForm.pdf','/uploads/request_3/ConstructionUndertakingForm.pdf','2025-02-09 08:02:00'),(25,3,3,'ResidentialLandMortgageForm.pdf','/uploads/request_3/ResidentialLandMortgageForm.pdf','2025-02-09 08:03:00'),(26,3,4,'DifferencePaymentUndertakingForm.pdf','/uploads/request_3/DifferencePaymentUndertakingForm.pdf','2025-02-09 08:04:00'),(27,3,5,'LandDisposalFreezeForm.pdf','/uploads/request_3/LandDisposalFreezeForm.pdf','2025-02-09 08:05:00'),(28,3,6,'EmiratesID.pdf','/uploads/request_3/EmiratesID.pdf','2025-02-09 08:06:00'),(29,3,7,'Passport.pdf','/uploads/request_3/Passport.pdf','2025-02-09 08:07:00'),(30,3,8,'FamilyBook.pdf','/uploads/request_3/FamilyBook.pdf','2025-02-09 08:08:00'),(31,3,9,'ResidentialLandPlotPlan.pdf','/uploads/request_3/ResidentialLandPlotPlan.pdf','2025-02-09 08:09:00'),(32,3,10,'SalaryCertificate.pdf','/uploads/request_3/SalaryCertificate.pdf','2025-02-09 08:10:00'),(33,3,11,'EmploymentContract.pdf','/uploads/request_3/EmploymentContract.pdf','2025-02-09 08:11:00'),(34,3,14,'MarriageCertificate.pdf','/uploads/request_3/MarriageCertificate.pdf','2025-02-09 08:12:00'),(35,4,1,'HousingServiceApplicationForm.pdf','/uploads/request_4/HousingServiceApplicationForm.pdf','2025-02-17 11:00:00'),(36,4,2,'ConstructionUndertakingForm.pdf','/uploads/request_4/ConstructionUndertakingForm.pdf','2025-02-17 11:01:00'),(37,4,6,'EmiratesID.pdf','/uploads/request_4/EmiratesID.pdf','2025-02-17 11:02:00'),(38,4,7,'Passport.pdf','/uploads/request_4/Passport.pdf','2025-02-17 11:03:00'),(39,4,8,'FamilyBook.pdf','/uploads/request_4/FamilyBook.pdf','2025-02-17 11:04:00'),(40,5,1,'HousingServiceApplicationForm.pdf','/uploads/request_5/HousingServiceApplicationForm.pdf','2025-03-04 09:00:00'),(41,5,2,'ConstructionUndertakingForm.pdf','/uploads/request_5/ConstructionUndertakingForm.pdf','2025-03-04 09:01:00'),(42,5,3,'ResidentialLandMortgageForm.pdf','/uploads/request_5/ResidentialLandMortgageForm.pdf','2025-03-04 09:02:00'),(43,5,6,'EmiratesID.pdf','/uploads/request_5/EmiratesID.pdf','2025-03-04 09:03:00'),(44,5,7,'Passport.pdf','/uploads/request_5/Passport.pdf','2025-03-04 09:04:00'),(45,5,8,'FamilyBook.pdf','/uploads/request_5/FamilyBook.pdf','2025-03-04 09:05:00'),(46,5,9,'ResidentialLandPlotPlan.pdf','/uploads/request_5/ResidentialLandPlotPlan.pdf','2025-03-04 09:06:00'),(47,5,10,'SalaryCertificate.pdf','/uploads/request_5/SalaryCertificate.pdf','2025-03-04 09:07:00'),(48,5,11,'EmploymentContract.pdf','/uploads/request_5/EmploymentContract.pdf','2025-03-04 09:08:00'),(49,5,12,'ProofOfIncome.pdf','/uploads/request_5/ProofOfIncome.pdf','2025-03-04 09:09:00'),(50,5,13,'BankStatement.pdf','/uploads/request_5/BankStatement.pdf','2025-03-04 09:10:00'),(51,5,14,'MarriageCertificate.pdf','/uploads/request_5/MarriageCertificate.pdf','2025-03-04 09:11:00'),(52,6,1,'HousingServiceApplicationForm.pdf','/uploads/request_6/HousingServiceApplicationForm.pdf','2026-04-19 09:00:00'),(53,6,6,'EmiratesID.pdf','/uploads/request_6/EmiratesID.pdf','2026-04-19 09:01:00'),(54,6,7,'Passport.pdf','/uploads/request_6/Passport.pdf','2026-04-19 09:02:00'),(55,6,8,'FamilyBook.pdf','/uploads/request_6/FamilyBook.pdf','2026-04-19 09:03:00'),(56,6,19,'HouseCompletionCertificate.pdf','/uploads/request_6/HouseCompletionCertificate.pdf','2026-04-19 09:04:00'),(57,6,14,'MarriageCertificate.pdf','/uploads/request_6/MarriageCertificate.pdf','2026-04-19 09:05:00'),(58,7,1,'HousingServiceApplicationForm.pdf','/uploads/request_7/HousingServiceApplicationForm.pdf','2025-03-27 10:00:00'),(59,7,2,'ConstructionUndertakingForm.pdf','/uploads/request_7/ConstructionUndertakingForm.pdf','2025-03-27 10:01:00'),(60,7,3,'ResidentialLandMortgageForm.pdf','/uploads/request_7/ResidentialLandMortgageForm.pdf','2025-03-27 10:02:00'),(61,7,6,'EmiratesID.pdf','/uploads/request_7/EmiratesID.pdf','2025-03-27 10:03:00'),(62,7,7,'Passport.pdf','/uploads/request_7/Passport.pdf','2025-03-27 10:04:00'),(63,7,8,'FamilyBook.pdf','/uploads/request_7/FamilyBook.pdf','2025-03-27 10:05:00'),(64,7,9,'ResidentialLandPlotPlan.pdf','/uploads/request_7/ResidentialLandPlotPlan.pdf','2025-03-27 10:06:00'),(65,7,10,'SalaryCertificate.pdf','/uploads/request_7/SalaryCertificate.pdf','2025-03-27 10:07:00'),(66,7,11,'EmploymentContract.pdf','/uploads/request_7/EmploymentContract.pdf','2025-03-27 10:08:00'),(67,7,12,'ProofOfIncome.pdf','/uploads/request_7/ProofOfIncome.pdf','2025-03-27 10:09:00'),(68,7,13,'BankStatement.pdf','/uploads/request_7/BankStatement.pdf','2025-03-27 10:10:00'),(69,7,14,'MarriageCertificate.pdf','/uploads/request_7/MarriageCertificate.pdf','2025-03-27 10:11:00'),(70,8,1,'HousingServiceApplicationForm.pdf','/uploads/request_8/HousingServiceApplicationForm.pdf','2026-04-30 08:30:00'),(71,8,6,'EmiratesID.pdf','/uploads/request_8/EmiratesID.pdf','2026-04-30 08:31:00'),(72,8,7,'Passport.pdf','/uploads/request_8/Passport.pdf','2026-04-30 08:32:00'),(73,8,8,'FamilyBook.pdf','/uploads/request_8/FamilyBook.pdf','2026-04-30 08:33:00'),(74,8,10,'SalaryCertificate.pdf','/uploads/request_8/SalaryCertificate.pdf','2026-04-30 08:34:00'),(75,8,11,'EmploymentContract.pdf','/uploads/request_8/EmploymentContract.pdf','2026-04-30 08:35:00'),(76,8,12,'ProofOfIncome.pdf','/uploads/request_8/ProofOfIncome.pdf','2026-04-30 08:36:00'),(77,8,13,'BankStatement.pdf','/uploads/request_8/BankStatement.pdf','2026-04-30 08:37:00'),(78,8,19,'HouseCompletionCertificate.pdf','/uploads/request_8/HouseCompletionCertificate.pdf','2026-04-30 08:38:00'),(79,8,14,'MarriageCertificate.pdf','/uploads/request_8/MarriageCertificate.pdf','2026-04-30 08:39:00'),(80,9,1,'HousingServiceApplicationForm.pdf','/uploads/request_9/HousingServiceApplicationForm.pdf','2025-04-11 09:00:00'),(81,9,2,'ConstructionUndertakingForm.pdf','/uploads/request_9/ConstructionUndertakingForm.pdf','2025-04-11 09:01:00'),(82,9,6,'EmiratesID.pdf','/uploads/request_9/EmiratesID.pdf','2025-04-11 09:02:00'),(83,9,7,'Passport.pdf','/uploads/request_9/Passport.pdf','2025-04-11 09:03:00'),(84,9,8,'FamilyBook.pdf','/uploads/request_9/FamilyBook.pdf','2025-04-11 09:04:00'),(85,10,1,'HousingServiceApplicationForm.pdf','/uploads/request_10/HousingServiceApplicationForm.pdf','2025-04-25 08:00:00'),(86,10,6,'EmiratesID.pdf','/uploads/request_10/EmiratesID.pdf','2025-04-25 08:01:00'),(87,10,7,'Passport.pdf','/uploads/request_10/Passport.pdf','2025-04-25 08:02:00'),(88,10,8,'FamilyBook.pdf','/uploads/request_10/FamilyBook.pdf','2025-04-25 08:03:00'),(89,10,10,'SalaryCertificate.pdf','/uploads/request_10/SalaryCertificate.pdf','2025-04-25 08:04:00'),(90,10,11,'EmploymentContract.pdf','/uploads/request_10/EmploymentContract.pdf','2025-04-25 08:05:00'),(91,10,12,'ProofOfIncome.pdf','/uploads/request_10/ProofOfIncome.pdf','2025-04-25 08:06:00'),(92,10,13,'BankStatement.pdf','/uploads/request_10/BankStatement.pdf','2025-04-25 08:07:00'),(93,10,19,'HouseCompletionCertificate.pdf','/uploads/request_10/HouseCompletionCertificate.pdf','2025-04-25 08:08:00'),(94,10,14,'MarriageCertificate.pdf','/uploads/request_10/MarriageCertificate.pdf','2025-04-25 08:09:00'),(95,11,1,'HousingServiceApplicationForm.pdf','/uploads/request_11/HousingServiceApplicationForm.pdf','2025-05-09 09:00:00'),(96,11,6,'EmiratesID.pdf','/uploads/request_11/EmiratesID.pdf','2025-05-09 09:01:00'),(97,11,7,'Passport.pdf','/uploads/request_11/Passport.pdf','2025-05-09 09:02:00'),(98,11,8,'FamilyBook.pdf','/uploads/request_11/FamilyBook.pdf','2025-05-09 09:03:00'),(99,11,19,'HouseCompletionCertificate.pdf','/uploads/request_11/HouseCompletionCertificate.pdf','2025-05-09 09:04:00'),(100,12,1,'HousingServiceApplicationForm.pdf','/uploads/request_12/HousingServiceApplicationForm.pdf','2025-05-24 08:00:00'),(101,12,2,'ConstructionUndertakingForm.pdf','/uploads/request_12/ConstructionUndertakingForm.pdf','2025-05-24 08:01:00'),(102,12,3,'ResidentialLandMortgageForm.pdf','/uploads/request_12/ResidentialLandMortgageForm.pdf','2025-05-24 08:02:00'),(103,12,4,'DifferencePaymentUndertakingForm.pdf','/uploads/request_12/DifferencePaymentUndertakingForm.pdf','2025-05-24 08:03:00'),(104,12,5,'LandDisposalFreezeForm.pdf','/uploads/request_12/LandDisposalFreezeForm.pdf','2025-05-24 08:04:00'),(105,12,6,'EmiratesID.pdf','/uploads/request_12/EmiratesID.pdf','2025-05-24 08:05:00'),(106,12,7,'Passport.pdf','/uploads/request_12/Passport.pdf','2025-05-24 08:06:00'),(107,12,8,'FamilyBook.pdf','/uploads/request_12/FamilyBook.pdf','2025-05-24 08:07:00'),(108,12,9,'ResidentialLandPlotPlan.pdf','/uploads/request_12/ResidentialLandPlotPlan.pdf','2025-05-24 08:08:00'),(109,12,10,'SalaryCertificate.pdf','/uploads/request_12/SalaryCertificate.pdf','2025-05-24 08:09:00'),(110,12,11,'EmploymentContract.pdf','/uploads/request_12/EmploymentContract.pdf','2025-05-24 08:10:00'),(111,12,14,'MarriageCertificate.pdf','/uploads/request_12/MarriageCertificate.pdf','2025-05-24 08:11:00'),(112,13,1,'HousingServiceApplicationForm.pdf','/uploads/request_13/HousingServiceApplicationForm.pdf','2026-01-17 09:00:00'),(113,13,2,'ConstructionUndertakingForm.pdf','/uploads/request_13/ConstructionUndertakingForm.pdf','2026-01-17 09:01:00'),(114,13,3,'ResidentialLandMortgageForm.pdf','/uploads/request_13/ResidentialLandMortgageForm.pdf','2026-01-17 09:02:00'),(115,13,6,'EmiratesID.pdf','/uploads/request_13/EmiratesID.pdf','2026-01-17 09:03:00'),(116,13,7,'Passport.pdf','/uploads/request_13/Passport.pdf','2026-01-17 09:04:00'),(117,13,8,'FamilyBook.pdf','/uploads/request_13/FamilyBook.pdf','2026-01-17 09:05:00'),(118,13,9,'ResidentialLandPlotPlan.pdf','/uploads/request_13/ResidentialLandPlotPlan.pdf','2026-01-17 09:06:00'),(119,13,10,'SalaryCertificate.pdf','/uploads/request_13/SalaryCertificate.pdf','2026-01-17 09:07:00'),(120,13,11,'EmploymentContract.pdf','/uploads/request_13/EmploymentContract.pdf','2026-01-17 09:08:00'),(121,13,12,'ProofOfIncome.pdf','/uploads/request_13/ProofOfIncome.pdf','2026-01-17 09:09:00'),(122,13,13,'BankStatement.pdf','/uploads/request_13/BankStatement.pdf','2026-01-17 09:10:00'),(123,13,14,'MarriageCertificate.pdf','/uploads/request_13/MarriageCertificate.pdf','2026-01-17 09:11:00'),(124,14,1,'HousingServiceApplicationForm.pdf','/uploads/request_14/HousingServiceApplicationForm.pdf','2025-06-07 10:00:00'),(125,14,6,'EmiratesID.pdf','/uploads/request_14/EmiratesID.pdf','2025-06-07 10:01:00'),(126,14,7,'Passport.pdf','/uploads/request_14/Passport.pdf','2025-06-07 10:02:00'),(127,14,8,'FamilyBook.pdf','/uploads/request_14/FamilyBook.pdf','2025-06-07 10:03:00'),(128,14,19,'HouseCompletionCertificate.pdf','/uploads/request_14/HouseCompletionCertificate.pdf','2025-06-07 10:04:00'),(129,15,1,'HousingServiceApplicationForm.pdf','/uploads/request_15/HousingServiceApplicationForm.pdf','2025-06-26 08:00:00'),(130,15,6,'EmiratesID.pdf','/uploads/request_15/EmiratesID.pdf','2025-06-26 08:01:00'),(131,15,7,'Passport.pdf','/uploads/request_15/Passport.pdf','2025-06-26 08:02:00'),(132,15,8,'FamilyBook.pdf','/uploads/request_15/FamilyBook.pdf','2025-06-26 08:03:00'),(133,15,10,'SalaryCertificate.pdf','/uploads/request_15/SalaryCertificate.pdf','2025-06-26 08:04:00'),(134,15,11,'EmploymentContract.pdf','/uploads/request_15/EmploymentContract.pdf','2025-06-26 08:05:00'),(135,15,12,'ProofOfIncome.pdf','/uploads/request_15/ProofOfIncome.pdf','2025-06-26 08:06:00'),(136,15,13,'BankStatement.pdf','/uploads/request_15/BankStatement.pdf','2025-06-26 08:07:00'),(137,15,19,'HouseCompletionCertificate.pdf','/uploads/request_15/HouseCompletionCertificate.pdf','2025-06-26 08:08:00'),(138,15,14,'MarriageCertificate.pdf','/uploads/request_15/MarriageCertificate.pdf','2025-06-26 08:09:00'),(139,16,1,'HousingServiceApplicationForm.pdf','/uploads/request_16/HousingServiceApplicationForm.pdf','2025-07-10 09:00:00'),(140,16,2,'ConstructionUndertakingForm.pdf','/uploads/request_16/ConstructionUndertakingForm.pdf','2025-07-10 09:01:00'),(141,16,3,'ResidentialLandMortgageForm.pdf','/uploads/request_16/ResidentialLandMortgageForm.pdf','2025-07-10 09:02:00'),(142,16,4,'DifferencePaymentUndertakingForm.pdf','/uploads/request_16/DifferencePaymentUndertakingForm.pdf','2025-07-10 09:03:00'),(143,16,5,'LandDisposalFreezeForm.pdf','/uploads/request_16/LandDisposalFreezeForm.pdf','2025-07-10 09:04:00'),(144,16,6,'EmiratesID.pdf','/uploads/request_16/EmiratesID.pdf','2025-07-10 09:05:00'),(145,16,7,'Passport.pdf','/uploads/request_16/Passport.pdf','2025-07-10 09:06:00'),(146,16,8,'FamilyBook.pdf','/uploads/request_16/FamilyBook.pdf','2025-07-10 09:07:00'),(147,16,9,'ResidentialLandPlotPlan.pdf','/uploads/request_16/ResidentialLandPlotPlan.pdf','2025-07-10 09:08:00'),(148,16,10,'SalaryCertificate.pdf','/uploads/request_16/SalaryCertificate.pdf','2025-07-10 09:09:00'),(149,16,11,'EmploymentContract.pdf','/uploads/request_16/EmploymentContract.pdf','2025-07-10 09:10:00'),(150,17,1,'HousingServiceApplicationForm.pdf','/uploads/request_17/HousingServiceApplicationForm.pdf','2025-07-27 10:00:00'),(151,17,2,'ConstructionUndertakingForm.pdf','/uploads/request_17/ConstructionUndertakingForm.pdf','2025-07-27 10:01:00'),(152,17,3,'ResidentialLandMortgageForm.pdf','/uploads/request_17/ResidentialLandMortgageForm.pdf','2025-07-27 10:02:00'),(153,17,6,'EmiratesID.pdf','/uploads/request_17/EmiratesID.pdf','2025-07-27 10:03:00'),(154,17,7,'Passport.pdf','/uploads/request_17/Passport.pdf','2025-07-27 10:04:00'),(155,17,8,'FamilyBook.pdf','/uploads/request_17/FamilyBook.pdf','2025-07-27 10:05:00'),(156,17,9,'ResidentialLandPlotPlan.pdf','/uploads/request_17/ResidentialLandPlotPlan.pdf','2025-07-27 10:06:00'),(157,17,10,'SalaryCertificate.pdf','/uploads/request_17/SalaryCertificate.pdf','2025-07-27 10:07:00'),(158,17,11,'EmploymentContract.pdf','/uploads/request_17/EmploymentContract.pdf','2025-07-27 10:08:00'),(159,17,12,'ProofOfIncome.pdf','/uploads/request_17/ProofOfIncome.pdf','2025-07-27 10:09:00'),(160,17,13,'BankStatement.pdf','/uploads/request_17/BankStatement.pdf','2025-07-27 10:10:00'),(161,17,14,'MarriageCertificate.pdf','/uploads/request_17/MarriageCertificate.pdf','2025-07-27 10:11:00'),(162,18,1,'HousingServiceApplicationForm.pdf','/uploads/request_18/HousingServiceApplicationForm.pdf','2026-02-15 09:00:00'),(163,18,2,'ConstructionUndertakingForm.pdf','/uploads/request_18/ConstructionUndertakingForm.pdf','2026-02-15 09:01:00'),(164,18,3,'ResidentialLandMortgageForm.pdf','/uploads/request_18/ResidentialLandMortgageForm.pdf','2026-02-15 09:02:00'),(165,18,4,'DifferencePaymentUndertakingForm.pdf','/uploads/request_18/DifferencePaymentUndertakingForm.pdf','2026-02-15 09:03:00'),(166,18,5,'LandDisposalFreezeForm.pdf','/uploads/request_18/LandDisposalFreezeForm.pdf','2026-02-15 09:04:00'),(167,18,6,'EmiratesID.pdf','/uploads/request_18/EmiratesID.pdf','2026-02-15 09:05:00'),(168,18,7,'Passport.pdf','/uploads/request_18/Passport.pdf','2026-02-15 09:06:00'),(169,18,8,'FamilyBook.pdf','/uploads/request_18/FamilyBook.pdf','2026-02-15 09:07:00'),(170,18,9,'ResidentialLandPlotPlan.pdf','/uploads/request_18/ResidentialLandPlotPlan.pdf','2026-02-15 09:08:00'),(171,18,10,'SalaryCertificate.pdf','/uploads/request_18/SalaryCertificate.pdf','2026-02-15 09:09:00'),(172,18,11,'EmploymentContract.pdf','/uploads/request_18/EmploymentContract.pdf','2026-02-15 09:10:00'),(173,18,14,'MarriageCertificate.pdf','/uploads/request_18/MarriageCertificate.pdf','2026-02-15 09:11:00'),(174,19,1,'HousingServiceApplicationForm.pdf','/uploads/request_19/HousingServiceApplicationForm.pdf','2025-08-08 08:00:00'),(175,19,2,'ConstructionUndertakingForm.pdf','/uploads/request_19/ConstructionUndertakingForm.pdf','2025-08-08 08:01:00'),(176,19,6,'EmiratesID.pdf','/uploads/request_19/EmiratesID.pdf','2025-08-08 08:02:00'),(177,19,7,'Passport.pdf','/uploads/request_19/Passport.pdf','2025-08-08 08:03:00'),(178,19,8,'FamilyBook.pdf','/uploads/request_19/FamilyBook.pdf','2025-08-08 08:04:00'),(179,20,1,'HousingServiceApplicationForm.pdf','/uploads/request_20/HousingServiceApplicationForm.pdf','2025-08-29 09:00:00'),(180,20,6,'EmiratesID.pdf','/uploads/request_20/EmiratesID.pdf','2025-08-29 09:01:00'),(181,20,7,'Passport.pdf','/uploads/request_20/Passport.pdf','2025-08-29 09:02:00'),(182,20,8,'FamilyBook.pdf','/uploads/request_20/FamilyBook.pdf','2025-08-29 09:03:00'),(183,20,10,'SalaryCertificate.pdf','/uploads/request_20/SalaryCertificate.pdf','2025-08-29 09:04:00'),(184,20,11,'EmploymentContract.pdf','/uploads/request_20/EmploymentContract.pdf','2025-08-29 09:05:00'),(185,20,12,'ProofOfIncome.pdf','/uploads/request_20/ProofOfIncome.pdf','2025-08-29 09:06:00'),(186,20,13,'BankStatement.pdf','/uploads/request_20/BankStatement.pdf','2025-08-29 09:07:00'),(187,20,19,'HouseCompletionCertificate.pdf','/uploads/request_20/HouseCompletionCertificate.pdf','2025-08-29 09:08:00'),(188,20,14,'MarriageCertificate.pdf','/uploads/request_20/MarriageCertificate.pdf','2025-08-29 09:09:00'),(189,21,1,'HousingServiceApplicationForm.pdf','/uploads/request_21/HousingServiceApplicationForm.pdf','2026-05-18 08:00:00'),(190,21,6,'EmiratesID.pdf','/uploads/request_21/EmiratesID.pdf','2026-05-18 08:01:00'),(191,21,7,'Passport.pdf','/uploads/request_21/Passport.pdf','2026-05-18 08:02:00'),(192,21,8,'FamilyBook.pdf','/uploads/request_21/FamilyBook.pdf','2026-05-18 08:03:00'),(193,21,19,'HouseCompletionCertificate.pdf','/uploads/request_21/HouseCompletionCertificate.pdf','2026-05-18 08:04:00'),(194,21,14,'MarriageCertificate.pdf','/uploads/request_21/MarriageCertificate.pdf','2026-05-18 08:05:00'),(195,22,1,'HousingServiceApplicationForm.pdf','/uploads/request_22/HousingServiceApplicationForm.pdf','2025-09-18 09:00:00'),(196,22,2,'ConstructionUndertakingForm.pdf','/uploads/request_22/ConstructionUndertakingForm.pdf','2025-09-18 09:01:00'),(197,22,6,'EmiratesID.pdf','/uploads/request_22/EmiratesID.pdf','2025-09-18 09:02:00'),(198,22,7,'Passport.pdf','/uploads/request_22/Passport.pdf','2025-09-18 09:03:00'),(199,22,8,'FamilyBook.pdf','/uploads/request_22/FamilyBook.pdf','2025-09-18 09:04:00'),(200,23,1,'HousingServiceApplicationForm.pdf','/uploads/request_23/HousingServiceApplicationForm.pdf','2025-10-06 08:00:00'),(201,23,2,'ConstructionUndertakingForm.pdf','/uploads/request_23/ConstructionUndertakingForm.pdf','2025-10-06 08:01:00'),(202,23,3,'ResidentialLandMortgageForm.pdf','/uploads/request_23/ResidentialLandMortgageForm.pdf','2025-10-06 08:02:00'),(203,23,4,'DifferencePaymentUndertakingForm.pdf','/uploads/request_23/DifferencePaymentUndertakingForm.pdf','2025-10-06 08:03:00'),(204,23,5,'LandDisposalFreezeForm.pdf','/uploads/request_23/LandDisposalFreezeForm.pdf','2025-10-06 08:04:00'),(205,23,6,'EmiratesID.pdf','/uploads/request_23/EmiratesID.pdf','2025-10-06 08:05:00'),(206,23,7,'Passport.pdf','/uploads/request_23/Passport.pdf','2025-10-06 08:06:00'),(207,23,8,'FamilyBook.pdf','/uploads/request_23/FamilyBook.pdf','2025-10-06 08:07:00'),(208,23,9,'ResidentialLandPlotPlan.pdf','/uploads/request_23/ResidentialLandPlotPlan.pdf','2025-10-06 08:08:00'),(209,23,10,'SalaryCertificate.pdf','/uploads/request_23/SalaryCertificate.pdf','2025-10-06 08:09:00'),(210,23,11,'EmploymentContract.pdf','/uploads/request_23/EmploymentContract.pdf','2025-10-06 08:10:00'),(211,23,14,'MarriageCertificate.pdf','/uploads/request_23/MarriageCertificate.pdf','2025-10-06 08:11:00'),(212,24,1,'HousingServiceApplicationForm.pdf','/uploads/request_24/HousingServiceApplicationForm.pdf','2026-03-24 09:00:00'),(213,24,2,'ConstructionUndertakingForm.pdf','/uploads/request_24/ConstructionUndertakingForm.pdf','2026-03-24 09:01:00'),(214,24,3,'ResidentialLandMortgageForm.pdf','/uploads/request_24/ResidentialLandMortgageForm.pdf','2026-03-24 09:02:00'),(215,24,6,'EmiratesID.pdf','/uploads/request_24/EmiratesID.pdf','2026-03-24 09:03:00'),(216,24,7,'Passport.pdf','/uploads/request_24/Passport.pdf','2026-03-24 09:04:00'),(217,24,8,'FamilyBook.pdf','/uploads/request_24/FamilyBook.pdf','2026-03-24 09:05:00'),(218,24,9,'ResidentialLandPlotPlan.pdf','/uploads/request_24/ResidentialLandPlotPlan.pdf','2026-03-24 09:06:00'),(219,24,10,'SalaryCertificate.pdf','/uploads/request_24/SalaryCertificate.pdf','2026-03-24 09:07:00'),(220,24,11,'EmploymentContract.pdf','/uploads/request_24/EmploymentContract.pdf','2026-03-24 09:08:00'),(221,24,12,'ProofOfIncome.pdf','/uploads/request_24/ProofOfIncome.pdf','2026-03-24 09:09:00'),(222,24,13,'BankStatement.pdf','/uploads/request_24/BankStatement.pdf','2026-03-24 09:10:00'),(223,24,14,'MarriageCertificate.pdf','/uploads/request_24/MarriageCertificate.pdf','2026-03-24 09:11:00'),(224,25,1,'HousingServiceApplicationForm.pdf','/uploads/request_25/HousingServiceApplicationForm.pdf','2025-10-27 09:00:00'),(225,25,2,'ConstructionUndertakingForm.pdf','/uploads/request_25/ConstructionUndertakingForm.pdf','2025-10-27 09:01:00'),(226,25,6,'EmiratesID.pdf','/uploads/request_25/EmiratesID.pdf','2025-10-27 09:02:00'),(227,25,7,'Passport.pdf','/uploads/request_25/Passport.pdf','2025-10-27 09:03:00'),(228,25,8,'FamilyBook.pdf','/uploads/request_25/FamilyBook.pdf','2025-10-27 09:04:00'),(229,25,14,'MarriageCertificate.pdf','/uploads/request_25/MarriageCertificate.pdf','2025-10-27 09:05:00'),(230,26,1,'HousingServiceApplicationForm.pdf','/uploads/request_26/HousingServiceApplicationForm.pdf','2025-11-14 08:00:00'),(231,26,6,'EmiratesID.pdf','/uploads/request_26/EmiratesID.pdf','2025-11-14 08:01:00'),(232,26,7,'Passport.pdf','/uploads/request_26/Passport.pdf','2025-11-14 08:02:00'),(233,26,8,'FamilyBook.pdf','/uploads/request_26/FamilyBook.pdf','2025-11-14 08:03:00'),(234,26,10,'SalaryCertificate.pdf','/uploads/request_26/SalaryCertificate.pdf','2025-11-14 08:04:00'),(235,26,11,'EmploymentContract.pdf','/uploads/request_26/EmploymentContract.pdf','2025-11-14 08:05:00'),(236,26,12,'ProofOfIncome.pdf','/uploads/request_26/ProofOfIncome.pdf','2025-11-14 08:06:00'),(237,26,13,'BankStatement.pdf','/uploads/request_26/BankStatement.pdf','2025-11-14 08:07:00'),(238,26,19,'HouseCompletionCertificate.pdf','/uploads/request_26/HouseCompletionCertificate.pdf','2025-11-14 08:08:00'),(239,26,14,'MarriageCertificate.pdf','/uploads/request_26/MarriageCertificate.pdf','2025-11-14 08:09:00'),(240,27,1,'HousingServiceApplicationForm.pdf','/uploads/request_27/HousingServiceApplicationForm.pdf','2026-05-08 08:00:00'),(241,27,6,'EmiratesID.pdf','/uploads/request_27/EmiratesID.pdf','2026-05-08 08:01:00'),(242,27,7,'Passport.pdf','/uploads/request_27/Passport.pdf','2026-05-08 08:02:00'),(243,27,8,'FamilyBook.pdf','/uploads/request_27/FamilyBook.pdf','2026-05-08 08:03:00'),(244,27,19,'HouseCompletionCertificate.pdf','/uploads/request_27/HouseCompletionCertificate.pdf','2026-05-08 08:04:00'),(245,27,14,'MarriageCertificate.pdf','/uploads/request_27/MarriageCertificate.pdf','2026-05-08 08:05:00'),(246,28,1,'HousingServiceApplicationForm.pdf','/uploads/request_28/HousingServiceApplicationForm.pdf','2025-12-03 09:00:00'),(247,28,2,'ConstructionUndertakingForm.pdf','/uploads/request_28/ConstructionUndertakingForm.pdf','2025-12-03 09:01:00'),(248,28,6,'EmiratesID.pdf','/uploads/request_28/EmiratesID.pdf','2025-12-03 09:02:00'),(249,28,7,'Passport.pdf','/uploads/request_28/Passport.pdf','2025-12-03 09:03:00'),(250,28,8,'FamilyBook.pdf','/uploads/request_28/FamilyBook.pdf','2025-12-03 09:04:00'),(251,29,1,'HousingServiceApplicationForm.pdf','/uploads/request_29/HousingServiceApplicationForm.pdf','2026-06-04 08:00:00'),(252,29,6,'EmiratesID.pdf','/uploads/request_29/EmiratesID.pdf','2026-06-04 08:01:00'),(253,29,7,'Passport.pdf','/uploads/request_29/Passport.pdf','2026-06-04 08:02:00'),(254,29,8,'FamilyBook.pdf','/uploads/request_29/FamilyBook.pdf','2026-06-04 08:03:00'),(255,29,19,'HouseCompletionCertificate.pdf','/uploads/request_29/HouseCompletionCertificate.pdf','2026-06-04 08:04:00'),(256,29,14,'MarriageCertificate.pdf','/uploads/request_29/MarriageCertificate.pdf','2026-06-04 08:05:00'),(257,30,1,'HousingServiceApplicationForm.pdf','/uploads/request_30/HousingServiceApplicationForm.pdf','2026-06-14 08:00:00'),(258,30,2,'ConstructionUndertakingForm.pdf','/uploads/request_30/ConstructionUndertakingForm.pdf','2026-06-14 08:01:00'),(259,30,3,'ResidentialLandMortgageForm.pdf','/uploads/request_30/ResidentialLandMortgageForm.pdf','2026-06-14 08:02:00'),(260,30,6,'EmiratesID.pdf','/uploads/request_30/EmiratesID.pdf','2026-06-14 08:03:00'),(261,30,7,'Passport.pdf','/uploads/request_30/Passport.pdf','2026-06-14 08:04:00'),(262,30,8,'FamilyBook.pdf','/uploads/request_30/FamilyBook.pdf','2026-06-14 08:05:00'),(263,30,9,'ResidentialLandPlotPlan.pdf','/uploads/request_30/ResidentialLandPlotPlan.pdf','2026-06-14 08:06:00'),(264,30,10,'SalaryCertificate.pdf','/uploads/request_30/SalaryCertificate.pdf','2026-06-14 08:07:00'),(265,30,11,'EmploymentContract.pdf','/uploads/request_30/EmploymentContract.pdf','2026-06-14 08:08:00'),(266,30,12,'ProofOfIncome.pdf','/uploads/request_30/ProofOfIncome.pdf','2026-06-14 08:09:00'),(267,30,13,'BankStatement.pdf','/uploads/request_30/BankStatement.pdf','2026-06-14 08:10:00'),(268,30,14,'MarriageCertificate.pdf','/uploads/request_30/MarriageCertificate.pdf','2026-06-14 08:11:00');
/*!40000 ALTER TABLE `requestdocument` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requestemploymentinformation`
--

DROP TABLE IF EXISTS `requestemploymentinformation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requestemploymentinformation` (
  `RequestEmploymentID` int NOT NULL AUTO_INCREMENT,
  `RequestID` int NOT NULL,
  `Employer` varchar(100) DEFAULT NULL,
  `JobTitle` varchar(100) DEFAULT NULL,
  `YearsOfService` int NOT NULL,
  `EmploymentStatus` enum('Employed','Unemployed','Self-Employed','Retired','Student') NOT NULL,
  PRIMARY KEY (`RequestEmploymentID`),
  UNIQUE KEY `RequestID` (`RequestID`),
  CONSTRAINT `FK_RequestEmploymentInformation_ServiceRequest` FOREIGN KEY (`RequestID`) REFERENCES `servicerequest` (`RequestID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requestemploymentinformation`
--

LOCK TABLES `requestemploymentinformation` WRITE;
/*!40000 ALTER TABLE `requestemploymentinformation` DISABLE KEYS */;
INSERT INTO `requestemploymentinformation` VALUES (31,1,'Dubai Police','Officer',11,'Employed'),(32,2,'Dubai Police','Officer',12,'Employed'),(33,3,'Dubai Municipality','Engineer',8,'Employed'),(34,4,NULL,NULL,0,'Unemployed'),(35,5,'DEWA','Administrator',9,'Employed'),(36,6,'DEWA','Administrator',10,'Employed'),(37,7,'RTA','Junior Supervisor',13,'Employed'),(38,8,'RTA','Supervisor',15,'Employed'),(39,9,NULL,NULL,0,'Student'),(40,10,'Emirates Airline','Technician',7,'Employed'),(41,11,NULL,NULL,0,'Unemployed'),(42,12,'Dubai Courts','Junior Lawyer',12,'Employed'),(43,13,'Dubai Courts','Lawyer',13,'Employed'),(44,14,NULL,NULL,0,'Retired'),(45,15,'Dubai Customs','Inspector',11,'Employed'),(46,16,'Private Company','HR Officer',6,'Employed'),(47,17,'Ministry of Education','Assistant Teacher',8,'Employed'),(48,18,'Ministry of Education','Teacher',9,'Employed'),(49,19,NULL,NULL,0,'Student'),(50,20,'Dubai Health','Nurse',13,'Employed'),(51,21,'Dubai Health','Nurse',14,'Employed'),(52,22,NULL,NULL,0,'Unemployed'),(53,23,'Dubai Police','Lieutenant',19,'Employed'),(54,24,'Dubai Police','Captain',20,'Employed'),(55,25,'Private Company','Accountant',5,'Employed'),(56,26,'RTA','Driver',8,'Employed'),(57,27,'RTA','Driver',9,'Employed'),(58,28,NULL,NULL,0,'Student'),(59,29,'Dubai Customs','Inspector',11,'Employed'),(60,30,'Dubai Customs','Inspector',11,'Employed'),(61,64,'','Eng',1,'Employed');
/*!40000 ALTER TABLE `requestemploymentinformation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requestfamilyinformation`
--

DROP TABLE IF EXISTS `requestfamilyinformation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requestfamilyinformation` (
  `RequestFamilyID` int NOT NULL AUTO_INCREMENT,
  `RequestID` int NOT NULL,
  `MaritalStatus` enum('Single','Married','Divorced','Widowed') NOT NULL,
  `FamilyMembers` int NOT NULL,
  `Dependents` int NOT NULL,
  PRIMARY KEY (`RequestFamilyID`),
  UNIQUE KEY `RequestID` (`RequestID`),
  CONSTRAINT `FK_RequestFamilyInformation_ServiceRequest` FOREIGN KEY (`RequestID`) REFERENCES `servicerequest` (`RequestID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requestfamilyinformation`
--

LOCK TABLES `requestfamilyinformation` WRITE;
/*!40000 ALTER TABLE `requestfamilyinformation` DISABLE KEYS */;
INSERT INTO `requestfamilyinformation` VALUES (1,1,'Married',5,2),(2,2,'Married',5,3),(3,3,'Married',4,2),(4,4,'Single',1,0),(5,5,'Married',5,3),(6,6,'Married',6,4),(7,7,'Married',7,4),(8,8,'Married',7,5),(9,9,'Single',1,0),(10,10,'Married',3,1),(11,11,'Single',1,0),(12,12,'Married',5,2),(13,13,'Married',5,3),(14,14,'Widowed',2,1),(15,15,'Married',4,2),(16,16,'Divorced',2,1),(17,17,'Married',6,3),(18,18,'Married',6,4),(19,19,'Single',1,0),(20,20,'Married',5,1),(21,21,'Married',5,2),(22,22,'Single',1,0),(23,23,'Married',8,5),(24,24,'Married',8,6),(25,25,'Married',4,2),(26,26,'Married',2,0),(27,27,'Married',3,1),(28,28,'Single',1,0),(29,29,'Married',4,2),(30,30,'Married',4,2),(31,64,'Single',5,0);
/*!40000 ALTER TABLE `requestfamilyinformation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requestfinancialinformation`
--

DROP TABLE IF EXISTS `requestfinancialinformation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requestfinancialinformation` (
  `RequestFinancialID` int NOT NULL AUTO_INCREMENT,
  `RequestID` int NOT NULL,
  `MonthlyIncome` decimal(10,2) NOT NULL,
  `MonthlyExpenses` decimal(10,2) NOT NULL,
  `Liabilities` decimal(10,2) NOT NULL,
  PRIMARY KEY (`RequestFinancialID`),
  UNIQUE KEY `RequestID` (`RequestID`),
  CONSTRAINT `FK_RequestFinancialInformation_ServiceRequest` FOREIGN KEY (`RequestID`) REFERENCES `servicerequest` (`RequestID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requestfinancialinformation`
--

LOCK TABLES `requestfinancialinformation` WRITE;
/*!40000 ALTER TABLE `requestfinancialinformation` DISABLE KEYS */;
INSERT INTO `requestfinancialinformation` VALUES (1,1,16000.00,8500.00,120000.00),(2,2,18000.00,9000.00,120000.00),(3,3,14000.00,7000.00,80000.00),(4,4,0.00,1500.00,0.00),(5,5,22000.00,12000.00,150000.00),(6,6,22000.00,12000.00,150000.00),(7,7,30000.00,17000.00,250000.00),(8,8,35000.00,18000.00,250000.00),(9,9,0.00,1000.00,0.00),(10,10,16000.00,8000.00,60000.00),(11,11,0.00,1200.00,0.00),(12,12,45000.00,18000.00,300000.00),(13,13,45000.00,20000.00,300000.00),(14,14,9000.00,4000.00,10000.00),(15,15,20000.00,9000.00,90000.00),(16,16,17000.00,8500.00,70000.00),(17,17,14000.00,7000.00,50000.00),(18,18,15000.00,7000.00,50000.00),(19,19,0.00,900.00,0.00),(20,20,28000.00,13000.00,200000.00),(21,21,28000.00,13000.00,170000.00),(22,22,0.00,1000.00,0.00),(23,23,50000.00,24000.00,400000.00),(24,24,52000.00,25000.00,400000.00),(25,25,13000.00,6500.00,30000.00),(26,26,16000.00,7500.00,45000.00),(27,27,16000.00,7500.00,45000.00),(28,28,0.00,800.00,0.00),(29,29,20000.00,9000.00,90000.00),(30,30,20000.00,9000.00,90000.00),(31,64,10000.00,0.00,0.00);
/*!40000 ALTER TABLE `requestfinancialinformation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `ReviewID` int NOT NULL AUTO_INCREMENT,
  `RequestID` int NOT NULL,
  `SpecialistID` int NOT NULL,
  `ReviewDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ReviewNotes` varchar(1000) NOT NULL,
  `Decision` enum('Approved','Rejected') NOT NULL,
  PRIMARY KEY (`ReviewID`),
  UNIQUE KEY `RequestID` (`RequestID`),
  KEY `FK_Review_Specialist` (`SpecialistID`),
  CONSTRAINT `FK_Review_ServiceRequest` FOREIGN KEY (`RequestID`) REFERENCES `servicerequest` (`RequestID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_Review_Specialist` FOREIGN KEY (`SpecialistID`) REFERENCES `specialist` (`PersonID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (1,1,21,'2025-01-18 10:30:00','Eligibility verified. Required documents complete.','Approved'),(2,2,22,'2026-03-16 11:00:00','Extension request meets all technical requirements.','Approved'),(3,3,23,'2025-02-15 09:45:00','Loan application approved after financial assessment.','Approved'),(4,4,24,'2025-02-21 14:20:00','Applicant does not satisfy service eligibility requirements.','Rejected'),(5,5,25,'2025-03-10 10:15:00','Maintenance grant approved after income verification.','Approved'),(6,6,21,'2026-04-23 09:30:00','Land grant requirements successfully verified.','Approved'),(7,7,22,'2025-04-02 11:45:00','Property inspection completed successfully.','Approved'),(8,8,23,'2026-05-05 13:10:00','Extension request approved after reviewing submitted plans.','Approved'),(9,9,24,'2025-04-15 10:20:00','Required supporting documents were incomplete.','Rejected'),(10,10,25,'2025-05-01 09:00:00','Financial documents verified successfully.','Approved'),(11,11,21,'2025-05-14 08:45:00','Application approved after document verification.','Approved'),(12,12,22,'2025-05-29 15:00:00','Housing loan approved. Credit assessment passed.','Approved'),(13,13,23,'2026-01-23 10:00:00','Maintenance request approved following site review.','Approved'),(14,14,24,'2025-06-12 09:50:00','Property does not satisfy grant conditions.','Rejected'),(15,15,25,'2025-07-02 11:15:00','Extension project approved after technical review.','Approved'),(16,16,21,'2025-07-16 13:40:00','Loan request approved after employment verification.','Approved'),(17,17,22,'2025-08-02 09:25:00','Maintenance grant approved.','Approved'),(18,18,23,'2026-02-20 14:10:00','Loan approved after updated financial review.','Approved'),(19,19,24,'2025-08-13 10:40:00','Applicant exceeds eligibility criteria for assistance.','Rejected'),(20,20,25,'2025-09-04 08:50:00','Extension grant approved after final review.','Approved'),(21,21,21,'2026-05-22 09:30:00','Land grant approved after final verification.','Approved'),(22,22,22,'2025-09-23 11:20:00','Applicant failed to provide all mandatory documents.','Rejected'),(23,23,23,'2025-10-12 15:30:00','Housing loan approved after financial assessment.','Approved'),(24,24,24,'2026-03-30 10:00:00','Maintenance grant approved.','Approved'),(25,25,25,'2025-11-01 09:10:00','Housing assistance approved after eligibility confirmation.','Approved'),(26,26,21,'2025-11-20 14:40:00','Extension request approved after engineering review.','Approved'),(27,27,22,'2026-05-13 08:55:00','Land grant approved after document validation.','Approved'),(28,28,23,'2025-12-08 09:35:00','Application rejected because eligibility conditions were not met.','Rejected'),(29,29,28,'2026-07-29 22:19:18','Land grant approved after ownership verification.','Approved'),(30,30,25,'2026-06-20 10:45:00','Maintenance grant approved after final inspection.','Approved'),(32,61,28,'2026-07-29 09:35:33','Everything looks good. Approved.','Approved'),(33,62,28,'2026-07-29 09:36:30','NO NO NO NO NO NO NO','Rejected'),(34,63,28,'2026-07-29 22:20:04','hdhdhd','Approved');
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `ServiceID` int NOT NULL AUTO_INCREMENT,
  `ServiceName` varchar(100) NOT NULL,
  `Description` varchar(500) NOT NULL,
  PRIMARY KEY (`ServiceID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (1,'Land Grant','Allocation of residential land to eligible UAE citizens in Dubai.'),(2,'Building Grant','Financial grant to assist eligible applicants in constructing a new residence.'),(3,'Building Loan','Housing loan for constructing a new residence on approved residential land.'),(4,'Maintenance Loan','Housing loan for maintaining, renovating, or extending an existing residence.'),(5,'Maintenance Grant','Financial grant to assist eligible applicants with maintaining or extending an existing residence.');
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `serviceeligibility`
--

DROP TABLE IF EXISTS `serviceeligibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `serviceeligibility` (
  `EligibilityID` int NOT NULL AUTO_INCREMENT,
  `ServiceID` int NOT NULL,
  `MinimumAge` int DEFAULT NULL,
  `MaximumAge` int DEFAULT NULL,
  `Gender` enum('Male','Female') DEFAULT NULL,
  `MaritalStatus` enum('Single','Married','Divorced','Widowed') DEFAULT NULL,
  `EmploymentStatus` enum('Employed','Unemployed','Retired','Student') DEFAULT NULL,
  `MinimumIncome` decimal(10,2) DEFAULT NULL,
  `MaximumIncome` decimal(10,2) DEFAULT NULL,
  `AdditionalRequirements` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`EligibilityID`),
  UNIQUE KEY `ServiceID` (`ServiceID`),
  CONSTRAINT `serviceeligibility_ibfk_1` FOREIGN KEY (`ServiceID`) REFERENCES `service` (`ServiceID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serviceeligibility`
--

LOCK TABLES `serviceeligibility` WRITE;
/*!40000 ALTER TABLE `serviceeligibility` DISABLE KEYS */;
INSERT INTO `serviceeligibility` VALUES (1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'UAE citizen, housing need, no previous government housing assistance, must construct house within 3 years of land allocation.'),(2,2,NULL,NULL,NULL,NULL,NULL,NULL,15000.00,'UAE citizen, housing need, owns residential land in Dubai, first mortgage in favor of Mohammed Bin Rashid Housing Establishment for 25 years.'),(3,3,21,NULL,NULL,NULL,'Employed',15000.00,100000.00,'UAE citizen, housing need, approved residential land grant, monthly deductions must not exceed 60% of income.'),(4,4,21,NULL,NULL,NULL,'Employed',15000.00,100000.00,'UAE citizen, house located in Dubai, house at least 10 years old, house not rented.'),(5,5,NULL,NULL,NULL,NULL,NULL,NULL,15000.00,'UAE citizen, existing house in Dubai, house at least 10 years old, house not rented.');
/*!40000 ALTER TABLE `serviceeligibility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicerequest`
--

DROP TABLE IF EXISTS `servicerequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicerequest` (
  `RequestID` int NOT NULL AUTO_INCREMENT,
  `CustomerID` int NOT NULL,
  `ServiceID` int NOT NULL,
  `TrackingNumber` varchar(50) NOT NULL,
  `SubmissionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('Pending','In Progress','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`RequestID`),
  UNIQUE KEY `TrackingNumber` (`TrackingNumber`),
  KEY `FK_ServiceRequest_Customer` (`CustomerID`),
  KEY `FK_ServiceRequest_Service` (`ServiceID`),
  CONSTRAINT `FK_ServiceRequest_Customer` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`PersonID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_ServiceRequest_Service` FOREIGN KEY (`ServiceID`) REFERENCES `service` (`ServiceID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicerequest`
--

LOCK TABLES `servicerequest` WRITE;
/*!40000 ALTER TABLE `servicerequest` DISABLE KEYS */;
INSERT INTO `servicerequest` VALUES (1,1,3,'MBRHE-2025-000001','2025-01-15 09:30:00','Completed'),(2,1,4,'MBRHE-2026-000002','2026-03-12 10:45:00','Approved'),(3,2,2,'MBRHE-2025-000003','2025-02-10 11:20:00','Approved'),(4,3,1,'MBRHE-2025-000004','2025-02-18 14:15:00','Rejected'),(5,4,3,'MBRHE-2025-000005','2025-03-05 09:10:00','Completed'),(6,4,5,'MBRHE-2026-000006','2026-04-20 13:40:00','Completed'),(7,5,3,'MBRHE-2025-000007','2025-03-28 15:20:00','Approved'),(8,5,4,'MBRHE-2026-000008','2026-05-01 08:50:00','Completed'),(9,6,1,'MBRHE-2025-000009','2025-04-12 10:00:00','Rejected'),(10,7,4,'MBRHE-2025-000010','2025-04-26 09:40:00','Completed'),(11,8,5,'MBRHE-2025-000011','2025-05-10 16:10:00','Approved'),(12,9,2,'MBRHE-2025-000012','2025-05-25 11:15:00','Approved'),(13,9,3,'MBRHE-2026-000013','2026-01-18 09:30:00','Completed'),(14,10,5,'MBRHE-2025-000014','2025-06-08 13:20:00','Rejected'),(15,11,4,'MBRHE-2025-000015','2025-06-27 10:10:00','Approved'),(16,12,2,'MBRHE-2025-000016','2025-07-11 09:50:00','Approved'),(17,13,3,'MBRHE-2025-000017','2025-07-28 15:30:00','Completed'),(18,13,2,'MBRHE-2026-000018','2026-02-16 10:40:00','Approved'),(19,14,1,'MBRHE-2025-000019','2025-08-09 14:45:00','Rejected'),(20,15,4,'MBRHE-2025-000020','2025-08-30 09:00:00','Completed'),(21,15,5,'MBRHE-2026-000021','2026-05-14 11:00:00','Approved'),(22,16,1,'MBRHE-2025-000022','2025-09-18 12:30:00','Rejected'),(23,17,3,'MBRHE-2025-000023','2025-10-05 10:20:00','Approved'),(24,17,4,'MBRHE-2026-000024','2026-03-22 13:00:00','Completed'),(25,18,2,'MBRHE-2025-000025','2025-10-27 08:45:00','Approved'),(26,19,4,'MBRHE-2025-000026','2025-11-12 09:15:00','Completed'),(27,19,5,'MBRHE-2026-000027','2026-06-02 14:20:00','Approved'),(28,20,1,'MBRHE-2025-000028','2025-11-29 10:50:00','Rejected'),(29,20,2,'MBRHE-2026-000029','2026-04-08 11:40:00','Approved'),(30,11,3,'MBRHE-2026-000030','2026-06-15 09:35:00','Completed'),(61,27,1,'SR-20260728064630-6205','2026-07-28 10:46:30','Approved'),(62,27,1,'SR-20260728064656-3287','2026-07-28 10:46:56','Rejected'),(63,27,2,'SR-20260728075225-1642','2026-07-28 11:52:25','Approved'),(64,27,5,'SR-20260728084644-2843','2026-07-28 12:46:44','In Progress');
/*!40000 ALTER TABLE `servicerequest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicerequireddocument`
--

DROP TABLE IF EXISTS `servicerequireddocument`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicerequireddocument` (
  `ServiceRequiredDocumentID` int NOT NULL AUTO_INCREMENT,
  `ServiceID` int NOT NULL,
  `DocumentTypeID` int NOT NULL,
  `Notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`ServiceRequiredDocumentID`),
  KEY `ServiceID` (`ServiceID`),
  KEY `DocumentTypeID` (`DocumentTypeID`),
  CONSTRAINT `servicerequireddocument_ibfk_1` FOREIGN KEY (`ServiceID`) REFERENCES `service` (`ServiceID`),
  CONSTRAINT `servicerequireddocument_ibfk_2` FOREIGN KEY (`DocumentTypeID`) REFERENCES `documenttype` (`DocumentTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicerequireddocument`
--

LOCK TABLES `servicerequireddocument` WRITE;
/*!40000 ALTER TABLE `servicerequireddocument` DISABLE KEYS */;
INSERT INTO `servicerequireddocument` VALUES (1,1,1,NULL),(2,1,2,NULL),(3,1,6,NULL),(4,1,7,NULL),(5,1,8,NULL),(6,1,17,'Only if applicable'),(7,1,14,'Only if married'),(8,1,18,'Only if applicable'),(9,2,1,NULL),(10,2,2,NULL),(11,2,3,NULL),(12,2,4,NULL),(13,2,5,NULL),(14,2,6,NULL),(15,2,7,NULL),(16,2,8,NULL),(17,2,9,NULL),(18,2,10,NULL),(19,2,11,NULL),(20,2,14,'Only if married'),(21,2,15,'Only if applicable'),(22,2,17,'Only if applicable'),(23,3,1,NULL),(24,3,2,NULL),(25,3,3,NULL),(26,3,6,NULL),(27,3,7,NULL),(28,3,8,NULL),(29,3,9,NULL),(30,3,10,NULL),(31,3,11,NULL),(32,3,12,NULL),(33,3,13,NULL),(34,3,14,'Only if married'),(35,3,17,'Only if applicable'),(36,4,1,NULL),(37,4,6,NULL),(38,4,7,NULL),(39,4,8,NULL),(40,4,10,NULL),(41,4,11,NULL),(42,4,12,NULL),(43,4,13,NULL),(44,4,19,'House completion certificate'),(45,4,14,'Only if married'),(46,5,1,NULL),(47,5,6,NULL),(48,5,7,NULL),(49,5,8,NULL),(50,5,19,'House completion certificate'),(51,5,14,'Only if married');
/*!40000 ALTER TABLE `servicerequireddocument` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialist`
--

DROP TABLE IF EXISTS `specialist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `specialist` (
  `PersonID` int NOT NULL,
  `Department` varchar(100) NOT NULL,
  `JobTitle` varchar(100) NOT NULL,
  PRIMARY KEY (`PersonID`),
  CONSTRAINT `FK_Specialist_Person` FOREIGN KEY (`PersonID`) REFERENCES `person` (`PersonID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialist`
--

LOCK TABLES `specialist` WRITE;
/*!40000 ALTER TABLE `specialist` DISABLE KEYS */;
INSERT INTO `specialist` VALUES (21,'Housing Loans','Housing Loan Specialist'),(22,'Housing Grants','Housing Grant Specialist'),(23,'Maintenance Services','Maintenance Specialist'),(24,'Customer Service','Senior Customer Service Specialist'),(25,'Review Department','Senior Housing Reviewer'),(28,'Review Department','Housing Reviewer');
/*!40000 ALTER TABLE `specialist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-30 12:45:43
