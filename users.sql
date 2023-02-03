-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2023 at 10:30 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `better_list`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `emailid` varchar(200) NOT NULL,
  `password` longtext NOT NULL,
  `company` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `is_verify` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `forgot_password_value` int(11) NOT NULL DEFAULT 0,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `emailid`, `password`, `company`, `country`, `is_verify`, `is_active`, `forgot_password_value`, `created_on`, `updated_on`) VALUES
(1, 'swapnil', 'Borhade', 'swapnil@hybreed.co', '$2y$12$SHmK8xh5gga5bEPuiw.INO4sSWpZoMPy7nI7dk6uKRKXRYpvHIwAG', '', 'India', 0, 1, 0, '2023-02-03 08:17:50', '2023-02-03 08:17:50'),
(3, 'Swapnil', 'Borhade', 'swapnil@hybreed.co.omf', '$2y$12$0Vep0uPBIbRsTHr7T.rmvuIGC/XjSUJKoMIrfzfoulbKm0oDrUGHW', 'Hybreed', 'India', 0, 1, 0, '2023-02-03 08:25:32', '2023-02-03 08:25:32'),
(4, 'Swapnil', 'Borhade', 'swapnil.borhade74@gmail.com', '$2y$12$sHNRCGK.eO16cULkMiTvSebPgfSKxiVpDRG1eok3BQDNqDWLced5K', 'Hybreed', 'India', 0, 1, 0, '2023-02-03 08:33:46', '2023-02-03 08:33:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
