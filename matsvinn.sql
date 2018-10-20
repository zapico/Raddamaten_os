-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 08, 2018 at 09:56 PM
-- Server version: 10.1.23-MariaDB-9+deb9u1
-- PHP Version: 7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matsvinn`
--

-- --------------------------------------------------------

--
-- Table structure for table `matlista_com`
--

CREATE TABLE `matlista_com` (
  `id` int(11) NOT NULL,
  `namn_matratt` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `beskrivning` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `laktos` int(11) DEFAULT NULL,
  `milk` int(11) DEFAULT NULL,
  `egg` int(11) DEFAULT NULL,
  `nuts` int(11) DEFAULT NULL,
  `fish` int(11) DEFAULT NULL,
  `meat` int(11) DEFAULT NULL,
  `veg` int(11) DEFAULT NULL,
  `datefrom` date NOT NULL,
  `dateto` date NOT NULL,
  `hamtning` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `portioner` int(11) NOT NULL,
  `bildnamn` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `matlista_priv`
--

CREATE TABLE `matlista_priv` (
  `id` int(11) NOT NULL,
  `namn_matratt` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `beskrivning` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `datefrom` date NOT NULL,
  `dateto` date NOT NULL,
  `hamtning` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `portioner` int(11) NOT NULL,
  `bildnamn` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `user_list`
--

CREATE TABLE `user_list` (
  `id` int(11) NOT NULL,
  `namn` varchar(50) NOT NULL,
  `orgnr` varchar(50) NOT NULL,
  `adress` varchar(100) NOT NULL,
  `ort` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefon` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `passwd` varchar(200) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usertype` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Indexes for table `matlista_com`
--
ALTER TABLE `matlista_com`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matlista_priv`
--
ALTER TABLE `matlista_priv`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_list`
--
ALTER TABLE `user_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `matlista_com`
--
ALTER TABLE `matlista_com`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `matlista_priv`
--
ALTER TABLE `matlista_priv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_list`
--
ALTER TABLE `user_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
