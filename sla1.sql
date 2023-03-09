-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2023 at 10:24 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sla1`
--

-- --------------------------------------------------------

--
-- Table structure for table `spv`
--

CREATE TABLE `spv` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nrp` int(11) NOT NULL,
  `area` varchar(50) NOT NULL,
  `profile` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `spv`
--

INSERT INTO `spv` (`id`, `username`, `password`, `nrp`, `area`, `profile`) VALUES
(1, 'umar', 'umar', 26474637, 'arab saudi', '64098ed3d065f.png');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nrp` varchar(20) NOT NULL,
  `area` varchar(50) NOT NULL,
  `profile` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password`, `nrp`, `area`, `profile`) VALUES
(1, 'adimiftah', 'adimiftah', '12344', 'tokyo', '6405b406ab95e.png'),
(6, 'makmur', 'makmur', '32', 'arab saudi', '6401ac7025f0a.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `task` varchar(50) NOT NULL,
  `deskripsi` varchar(50) NOT NULL,
  `prog` varchar(50) NOT NULL,
  `tngl` varchar(50) NOT NULL,
  `gambare` varchar(50) NOT NULL,
  `area` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `nama`, `task`, `deskripsi`, `prog`, `tngl`, `gambare`, `area`) VALUES
(21, 'arab saudi', 'mengepel', 'wwwwwwww', '0', '2023-03-10', '', ''),
(22, 'arab saudi', 'mengepel', 'wdwd', '0', '2023-03-09', '', ''),
(23, 'makmur', 'mengepel', 'fdfdgf', '0', '2023-03-09', '', ''),
(24, 'makmur', 'mengepel', 'ggfgfgf', '0', '2023-03-10', '', 'arab saudi'),
(25, 'makmur', 'membersikan jendela', 'gfgfgf', '0', '2023-03-11', '', 'arab saudi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `spv`
--
ALTER TABLE `spv`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `spv`
--
ALTER TABLE `spv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
