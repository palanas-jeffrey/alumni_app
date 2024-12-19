-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 10:10 AM
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
-- Database: `user_accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `alumnus_id` int(11) NOT NULL,
  `donation_type` enum('money','items') NOT NULL,
  `donation_amount` decimal(10,2) DEFAULT NULL,
  `items_donated` text DEFAULT NULL,
  `payment_method` enum('creditCard','paypal','bankTransfer') DEFAULT NULL,
  `contact` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `alumnus_id`, `donation_type`, `donation_amount`, `items_donated`, `payment_method`, `contact`, `created_at`) VALUES
(1, 1, 'money', 20.00, '', 'creditCard', '09982423808', '2024-10-09 11:39:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `course_graduated` varchar(100) DEFAULT NULL,
  `currently_connected_to` text DEFAULT NULL,
  `image` longblob DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','alumni_officer','alumnus') NOT NULL DEFAULT 'alumnus',
  `approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `last_name`, `first_name`, `middle_name`, `gender`, `batch`, `course_graduated`, `currently_connected_to`, `image`, `email`, `password`, `user_type`, `approved`, `created_at`) VALUES
(1, 'Java', 'Josh Nil', 'O', 'male', ' 2024', 'BS Information Technology', '', NULL, 'joshnil.java@students.isatu.edu.ph', 'joshniljava', 'alumnus', 1, '2024-10-10 03:33:48'),
(3, 'Importante', 'Renan', 'D', 'male', '2024', 'BS Information Technology', '', NULL, 'renan.importante@students.isatu.edu.ph', 'renanimportante', 'admin', 0, '2024-10-10 03:33:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
