-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 21, 2016 at 10:28 PM
-- Server version: 5.5.35-1ubuntu1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bsl_copy`
--

-- --------------------------------------------------------

--
-- Table structure for table `catproducts`
--

CREATE TABLE IF NOT EXISTS `catproducts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(545) NOT NULL,
  `na` int(10) unsigned NOT NULL,
  `owner` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index_2` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `catproducts`
--

INSERT INTO `catproducts` (`id`, `code`, `name`, `na`, `owner`) VALUES
(9, 'P', 'Plastik', 0, 'induk'),
(10, 'K', 'Kertas', 0, 'induk'),
(11, 'S&B', 'Seng & besi', 0, 'induk'),
(12, 'A', 'Alumunium', 0, 'induk'),
(13, 'B', 'Botol & Kaca', 0, 'induk'),
(14, 'KN&T', 'Kuningan & tembaga', 0, 'induk'),
(15, 'AK', 'Aki', 0, 'induk'),
(16, 'DLL', 'Dan Lain Lain', 0, 'induk');

-- --------------------------------------------------------

--
-- Table structure for table `myprice`
--

CREATE TABLE IF NOT EXISTS `myprice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `buyerid` varchar(45) NOT NULL,
  `subcatprod` varchar(45) NOT NULL,
  `price` double NOT NULL,
  `prodname` varchar(445) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index_2` (`buyerid`,`subcatprod`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=222 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(445) NOT NULL,
  `content` varchar(2045) NOT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `val` varchar(545) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subcatproducts`
--

CREATE TABLE IF NOT EXISTS `subcatproducts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(545) NOT NULL,
  `na` int(10) unsigned NOT NULL,
  `parentid` varchar(55) NOT NULL,
  `unit` varchar(45) NOT NULL,
  `owner` varchar(45) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `Index_2` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Dumping data for table `subcatproducts`
--

INSERT INTO `subcatproducts` (`id`, `code`, `name`, `na`, `parentid`, `unit`, `owner`) VALUES
(7, 'P1', 'PP Bening', 0, 'P', 'Kg', ''),
(8, 'P2', 'PP Setengah Bening', 0, 'P', 'Kg', ''),
(9, 'P3', 'PP Kotor', 0, 'P', 'Kg', ''),
(10, 'P4', 'PP Sablon', 0, 'P', 'Kg', ''),
(11, 'P5', 'Kresek/Warna Tipis', 0, 'P', 'Kg', ''),
(12, 'P6', 'Aqua Botol', 0, 'P', 'Kg', ''),
(13, 'P7', 'Aqua Gelas', 0, 'P', 'Kg', ''),
(14, 'P8', 'PP Campur', 0, 'P', 'Kg', ''),
(15, 'P9', 'Bak Warna', 0, 'P', 'Kg', ''),
(16, 'P10', 'Bak Hitam', 0, 'P', 'Kg', ''),
(17, 'P11', 'Blowing', 0, 'P', 'Kg', ''),
(18, 'P12', 'Kulit Kabel', 0, 'P', 'Kg', ''),
(19, 'P13', 'Paralon', 0, 'P', 'Kg', ''),
(20, 'P14', 'Karpet/Talang Listrik', 0, 'P', 'Kg', ''),
(21, 'P15', 'Jerigen', 0, 'P', 'Kg', ''),
(22, 'P16', 'Tutup Aqua Botol', 0, 'P', 'Kg', ''),
(23, 'P17', 'Tutup Botol Warna', 0, 'P', 'Kg', ''),
(24, 'P18', 'Tali PET', 0, 'P', 'Kg', ''),
(25, 'P19', 'Sol/Gembos Sepatu', 0, 'P', 'Kg', ''),
(26, 'P20', 'Glangsing', 0, 'P', 'Kg', ''),
(27, 'P21', 'Botol Mnm Warna Bersih', 0, 'P', 'Kg', ''),
(28, 'P22', 'Botol Mnm Warna Kotor', 0, 'P', 'Kg', ''),
(29, 'K1', 'Buku Tulis', 0, 'K', 'Kg', ''),
(30, 'K2', 'HVS', 0, 'K', 'Kg', ''),
(31, 'K3', 'Koran', 0, 'K', 'Kg', ''),
(32, 'K4', 'Kertas Semen', 0, 'K', 'Kg', ''),
(33, 'K5', 'Buram/Majalah/Duplek', 0, 'K', 'Kg', ''),
(34, 'K6', 'Duplek Percetakan', 0, 'K', 'Kg', ''),
(35, 'K7', 'Kardus Bagus', 0, 'K', 'Kg', ''),
(36, 'K8', 'Kardus Jelek', 0, 'K', 'Kg', ''),
(37, 'S1', 'Seng Omplong', 0, 'S&B', 'Kg', ''),
(38, 'S2', 'Seng Biasa', 0, 'S&B', 'Kg', ''),
(39, 'BES1', 'Besi Super', 0, 'S&B', 'Kg', ''),
(40, 'BES2', 'Besi Biasa', 0, 'S&B', 'Kg', ''),
(41, 'A1', 'Slender Cop/Seker', 0, 'A', 'Kg', ''),
(42, 'A2', 'Antena/Panci/Wajan', 0, 'A', 'Kg', ''),
(43, 'A3', 'Kaleng Alumunium', 0, 'A', 'Kg', ''),
(44, 'A4', 'Flat', 0, 'A', 'Kg', ''),
(45, 'A5', 'Siku', 0, 'A', 'Kg', ''),
(46, 'A6', 'Tutup Botol Alumunium', 0, 'A', 'Kg', ''),
(47, 'A7', 'Perunggu', 0, 'A', 'Kg', ''),
(48, 'A8', 'Stainless Monel', 0, 'A', 'Kg', ''),
(49, 'B1', 'Kaca Pecah Bening', 0, 'B', 'Kg', ''),
(50, 'B2', 'Botol Marjan', 0, 'B', 'Kg', ''),
(51, 'B3', 'Botol Orson', 0, 'B', 'Kg', ''),
(52, 'B4', 'Botol Kecap', 0, 'B', 'Kg', ''),
(53, 'B5', 'Botol Bensin', 0, 'B', 'Kg', ''),
(54, 'B6', 'Botol Bir', 0, 'B', 'Kg', ''),
(55, 'B7', 'Botol Coca Cola/Sprite', 0, 'B', 'Kg', ''),
(57, 'KN1', 'Kuningan', 0, 'KN&T', 'Kg', ''),
(58, 'T1', 'Tembaga Biasa', 0, 'KN&T', 'Kg', ''),
(59, 'T2', 'Tembaga Super', 0, 'KN&T', 'Kg', ''),
(60, 'AK1', 'Aki', 0, 'AK', 'Pcs', ''),
(62, 'DLL1', 'Dan Lain Lain', 0, 'DLL', 'Pcs', '');

-- --------------------------------------------------------

--
-- Table structure for table `trx`
--

CREATE TABLE IF NOT EXISTS `trx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dkbyseller` varchar(45) NOT NULL,
  `amount` double NOT NULL,
  `citizenid` varchar(50) NOT NULL,
  `sellerid` varchar(45) NOT NULL,
  `vol` double NOT NULL,
  `subcatprod` varchar(45) NOT NULL,
  `priceperunit` varchar(45) NOT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `balance` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trxworkers`
--

CREATE TABLE IF NOT EXISTS `trxworkers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dkbyworker` varchar(45) NOT NULL,
  `amount` double NOT NULL,
  `citizenid` varchar(50) NOT NULL,
  `sellerid` varchar(45) NOT NULL,
  `vol` double NOT NULL,
  `subcatprod` varchar(45) DEFAULT NULL,
  `priceperunit` varchar(45) DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `balance` double NOT NULL,
  `printed` int(10) unsigned NOT NULL DEFAULT '0',
  `ket` varchar(555) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE IF NOT EXISTS `workers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `pass` varchar(45) DEFAULT NULL,
  `name` varchar(545) DEFAULT NULL,
  `address` varchar(545) DEFAULT NULL,
  `head` int(10) unsigned DEFAULT NULL,
  `na` int(10) unsigned DEFAULT '0',
  `masterid` varchar(45) DEFAULT NULL,
  `level` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index_2` (`masterid`),
  UNIQUE KEY `Index_3` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=777 ;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`id`, `username`, `pass`, `name`, `address`, `head`, `na`, `masterid`, `level`, `phone`) VALUES
(1, 'induk', 'induk', 'Operator Induk', 'Pusat', NULL, 0, 'ADMIN', 'admin', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
