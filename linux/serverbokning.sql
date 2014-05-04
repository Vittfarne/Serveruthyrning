-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2014 at 01:20 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `serverbokning`
--

-- --------------------------------------------------------

--
-- Table structure for table `bokningar`
--

CREATE TABLE IF NOT EXISTS `bokningar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) NOT NULL,
  `namn` varchar(40) NOT NULL,
  `losen` varchar(40) NOT NULL,
  `rcon` varchar(40) NOT NULL,
  `spel` varchar(50) NOT NULL,
  `medlemsid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bokningar`
--

INSERT INTO `bokningar` (`id`, `ip`, `namn`, `losen`, `rcon`, `spel`, `medlemsid`) VALUES
(1, '123.123.123.123', 'TEH WARRiORS | asd', 'asdasd', 'asdasd', 'Counter Strike: Global Offensive', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
