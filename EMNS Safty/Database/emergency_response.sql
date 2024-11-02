-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 12:51 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emergency_response`
--

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `accidentType` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `name`, `location`, `accidentType`, `details`, `created_at`) VALUES
(1, 'Eranda', 'Galle', 'Medical Emergency', 'no', '2024-10-24 05:53:40'),
(2, 'nimesh', 'Kandy', 'Fire', 'i want help', '2024-10-24 05:55:47'),
(3, 'nimesh 2', 'Anuradhapura', 'Car Accident', 'help', '2024-10-24 08:25:23'),
(4, 'sample', 'Negombo', 'Fire', 'no', '2024-10-24 09:05:05'),
(5, 'Eranda', 'Colombo', 'Other', 'yes', '2024-10-24 09:13:51'),
(6, 'Admin', 'Puttalam', 'Medical Emergency', 'banda', '2024-10-24 09:19:24'),
(7, 'jj', 'Kandy', 'Fire', 'nn', '2024-10-24 09:28:09'),
(8, 'sahan', 'Vavuniya', 'Fire', 'sahan', '2024-10-24 09:30:20'),
(9, 'Bat', 'Kandy', 'Car Accident', 'fdd', '2024-10-24 09:45:10'),
(10, 'sample', 'Kandy', 'Medical Emergency', 'eeee', '2024-10-24 10:38:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
