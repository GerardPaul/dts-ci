-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2014 at 10:44 AM
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
  `created` int(11) NOT NULL,
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chatmessages`
--

CREATE TABLE IF NOT EXISTS `chatmessages` (
`id` int(11) NOT NULL,
  `chatId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `from` varchar(100) NOT NULL,
  `dueDate` varchar(10) DEFAULT NULL,
  `attachment` varchar(250) DEFAULT NULL,
  `status` enum('Compiled','On-Going','Cancelled','') NOT NULL,
  `referenceNumber` int(11) DEFAULT NULL,
  `dateReceived` varchar(10) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`id`, `subject`, `from`, `dueDate`, `attachment`, `status`, `referenceNumber`, `dateReceived`) VALUES
(1, 'Test', 'Test', '09/30/2014', 'C:/xampp/htdocs/dts-ci/upload/14092337-12314134-New_Text_Document.txt', 'Compiled', 12314134, '09/23/2014'),
(2, 'This is a test subject for document', 'Gerard Paul Labitad', '09/30/2014', 'C:/xampp/htdocs/dts-ci/upload/14092342-123441144-New_Text_Document.txt', 'On-Going', 123441144, '09/23/2014'),
(3, 'Subject For Test Document', 'Paul Gerard', '10/02/2014', 'C:/xampp/htdocs/dts-ci/upload/14092356-234523456-New_Text_Document.txt', 'Cancelled', 234523456, '09/24/2014'),
(4, 'Document Added', 'Sec. User', '09/30/2014', 'C:/xampp/htdocs/dts-ci/upload/14092514-11123123123-New_Text_Document.txt', 'On-Going', 2147483647, '09/25/2014'),
(5, 'This is a Test Document', 'Test Person', '10/07/2014', 'C:/xampp/htdocs/dts-ci/upload/14100228-11231231-New_Text_Document.txt', 'On-Going', 11231231, '10/02/2014');

-- --------------------------------------------------------

--
-- Table structure for table `rdtrack`
--

CREATE TABLE IF NOT EXISTS `rdtrack` (
`id` int(11) NOT NULL,
  `document` int(11) NOT NULL,
  `action` enum('Handle','Comment','Information','Prepare Draft','Reply','Discuss with Me','Note and File','Note and Return','Give Status') DEFAULT NULL,
  `dateReceived` varchar(10) DEFAULT '0',
  `notes` text,
  `ard` int(11) DEFAULT NULL,
  `ardDateReceived` varchar(10) DEFAULT '0',
  `emp` int(11) DEFAULT NULL,
  `empDateReceived` varchar(10) DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `rdtrack`
--

INSERT INTO `rdtrack` (`id`, `document`, `action`, `dateReceived`, `notes`, `ard`, `ardDateReceived`, `emp`, `empDateReceived`) VALUES
(1, 1, 'Discuss with Me', '09/25/2014', 'Please discuss with me.', 6, '0', 0, '0'),
(2, 2, NULL, '09/25/2014', NULL, 0, '0', 0, '0'),
(3, 3, 'Information', '09/25/2014', 'asdasdkajldkajsldkajslkdjalkwaksdjalsdnamsdambehabnfbadmhfgjhasbdnbfmndfbasenmasdfmsdfmasefsfasesef', 4, '0', 12, '0'),
(4, 4, 'Handle', '10/03/2014', 'This is a test forward to TSSD ARD.', 4, '0', 0, '0'),
(5, 5, 'Comment', '10/02/2014', 'FASD Employee Four', 6, '0', 10, '0');

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
  `userType` enum('RD','SEC','ARD','EMP','ADMIN') NOT NULL,
  `division` int(11) NOT NULL,
  `resetHash` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `salt`, `userType`, `division`, `resetHash`, `status`) VALUES
(1, 'Admin', 'Admin', 'admin@mail.com', 'administrator', '41d4cede6fa08bd9ee5ca045e9800731dd93e76905a9532364b437ebe3ec2508', '076a5936dc0874a1dee13f8aadd1a88e', 'ADMIN', 4, NULL, 1),
(2, 'Test', 'RD', 'rd@mail.com', 'testrd', 'd0a89572e36c71d12c701c61770567ad408906781402b37c217b5c5955472c7f', '6b7023f3ddecf4e5323a9515700ff499', 'RD', 1, NULL, 1),
(3, 'Test', 'SEC', 'sec@mail.com', 'testsec', 'f407711e32dd6d1f196897694be9dd0a3acbde2a7f7fda1c348b67bd8693d31a', 'a3a5968fb369267e88323a647ca42580', 'SEC', 1, NULL, 1),
(4, 'TSSD', 'ARD', 'tssdard@mail.com', 'tssdard', '6685838d32ff9fac4784153ef56cef2b1accc89c1e95665364933788eff3a9ac', 'a2c65febfbcff1ec2fbc535ac8a32518', 'ARD', 4, NULL, 1),
(5, 'TSD', 'ARD', 'tsdard@mail.com', 'tsdard', '589627ab176d00bf116e1109d96c251acab9bbf08e4c313cbf049064a91259a7', '8f8ff0c5037ea3156b227f00e82c05d8', 'ARD', 3, NULL, 1),
(6, 'FASD', 'ARD', 'fasdard@mail.com', 'fasdard', '8fb7093166e11669f10fbb4f7478ff41df82421f54d1ba413a1a687c6163dac2', 'd9736b2f4a02b2d9bd5a99d5d2f0b27b', 'ARD', 2, NULL, 1),
(7, 'One', 'Employee', 'emp1@mail.com', 'empone', '409e7d495dbc24a57490db01a53573e9c8e4476bd9c7f301f485b1117229e1d5', 'c00bc73528595ec73586f1e3df11edac', 'EMP', 2, NULL, 1),
(8, 'Two', 'Employee', 'emp2@mail.com', 'emptwo', '422f61e1e3a09571410d8012002eb41fc0e385bcfe40e251181c7fb26d49bc2a', 'e0a7a9b863973b5945bd810461531e02', 'EMP', 3, NULL, 1),
(9, 'Three', 'Employee', 'emp3@mail.com', 'empthree', 'cb3cf7fd050919aa12be6d93fec9c09b5a35bf06920e55544935ff5db640c02d', 'a2eec2f4b8dc61efa0fc6714c7b4ed38', 'EMP', 4, NULL, 1),
(10, 'Four', 'Employee', 'emp4@mail.com', 'empfour', 'dadd4a58704b7f777101b5f3b8f981d2894f473dacea5716721bc3a9c7aa8149', '394fc73229f39373cede26fe1608a2f6', 'EMP', 2, NULL, 1),
(11, 'Five', 'Employee', 'emp5@mail.com', 'empfive', 'd90244cc2e934a1ed9a9862d26660f91b3ec8d407fe55b81c9bee524a4788582', '535c95f77ff86dbd099def03aa4eb7b2', 'EMP', 3, NULL, 1),
(12, 'Six', 'Employee', 'emp6@mail.com', 'empsix', '888487c61854da2a28a713958cb57b32eb7d64179e50d250e61158233fd42567', '0b541e061cf7d4ce6c066878299d2b41', 'EMP', 4, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
 ADD PRIMARY KEY (`id`), ADD KEY `document` (`document`);

--
-- Indexes for table `chatmessages`
--
ALTER TABLE `chatmessages`
 ADD PRIMARY KEY (`id`);

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
-- Indexes for table `rdtrack`
--
ALTER TABLE `rdtrack`
 ADD PRIMARY KEY (`id`), ADD KEY `document` (`document`), ADD KEY `ard` (`ard`,`emp`), ADD KEY `ard_2` (`ard`), ADD KEY `emp` (`emp`);

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chatmessages`
--
ALTER TABLE `chatmessages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `rdtrack`
--
ALTER TABLE `rdtrack`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
ADD CONSTRAINT `chat_document` FOREIGN KEY (`document`) REFERENCES `document` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
