-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 04:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `userdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `owner` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `name`, `latitude`, `longitude`, `owner`, `created_at`) VALUES
(1, 'admin', 53.791931, -1.760559, 'admin', '2025-03-07 14:58:35');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `action` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `username`, `action`, `timestamp`) VALUES
(1, 'admin', 'Admin login', '2025-03-07 09:53:10'),
(2, 'user1', 'User signed up and is awaiting approval', '2025-03-07 09:53:10'),
(3, 'Rayees', 'User signed up and is awaiting email verification', '2025-03-07 10:27:11'),
(4, 'admin', 'User logged in', '2025-03-07 10:33:58'),
(5, 'admin', 'Visited admin dashboard', '2025-03-07 10:33:58'),
(6, 'admin', 'Asset ID 2 deleted', '2025-03-07 11:44:26'),
(7, 'dan', 'User registered', '2025-03-07 14:08:02');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `controls_visibility` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verification_code` varchar(255) DEFAULT NULL,
  `add_visible` tinyint(1) DEFAULT 1,
  `edit_visible` tinyint(1) DEFAULT 1,
  `delete_visible` tinyint(1) DEFAULT 1,
  `filter_visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `approved`, `created_at`, `verification_code`, `add_visible`, `edit_visible`, `delete_visible`, `filter_visible`) VALUES
(1, 'admin', '$2y$10$HVHJ.oBUK0W9fk/HUVYxyO5mqJc6Oz2WigQPLSVnEBhKldwqEXzb2', 'admin@example.com', 'admin', 1, '2025-03-07 09:53:10', NULL, 1, 1, 1, 1),
(2, 'user1', '$2y$10$jmG5/U1F7z5KnXnntEOtVlnW.gXHXi6KpoVXMn5VxP4KnKj0BKmV6', 'user1@example.com', 'user', 1, '2025-03-07 09:53:10', NULL, 1, 1, 1, 1),
(3, 'user2', '$2y$10$gMd4eKHKZyMM4UnjyZxi4Zq2H6Fk7zYQhqaX5DMyfC1knnPAoQg8m', 'user2@example.com', 'user', 1, '2025-03-07 09:53:10', NULL, 1, 1, 1, 1),
(7, 'Rxyees', '$2y$10$3hzr7cedIPAMz83qHr8NZeb34lBl7/QvXGYtUaq4E/PCG/8Rdsrw2', '', 'user', 0, '2025-03-07 14:04:20', NULL, 1, 1, 1, 1),
(8, 'dan', '$2y$10$fFlv2WeLx8TMUHCMtlxRuOJbTicwx3SbwkMF5onQOkhX4xyZlEdO2', '', 'user', 0, '2025-03-07 14:08:02', NULL, 1, 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
