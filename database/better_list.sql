-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2023 at 12:13 PM
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
-- Table structure for table `tbl_license`
--

CREATE TABLE `tbl_license` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `website` varchar(200) NOT NULL,
  `license_key` varchar(200) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_license`
--

INSERT INTO `tbl_license` (`id`, `userid`, `website`, `license_key`, `start_date`, `end_date`, `is_active`) VALUES
(1, 1, '', '7e0025bb46fb474d', '2023-03-14 06:27:27', '2024-03-13 05:53:39', 1),
(2, 1, '', '6de3203b198f6016', '2023-03-14 06:27:30', '2024-03-13 05:53:39', 1),
(3, 1, '', '687e6a9f126a87eb', '2023-03-14 06:27:31', '2024-03-13 05:53:39', 1),
(4, 1, '', 'b8cbbe9e36dc334f', '2023-03-14 06:27:33', '2024-03-13 05:53:39', 1),
(5, 1, '', 'eb44a3cbef41ff7c', '2023-03-14 06:27:35', '2024-03-13 05:53:39', 1),
(7, 2, '', 'fc3c49a56bd2891d', '2023-03-14 11:53:53', '2024-03-13 11:53:53', 1),
(8, 2, '', 'b09ca51c35ec9aa3', '2023-03-14 12:01:05', '2024-03-13 11:53:53', 1),
(9, 2, '', 'cd29ee16b7a70845', '2023-03-14 12:02:02', '2024-03-13 11:53:53', 1),
(10, 2, '', 'c26e40382655eb84', '2023-03-14 12:02:37', '2024-03-13 11:53:53', 1),
(11, 2, '', '6b06e9c42181bc63', '2023-03-14 12:02:39', '2024-03-13 11:53:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_payment`
--

INSERT INTO `tbl_payment` (`id`, `userid`, `start_date`, `end_date`, `is_active`) VALUES
(1, 1, '2023-03-14 05:53:39', '2024-03-13 05:53:39', 1),
(2, 2, '2023-03-14 11:53:53', '2024-03-13 11:53:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_template`
--

CREATE TABLE `tbl_template` (
  `id` int(11) NOT NULL,
  `template_name` varchar(200) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_template`
--

INSERT INTO `tbl_template` (`id`, `template_name`, `is_active`) VALUES
(1, 'Red', 1),
(2, 'Green', 1),
(3, 'Orange', 1),
(4, 'Blue', 1),
(5, 'Black', 1),
(6, 'White', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `emailid` varchar(200) NOT NULL,
  `password` longtext NOT NULL,
  `company` varchar(200) NOT NULL,
  `address` longtext NOT NULL,
  `city` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `is_verify` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `firstname`, `lastname`, `mobile`, `emailid`, `password`, `company`, `address`, `city`, `country`, `is_verify`, `is_active`, `created_on`) VALUES
(1, 'Swapnil', 'Borhade', '9768165858', 'swapnil@hybreed.co', '$2y$12$MKKwqreZgTiFJYuoJUh9su7F14iWAtpLw/cU9ZYoXPhCaY4tzXFMm', 'Hybreed', 'Thane', 'Mumabi', 'India', 1, 1, '2023-03-14 05:53:36'),
(2, 'Swapnil', 'Borhade', '9876543210', 'swapnil@hybreed2.co', '$2y$12$87NNnjjcQHk5CoPd2EDMbuLXhQLIgyvG1rUSS5PB2TyOTPnsTkEJy', '', '', '', 'India', 1, 1, '2023-03-14 11:53:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_license`
--
ALTER TABLE `tbl_license`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_template`
--
ALTER TABLE `tbl_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_license`
--
ALTER TABLE `tbl_license`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_template`
--
ALTER TABLE `tbl_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
