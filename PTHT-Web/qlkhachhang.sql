-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2025 at 10:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlkhachhang`
--

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `user_name` varchar(50) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthday` date NOT NULL,
  `tel` varchar(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `link_anh` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`user_name`, `gender`, `birthday`, `tel`, `address`, `email`, `password`, `link_anh`) VALUES
('a', 'mal', '2025-10-17', '0325855154', 'Dai hoc Can Tho', 'a@ctu.edu.vn', '827ccb0eea8a706c4c34a16891f84e7b', '');

-- --------------------------------------------------------

--
-- Table structure for table `nhanviensoatve`
--

CREATE TABLE `nhanviensoatve` (
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `nhanviensoatve`
--

INSERT INTO `nhanviensoatve` (`email`, `password`, `user_name`, `gender`) VALUES
('nvsv@ctu.edu.vn', '12345', 'nhanviensoatve', 'male');

-- --------------------------------------------------------

--
-- Table structure for table `nhatochuc`
--

CREATE TABLE `nhatochuc` (
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `nhatochuc`
--

INSERT INTO `nhatochuc` (`email`, `password`, `user_name`) VALUES
('ntc@ctu.edu.vn', '12345', 'nhatochuc');

-- --------------------------------------------------------

--
-- Table structure for table `quantrivien`
--

CREATE TABLE `quantrivien` (
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `quantrivien`
--

INSERT INTO `quantrivien` (`email`, `password`, `user_name`) VALUES
('qtv@ctu.edu.vn', '12345', 'quantrivien');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `nhanviensoatve`
--
ALTER TABLE `nhanviensoatve`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `nhatochuc`
--
ALTER TABLE `nhatochuc`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `quantrivien`
--
ALTER TABLE `quantrivien`
  ADD PRIMARY KEY (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
