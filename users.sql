-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 13, 2026 at 04:06 AM
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
-- Database: `ener_nova_capri`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` varchar(50) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_picture_attachment` varchar(255) DEFAULT NULL,
  `cellphone_number` bigint(11) NOT NULL,
  `house_number` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `residency_status` enum('Owned','Rented') NOT NULL DEFAULT 'Owned',
  `proof_of_residency_type_name` varchar(150) NOT NULL,
  `religion` varchar(255) NOT NULL,
  `meralco_bill_attachment` varchar(255) DEFAULT NULL,
  `rented_proof_attachment` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `user_role` varchar(50) NOT NULL DEFAULT 'resident',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected','deactivated') DEFAULT 'pending',
  `deactivation_reason` text DEFAULT NULL,
  `fields_to_revise` text DEFAULT NULL COMMENT 'JSON array of field names that need revision',
  `password_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `bar_code` varchar(255) DEFAULT NULL,
  `is_verified` int(11) DEFAULT 0,
  `is_face_verified` tinyint(1) DEFAULT 0,
  `face_descriptor` text DEFAULT NULL,
  `last_seen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_watching_live` tinyint(1) DEFAULT 0,
  `otp_expiry` datetime DEFAULT NULL,
  `is_resubmit` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `sex`, `civil_status`, `email`, `profile_picture_attachment`, `cellphone_number`, `house_number`, `street`, `area`, `residency_status`, `proof_of_residency_type_name`, `religion`, `meralco_bill_attachment`, `rented_proof_attachment`, `password`, `otp`, `user_role`, `created_at`, `status`, `deactivation_reason`, `fields_to_revise`, `password_token`, `token_expiry`, `bar_code`, `is_verified`, `is_face_verified`, `face_descriptor`, `last_seen`, `is_watching_live`, `otp_expiry`, `is_resubmit`) VALUES
(3046, 'Aida', 'Labayo', 'Plojo', '', '1987-05-15', 'Female', 'Single', 'sofiyaloreynnn21@gmail.com', 'resident_profile_3046_69808e7e036b8.jpg', 9515457586, '11', 'Lily', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'ROMAN CATHOLIC', '69808089f2cda.jpg', NULL, '$2y$10$aAEAY367XYDYsjg0L8UmguAVipkZSF3W/ayzR1J1wbYR4ij2O3a5G', NULL, 'staff', '2026-02-02 02:46:33', 'approved', NULL, NULL, NULL, NULL, 'BC25cOPy', 1, 0, NULL, '2026-02-12 17:35:33', 0, NULL, 0),
(3048, 'Christian', 'Antonio', 'Cando', '', '1990-02-27', 'Male', 'Single', 'lcristianmarkangelo@gmail.com', 'avatar_1770905507_logo.jpg', 9563698586, '30', 'Camia', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'BORN AGAIN CHRISTIAN', '698081e09266f.jpeg', NULL, 'lcristianmarkangelo@gmail.com', NULL, 'admin', '2026-02-02 02:52:16', 'approved', NULL, NULL, NULL, NULL, 'BC257JjS', 1, 0, NULL, '2026-02-13 04:04:38', 0, NULL, 0),
(3132, 'Sofia', 'Lorraine', 'Signo', '', '2006-09-15', 'Female', 'Single', 'manining.commonwealth.gov@gmail.com', NULL, 9085919898, '10', 'Lily St', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'BORN AGAIN CHRISTIAN', '698e00dbc8ee4.jpg', NULL, '$2y$10$.XVM2CYjy3ZE/eUoliEsO.f/YUCvVXL45xr/tsoNcsaRK7XvW8ofW', NULL, 'resident', '2026-02-12 16:33:31', 'approved', NULL, NULL, NULL, NULL, 'BC25Uhnh', 1, 0, NULL, '2026-02-12 18:15:47', 0, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3133;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
