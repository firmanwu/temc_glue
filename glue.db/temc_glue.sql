-- --------------------------------------------------------
-- 主機:                           localhost
-- 伺服器版本:                        10.2.13-MariaDB-log - mariadb.org binary distribution
-- 伺服器操作系統:                      Win64
-- HeidiSQL 版本:                  9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 傾印 temc_glue 的資料庫結構
CREATE DATABASE IF NOT EXISTS `temc_glue` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `temc_glue`;

-- 傾印  表格 temc_glue.customer 結構
CREATE TABLE IF NOT EXISTS `customer` (
  `customerID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `customerName` varchar(256) NOT NULL DEFAULT '0',
  PRIMARY KEY (`customerID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在傾印表格  temc_glue.customer 的資料：~2 rows (大約)
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
REPLACE INTO `customer` (`customerID`, `customerName`) VALUES
	(1, 'TEMC'),
	(2, '太倉');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- 傾印  表格 temc_glue.operator 結構
CREATE TABLE IF NOT EXISTS `operator` (
  `operatorID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `operatorName` varchar(256) NOT NULL DEFAULT '0',
  PRIMARY KEY (`operatorID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在傾印表格  temc_glue.operator 的資料：~2 rows (大約)
/*!40000 ALTER TABLE `operator` DISABLE KEYS */;
REPLACE INTO `operator` (`operatorID`, `operatorName`) VALUES
	(1, '賴玉英'),
	(2, '胡展誌');
/*!40000 ALTER TABLE `operator` ENABLE KEYS */;

-- 傾印  表格 temc_glue.order 結構
CREATE TABLE IF NOT EXISTS `order` (
  `orderID` varchar(256) NOT NULL,
  `customerOrderID` varchar(256) DEFAULT NULL,
  `deadline` date NOT NULL,
  `sales` varchar(256) NOT NULL,
  `product` int(16) unsigned NOT NULL,
  `customer` int(16) unsigned NOT NULL,
  `packaging` int(16) unsigned NOT NULL,
  `expectingPackageNumber` float unsigned NOT NULL,
  `expectingWeight` float unsigned NOT NULL,
  `remainingPackageNumber` float unsigned NOT NULL,
  `remainingWeight` float unsigned NOT NULL,
  `complete` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`orderID`),
  KEY `orderCustomer` (`customer`),
  KEY `orderProduct` (`product`),
  KEY `orderPackaging` (`packaging`),
  CONSTRAINT `orderCustomer` FOREIGN KEY (`customer`) REFERENCES `customer` (`customerID`),
  CONSTRAINT `orderPackaging` FOREIGN KEY (`packaging`) REFERENCES `packaging` (`packagingID`),
  CONSTRAINT `orderProduct` FOREIGN KEY (`product`) REFERENCES `product` (`productID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  temc_glue.order 的資料：~9 rows (大約)
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
REPLACE INTO `order` (`orderID`, `customerOrderID`, `deadline`, `sales`, `product`, `customer`, `packaging`, `expectingPackageNumber`, `expectingWeight`, `remainingPackageNumber`, `remainingWeight`, `complete`) VALUES
	('E20180803001', '20180803001', '2018-08-14', '內銷', 5, 1, 1, 20, 340, 12, 204, 1),
	('E20180803002', '20180803002', '2018-08-17', '外銷', 2, 2, 2, 300, 7500, 220, 5500, 1),
	('E20180803003', '20180803003', '2018-08-13', '內銷', 3, 1, 1, 50, 850, 0, 0, 1),
	('E20180803004', '20180803004', '2018-08-22', '內銷', 4, 1, 1, 40, 680, 5, 85, 0),
	('E20180803005', '20180803005', '2018-08-15', '內銷', 2, 1, 2, 40, 1000, 25, 625, 0),
	('E20180803006', '20180803006', '2018-08-28', '內銷', 4, 1, 1, 30, 510, 30, 510, 0),
	('E20180803007', '20180803007', '2018-08-20', '內銷', 6, 1, 2, 20, 500, 0, 0, 0),
	('E20180803008', '20180803008', '2018-08-31', '內銷', 1, 1, 1, 15, 255, 0, 0, 0),
	('E20180803009', '20180803009', '2018-08-27', '內銷', 3, 1, 2, 25, 625, 0, 0, 0),
	('E20180803010', '20180803010', '2018-08-31', '內銷', 5, 1, 2, 33, 825, 6, 150, 0);
/*!40000 ALTER TABLE `order` ENABLE KEYS */;

-- 傾印  表格 temc_glue.packaging 結構
CREATE TABLE IF NOT EXISTS `packaging` (
  `packagingID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `packaging` varchar(256) NOT NULL,
  `unitWeight` float unsigned NOT NULL,
  PRIMARY KEY (`packagingID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在傾印表格  temc_glue.packaging 的資料：~2 rows (大約)
/*!40000 ALTER TABLE `packaging` DISABLE KEYS */;
REPLACE INTO `packaging` (`packagingID`, `packaging`, `unitWeight`) VALUES
	(1, '箱裝', 17),
	(2, '桶裝', 25);
/*!40000 ALTER TABLE `packaging` ENABLE KEYS */;

-- 傾印  表格 temc_glue.product 結構
CREATE TABLE IF NOT EXISTS `product` (
  `productID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `productName` varchar(256) NOT NULL DEFAULT '0',
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- 正在傾印表格  temc_glue.product 的資料：~6 rows (大約)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
REPLACE INTO `product` (`productID`, `productName`) VALUES
	(1, 'CFX-12'),
	(2, 'CFX-27'),
	(3, 'CFX-27A'),
	(4, 'CFX-27T'),
	(5, 'CFX-27H'),
	(6, 'CFX-27HB'),
	(7, 'CFX-30');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- 傾印  表格 temc_glue.production 結構
CREATE TABLE IF NOT EXISTS `production` (
  `productionID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `order` varchar(256) NOT NULL,
  `operator` int(16) unsigned NOT NULL,
  `batchNumber` varchar(256) NOT NULL,
  `producingDate` date NOT NULL,
  `producedPackageNumber` float unsigned NOT NULL DEFAULT 0,
  `producedWeight` float unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`productionID`),
  KEY `productionOrder` (`order`),
  KEY `productionOperator` (`operator`),
  CONSTRAINT `productionOperator` FOREIGN KEY (`operator`) REFERENCES `operator` (`operatorID`),
  CONSTRAINT `productionOrder` FOREIGN KEY (`order`) REFERENCES `order` (`orderID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- 正在傾印表格  temc_glue.production 的資料：~12 rows (大約)
/*!40000 ALTER TABLE `production` DISABLE KEYS */;
REPLACE INTO `production` (`productionID`, `order`, `operator`, `batchNumber`, `producingDate`, `producedPackageNumber`, `producedWeight`) VALUES
	(2, 'E20180803005', 1, '20180803001', '2018-01-07', 15, 375),
	(3, 'E20180803008', 2, '20180803002', '2018-02-15', 8, 136),
	(4, 'E20180803002', 1, '20180803003', '2018-03-13', 80, 2000),
	(5, 'E20180803007', 2, '20180803004', '2018-04-16', 20, 500),
	(6, 'E20180803001', 1, '20180803005', '2018-05-09', 8, 136),
	(7, 'E20180803009', 2, '20180803006', '2018-06-20', 21, 525),
	(8, 'E20180803004', 1, '20180803007', '2018-07-16', 35, 595),
	(10, 'E20180803008', 2, '20180803008', '2018-08-27', 7, 119),
	(11, 'E20180803009', 1, '20180803009', '2018-09-23', 4, 100),
	(12, 'E20180803003', 2, '20180803010', '2018-08-09', 38, 646),
	(13, 'E20180803003', 2, '20180803011', '2018-08-09', 12, 204),
	(14, 'E20180803010', 2, '20180803012', '2018-08-28', 6, 150),
	(15, 'E20180803010', 1, '20180803013', '2018-08-24', 8, 200),
	(16, 'E20180803010', 1, '20180803013', '2018-08-17', 13, 325);
/*!40000 ALTER TABLE `production` ENABLE KEYS */;

-- 傾印  表格 temc_glue.productiontarget 結構
CREATE TABLE IF NOT EXISTS `productiontarget` (
  `productionTargetID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `targetYear` year(4) NOT NULL,
  `yearlyTotalWeight` int(12) unsigned NOT NULL,
  `quarterOneTotalWeight` int(12) unsigned NOT NULL,
  `quarterTwoTotalWeight` int(12) unsigned NOT NULL,
  `quarterThreeTotalWeight` int(12) unsigned NOT NULL,
  `quarterFourTotalWeight` int(12) unsigned NOT NULL,
  PRIMARY KEY (`productionTargetID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 正在傾印表格  temc_glue.productiontarget 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `productiontarget` DISABLE KEYS */;
REPLACE INTO `productiontarget` (`productionTargetID`, `targetYear`, `yearlyTotalWeight`, `quarterOneTotalWeight`, `quarterTwoTotalWeight`, `quarterThreeTotalWeight`, `quarterFourTotalWeight`) VALUES
	(1, '2018', 10000, 3000, 2000, 3000, 2000);
/*!40000 ALTER TABLE `productiontarget` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
