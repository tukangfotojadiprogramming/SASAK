-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Juli 10, 2025 at 13:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sasak`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Nama Depan` varchar(100) DEFAULT NULL,
  `Nama Belakang` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Nomor HP` varchar(15) NOT NULL,
  `Password` varchar(15) NOT NULL,
  `Konfirmasi Password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Nama Depan`, `Nama Belakang`, `Email`, `Nomor HP`, `Password`, `Konfirmasi Password`) VALUES
('zamzami satria tegar', 'tegar', 'tegarpotret@gmail.com', '083444666907', '123', '123'),
('Tes Nama', 'tesuser', 'tes@example.com', '08123456789', 'tes123', 'tes123'),
('zamzam zamzam', 'zami zami', 'tegarpotret222@gmail.com', '083444666907', '123', '123');


--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
