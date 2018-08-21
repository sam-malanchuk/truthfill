-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 20, 2018 at 09:16 PM
-- Server version: 5.6.39-cll-lve
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `youth_dir_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_views`
--

CREATE TABLE `table_views` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `table_values` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `youth_dir`
--

CREATE TABLE `youth_dir` (
  `ID` int(11) NOT NULL,
  `lname` varchar(24) CHARACTER SET utf8 DEFAULT NULL,
  `fname` varchar(18) CHARACTER SET utf8 DEFAULT NULL,
  `bday` date DEFAULT NULL,
  `tel` text NOT NULL,
  `baptism` tinyint(1) NOT NULL,
  `tmessages` tinyint(1) NOT NULL,
  `choir` tinyint(1) NOT NULL,
  `street` varchar(35) NOT NULL,
  `city` varchar(15) NOT NULL,
  `zipcode` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf32;

--
-- Dumping data for table `youth_dir`
--

INSERT INTO `youth_dir` (`ID`, `lname`, `fname`, `bday`, `tel`, `baptism`, `tmessages`, `choir`, `street`, `city`, `zipcode`) VALUES
(1, 'Сусляков', 'Иммануил', '0000-00-00', '', 1, 1, 1, '', '', 0),
(2, 'Поливанов', 'Аркадий', '0000-00-00', '', 0, 0, 0, '', '', 0),
(15, 'Crayne', 'Icabad', '2018-04-16', '3333333', 0, 1, 1, '', '', 0),
(8, 'Рудов', 'Борис', '2017-09-25', '', 1, 1, 0, '', '', 0),
(10, 'Сергеев', 'Аркадий', '0000-00-00', '', 0, 0, 0, '', '', 0),
(16, 'Malanchuk', 'Sam', '2018-03-04', '9802487983', 1, 0, 0, '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_views`
--
ALTER TABLE `table_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `youth_dir`
--
ALTER TABLE `youth_dir`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `table_views`
--
ALTER TABLE `table_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `youth_dir`
--
ALTER TABLE `youth_dir`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
