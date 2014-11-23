-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2014 at 12:44 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dts`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
`id` int(11) NOT NULL,
  `document` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE IF NOT EXISTS `division` (
`id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `division`
--

INSERT INTO `division` (`id`, `name`, `description`) VALUES
(1, 'ORD', 'Office of the Regional Director'),
(2, 'FASD', 'Finance and Administration Services Division'),
(3, 'TSD', 'Technical Services Division'),
(4, 'TSSD', 'Technical Support Services Division');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE IF NOT EXISTS `document` (
`id` int(11) NOT NULL,
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
  `dateReceived` date NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
`id` int(11) NOT NULL,
  `actionTaken` varchar(250) NOT NULL,
  `timeOccured` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE IF NOT EXISTS `track` (
`id` int(11) NOT NULL,
  `document` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `received` date NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) DEFAULT NULL,
  `userType` enum('RD','SEC','ARD','EMP','ADMIN','OIC') NOT NULL,
  `division` int(11) NOT NULL,
  `resetHash` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `salt`, `userType`, `division`, `resetHash`, `status`) VALUES
(1, 'Administrator', 'Administrator', 'admin@mail.com', 'administrator', 'ac1c6ced3e6960af91fee76c4f96887ac5c2df1cbbf760c9a2658fc8579ab773', '0c4fe3094e35e0fc770796c7c28a7828', 'ADMIN', 4, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
 ADD PRIMARY KEY (`id`), ADD KEY `document` (`document`,`user`), ADD KEY `user` (`user`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `track`
--
ALTER TABLE `track`
 ADD PRIMARY KEY (`id`), ADD KEY `document` (`document`,`user`), ADD KEY `user` (`user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD KEY `division` (`division`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `track`
--
ALTER TABLE `track`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
ADD CONSTRAINT `document_chat_fk` FOREIGN KEY (`document`) REFERENCES `document` (`id`),
ADD CONSTRAINT `user_chat_fk` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

--
-- Constraints for table `track`
--
ALTER TABLE `track`
ADD CONSTRAINT `document_track_fk` FOREIGN KEY (`document`) REFERENCES `document` (`id`),
ADD CONSTRAINT `user_track_fk` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
ADD CONSTRAINT `division_fk` FOREIGN KEY (`division`) REFERENCES `division` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
