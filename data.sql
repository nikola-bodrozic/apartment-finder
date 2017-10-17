-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 18, 2017 at 01:28 AM
-- Server version: 5.6.37-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

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
(1, 'studio-for-big-family', 48000, 1, 51.881409, -0.568542),
(2, 'house-with-garden', 12000, 1, 51.915302, -0.304871),
(3, 'apartment-in-quiet-street', 18000, 2, 51.527370, 0.153809),
(4, 'house-nice-view', 48000, 2, 51.523952, -0.401001),
(5, 'house-with-yard', 11000, 2, 51.479504, 0.175781),
(6, 'apartment-with-riverfront', 45000, 2, 51.308125, -0.241699),
(7, 'nice-house-in-suburb', 12000, 3, 51.769371, -1.444702),
(8, 'studio-for-single-person', 15000, 3, 51.782967, -1.038208);

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
(1, 'Luton'),
(2, 'London'),
(3, 'Oxford');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `fol_city`
--
ALTER TABLE `fol_city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;COMMIT;
