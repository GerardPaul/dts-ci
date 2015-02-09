-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2014 at 01:22 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dostxi_dts`
--

-- --------------------------------------------------------

--
-- Table structure for table `dts_chat`
--

CREATE TABLE IF NOT EXISTS `dts_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `document` (`document`,`user`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dts_division`
--

CREATE TABLE IF NOT EXISTS `dts_division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `dts_division`
--

INSERT INTO `dts_division` (`id`, `name`, `description`) VALUES
(1, 'ORD', 'Office of the Regional Director'),
(2, 'FASD', 'Finance and Administration Services Division'),
(3, 'TSD', 'Technical Services Division'),
(4, 'TSSD', 'Technical Support Services Division');

-- --------------------------------------------------------

--
-- Table structure for table `dts_document`
--

CREATE TABLE IF NOT EXISTS `dts_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL,
  `description` text,
  `action` varchar(20) DEFAULT NULL,
  `from` varchar(100) NOT NULL,
  `dueDate` date DEFAULT NULL,
  `due15Days` date NOT NULL,
  `deadline` date NOT NULL,
  `attachment` varchar(250) DEFAULT NULL,
  `status` enum('Compiled','On-Going','Cancelled','') DEFAULT NULL,
  `referenceNumber` varchar(20) DEFAULT NULL,
  `dateReceived` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dts_logs`
--

CREATE TABLE IF NOT EXISTS `dts_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actionTaken` varchar(250) NOT NULL,
  `timeOccured` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dts_notification`
--

CREATE TABLE IF NOT EXISTS `dts_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `object` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `creator` (`creator`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dts_track`
--

CREATE TABLE IF NOT EXISTS `dts_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `received` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `document` (`document`,`user`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dts_user`
--

CREATE TABLE IF NOT EXISTS `dts_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) DEFAULT NULL,
  `userType` enum('RD','SEC','ARD','EMP','ADMIN','OIC') NOT NULL,
  `division` int(11) NOT NULL,
  `resetHash` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `division` (`division`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `dts_user`
--

INSERT INTO `dts_user` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `salt`, `userType`, `division`, `resetHash`, `status`) VALUES
(1, 'Gerard Paul', 'Labitad', 'gerardpaul.labitad@outlook.com', 'administrator', '768846218b45b8d8e07e2b700eb2c4b76e95ccd49ac1dcb48b32f6776862168d', 'f4f79924a7c298a43d83804518790c9c', 'ADMIN', 4, NULL, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dts_chat`
--
ALTER TABLE `dts_chat`
  ADD CONSTRAINT `document_chat_fk` FOREIGN KEY (`document`) REFERENCES `dts_document` (`id`),
  ADD CONSTRAINT `user_chat_fk` FOREIGN KEY (`user`) REFERENCES `dts_user` (`id`);

--
-- Constraints for table `dts_notification`
--
ALTER TABLE `dts_notification`
  ADD CONSTRAINT `creator_id_fk` FOREIGN KEY (`creator`) REFERENCES `dts_user` (`id`),
  ADD CONSTRAINT `receiver_id_fk` FOREIGN KEY (`receiver`) REFERENCES `dts_user` (`id`);

--
-- Constraints for table `dts_track`
--
ALTER TABLE `dts_track`
  ADD CONSTRAINT `document_track_fk` FOREIGN KEY (`document`) REFERENCES `dts_document` (`id`),
  ADD CONSTRAINT `user_track_fk` FOREIGN KEY (`user`) REFERENCES `dts_user` (`id`);

--
-- Constraints for table `dts_user`
--
ALTER TABLE `dts_user`
  ADD CONSTRAINT `division_fk` FOREIGN KEY (`division`) REFERENCES `dts_division` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
