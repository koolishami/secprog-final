-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2023 at 07:38 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secprog_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

CREATE TABLE `otp_verification` (
  `id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `valid_until` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otp_verification`
--

INSERT INTO `otp_verification` (`id`, `email`, `otp`, `valid_until`) VALUES
(4, 'muhammadhaziq.s@graduate.utm.my', '624444', '2023-07-07 01:30:08.000000'),
(5, 'muhammadhaziq.s@graduate.utm.my', '997586', '2023-07-07 01:35:55.000000'),
(6, 'muhammadhaziq.s@graduate.utm.my', '984951', '2023-07-14 01:36:26.000000');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `ID` int(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `total_amount` int(100) NOT NULL,
  `card_num` varchar(100) NOT NULL,
  `expiry_month` varchar(100) NOT NULL,
  `expiry_year` varchar(100) NOT NULL,
  `cvv` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`ID`, `email`, `total_amount`, `card_num`, `expiry_month`, `expiry_year`, `cvv`) VALUES
(3, 'muhammadhaziq.s@graduate.utm.my', 45, '+jFpQzHMmcmL+X3EHZYI8g==', 'LzQSHNCTbdXQL8DV+QA18A==', 'T44AtqxGIQtQDHlcqeXV8Q==', 'KwPy3pCvNyjPfzcX/gWiFQ=='),
(4, 'ahmadramadhansyukri@graduate.utm.my', 842, '6C5PD8pOg6B1jkVSjaz7UemEkJH5Qzxuo8JQz2k4HAU=', 'scbb7FtjdHRs8OY1zEKZyg==', 'H3sR6jM+ZprhaSwSs6r//g==', 'LSwePGHVJZ1nrnjqgT3LUQ=='),
(5, 'ahmadramadhansyukri@graduate.utm.my', 2147483647, 'l2xsVEPgm5XkD9O3Q9E9YI/1/n+hnysfG4ypmlpi+AQ=', 'qhMjijyv/eOYE6gJG/p5+A==', 'GxE0F9RhmPVZLmpUjOek7A==', 'GxE0F9RhmPVZLmpUjOek7A==');

-- --------------------------------------------------------

--
-- Table structure for table `reset`
--

CREATE TABLE `reset` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reset`
--

INSERT INTO `reset` (`id`, `token`, `email`) VALUES
(7, '649c42b07d636', 'muhammadhaziq.s@gradute.utm.my');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `hashed_pw` varchar(64) NOT NULL,
  `salt` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `hashed_pw`, `salt`) VALUES
(1, 'testuser', '0d9dfd4af7b77415f2d8155b7e0d3849afa5be1b2d9d644229c2e10260d3c0bf', '4043f7d639'),
(2, 'another user', '5f1b33e9b0d2f308cb378253aca7688dcaac87b335fdbc3c3867ce69768f66ec', 'b7a4271e6d'),
(3, 'user', 'a9dd3186f0f7bcce27091f4a6bc25114bedbc70ef9834a1ffec8f62583c0ab64', '05dfa9bcb1'),
(4, 'muhammadhaziq.s@graduate.utm.my', 'e76fd0bbb9a82fbefe774e9bf303aec2a237686b8e9bb9449d8d51c3cd249903', '83c2c00613'),
(6, 'ahmadramadhansyukri@graduate.utm.my', '54238e683c6adff6fd4b817762cd69e7b618e897d03a6261cf2812f7263323b5', 'b98d325e01'),
(7, 'alert(&#34;hi there&#34;);', '9ead0686fd7760597e78c7ce9fa7386bf6cfdd568f645b280cf9f67cc3930837', '82d2666249');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `reset`
--
ALTER TABLE `reset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `otp_verification`
--
ALTER TABLE `otp_verification`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `ID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reset`
--
ALTER TABLE `reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
