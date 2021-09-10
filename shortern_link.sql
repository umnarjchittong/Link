-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2021 at 10:05 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shortern_link`
--

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `links_id` int(11) NOT NULL,
  `links_code` varchar(10) NOT NULL,
  `links_title` varchar(80) NOT NULL,
  `links_url` text NOT NULL,
  `links_user` varchar(30) NOT NULL,
  `links_user_id` varchar(13) NOT NULL,
  `links_time` datetime NOT NULL DEFAULT current_timestamp(),
  `links_status` varchar(10) NOT NULL DEFAULT 'enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`links_id`, `links_code`, `links_title`, `links_url`, `links_user`, `links_user_id`, `links_time`, `links_status`) VALUES
(6, '92810', 'คณะสถาปัตย์ฯ', 'https://google.com', 'Judarad', '3501300406151', '2021-09-10 10:27:16', 'enable'),
(7, '98210', 'คณะสถาปัตย์ฯ', 'https://google.com', 'Judarad', '3501300406151', '2021-09-10 11:00:20', 'enable'),
(12, 'B902', 'คณะสถาปัตย์ฯ', 'https://arch.mju.ac.th/staff', 'Umnarj', '3500700238956', '2021-09-10 11:26:56', 'enable'),
(13, 'DFA11', 'คณะสถาปัตย์ฯ', 'https://arch.mju.ac.th/about/', 'Umnarj', '3500700238956', '2021-09-10 11:27:41', 'delete'),
(14, '11A3', 'คณะสถาปัตย์ฯ', 'https://arch.mju.ac.th/about/', 'Umnarj', '3500700238956', '2021-09-10 11:28:47', 'enable');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `logs_id` int(11) NOT NULL,
  `links_id` int(11) NOT NULL,
  `links_code` varchar(10) NOT NULL,
  `logs_time` datetime NOT NULL DEFAULT current_timestamp(),
  `logs_status` varchar(10) NOT NULL DEFAULT 'enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`logs_id`, `links_id`, `links_code`, `logs_time`, `logs_status`) VALUES
(1, 14, '11A3', '2021-09-10 13:55:49', 'enable'),
(2, 14, '11A3', '2021-09-10 13:58:36', 'enable'),
(3, 14, '11A3', '2021-09-10 14:00:25', 'enable'),
(4, 14, '11A3', '2021-09-10 14:00:28', 'enable'),
(5, 14, '11A3', '2021-09-10 14:00:42', 'enable'),
(6, 12, 'B902', '2021-09-10 14:56:20', 'enable'),
(7, 12, 'B902', '2021-09-10 14:56:54', 'enable'),
(8, 12, 'B902', '2021-09-10 14:57:10', 'enable'),
(9, 12, 'B902', '2021-09-10 14:57:15', 'enable'),
(10, 12, 'B902', '2021-09-10 14:57:28', 'enable'),
(11, 12, 'B902', '2021-09-10 14:57:39', 'enable');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`links_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`logs_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `links_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `logs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
