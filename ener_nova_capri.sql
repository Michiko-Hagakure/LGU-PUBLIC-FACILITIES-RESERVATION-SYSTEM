-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 13, 2026 at 05:20 AM
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
  `type` enum('video','poster','news','article') NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','archived') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `type`, `title`, `content`, `link_url`, `media_path`, `created_at`, `status`) VALUES
(16, 'news', 'Challenges and prospects of the energy transition in the Philippines', 'The Philippines faces a critical challenge in meeting its energy transition targets while balancing economic,geopolitical, and environmental realities. The country has pledged to cut greenhouse gas emissions by 75%by 2030 (dependent on international support) and aims to raise renewables’ share in its energy mix to 35% by2030 and 50% by 2040. However, these goals are undermined by the continued dominance of fossil fuels,with coal and natural gas still supplying over 75% of the nation’s energy.', 'https://philippinesinstitute.anu.edu.au/content-centre/research/challenges-and-prospects-energy-transition-Philippines', 'uploads/avatar_1770485684_qc.jpg', '2026-02-06 18:42:15', 'active');

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

--
-- Dumping data for table `challenges`
--

INSERT INTO `challenges` (`challenge_id`, `title`, `description`, `start_date`, `end_date`, `target_area`, `banner_image`, `proof_type`, `keyword`, `status`, `created_by`, `created_at`, `is_finalized`) VALUES
(12, 'HAHA', 'HAHA', '2026-02-13', '2026-02-19', 'All', '1770902084_jeyls.jpg', 'video', 'hahaha', 'Published', 3048, '2026-02-12 13:14:44', 0),
(13, 'TAPAT MO LINIS MO', 'LINIS TIME', '2026-02-13', '2026-02-14', 'AREA 1', '1770829181_bg1.jpg', 'video', 'CLEANLINESS', 'Published', 3046, '2026-02-12 18:17:57', 0),
(14, 'challenge', 'wqeq', '2026-02-13', '2026-02-18', 'All', '1770920964_bg1.jpg', 'video', 'sda', 'Published', 3048, '2026-02-12 18:29:24', 0);

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
(9, 12, 3111, 'res_1770902095_energysaving.mp4', 'Approved', '2026-02-12 13:14:55', 'Standard', NULL, NULL, 0, NULL, NULL, NULL),
(10, 13, 3132, 'res_1770920377_energysaving.mp4', 'Approved', '2026-02-12 18:19:37', 'Finalist', 1, NULL, 1, '1770920657_proof1_2.jpg', '1770920657_proof2_10.jpg', '2026-02-13 02:24:17');

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

--
-- Dumping data for table `correction_details`
--

INSERT INTO `correction_details` (`request_id`, `user_id`, `requested_data`, `reason_for_change`, `status`, `admin_remarks`, `created_at`, `admin_feedback`, `proof_attachment`) VALUES
(26, 3132, '{\"first_name\":{\"old\":\"SOfia\",\"new\":\"Sofia\"},\"middle_name\":{\"old\":\"Lorraine\",\"new\":\"Lorraine\"},\"last_name\":{\"old\":\"Signo\",\"new\":\"Signo\"},\"birthdate\":{\"old\":\"2006-09-15\",\"new\":\"2006-09-15\"},\"house_number\":{\"old\":\"21\",\"new\":\"10\"},\"street\":{\"old\":\"Lily St\",\"new\":\"Lily St\"},\"area\":{\"old\":\"AREA 1\",\"new\":\"AREA 1\"}}', 'gusto ko lang', 'approved', NULL, '2026-02-12 17:04:38', 'Your request has been processed by the admin.', '[\"proof_1770915878_2.jpg\",\"proof_1770915878_10.jpg\"]');

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

--
-- Dumping data for table `correction_requests`
--

INSERT INTO `correction_requests` (`request_id`, `user_id`, `reading_id`, `reason`, `requested_kwh`, `requested_bill_amount`, `requested_reading_date`, `new_bill_file_path_1`, `new_bill_file_path_2`, `status`, `requested_at`, `reviewed_at`) VALUES
(3, 3132, 42, 'mali ng upload', 240.00, 500.00, '2026-02-13', 'uploads/bills/3132_1770916972_1220_10.jpg', 'uploads/bills/3132_1770916972_9278_10.jpg', 'pending', '2026-02-12 17:22:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `live_chat`
--

CREATE TABLE `live_chat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `session_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_chat`
--

INSERT INTO `live_chat` (`id`, `user_id`, `name`, `message`, `created_at`, `session_id`) VALUES
(36, 3132, 'Sofia Signo', 'wow', '2026-02-12 18:09:16', 32);

-- --------------------------------------------------------

--
-- Table structure for table `live_sessions`
--

CREATE TABLE `live_sessions` (
  `id` int(11) NOT NULL,
  `youtube_id` varchar(255) NOT NULL,
  `total_viewers` int(11) DEFAULT 0,
  `viewer_list` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'finished',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_sessions`
--

INSERT INTO `live_sessions` (`id`, `youtube_id`, `total_viewers`, `viewer_list`, `status`, `created_at`) VALUES
(30, '{\"id\":\"2QkvUABk7HE\",\"platform\":\"youtube\"}', 3, 'Aida Plojo, Christian Cando, Sofia Signo', 'finished', '2026-02-12 18:05:33'),
(31, '{\"id\":\"2QkvUABk7HE\",\"platform\":\"youtube\"}', 3, 'Aida Plojo, Christian Cando, Sofia Signo', 'finished', '2026-02-12 18:08:34'),
(32, '{\"id\":\"XZMuSgEPf_M\",\"platform\":\"youtube\"}', 3, 'Aida Plojo, Christian Cando, Sofia Signo', 'finished', '2026-02-12 18:09:40');

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
  `facility_name` varchar(255) DEFAULT NULL,
  `equipment` text DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `schedule_start_time` varchar(50) DEFAULT NULL,
  `schedule_end_time` varchar(50) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `my_fund_requests`
--

INSERT INTO `my_fund_requests` (`id`, `government_id`, `user_id`, `amount`, `purpose`, `logistics`, `status`, `created_at`, `feedback`, `seminar_info`, `seminar_image`, `seminar_id`, `approved_amount`, `facility_name`, `equipment`, `schedule_date`, `schedule_start_time`, `schedule_end_time`, `admin_notes`) VALUES
(25, 26, 82, 50000.00, 'IEC Materials', 'Categories: A. Venue & Physical, B. Audio-Visual, C. Speakers/Services, F. IT Systems | Specifics: chairs, tables, extra computers', 'Approved', '2026-01-29 09:50:34', 'uki', 'Energy Conservation Awareness  (2025-10-23)', 'assets/seminar_img/10.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 27, 82, 10000.00, 'Tech Services', 'Categories: A. Venue & Physical, C. Speakers/Services, E. Food & Welfare | Specifics: lahat boss', 'Rejected', '2026-01-29 10:28:06', 'auq', 'Energy Conservation Awareness  (2025-10-23)', 'assets/seminar_img/10.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 28, 82, 135.00, 'Seminar Logistics', 'Categories: A. Venue & Physical | Specifics: FIANGE', 'Approved', '2026-01-29 11:21:49', 'dsf', 'Sampol (2026-01-29)', 'assets/seminar_img/9.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 31, 82, 55555.00, 'Tech Services', 'Categories: A. Venue & Physical, C. Speakers/Services, D. IEC Materials, E. Food & Welfare, F. IT Systems | Specifics: TEST TEST TEST', 'Approved', '2026-01-31 04:49:01', '', 'Energy Conservation Awareness  (2025-10-23)', 'assets/seminar_img/10.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 32, 82, 1000.00, 'Tech Services', 'Categories: D. IEC Materials, F. IT Systems, L. Contingency | Specifics: TETETESTSTTST', 'Approved', '2026-01-31 04:49:48', 'qweqeqeq', 'Energy Conservation Awareness  (2025-10-23)', 'assets/seminar_img/10.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 33, 82, 77777.00, 'IEC Materials', 'Categories: A. Venue & Physical | Specifics: This', 'Approved', '2026-02-01 10:11:39', '', 'ZXC (2026-01-31)', 'assets/seminar_img/10.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 34, 82, 1231321.00, 'IEC Materials', 'Categories: B. Audio-Visual | Specifics: wq', 'pending', '2026-02-01 12:16:20', NULL, 'ZXC (2026-01-31)', 'assets/seminar_img/10.jpg', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 35, 3048, 341.00, 'Tech Services', 'Categories: A. Venue & Physical | Specifics: qwe', 'Approved', '2026-02-02 11:52:17', '', 'ZXC (2026-01-31)', 'assets/seminar_img/10.jpg', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 3, 3048, 0.00, 'The purpose of an energy efficiency seminar is to educate participants on the principles, strategies, and best practices of energy efficiency and conservation. These seminars aim to improve knowledge and skills in energy management, monitoring, reporting, and verification, as well as to introduce municipal energy management as part of the national monitoring, reporting, and verification system. They also cover energy audits, multicriteria analysis, and best practices for funding energy efficiency measures. By providing these insights, seminars help participants understand the importance of energy efficiency in reducing carbon emissions and improving overall energy system efficiency. \r\nrewrap.eu', NULL, 'pending', '2026-02-13 04:30:57', NULL, 'KURYENTIPID', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 4, 3048, 0.00, 'seminar', NULL, 'pending', '2026-02-13 04:58:48', NULL, 'KURYENTIPID', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
(5, 'KURYENTIPID', '2026-02-13', '06:51:00', '08:51:00', 'SEMINAR', 'Multi-purpose Hall', 'ALL AREAS', NULL, '2026-02-12 17:52:11', 'uploads/thumbnails/custom_1770918731.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
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
(3048, 'Christian', 'Antonio', 'Cando', '', '1990-02-27', 'Male', 'Single', 'lcristianmarkangelo@gmail.com', 'avatar_1770905507_logo.jpg', 9563698586, '30', 'Camia', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'BORN AGAIN CHRISTIAN', '698081e09266f.jpeg', NULL, '$2y$10$YAG18drhOZ.gIvDOGDgq8./aEd8eN8VWQz5GxtshdcSQE6XJF1x7O', NULL, 'admin', '2026-02-02 02:52:16', 'approved', NULL, NULL, NULL, NULL, 'BC257JjS', 1, 0, NULL, '2026-02-13 05:17:32', 0, NULL, 0),
(3132, 'Sofia', 'Lorraine', 'Signo', '', '2006-09-15', 'Female', 'Single', 'manining.commonwealth.gov@gmail.com', NULL, 9085919898, '10', 'Lily St', 'AREA 1', 'Owned', 'MERALCO BILL/ELECTRICITY BILL', 'BORN AGAIN CHRISTIAN', '698e00dbc8ee4.jpg', NULL, '$2y$10$.XVM2CYjy3ZE/eUoliEsO.f/YUCvVXL45xr/tsoNcsaRK7XvW8ofW', NULL, 'resident', '2026-02-12 16:33:31', 'approved', NULL, NULL, NULL, NULL, 'BC25Uhnh', 1, 0, NULL, '2026-02-12 18:15:47', 0, NULL, 0);

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
(44, 3132, 232.00, 232.00, '2026-02-12', 'uploads/bills/1770917920_front_3132.jpg', 'uploads/bills/1770917920_back_3132.jpg', '2026-02-12 17:38:40', 'original', 1, 3046, NULL, 0, 1, NULL, 'may problem sa bills');

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
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `challenge_id` (`challenge_id`);

--
-- Indexes for table `correction_details`
--
ALTER TABLE `correction_details`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `correction_history`
--
ALTER TABLE `correction_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `correction_requests`
--
ALTER TABLE `correction_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reading_id` (`reading_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_electricity_readings`
--
ALTER TABLE `user_electricity_readings`
  ADD PRIMARY KEY (`reading_id`),
  ADD KEY `user_id_idx` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `challenges`
--
ALTER TABLE `challenges`
  MODIFY `challenge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `challenge_submissions`
--
ALTER TABLE `challenge_submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `correction_details`
--
ALTER TABLE `correction_details`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `correction_history`
--
ALTER TABLE `correction_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `correction_requests`
--
ALTER TABLE `correction_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `live_chat`
--
ALTER TABLE `live_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `live_sessions`
--
ALTER TABLE `live_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `my_fund_requests`
--
ALTER TABLE `my_fund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `seminars`
--
ALTER TABLE `seminars`
  MODIFY `seminar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3133;

--
-- AUTO_INCREMENT for table `user_electricity_readings`
--
ALTER TABLE `user_electricity_readings`
  MODIFY `reading_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `challenge_submissions`
--
ALTER TABLE `challenge_submissions`
  ADD CONSTRAINT `challenge_submissions_ibfk_1` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`challenge_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
