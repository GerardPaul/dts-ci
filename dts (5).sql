-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2014 at 04:56 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `document` (`document`,`user`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `document`, `user`, `message`, `created`) VALUES
(3, 4, 2, 'sample message', '2014-10-06 06:32:20'),
(4, 3, 2, 'hi', '2014-10-06 06:40:21'),
(5, 3, 2, 'this is another message', '2014-10-06 06:52:25'),
(6, 3, 5, 'from other person', '2014-10-06 06:56:10'),
(7, 3, 2, 'hello', '2014-10-06 07:49:16'),
(8, 5, 2, 'hello', '2014-10-06 08:51:38'),
(9, 5, 2, 'hello', '2014-10-06 08:53:51'),
(10, 5, 2, 'fsdfsdfsd', '2014-10-06 09:56:25'),
(11, 5, 2, 'fdfsd', '2014-10-06 09:57:28'),
(12, 3, 2, 'another chat', '2014-10-06 10:00:05'),
(13, 5, 2, 'new', '2014-10-06 10:01:28'),
(14, 3, 2, 'this is another chat', '2014-10-06 11:34:16'),
(15, 4, 2, 'what about now', '2014-10-06 11:51:14'),
(16, 3, 2, 'how to scroll up.', '2014-10-06 11:52:23'),
(17, 3, 2, 'scroll up', '2014-10-06 11:54:06'),
(18, 3, 2, 'come on', '2014-10-06 12:01:22'),
(19, 3, 2, 'hello me', '2014-10-06 12:41:03'),
(20, 4, 2, 'hello', '2014-10-06 14:18:28'),
(21, 4, 2, 'how are you.', '2014-10-06 14:18:44'),
(22, 4, 4, 'hello from ard tssd', '2014-10-07 00:53:07'),
(23, 4, 4, 'oh no.', '2014-10-07 00:53:18'),
(24, 3, 4, 'hi there', '2014-10-07 00:55:14'),
(25, 6, 2, 'please confirm on this one.', '2014-10-07 01:28:22'),
(26, 7, 2, 'this is a sample chat message!', '2014-10-07 02:08:43'),
(27, 7, 5, 'hello ', '2014-10-07 02:10:40'),
(28, 6, 5, 'ok sir.', '2014-10-07 02:12:03'),
(29, 6, 5, 'employee 5 please follow this', '2014-10-07 02:17:56'),
(30, 5, 10, 'hello', '2014-10-07 03:28:59'),
(31, 5, 2, 'what is the status of the document?', '2014-10-07 03:29:22'),
(32, 5, 2, 'can you give me an update?', '2014-10-07 03:29:45'),
(33, 5, 10, 'it is currently in progress', '2014-10-07 03:30:02'),
(34, 5, 6, 'please see this through immediately.', '2014-10-07 03:31:59'),
(35, 5, 10, 'hello maam.', '2014-10-07 03:38:28'),
(36, 5, 6, 'this is a test', '2014-10-07 03:38:46'),
(37, 5, 6, 'new message', '2014-10-07 03:39:17'),
(38, 5, 10, 'newer message', '2014-10-07 03:39:27'),
(39, 5, 6, 'fjasldfj', '2014-10-07 03:41:50'),
(40, 5, 10, 'dfsdfsdfse', '2014-10-07 03:41:58'),
(41, 5, 6, 'here it is', '2014-10-07 03:42:09'),
(42, 3, 12, 'is there anyone out there?', '2014-10-07 07:17:35'),
(43, 6, 11, 'yes sir', '2014-10-09 14:34:33'),
(44, 7, 11, 'hahaha', '2014-10-09 14:50:51');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE IF NOT EXISTS `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL,
  `from` varchar(100) NOT NULL,
  `dueDate` varchar(10) DEFAULT NULL,
  `attachment` varchar(250) DEFAULT NULL,
  `status` enum('Compiled','On-Going','Cancelled','') NOT NULL,
  `referenceNumber` int(11) DEFAULT NULL,
  `dateReceived` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`id`, `subject`, `from`, `dueDate`, `attachment`, `status`, `referenceNumber`, `dateReceived`) VALUES
(1, 'Test', 'Test', '09/30/2014', 'C:/xampp/htdocs/dts-ci/upload/14092337-12314134-New_Text_Document.txt', 'Compiled', 12314134, '09/23/2014'),
(2, 'This is a test subject for document', 'Gerard Paul Labitad', '09/30/2014', 'C:/xampp/htdocs/dts-ci/upload/14092342-123441144-New_Text_Document.txt', 'On-Going', 123441144, '09/23/2014'),
(3, 'Subject For Test Document', 'Paul Gerard', '10/02/2014', 'C:/xampp/htdocs/dts-ci/upload/14092356-234523456-New_Text_Document.txt', 'Compiled', 234523456, '09/24/2014'),
(4, 'Document Added', 'Sec. User', '09/30/2014', 'C:/xampp/htdocs/dts-ci/upload/14092514-11123123123-New_Text_Document.txt', 'Compiled', 2147483647, '09/25/2014'),
(5, 'This is a Test Document', 'Test Person', '10/07/2014', 'C:/xampp/htdocs/dts-ci/upload/14100228-11231231-New_Text_Document.txt', 'On-Going', 11231231, '10/02/2014'),
(6, 'URGENT!!!!!', 'PHILSCI', '10/10/2014', 'C:/xampp/htdocs/dts-ci/upload/14100712-11123123124314-New_Text_Document.txt', 'Compiled', 2147483647, '10/07/2014'),
(7, 'Urgent! Followup', 'DOST Manila', '10/13/2014', 'No File.', 'On-Going', 2147483647, '10/07/2014'),
(8, 'Download', 'Test Person', '10/17/2014', 'C:/xampp/htdocs/dts-ci/upload/14100737-34245345345-C360_2014-09-26-11-17-17-003.jpg', 'On-Going', 2147483647, '10/09/2014');

-- --------------------------------------------------------

--
-- Table structure for table `rdtrack`
--

CREATE TABLE IF NOT EXISTS `rdtrack` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) NOT NULL,
  `action` enum('Handle','Comment','Information','Prepare Draft','Reply','Discuss with Me','Note and File','Note and Return','Give Status') DEFAULT NULL,
  `dateReceived` varchar(10) DEFAULT '0',
  `notes` text,
  `ard` int(11) NOT NULL DEFAULT '0',
  `ardDateReceived` varchar(10) NOT NULL DEFAULT '0',
  `emp` int(11) NOT NULL DEFAULT '0',
  `empDateReceived` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `document` (`document`),
  KEY `ard` (`ard`,`emp`),
  KEY `ard_2` (`ard`),
  KEY `emp` (`emp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `rdtrack`
--

INSERT INTO `rdtrack` (`id`, `document`, `action`, `dateReceived`, `notes`, `ard`, `ardDateReceived`, `emp`, `empDateReceived`) VALUES
(1, 1, 'Discuss with Me', '09/25/2014', 'Please discuss with me.', 6, '0', 0, '0'),
(2, 2, NULL, '09/25/2014', NULL, 0, '0', 0, '0'),
(3, 3, 'Information', '09/25/2014', 'asdasdkajldkajsldkajslkdjalkwaksdjalsdnamsdambehabnfbadmhfgjhasbdnbfmndfbasenmasdfmsdfmasefsfasesef', 4, '10/07/2014', 12, '10/07/2014'),
(4, 4, 'Handle', '10/03/2014', 'This is a test forward to TSSD ARD.', 4, '10/07/2014', 0, '0'),
(5, 5, 'Comment', '10/02/2014', 'FASD Employee Four', 6, '10/07/2014', 10, '10/07/2014'),
(6, 6, 'Give Status', '10/07/2014', 'what is the status of this request', 5, '10/07/2014', 11, '10/09/2014'),
(7, 7, 'Handle', '10/07/2014', 'See this', 5, '10/07/2014', 11, '10/09/2014'),
(8, 8, NULL, '0', NULL, 0, '0', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) DEFAULT NULL,
  `userType` enum('RD','SEC','ARD','EMP','ADMIN') NOT NULL,
  `division` int(11) NOT NULL,
  `resetHash` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `division` (`division`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `salt`, `userType`, `division`, `resetHash`, `status`) VALUES
(1, 'Admin', 'Admin', 'admin@mail.com', 'administrator', 'ac1c6ced3e6960af91fee76c4f96887ac5c2df1cbbf760c9a2658fc8579ab773', '0c4fe3094e35e0fc770796c7c28a7828', 'ADMIN', 4, NULL, 1),
(2, 'Test', 'RD', 'rd@mail.com', 'testrd', '119c1c994653de477b3203657e76def65138bd049cae5e7baf3efed79a499185', '1afddde462d1090fd1ce5742d6f8327a', 'RD', 1, NULL, 1),
(3, 'Test', 'SEC', 'sec@mail.com', 'testsec', 'f407711e32dd6d1f196897694be9dd0a3acbde2a7f7fda1c348b67bd8693d31a', 'a3a5968fb369267e88323a647ca42580', 'SEC', 1, NULL, 1),
(4, 'TSSD', 'ARD', 'tssdard@mail.com', 'tssdard', '85e6bd77f344d29dc3c0c1a779ba53683acf9d3066a4e1c3cc3ab54841598390', 'fb1d8bc76acdd08f8208915d6c431942', 'ARD', 4, NULL, 1),
(5, 'TSD', 'ARD', 'tsdard@mail.com', 'tsdard', '589627ab176d00bf116e1109d96c251acab9bbf08e4c313cbf049064a91259a7', '8f8ff0c5037ea3156b227f00e82c05d8', 'ARD', 3, NULL, 1),
(6, 'FASD', 'ARD', 'fasdard@mail.com', 'fasdard', '8fb7093166e11669f10fbb4f7478ff41df82421f54d1ba413a1a687c6163dac2', 'd9736b2f4a02b2d9bd5a99d5d2f0b27b', 'ARD', 2, NULL, 1),
(7, 'One', 'Employee', 'emp1@mail.com', 'empone', '409e7d495dbc24a57490db01a53573e9c8e4476bd9c7f301f485b1117229e1d5', 'c00bc73528595ec73586f1e3df11edac', 'EMP', 2, NULL, 1),
(8, 'Two', 'Employee', 'emp2@mail.com', 'emptwo', '422f61e1e3a09571410d8012002eb41fc0e385bcfe40e251181c7fb26d49bc2a', 'e0a7a9b863973b5945bd810461531e02', 'EMP', 3, NULL, 1),
(9, 'Three', 'Employee', 'emp3@mail.com', 'empthree', 'cb3cf7fd050919aa12be6d93fec9c09b5a35bf06920e55544935ff5db640c02d', 'a2eec2f4b8dc61efa0fc6714c7b4ed38', 'EMP', 4, NULL, 1),
(10, 'Four', 'Employee', 'emp4@mail.com', 'empfour', 'dadd4a58704b7f777101b5f3b8f981d2894f473dacea5716721bc3a9c7aa8149', '394fc73229f39373cede26fe1608a2f6', 'EMP', 2, NULL, 1),
(11, 'Five', 'Employee', 'emp5@mail.com', 'empfive', '734c6fe246cfb9e93f5ee35ad0622b71a3ebbe158e5cac9083528976c7ec839d', 'f1fe250f1429cb5d82a50f7751c1219f', 'EMP', 3, NULL, 1),
(12, 'Six', 'Employee', 'emp6@mail.com', 'empsix', '888487c61854da2a28a713958cb57b32eb7d64179e50d250e61158233fd42567', '0b541e061cf7d4ce6c066878299d2b41', 'EMP', 4, NULL, 1);

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
-- Constraints for table `rdtrack`
--
ALTER TABLE `rdtrack`
  ADD CONSTRAINT `document_fk` FOREIGN KEY (`document`) REFERENCES `document` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `division_fk` FOREIGN KEY (`division`) REFERENCES `division` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
