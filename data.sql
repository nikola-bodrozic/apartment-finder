-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2017 at 06:45 PM
-- Server version: 5.6.35-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nikolabo_ingatio`
--

-- --------------------------------------------------------

--
-- Table structure for table `fol_apartment`
--

CREATE TABLE `fol_apartment` (
  `id` int(11) NOT NULL,
  `slug` varchar(256) NOT NULL,
  `rent_amount` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lang` float(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fol_apartment`
--

INSERT INTO `fol_apartment` (`id`, `slug`, `rent_amount`, `area_id`, `lat`, `lang`) VALUES
(1, 'hyggeligt-raekkehus-med-central-beliggenhed', 48000, 1, 55.706631, 11.580622),
(2, 'utrolig-udsigtslejlighed-i-det-prisbelonnede-8', 12000, 1, 55.682789, 13.591911),
(3, 'stor-skon-villa-med-havudsigt', 18000, 2, 55.399837, 11.372463),
(4, 'house-nice-view', 48000, 3, 55.081406, 12.013550),
(5, 'house-wiht-garden', 11000, 3, 55.729069, 13.120422),
(6, 'apartment-with-riverfront', 45000, 2, 55.156799, 11.898193);

-- --------------------------------------------------------

--
-- Table structure for table `fol_city`
--

CREATE TABLE `fol_city` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fol_city`
--

INSERT INTO `fol_city` (`id`, `name`) VALUES
(1, 'Kopenhagen'),
(2, 'Slagelse'),
(3, 'Hellerup');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fol_apartment`
--
ALTER TABLE `fol_apartment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fol_city`
--
ALTER TABLE `fol_city`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fol_apartment`
--
ALTER TABLE `fol_apartment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `fol_city`
--
ALTER TABLE `fol_city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
