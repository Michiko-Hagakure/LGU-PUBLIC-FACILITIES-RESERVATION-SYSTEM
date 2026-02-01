-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 29, 2026 at 09:33 AM
-- Server version: 10.11.14-MariaDB-ubu2204
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `faci_facility`
--

-- --------------------------------------------------------

--
-- Table structure for table `fund_requests`
--

CREATE TABLE `fund_requests` (
  `id` int(11) NOT NULL,
  `requester_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `logistics` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `feedback` text DEFAULT NULL,
  `seminar_info` text DEFAULT NULL,
  `seminar_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fund_requests`
--

INSERT INTO `fund_requests` (`id`, `requester_name`, `user_id`, `amount`, `purpose`, `logistics`, `status`, `created_at`, `updated_at`, `feedback`, `seminar_info`, `seminar_image`) VALUES
(20, 'Christian A. Cando', 82, 123123.00, 'IEC Materials', 'Categories: C. Speakers/Services | Specifics: haha', 'Rejected', '2026-01-29 08:35:06', '2026-01-29 08:35:16', 'hahaaha', NULL, NULL),
(22, 'Christian A. Cando', 82, 50000.00, 'Tech Services', 'Categories: C. Speakers/Services | Specifics: need po namin ng ganto', 'Rejected', '2026-01-29 08:42:22', '2026-01-29 09:13:37', 'hehe', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fund_requests`
--
ALTER TABLE `fund_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fund_requests`
--
ALTER TABLE `fund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
