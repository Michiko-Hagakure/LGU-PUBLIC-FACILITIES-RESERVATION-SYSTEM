-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 14, 2026 at 04:50 AM
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
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `type` enum('news','article','poster','video') NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `type`, `title`, `content`, `link_url`, `media_path`, `status`, `created_at`) VALUES
(2, 'news', 'DOE Promotes Energy Efficiency and Conservation Program (EECP)', 'The Department of Energy (DOE) continues to promote the Energy Efficiency and Conservation Program (EECP) to encourage households and businesses to reduce electricity consumption. The program supports the implementation of Republic Act 11285 to institutionalize energy efficiency and conservation nationwide.', 'https://www.doe.gov.ph/energy-efficiency', 'uploads/1.jpg', 'active', '2026-02-13 22:49:04'),
(3, 'article', 'Energy Efficiency 2023 Report Highlights Global Progress', 'According to the International Energy Agency (IEA), energy efficiency improvements are accelerating globally, helping reduce emissions and lower energy costs. The report emphasizes the importance of efficient appliances, smart technologies, and policy enforcement to meet climate targets.', 'https://www.iea.org/reports/energy-efficiency-2023', 'uploads/2.png', 'active', '2026-02-13 22:49:04'),
(4, 'poster', 'Save Energy. Save Money. Choose ENERGY STAR Appliances.', 'Look for the ENERGY STAR label when buying appliances. ENERGY STAR certified products use less energy, reduce greenhouse gas emissions, and help households save on electricity bills without sacrificing performance.', 'https://www.energystar.gov/', 'uploads/3.png', 'active', '2026-02-13 22:49:04');

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE `challenges` (
  `challenge_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `target_area` varchar(50) DEFAULT 'All',
  `banner_image` varchar(255) DEFAULT NULL,
  `proof_type` varchar(50) DEFAULT 'video',
  `keyword` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Published','Archived') DEFAULT 'Pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_finalized` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `challenge_submissions`
--

CREATE TABLE `challenge_submissions` (
  `submission_id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `proof_attachment` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `judging_status` enum('Standard','Finalist','Disqualified') DEFAULT 'Standard',
  `ranking` int(11) DEFAULT NULL,
  `disqualification_reason` text DEFAULT NULL,
  `is_rewarded` tinyint(1) DEFAULT 0,
  `reward_photo_1` varchar(255) DEFAULT NULL,
  `reward_photo_2` varchar(255) DEFAULT NULL,
  `reward_claimed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `challenge_submissions`
--

INSERT INTO `challenge_submissions` (`submission_id`, `challenge_id`, `user_id`, `proof_attachment`, `status`, `submitted_at`, `judging_status`, `ranking`, `disqualification_reason`, `is_rewarded`, `reward_photo_1`, `reward_photo_2`, `reward_claimed_at`) VALUES
(1, 1, 3111, 'res_1771031418_31secs.mp4', 'Pending', '2026-02-14 01:10:18', 'Standard', NULL, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `correction_details`
--

CREATE TABLE `correction_details` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `requested_data` text NOT NULL,
  `reason_for_change` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_feedback` text DEFAULT NULL,
  `proof_attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `correction_history`
--

CREATE TABLE `correction_history` (
  `history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `change_type` enum('request_approval','direct_edit') NOT NULL,
  `old_data` text DEFAULT NULL,
  `new_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `correction_requests`
--

CREATE TABLE `correction_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reading_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `requested_kwh` decimal(10,2) DEFAULT NULL,
  `requested_bill_amount` decimal(10,2) DEFAULT NULL,
  `requested_reading_date` date DEFAULT NULL,
  `new_bill_file_path_1` varchar(255) DEFAULT NULL,
  `new_bill_file_path_2` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_chat`
--

CREATE TABLE `live_chat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `session_id` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_chat`
--

INSERT INTO `live_chat` (`id`, `user_id`, `name`, `message`, `session_id`, `created_at`) VALUES
(2, 3115, 'Crystal ashley Natividad', '❤❤', 3, '2026-02-14 00:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `live_sessions`
--

CREATE TABLE `live_sessions` (
  `id` int(11) NOT NULL,
  `youtube_id` text NOT NULL,
  `total_viewers` int(11) DEFAULT 0,
  `viewer_list` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'finished',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_sessions`
--

INSERT INTO `live_sessions` (`id`, `youtube_id`, `total_viewers`, `viewer_list`, `status`, `created_at`) VALUES
(3, '{\"id\":\"QqaL2FwS564\",\"platform\":\"youtube\"}', 7, 'Aida Bruma, Christian Cando, Jhales Santiago, Shena Flores, John Selato, Nancy Zubiri, Crystal ashley Natividad', 'finished', '2026-02-14 00:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `my_fund_requests`
--

CREATE TABLE `my_fund_requests` (
  `id` int(11) NOT NULL,
  `government_id` int(11) NOT NULL COMMENT 'ID returned by Government API',
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `purpose` text NOT NULL,
  `logistics` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `feedback` text DEFAULT NULL,
  `seminar_info` text DEFAULT NULL,
  `seminar_image` varchar(255) DEFAULT NULL,
  `seminar_id` int(11) DEFAULT NULL,
  `approved_amount` decimal(15,2) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `facility_name` varchar(255) DEFAULT NULL,
  `facility_capacity` int(11) DEFAULT NULL,
  `equipment` text DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `schedule_start_time` varchar(50) DEFAULT NULL,
  `schedule_end_time` varchar(50) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `budget_breakdown` text DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `my_fund_requests`
--

INSERT INTO `my_fund_requests` (`id`, `government_id`, `user_id`, `amount`, `purpose`, `logistics`, `status`, `created_at`, `feedback`, `seminar_info`, `seminar_image`, `seminar_id`, `approved_amount`, `facility_id`, `facility_name`, `facility_capacity`, `equipment`, `schedule_date`, `schedule_start_time`, `schedule_end_time`, `admin_notes`, `budget_breakdown`, `approved_by`, `approved_at`) VALUES
(37, 1001, 3114, 1500.00, 'Fund request to cover seminar registration and materials for Energy Efficiency Workshop.', 'Transport and venue setup will be managed by the barangay logistics team.', 'pending', '2026-02-13 23:50:32', NULL, 'Energy Efficiency General Assembly for All Areas', 'uploads/6.jpg', 9, NULL, 2, 'Barangay Multi-Purpose Hall', 100, 'Projector, Whiteboard, Microphones', '2026-01-30', '13:00', '16:30', NULL, '{\"Registration\": 500, \"Materials\": 500, \"Snacks\": 500}', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seminars`
--

CREATE TABLE `seminars` (
  `seminar_id` int(11) NOT NULL,
  `seminar_title` varchar(255) NOT NULL,
  `seminar_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `target_area` varchar(255) NOT NULL,
  `attachments_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seminar_image_url` varchar(255) DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seminars`
--

INSERT INTO `seminars` (`seminar_id`, `seminar_title`, `seminar_date`, `start_time`, `end_time`, `description`, `location`, `target_area`, `attachments_path`, `created_at`, `seminar_image_url`, `is_archived`) VALUES
(3, 'January Energy Efficiency General Assembly', '2026-01-25', '09:00:00', '12:00:00', 'This general assembly seminar covered energy efficiency awareness, proper monthly kWh submission, and electricity bill monitoring for all residents. The session included a live demonstration of the barangay energy monitoring dashboard and open forum for electricity-saving concerns.', 'Barangay Covered Court', 'ALL AREAS', 'uploads/4.png', '2026-02-13 22:59:59', 'uploads/4.png', 0),
(4, 'Community Energy Conservation & Smart Monitoring Seminar', '2026-01-30', '13:00:00', '16:30:00', 'This seminar focused on reducing electricity consumption through smart appliance usage, proper air-conditioning settings, and consistent monthly kWh monitoring. Residents were guided on how to analyze their electricity bills and compare consumption trends using the barangay energy monitoring system.', 'Barangay Multi-Purpose Hall', 'ALL AREAS', 'uploads/7.jpg', '2026-02-13 23:04:03', 'uploads/7.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'live_stream_id', '');

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
  `status` enum('pending','approved','rejected','deactivated','returned') DEFAULT 'pending',
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
(3046, 'Aida', 'Labayo', 'Bruma', '', '1987-05-15', 'Female', 'Single', 'sofiyaloreynnn21@gmail.com', 'avatar_1770905507_logo.jpg', 9515457586, '11', 'Lily Street', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'ROMAN CATHOLIC', 'bill1.jpg', NULL, '$2y$10$aAEAY367XYDYsjg0L8UmguAVipkZSF3W/ayzR1J1wbYR4ij2O3a5G', NULL, 'staff', '2026-02-01 18:46:33', 'approved', NULL, NULL, NULL, NULL, 'BC25cOPy', 1, 0, NULL, '2026-02-14 01:12:21', 0, NULL, 0),
(3048, 'Christian', 'Antonio', 'Cando', '', '1990-02-27', 'Male', 'Single', 'jhalesarizosantiago@gmail.com', 'kap.jpg', 9563698586, '30', 'Camia Street', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'BORN AGAIN CHRISTIAN', 'bill1.jpg', NULL, '$2y$10$MavrgXT7Z.TZYEA.TRvXJOByMNj9mFDOH.7kdmk.5jj4BRUSA5oPi', NULL, 'admin', '2026-02-01 18:52:16', 'approved', NULL, NULL, NULL, NULL, 'BC257JjS', 1, 0, NULL, '2026-02-14 00:26:21', 0, NULL, 0),
(3111, 'Jhales', 'Arizo', 'Santiago', '', '2002-09-16', 'Male', 'Single', 'jeylzuayanokoji@gmail.com', 'avatar_1770997870_jhalesPic.jpg', 9303207238, '20', 'Lily Street', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'BAPTIST', 'bill1.jpg', NULL, '$2y$10$nPkuvnXDwxTmDwdKIcqaM.hLxKdpmgrJEP0IXg/1ImrEplp/gdiLm', NULL, 'resident', '2026-02-05 12:24:09', 'approved', NULL, NULL, NULL, NULL, 'BC257dPn', 1, 0, NULL, '2026-02-14 01:07:17', 1, NULL, 0),
(3112, 'Shina', 'F.', 'Santos', '', '1998-04-12', 'Female', 'Single', 'shena.flores@email.com', 'diana.jpg', 9123456789, '12', 'Rose Street', 'AREA 2', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'Catholic', 'bill1.jpg', NULL, '$2y$10$examplehash1', NULL, 'resident', '2026-02-13 23:24:34', 'approved', NULL, NULL, NULL, NULL, 'BC26bdPn', 1, 0, NULL, '2026-02-14 00:48:37', 0, NULL, 0),
(3113, 'John', 'J.', 'Selato', '', '1995-07-23', 'Male', 'Married', 'carlo.romano@email.com', 'john.jpg', 9234567890, '45', 'Sampaguita Street', 'AREA 3', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'Christian', 'bill1.jpg', NULL, '$2y$10$examplehash2', NULL, 'resident', '2026-02-13 23:24:34', 'approved', NULL, NULL, NULL, NULL, 'B3z57dPn', 1, 0, NULL, '2026-02-14 00:46:36', 0, NULL, 0),
(3114, 'Nancy', 'Mediola', 'Zubiri', '', '2000-11-05', 'Female', 'Single', 'zubiri@gmail.com', 'nancy.jpg', 9345678901, '8', 'Orchid Street', 'AREA 4', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'Baptist', 'bill1.jpg', NULL, '$2y$10$Vb9tH8V8FjM8a0nWk9dDGeD8lR1HwZT0a3LJ3gXgR1aQyJ5M5g1kK', NULL, 'resident', '2026-02-13 23:24:34', 'approved', NULL, NULL, NULL, NULL, 'BC257dCz', 1, 0, NULL, '2026-02-14 00:47:30', 0, NULL, 0),
(3115, 'Crystal Ashley', 'Tabuena', 'Natividad', '', '2004-05-29', 'Female', 'Single', 'crys.natividad16@gmail.com', 'avatar_1771026773_1000005167.png', 9296467356, '7 ', 'Garnet ', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'ROMAN CATHOLIC', '698fb7280fad6.jpg', NULL, '$2y$10$91YFTnswEYdrCTb4yiGKpeaRZKT7dOcb2970C0PSdLEq5o5lmVM5.', NULL, 'resident', '2026-02-13 23:43:36', 'approved', NULL, NULL, NULL, NULL, 'BC255Jff', 1, 0, NULL, '2026-02-14 04:34:50', 1, NULL, 0),
(3116, 'John Carlo', 'Gime', 'Romano', '', '2004-03-20', 'Male', 'Single', 'romanojohncarlo195@gmail.com', 'avatar_1771029316_A.jpg', 9923707770, 'bldg 2322', 'mrb phase 3 compound pilot', 'AREA 5', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'ROMAN CATHOLIC', '698fc14c60514.jpg', NULL, '$2y$10$jtUQLM372Ctej78/niEKEOHYE/ncT.a.HqtXSkQnCylTOZ5Siimke', NULL, 'resident', '2026-02-14 00:26:52', 'approved', NULL, NULL, NULL, NULL, 'BC259CPS', 1, 0, NULL, '2026-02-14 00:38:41', 0, NULL, 0),
(3119, 'Maria Sheena', 'Soliman', 'Bigcas', '', '2004-03-25', 'Female', 'Single', 'crystalala02@gmail.com', 'avatar_1771031381_1000005180.png', 9296467356, '5', 'Garnet', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'ROMAN CATHOLIC', '698fc8cd9004a.jpg', NULL, '$2y$10$wZ/87sYv.0S/ULtTuHymI.vug0LMbemYEqi.AghMPJbHECoLBprTG', NULL, 'resident', '2026-02-14 00:58:53', 'approved', NULL, NULL, NULL, NULL, 'BC25XEpZ', 1, 0, NULL, '2026-02-14 01:39:45', 0, NULL, 0),
(3121, 'shena', 'francisco', 'flores', '', '2003-11-21', 'Female', 'Single', 'shenaflores02103@gmail.com', NULL, 9630123142, '1025', 'A. Mariano st', 'AREA 6', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'IGLESIA NI CRISTO', '698ff5a7b839f.jpg', NULL, NULL, NULL, 'resident', '2026-02-14 04:10:15', 'pending', NULL, NULL, 'f3055781a4d94d93d91a0cb64e6ff8c52a556c65a9c69c15bd07619d37f444e3', '2026-02-15 04:10:15', 'BC25G2QL', 0, 0, NULL, '2026-02-14 04:10:15', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_electricity_readings`
--

CREATE TABLE `user_electricity_readings` (
  `reading_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meter_reading` decimal(10,2) NOT NULL,
  `bill_amount` decimal(10,2) NOT NULL,
  `reading_date` date NOT NULL,
  `bill_file_path_1` varchar(255) DEFAULT NULL,
  `bill_file_path_2` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `correction_status` enum('original','corrected') NOT NULL DEFAULT 'original',
  `is_census` tinyint(1) DEFAULT 0,
  `recorded_by_staff_id` int(11) DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0,
  `household_members` int(11) DEFAULT 1,
  `primary_appliances` text DEFAULT NULL,
  `notes_etc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_electricity_readings`
--

INSERT INTO `user_electricity_readings` (`reading_id`, `user_id`, `meter_reading`, `bill_amount`, `reading_date`, `bill_file_path_1`, `bill_file_path_2`, `created_at`, `correction_status`, `is_census`, `recorded_by_staff_id`, `recorded_by`, `is_archived`, `household_members`, `primary_appliances`, `notes_etc`) VALUES
(1, 3111, 520.75, 6000.00, '2026-01-31', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-13 23:13:42', 'original', 0, NULL, 3111, 0, 4, 'Air Conditioner (1), Refrigerator (1), Electric Fan (2), Television (1), Rice Cooker (1)', ''),
(2, 3111, 345.40, 4000.00, '2026-02-28', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-13 23:13:42', 'original', 0, NULL, 3111, 0, 4, 'Air Conditioner (1), Refrigerator (1), Electric Fan (2), Television (1), Rice Cooker (1)', 'Reduced air-conditioner usage and improved energy-saving practices after seminar.'),
(3, 3112, 480.50, 5500.00, '2026-01-31', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-13 23:23:50', 'original', 0, NULL, 3112, 0, 3, 'Aircon (1), Refrigerator (1), TV (1), Electric Fan (2)', 'High January consumption due to extended aircon usage.'),
(4, 3112, 320.20, 3900.00, '2026-02-28', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-13 23:23:50', 'original', 0, NULL, 3112, 0, 3, 'Aircon (1), Refrigerator (1), TV (1), Electric Fan (2)', 'Reduced usage after applying energy-saving practices.'),
(5, 3113, 600.80, 7200.00, '2026-01-31', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-13 23:23:50', 'original', 0, NULL, 3113, 0, 5, 'Aircon (2), Refrigerator (1), Washing Machine (1), TV (2)', 'High consumption due to multiple appliances and family size.'),
(6, 3113, 420.40, 5000.00, '2026-02-28', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-13 23:23:50', 'original', 0, NULL, 3113, 0, 5, 'Aircon (2), Refrigerator (1), Washing Machine (1), TV (2)', 'Reduced consumption after monitoring monthly kWh usage.'),
(7, 3114, 350.25, 4500.00, '2026-01-31', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-13 23:23:50', 'original', 0, NULL, 3114, 0, 2, 'Aircon (1), Refrigerator (1), Laptop (1), Electric Fan (1)', 'Moderate January usage.'),
(8, 3114, 250.10, 3200.00, '2026-02-28', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-13 23:23:50', 'original', 0, NULL, 3114, 0, 2, 'Aircon (1), Refrigerator (1), Laptop (1), Electric Fan (1)', 'Improved energy discipline and reduced AC usage.'),
(9, 3111, 610.00, 6000.00, '2025-12-31', 'uploads/bill1.jpg', 'uploads/bill2.jpg', '2026-02-14 00:21:16', 'original', 0, NULL, 3111, 0, 4, 'Air Conditioner (1), Refrigerator (1), TV (1), Electric Fan (2), Rice Cooker (1)', 'High usage due to holiday season in December.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`challenge_id`);

--
-- Indexes for table `challenge_submissions`
--
ALTER TABLE `challenge_submissions`
  ADD PRIMARY KEY (`submission_id`);

--
-- Indexes for table `live_chat`
--
ALTER TABLE `live_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_sessions`
--
ALTER TABLE `live_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_fund_requests`
--
ALTER TABLE `my_fund_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seminars`
--
ALTER TABLE `seminars`
  ADD PRIMARY KEY (`seminar_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_electricity_readings`
--
ALTER TABLE `user_electricity_readings`
  ADD PRIMARY KEY (`reading_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `challenges`
--
ALTER TABLE `challenges`
  MODIFY `challenge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `challenge_submissions`
--
ALTER TABLE `challenge_submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `live_chat`
--
ALTER TABLE `live_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `live_sessions`
--
ALTER TABLE `live_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `my_fund_requests`
--
ALTER TABLE `my_fund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `seminars`
--
ALTER TABLE `seminars`
  MODIFY `seminar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3122;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
