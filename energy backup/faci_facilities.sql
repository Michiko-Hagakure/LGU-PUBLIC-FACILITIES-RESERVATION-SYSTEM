-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 01, 2026 at 11:23 AM
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
-- Database: `faci_facilities`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('general','maintenance','event','urgent','facility_update') NOT NULL DEFAULT 'general',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `target_audience` enum('all','citizens','admins') NOT NULL DEFAULT 'all',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `type`, `priority`, `target_audience`, `is_active`, `is_pinned`, `start_date`, `end_date`, `created_by`, `attachment_path`, `additional_info`, `created_at`, `updated_at`) VALUES
(1, 'Welcome to LGU Facility Reservation System', 'We are pleased to announce the launch of our new online facility reservation system. You can now book facilities, make payments, and track your reservations all in one place! Experience a seamless booking process with real-time availability checking and instant confirmation.', 'general', 'high', 'citizens', 1, 1, '2025-11-20', '2026-02-20', 1, NULL, 'For any questions or assistance, please contact our support team at support@lgu.gov.ph', '2025-11-20 06:39:14', '2025-11-20 06:39:14'),
(2, 'Facility Maintenance Schedule - December 2025', 'Please be advised that the Main Conference Hall will undergo scheduled maintenance from December 15-20, 2025. The facility will be temporarily unavailable for booking during this period. We apologize for any inconvenience this may cause.', 'maintenance', 'medium', 'all', 1, 0, '2025-11-20', NULL, 1, NULL, 'Maintenance includes electrical upgrades, air conditioning servicing, and interior refurbishment.', '2025-11-20 06:39:14', '2025-11-20 06:39:14'),
(3, 'Special Holiday Rates - Christmas Season', 'Enjoy special discounted rates for facility bookings during the Christmas season! Book now and get 20% off on all venues from December 1-31, 2025. Perfect for your holiday parties and celebrations. Limited slots available!', 'event', 'medium', 'citizens', 1, 0, '2025-11-20', '2025-12-31', 1, NULL, NULL, '2025-11-20 06:39:14', '2025-11-20 06:39:14'),
(4, 'New Facility Added: Sports Complex', 'We are excited to announce the addition of our brand new Sports Complex to the reservation system. Features include basketball courts, volleyball courts, badminton courts, and a fully-equipped fitness center. Book now and enjoy world-class sports facilities!', 'facility_update', 'high', 'all', 1, 1, '2025-11-20', NULL, 1, NULL, 'Opening special: First 50 bookings get 30% discount!', '2025-11-20 06:39:14', '2025-11-20 06:39:14'),
(5, 'URGENT: Payment Deadline Extension', 'Due to technical issues, we are extending the payment deadline for all pending reservations by 48 hours. Please ensure your payments are settled before the new deadline to avoid cancellation.', 'urgent', 'urgent', 'citizens', 1, 0, '2025-11-20', '2025-11-23', 1, NULL, NULL, '2025-11-20 06:39:14', '2025-11-20 06:39:14'),
(6, 'New Equipment Available for Rent', 'Great news! We have added new equipment to our inventory including premium sound systems, LED screens, and professional lighting equipment. Perfect for your events and conferences.', 'general', 'low', 'all', 1, 0, '2025-11-20', NULL, 1, NULL, 'Check our equipment catalog for pricing and availability.', '2025-11-15 06:39:14', '2025-11-15 06:39:14');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `base_rate` decimal(10,2) NOT NULL COMMENT 'Base facility rate (3 hours)',
  `extension_rate` decimal(10,2) DEFAULT NULL COMMENT 'Extension charges',
  `equipment_total` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Total equipment costs',
  `subtotal` decimal(10,2) NOT NULL COMMENT 'Before discounts',
  `city_of_residence` varchar(255) DEFAULT NULL COMMENT 'User city for resident discount',
  `is_resident` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Eligible for 30% resident discount',
  `resident_discount_rate` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT '30% for residents',
  `resident_discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `special_discount_type` varchar(255) DEFAULT NULL COMMENT 'senior, pwd, student',
  `special_discount_id_path` varchar(255) DEFAULT NULL COMMENT 'Uploaded ID for verification',
  `special_discount_rate` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Additional 20%',
  `special_discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL COMMENT 'Final amount to pay',
  `purpose` text DEFAULT NULL COMMENT 'Event purpose/description',
  `expected_attendees` int(11) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `valid_id_type` varchar(255) DEFAULT NULL COMMENT 'Type of ID uploaded (SSS, UMID, etc.)',
  `valid_id_front_path` varchar(255) DEFAULT NULL,
  `valid_id_back_path` varchar(255) DEFAULT NULL,
  `valid_id_selfie_path` varchar(255) DEFAULT NULL,
  `supporting_doc_path` varchar(255) DEFAULT NULL COMMENT 'Additional documents',
  `rejected_reason` text DEFAULT NULL,
  `canceled_reason` text DEFAULT NULL,
  `canceled_at` timestamp NULL DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `applicant_email` varchar(255) DEFAULT NULL,
  `applicant_phone` varchar(255) DEFAULT NULL,
  `applicant_address` text DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `staff_verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `staff_verified_at` timestamp NULL DEFAULT NULL,
  `staff_notes` text DEFAULT NULL,
  `admin_approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_approved_at` timestamp NULL DEFAULT NULL,
  `admin_approval_notes` text DEFAULT NULL,
  `reserved_until` timestamp NULL DEFAULT NULL COMMENT '24-hour hold expires at this time',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `facility_id`, `start_time`, `end_time`, `base_rate`, `extension_rate`, `equipment_total`, `subtotal`, `city_of_residence`, `is_resident`, `resident_discount_rate`, `resident_discount_amount`, `special_discount_type`, `special_discount_id_path`, `special_discount_rate`, `special_discount_amount`, `total_discount`, `total_amount`, `purpose`, `expected_attendees`, `special_requests`, `valid_id_type`, `valid_id_front_path`, `valid_id_back_path`, `valid_id_selfie_path`, `supporting_doc_path`, `rejected_reason`, `canceled_reason`, `canceled_at`, `user_name`, `applicant_name`, `applicant_email`, `applicant_phone`, `applicant_address`, `event_name`, `event_description`, `event_date`, `status`, `staff_verified_by`, `staff_verified_at`, `staff_notes`, `admin_approved_by`, `admin_approved_at`, `admin_approval_notes`, `reserved_until`, `created_at`, `updated_at`, `expired_at`, `deleted_at`, `deleted_by`) VALUES
(3, 5, 12, '2025-12-27 08:00:00', '2025-12-27 13:00:00', 7000.00, 1000.00, 51200.00, 59200.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 11840.00, 11840.00, 47360.00, 'Sports Event', 50, NULL, 'School ID', 'bookings/valid_ids/front/UmDMG5ekiJcEu1Eta2lkytHpoMEw9WTdz8QeEMHO.jpg', 'bookings/valid_ids/back/tK1cSfRsOL1rfAdUBqvt20kWoHYPFEtnP8XDrlwI.jpg', 'bookings/valid_ids/selfie/1vFsLEbiy62EkCR7rHzwsCF1EDDjMXIDUO52JoIc.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 3, '2025-12-22 23:05:36', NULL, 2, '2025-12-20 21:43:13', NULL, NULL, '2025-12-18 23:07:59', '2025-12-27 21:19:26', NULL, NULL, NULL),
(4, 5, 11, '2026-01-02 08:00:00', '2026-01-02 13:00:00', 4050.00, 600.00, 0.00, 4650.00, 'Quezon City', 0, 0.00, 0.00, 'senior', NULL, 20.00, 930.00, 930.00, 3720.00, 'Wedding Reception', 30, NULL, 'Senior Citizen ID', 'bookings/valid_ids/front/a6Jwc3Fz7ykmuODz9Rxd4YOIns0JW8kG4MXVDXau.jpg', 'bookings/valid_ids/back/06zURp2MmpZDgH0eKdPybtNClVxiscJJfr26liNj.jpg', 'bookings/valid_ids/selfie/VKWm1biv7mf9euvnIpTpT76YOGkr5fj87iSa2agp.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 3, '2025-12-25 01:36:50', NULL, 2, '2025-12-27 07:57:03', NULL, NULL, '2025-12-24 23:51:41', '2026-01-03 05:55:02', NULL, NULL, NULL),
(5, 5, 13, '2026-01-05 08:00:00', '2026-01-05 11:00:00', 7250.00, 0.00, 4000.00, 11250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 11250.00, 'Educational Workshop', 50, NULL, 'SSS ID', 'bookings/valid_ids/front/YZzhEExri5HZYddmAhkpWDm8Ufrp7Vk0aoNV9WI6.jpg', 'bookings/valid_ids/back/AMSUToKjpAHLdaQZhTrukakweKf5bGpHIbStDI2S.jpg', 'bookings/valid_ids/selfie/GPjJY8xnPvEnsfAn52Rx1l4NawG68j3Nbu0LzMS4.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2025-12-27 07:25:28', NULL, 2, '2025-12-27 07:56:44', NULL, NULL, '2025-12-27 07:24:32', '2025-12-27 07:56:44', NULL, NULL, NULL),
(6, 5, 13, '2026-01-05 13:00:00', '2026-01-05 18:00:00', 5075.00, 875.00, 10300.00, 16250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 16250.00, 'Charity Event', 35, NULL, 'PhilHealth ID', 'bookings/valid_ids/front/MHmQK4HZYkCY5mfmFr0h6WE7L7zYZdEZhmAdzxEE.jpg', 'bookings/valid_ids/back/KJPASj4DUJHkIusGGYPBDkj5EJiM9YUtdPRBA3tk.jpg', 'bookings/valid_ids/selfie/w3LnMkc2AIu8mpyEbNj3rGy79Q0UmvxaPB5Rp2ID.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2025-12-27 22:43:22', NULL, 2, '2025-12-29 09:39:30', NULL, NULL, '2025-12-27 22:42:19', '2025-12-29 09:39:30', NULL, NULL, NULL),
(7, 5, 11, '2026-01-05 08:00:00', '2026-01-05 13:00:00', 6750.00, 1000.00, 48050.00, 55800.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 55800.00, 'Cultural Event', 50, 'Please arrange and prepare the equipment well.\r\n\r\nThank you!', 'Passport', 'bookings/valid_ids/front/RrR1HQcPYqwSuea3khwPElqvFzrmC9vna9uzI4wm.jpg', 'bookings/valid_ids/back/ZAyZsekJmpz05cuSWvILFZuf6wQldVzgP0CfxJWl.jpg', 'bookings/valid_ids/selfie/zUYcct6mmPu9zLbKc1cyvlAKVZmoPGMO0sLPyO8D.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2025-12-28 05:22:45', NULL, 2, '2025-12-28 06:02:37', NULL, NULL, '2025-12-28 04:24:57', '2025-12-28 06:02:37', NULL, NULL, NULL),
(8, 5, 14, '2026-01-06 08:00:00', '2026-01-06 11:00:00', 19500.00, 0.00, 44600.00, 64100.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 64100.00, 'Boxing Match', 150, 'Let\'s Gooooo.', 'SSS ID', 'bookings/valid_ids/front/J0WLEYduNKIxmjuoBHhaXv9RzqjC4r4RWBnEiJvV.jpg', 'bookings/valid_ids/back/LKdUinnrggXhurC60rsCvhWXQIyLlqAsgvInazr4.jpg', 'bookings/valid_ids/selfie/EGMvrjajDi9cFEJOQ34guFRq3mPfJi2b0BZzVpBW.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2025-12-28 09:14:14', NULL, 2, '2025-12-29 23:20:50', NULL, NULL, '2025-12-28 08:31:14', '2025-12-29 23:20:50', NULL, NULL, NULL),
(9, 5, 14, '2026-01-06 13:00:00', '2026-01-06 16:00:00', 6500.00, 0.00, 0.00, 6500.00, 'Quezon City', 0, 0.00, 0.00, 'senior', NULL, 20.00, 1300.00, 1300.00, 5200.00, 'Family Reunion', 50, NULL, 'Senior Citizen ID', 'bookings/valid_ids/front/r1yc7Sw63XRTNpGgTlW71VuJ1vU84CE5WYSMzV2k.jpg', 'bookings/valid_ids/back/fX6DlRG1Dtlk6IORagZcrZK4q4rthfjGcjIGiG0m.jpg', 'bookings/valid_ids/selfie/YzM5cT1r1jJn2ER1Y8L4pJife1Z07fEyZFocmRI7.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2025-12-28 09:14:59', NULL, 2, '2025-12-29 23:20:22', NULL, NULL, '2025-12-28 08:58:30', '2025-12-29 23:20:22', NULL, NULL, NULL),
(10, 5, 14, '2026-01-07 08:00:00', '2026-01-07 13:00:00', 3250.00, 500.00, 0.00, 3750.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 750.00, 750.00, 3000.00, 'Sports Event', 25, NULL, 'School ID', 'bookings/valid_ids/front/EqLx4aE87e7pW2ZfTFlhVKIvcIvf9fslBpgVtc6B.jpg', 'bookings/valid_ids/back/hhzQUZNn8bgghFMe6l92O9RwDXaDCAMnTWzscnLB.jpg', 'bookings/valid_ids/selfie/QVTEMKkJ4rav7KaoZ7GVIgP9R8KOtaf2kalUnCak.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'refunded', 3, '2025-12-29 23:15:25', NULL, 2, '2025-12-29 23:19:43', NULL, NULL, '2025-12-29 23:13:12', '2026-01-06 06:45:42', NULL, NULL, NULL),
(11, 5, 13, '2026-01-07 08:00:00', '2026-01-07 11:00:00', 5075.00, 0.00, 0.00, 5075.00, 'Quezon City', 0, 0.00, 0.00, 'senior', NULL, 20.00, 1015.00, 1015.00, 4060.00, 'Company Seminar/Training', 35, NULL, 'Senior Citizen ID', 'bookings/valid_ids/front/wkHOe0Y3zUEfVXV6IVBKH9ROwAtEKztwA1Xlz83m.jpg', 'bookings/valid_ids/back/TG1auN7tohLSgBgaxIelqNowS4YpXDm5icwV4HKZ.jpg', 'bookings/valid_ids/selfie/8St6WNPsJSpx09P3Pwq9wjnfmcRYlHQhHjMYsn9o.jpg', NULL, 'Wrong id', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rejected', 3, '2025-12-31 21:26:12', NULL, NULL, NULL, NULL, NULL, '2025-12-29 23:28:35', '2025-12-31 21:26:12', NULL, NULL, NULL),
(12, 5, 11, '2026-01-09 08:00:00', '2026-01-09 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 810.00, 810.00, 3240.00, 'Birthday Celebration', 30, NULL, 'School ID', 'bookings/valid_ids/front/CF2hDaE5hVmfPBdZnQjeOnSCVTIzfhNej3HxIHPV.jpg', 'bookings/valid_ids/back/9AjymiqenuSvvhZ9BvJVtTexWRp37TZeq6tIltyY.jpg', 'bookings/valid_ids/selfie/HnNLOnDbEc1fYIDWbNAlB0cXgHXKWeGCN3tKOhLz.jpg', NULL, 'Wrong id', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rejected', 3, '2025-12-31 21:37:21', NULL, NULL, NULL, NULL, NULL, '2025-12-31 21:15:27', '2025-12-31 21:37:21', NULL, NULL, NULL),
(13, 5, 13, '2026-01-09 08:00:00', '2026-01-09 11:00:00', 5075.00, 0.00, 0.00, 5075.00, 'Quezon City', 0, 0.00, 0.00, 'pwd', NULL, 20.00, 1015.00, 1015.00, 4060.00, 'Birthday Celebration', 35, NULL, 'PWD ID', 'bookings/valid_ids/front/dypRFSx4PSV5KeQ77fkltOWGa5lc6BUys0vM2yLg.jpg', 'bookings/valid_ids/back/GBbiRHeSBd9tsBTwhw49td6Ix7HVuV5OwHiUoYfx.jpg', 'bookings/valid_ids/selfie/7zYPWKFZBp4J2gbtueoAQRBrnyMuSkesluwQRQGB.jpg', NULL, 'Wrong date', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-31 21:40:01', '2025-12-31 21:40:28', NULL, NULL, NULL),
(14, 5, 14, '2026-01-09 08:00:00', '2026-01-09 11:00:00', 3250.00, 0.00, 0.00, 3250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 3250.00, 'Birthday Celebration', 25, NULL, 'SSS ID', 'bookings/valid_ids/front/2aw3x07JpXl6uHfEjoFykF85FG4u2A4AURFXLtlN.jpg', 'bookings/valid_ids/back/QFJP2od0xa6onYhUEy3JEF54eJXItyjrbHGoGUel.jpg', 'bookings/valid_ids/selfie/u9Pi3f4plWjUompYxI0UcG4hTTpD2lpjGKg8EcCR.jpg', NULL, 'Wrong date', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-31 21:46:14', '2025-12-31 21:46:45', NULL, NULL, NULL),
(15, 5, 11, '2026-01-15 08:00:00', '2026-01-15 11:00:00', 4050.00, 0.00, 2000.00, 6050.00, 'Quezon City', 0, 0.00, 0.00, 'pwd', NULL, 20.00, 1210.00, 1210.00, 4840.00, 'Wala lang', 30, NULL, 'PWD ID', 'bookings/valid_ids/front/aqPFsFtbaabTMdC663eLmp83LgRmzLDi8QNa0PI3.jpg', 'bookings/valid_ids/back/dEHFY7maClbekAhZn9iTY2DRrGkPooHpIhesqjlI.jpg', 'bookings/valid_ids/selfie/nRQLGuwIEWa3Me0SCh4oSNc06lmdcTuxQy6CtVhH.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2026-01-07 03:20:35', NULL, 2, '2026-01-07 03:58:46', NULL, NULL, '2026-01-06 18:44:05', '2026-01-07 03:58:46', NULL, NULL, NULL),
(16, 5, 11, '2026-01-21 08:00:00', '2026-01-21 11:00:00', 4455.00, 0.00, 0.00, 4455.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4455.00, 'Wedding Reception', 33, NULL, 'SSS ID', 'bookings/valid_ids/front/il0YlzEUnwh6PkiiLOsno0V0CUQxhsNGo6prVKpI.png', 'bookings/valid_ids/back/1sHbvPEBs3VmzYTyHDHskdNCFle0pRZKm7Nb0tl2.png', 'bookings/valid_ids/selfie/zlZjzIfnOmSHZOJhWVrBYVONC6WbiqKKdTo5vQEF.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2026-01-13 03:03:05', NULL, 2, '2026-01-13 03:07:19', NULL, NULL, '2026-01-13 03:01:12', '2026-01-13 03:07:19', NULL, NULL, NULL),
(17, 5, 13, '2026-01-21 08:00:00', '2026-01-21 11:00:00', 5510.00, 0.00, 0.00, 5510.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 5510.00, 'Birthday Celebration', 38, NULL, 'SSS ID', 'bookings/valid_ids/front/zqXzLjohE6pJA9N7UJJ1QHkQRJjU5smkD26PkjLx.png', 'bookings/valid_ids/back/VfLiSwHrdPQMetzzVyyhLZCptAOuJx5UdIv7Ho0M.png', 'bookings/valid_ids/selfie/AqwxuhD5EzkATtC6M1Hq7HJLzoFAOMs7z7msDIxX.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2026-01-13 03:14:41', NULL, 2, '2026-01-13 03:17:30', NULL, NULL, '2026-01-13 03:13:28', '2026-01-13 03:17:30', NULL, NULL, NULL),
(18, 5, 14, '2026-01-22 08:00:00', '2026-01-22 11:00:00', 3250.00, 0.00, 20000.00, 23250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 23250.00, 'Birthday Celebration', 25, NULL, 'SSS ID', 'bookings/valid_ids/front/KUAwCsDLjo77aUHuiZFrQTVqinok2SQVPkYOb7ib.png', 'bookings/valid_ids/back/tC2Rw7QhoEPorKsk7hLoV6Rp9HLikh6d3bsRPjO7.png', 'bookings/valid_ids/selfie/vE0aqXCyTL1spK1Qla5dYBYdxqQfe9saOEdjF6xU.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2026-01-13 19:19:26', NULL, 2, '2026-01-13 19:23:14', NULL, NULL, '2026-01-13 19:16:39', '2026-01-13 19:23:14', NULL, NULL, NULL),
(19, 5, 11, '2026-01-22 08:00:00', '2026-01-22 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/PrReX91J2PROVmlhzzWEMd4UzXoQsZ7UcMV8UCZI.png', 'bookings/valid_ids/back/NmqpAOAxmNG9g1ve9pkfAp9PIQkUDVwxPnVKk2UI.png', 'bookings/valid_ids/selfie/Q53paABKWVZIGpkLBDbuiPydHP52f6AF7ipegJJ3.png', NULL, NULL, 'Payment deadline exceeded', '2026-01-16 21:25:35', 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'canceled', 3, '2026-01-13 19:30:28', NULL, NULL, NULL, NULL, NULL, '2026-01-13 19:27:54', '2026-01-16 21:25:35', NULL, NULL, NULL),
(20, 5, 11, '2026-01-22 13:00:00', '2026-01-22 16:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/RChKnqwq4Eedg3fP4waGP3wE6Ow6bpKiWIrTLE5D.png', 'bookings/valid_ids/back/G2gurtZYQSdsCgofltf2NaEPlKBvS0QF2xjRrtxG.png', 'bookings/valid_ids/selfie/hkBCiraZFGzc6QW1JcumQKHG3leIkMdp2sOjMJDC.png', NULL, NULL, 'Payment deadline exceeded', '2026-01-16 21:25:35', 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'canceled', 3, '2026-01-13 19:34:12', NULL, NULL, NULL, NULL, NULL, '2026-01-13 19:33:15', '2026-01-16 21:25:35', NULL, NULL, NULL),
(21, 5, 11, '2026-01-22 18:00:00', '2026-01-22 21:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/EX74RHAog3aPaQ9vIYNe6QWFSGj9Hlfps7SZQPN8.png', 'bookings/valid_ids/back/8GZgFDuH0NB7Pd5c99w5mUDZnBxHUAUULsQbx4My.png', 'bookings/valid_ids/selfie/VERadGBxFnnZ5yrxr0iH36IEI4UFYnVAt4Ho6brB.png', NULL, NULL, 'Payment deadline exceeded', '2026-01-16 21:25:35', 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'canceled', 3, '2026-01-13 19:37:01', NULL, NULL, NULL, NULL, NULL, '2026-01-13 19:36:09', '2026-01-16 21:25:35', NULL, NULL, NULL),
(22, 5, 11, '2026-01-26 08:00:00', '2026-01-26 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/jpLjgTTINuGNQoLvOwq5u9wKMOC1JXlds91FXr5C.png', 'bookings/valid_ids/back/0przzM5OVAUM78cdm73ZckJAjc5ohxQBw413GhLf.png', 'bookings/valid_ids/selfie/NDbwuPt1E7MpDCFDqa6omHrYlyBhwkp3YctYUx1M.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'staff_verified', 3, '2026-01-16 21:10:50', NULL, NULL, NULL, NULL, NULL, '2026-01-16 20:40:21', '2026-01-16 21:10:50', NULL, NULL, NULL),
(23, 5, 13, '2026-01-26 08:00:00', '2026-01-26 11:00:00', 5075.00, 0.00, 0.00, 5075.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 5075.00, 'Wedding Reception', 35, NULL, 'SSS ID', 'bookings/valid_ids/front/XYL6TtEvNdpn8f0XA4QWl5XTBImLZfH2nAefs62T.png', 'bookings/valid_ids/back/T2I3kCIBJKYu5Ud0izD4DPVa4m0fTKti0BdSHf5H.png', 'bookings/valid_ids/selfie/tXwadIXCAjAZyvLJnTzNpyHD2ang7a55BiUHUHak.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2026-01-16 21:59:38', NULL, NULL, NULL, NULL, NULL, '2026-01-16 20:49:43', '2026-01-28 00:34:15', NULL, NULL, NULL),
(24, 5, 14, '2026-01-26 08:00:00', '2026-01-26 11:00:00', 3250.00, 0.00, 0.00, 3250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 3250.00, 'Charity Event', 25, NULL, 'SSS ID', 'bookings/valid_ids/front/SOhZ0pOzjQJyX33be30jByU9aK2z7No4a5lNIGeY.png', 'bookings/valid_ids/back/Pc0cuCAQV5ODny5Wh6YrYn2sxWBU3IODN1XROoYY.png', 'bookings/valid_ids/selfie/wRPaBPaGRLGVlKOXAWuJoWvfq6IXc7zJW5349dzw.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2026-01-16 21:05:41', NULL, 2, '2026-01-16 21:15:50', NULL, NULL, '2026-01-16 21:03:00', '2026-01-16 21:15:50', NULL, NULL, NULL),
(25, 5, 12, '2026-02-05 08:00:00', '2026-02-05 11:00:00', 7000.00, 0.00, 0.00, 7000.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 1400.00, 1400.00, 5600.00, 'Birthday Celebration', 50, NULL, 'School ID', 'bookings/valid_ids/front/UW0BduhqczcTbKKyUCiGr3tKTXDKNbj3jS2Uruac.png', 'bookings/valid_ids/back/eqMbowRY361dGEAUVEtyHvzk91DtOAVryUQUVcH9.png', 'bookings/valid_ids/selfie/bSGGSSQUOYqBVecVBlrrfRW3jtwQxS73mdMGs7O0.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', 3, '2026-01-28 09:01:01', NULL, NULL, NULL, NULL, NULL, '2026-01-28 08:59:55', '2026-01-28 09:02:35', NULL, NULL, NULL),
(26, NULL, 11, '2026-01-29 08:00:00', '2026-01-29 11:00:00', 13500.00, 0.00, 0.00, 13500.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 13500.00, 'wegsgsgs', 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Hawk', 'Hawk', '1hawkeye101010101@gmail.com', '09515691003', 'Area 5a Naval Street', 'My birthday', NULL, NULL, 'pending', NULL, NULL, 'Submitted via API from: LGU1_Citizen_Portal (Ref: CP-1769701221-18)', NULL, NULL, NULL, NULL, '2026-01-29 15:40:21', '2026-01-29 15:40:21', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_conflicts`
--

CREATE TABLE `booking_conflicts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `city_event_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','resolved') NOT NULL DEFAULT 'pending',
  `citizen_choice` enum('reschedule','refund','no_response') DEFAULT NULL,
  `response_deadline` datetime NOT NULL,
  `responded_at` datetime DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `new_booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `refund_method` enum('cash','gcash','paymaya','bank_transfer') DEFAULT NULL,
  `refund_account_name` varchar(255) DEFAULT NULL,
  `refund_account_number` varchar(255) DEFAULT NULL,
  `refund_bank_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_conflicts`
--

INSERT INTO `booking_conflicts` (`id`, `booking_id`, `city_event_id`, `status`, `citizen_choice`, `response_deadline`, `responded_at`, `resolved_at`, `new_booking_id`, `admin_notes`, `refund_method`, `refund_account_name`, `refund_account_number`, `refund_bank_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 10, 1, 'resolved', 'refund', '2026-01-13 13:03:25', '2026-01-06 14:45:42', '2026-01-06 14:45:42', NULL, 'Resolved by citizen', 'cash', NULL, NULL, NULL, '2026-01-06 05:03:25', '2026-01-06 06:45:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_equipment`
--

CREATE TABLE `booking_equipment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Number of units rented',
  `price_per_unit` decimal(10,2) NOT NULL COMMENT 'Price at time of booking (locked)',
  `subtotal` decimal(10,2) NOT NULL COMMENT 'quantity * price_per_unit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_equipment`
--

INSERT INTO `booking_equipment` (`id`, `booking_id`, `equipment_item_id`, `quantity`, `price_per_unit`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 3, 19, 4, 2000.00, 8000.00, '2025-12-18 23:07:59', '2025-12-18 23:07:59'),
(2, 3, 20, 6, 1500.00, 9000.00, '2025-12-18 23:07:59', '2025-12-18 23:07:59'),
(3, 3, 21, 4, 1800.00, 7200.00, '2025-12-18 23:07:59', '2025-12-18 23:07:59'),
(4, 3, 38, 4, 3000.00, 12000.00, '2025-12-18 23:07:59', '2025-12-18 23:07:59'),
(5, 3, 3, 200, 75.00, 15000.00, '2025-12-18 23:07:59', '2025-12-18 23:07:59'),
(6, 5, 20, 1, 1500.00, 1500.00, '2025-12-27 07:24:32', '2025-12-27 07:24:32'),
(7, 5, 1, 100, 25.00, 2500.00, '2025-12-27 07:24:32', '2025-12-27 07:24:32'),
(8, 6, 19, 1, 2000.00, 2000.00, '2025-12-27 22:42:19', '2025-12-27 22:42:19'),
(9, 6, 2, 100, 30.00, 3000.00, '2025-12-27 22:42:19', '2025-12-27 22:42:19'),
(10, 6, 31, 1, 2500.00, 2500.00, '2025-12-27 22:42:19', '2025-12-27 22:42:19'),
(11, 6, 7, 10, 250.00, 2500.00, '2025-12-27 22:42:19', '2025-12-27 22:42:19'),
(12, 6, 23, 2, 150.00, 300.00, '2025-12-27 22:42:19', '2025-12-27 22:42:19'),
(13, 7, 1, 100, 25.00, 2500.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(14, 7, 17, 2, 800.00, 1600.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(15, 7, 37, 100, 50.00, 5000.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(16, 7, 18, 1, 600.00, 600.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(17, 7, 16, 3, 1200.00, 3600.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(18, 7, 13, 1, 1500.00, 1500.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(19, 7, 34, 1, 1500.00, 1500.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(20, 7, 15, 1, 500.00, 500.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(21, 7, 14, 1, 350.00, 350.00, '2025-12-28 04:24:57', '2025-12-28 04:24:57'),
(22, 7, 35, 1, 800.00, 800.00, '2025-12-28 04:24:58', '2025-12-28 04:24:58'),
(23, 7, 40, 1, 3500.00, 3500.00, '2025-12-28 04:24:58', '2025-12-28 04:24:58'),
(24, 7, 10, 1, 4500.00, 4500.00, '2025-12-28 04:24:58', '2025-12-28 04:24:58'),
(25, 7, 6, 10, 450.00, 4500.00, '2025-12-28 04:24:58', '2025-12-28 04:24:58'),
(26, 7, 23, 8, 150.00, 1200.00, '2025-12-28 04:24:58', '2025-12-28 04:24:58'),
(27, 7, 22, 7, 200.00, 1400.00, '2025-12-28 04:24:58', '2025-12-28 04:24:58'),
(28, 7, 25, 3, 5000.00, 15000.00, '2025-12-28 04:24:58', '2025-12-28 04:24:58'),
(29, 8, 19, 1, 2000.00, 2000.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(30, 8, 38, 1, 3000.00, 3000.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(31, 8, 27, 100, 50.00, 5000.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(32, 8, 13, 1, 1500.00, 1500.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(33, 8, 34, 1, 1500.00, 1500.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(34, 8, 15, 1, 500.00, 500.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(35, 8, 14, 1, 350.00, 350.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(36, 8, 35, 1, 800.00, 800.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(37, 8, 10, 1, 4500.00, 4500.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(38, 8, 7, 1, 250.00, 250.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(39, 8, 22, 1, 200.00, 200.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(40, 8, 25, 5, 5000.00, 25000.00, '2025-12-28 08:31:14', '2025-12-28 08:31:14'),
(41, 15, 19, 1, 2000.00, 2000.00, '2026-01-06 18:44:05', '2026-01-06 18:44:05'),
(42, 18, 38, 4, 3000.00, 12000.00, '2026-01-13 19:16:39', '2026-01-13 19:16:39'),
(43, 18, 17, 10, 800.00, 8000.00, '2026-01-13 19:16:39', '2026-01-13 19:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `budget_allocations`
--

CREATE TABLE `budget_allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fiscal_year` int(11) NOT NULL,
  `category` enum('maintenance','equipment','operations','staff','utilities','other') NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `allocated_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `spent_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_expenditures`
--

CREATE TABLE `budget_expenditures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_allocation_id` bigint(20) UNSIGNED NOT NULL,
  `expenditure_type` enum('maintenance','equipment_purchase','operational_cost','staff_salary','utility_bill','other') NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expenditure_date` date NOT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `facility_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `citizen_program_registrations`
--

CREATE TABLE `citizen_program_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `government_program_booking_id` bigint(20) UNSIGNED NOT NULL,
  `citizen_id` bigint(20) UNSIGNED NOT NULL,
  `registration_status` enum('registered','attended','cancelled','no_show') NOT NULL DEFAULT 'registered',
  `qr_code` varchar(255) DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `attended_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city_events`
--

CREATE TABLE `city_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_description` text DEFAULT NULL,
  `event_type` varchar(255) NOT NULL DEFAULT 'government',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('scheduled','ongoing','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `affected_bookings_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `city_events`
--

INSERT INTO `city_events` (`id`, `facility_id`, `start_time`, `end_time`, `event_title`, `event_description`, `event_type`, `created_by`, `status`, `affected_bookings_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 14, '2026-01-07 08:00:00', '2026-01-07 13:00:00', 'Annual City Anniversary Celebration', NULL, 'government', 2, 'scheduled', 1, '2026-01-06 05:03:23', '2026-01-06 05:03:31', NULL),
(2, 12, '2026-01-17 13:00:00', '2026-01-17 16:00:00', 'Independence Day Celebration', NULL, 'government', 2, 'scheduled', 0, '2026-01-16 20:42:43', '2026-01-16 20:42:43', NULL),
(3, 11, '2026-01-17 13:00:00', '2026-01-17 16:00:00', 'Founding Anniversary', NULL, 'government', 2, 'scheduled', 0, '2026-01-16 20:51:06', '2026-01-16 20:51:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `community_maintenance_requests`
--

CREATE TABLE `community_maintenance_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_report_id` bigint(20) UNSIGNED DEFAULT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `resident_name` varchar(255) NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `unit_number` varchar(255) DEFAULT NULL,
  `report_type` enum('maintenance','complaint','suggestion','emergency') DEFAULT 'maintenance',
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `status` enum('submitted','reviewed','in_progress','resolved','closed') DEFAULT 'submitted',
  `submitted_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `community_maintenance_requests`
--

INSERT INTO `community_maintenance_requests` (`id`, `external_report_id`, `facility_id`, `facility_name`, `resident_name`, `contact_info`, `subject`, `description`, `unit_number`, `report_type`, `priority`, `status`, `submitted_by_user_id`, `created_at`, `updated_at`) VALUES
(1, 5, 16, 'M.I.C.E. Breakout Room 1', 'Llaneta Cristian Pastoril', '09123456789', 'hrtjrhr', 'dxhzhsdh', 'M.I.C.E. Breakout Room 1, Quezon City M.I.C.E. Center, Floor 1, Quezon City, Metro Manila', 'maintenance', 'medium', 'submitted', 2, '2026-01-29 15:34:38', '2026-01-29 15:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `energy_reports_received`
--

CREATE TABLE `energy_reports_received` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `external_report_id` varchar(255) NOT NULL,
  `energy_consumed_kwh` decimal(10,2) NOT NULL,
  `energy_cost` decimal(10,2) NOT NULL,
  `efficiency_rating` varchar(255) DEFAULT NULL,
  `recommendations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`recommendations`)),
  `received_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Full data from external system' CHECK (json_valid(`received_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_inventory`
--

CREATE TABLE `equipment_inventory` (
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `available_quantity` int(11) NOT NULL DEFAULT 0,
  `in_use_quantity` int(11) NOT NULL DEFAULT 0,
  `maintenance_quantity` int(11) NOT NULL DEFAULT 0,
  `condition` varchar(50) NOT NULL DEFAULT 'good',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_inventory`
--

INSERT INTO `equipment_inventory` (`equipment_id`, `equipment_name`, `category`, `total_quantity`, `available_quantity`, `in_use_quantity`, `maintenance_quantity`, `condition`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Projector', 'AV Equipment', 15, 12, 2, 1, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(2, 'LCD Screen', 'AV Equipment', 10, 8, 2, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(3, 'Sound System', 'AV Equipment', 8, 6, 2, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(4, 'Wireless Microphone', 'AV Equipment', 25, 20, 5, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(5, 'Wired Microphone', 'AV Equipment', 30, 25, 3, 2, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(6, 'Stage Lights', 'AV Equipment', 20, 18, 2, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(7, 'Laptop', 'Computing', 12, 10, 2, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(8, 'Desktop Computer', 'Computing', 8, 7, 1, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(9, 'Whiteboard', 'Presentation', 15, 12, 3, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(10, 'Flip Chart', 'Presentation', 20, 18, 2, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(11, 'Podium', 'Presentation', 5, 4, 1, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(12, 'Tables', 'Furniture', 200, 180, 20, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(13, 'Chairs', 'Furniture', 800, 750, 50, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(14, 'Extension Cords', 'Power', 50, 45, 5, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(15, 'Generator', 'Power', 3, 2, 1, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(16, 'Air Conditioning Unit', 'Climate Control', 25, 23, 1, 1, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(17, 'Electric Fan', 'Climate Control', 60, 55, 5, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47'),
(18, 'Tent/Canopy', 'Event', 10, 8, 2, 0, 'good', NULL, '2026-01-09 22:34:47', '2026-01-09 22:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_items`
--

CREATE TABLE `equipment_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Equipment name (e.g., Monobloc Chair, Round Table)',
  `category` varchar(255) NOT NULL COMMENT 'chairs, tables, sound_system, lighting, etc.',
  `description` text DEFAULT NULL,
  `price_per_unit` decimal(10,2) NOT NULL COMMENT 'Rental price per unit',
  `quantity_available` int(11) NOT NULL DEFAULT 0 COMMENT 'Total units available',
  `is_available` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Can be rented',
  `image_path` varchar(255) DEFAULT NULL COMMENT 'Photo of equipment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_items`
--

INSERT INTO `equipment_items` (`id`, `name`, `category`, `description`, `price_per_unit`, `quantity_available`, `is_available`, `image_path`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Monobloc Chair (White)', 'chairs', 'Standard plastic monobloc chair, white color, suitable for indoor and outdoor events', 25.00, 500, 1, NULL, '2025-11-15 09:45:11', '2025-11-15 09:45:11', NULL),
(2, 'Monobloc Chair (Colored)', 'chairs', 'Colored plastic monobloc chairs in various colors', 30.00, 300, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(3, 'Banquet Chair with Cover', 'chairs', 'Padded banquet chair with white cover, ideal for formal events', 75.00, 200, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(4, 'Folding Chair (Metal)', 'chairs', 'Metal folding chair with padded seat', 40.00, 150, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(5, 'Round Table (6-seater)', 'tables', 'Round table suitable for 6 people, includes white tablecloth', 300.00, 50, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(6, 'Round Table (10-seater)', 'tables', 'Large round table suitable for 10 people, includes white tablecloth', 450.00, 30, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(7, 'Rectangular Table (6ft)', 'tables', '6-foot rectangular table, suitable for buffet or registration', 250.00, 40, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(8, 'Cocktail Table', 'tables', 'High cocktail table with cover, perfect for standing receptions', 200.00, 25, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(9, 'Basic Sound System Package', 'sound_system', 'Includes 2 speakers, 1 amplifier, 2 wireless microphones, and mixer', 2500.00, 5, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(10, 'Premium Sound System Package', 'sound_system', 'Includes 4 speakers, 2 amplifiers, 4 wireless microphones, mixer, and equalizer', 4500.00, 3, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(12, 'Standing Speaker', 'sound_system', 'Professional standing speaker (can be rented individually)', 800.00, 10, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(13, 'LED Par Light (Set of 4)', 'lighting', 'LED par lights for stage lighting, set of 4 with controller', 1500.00, 6, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(14, 'String Lights (20 meters)', 'lighting', 'Decorative string lights for ambiance, 20 meters', 350.00, 20, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(15, 'Spotlight', 'lighting', 'Professional spotlight for stage or presentation', 500.00, 8, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(16, 'Stage Backdrop (Customizable)', 'decorations', 'Customizable stage backdrop with stand', 1200.00, 5, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(17, 'Balloon Arch Kit', 'decorations', 'Balloon arch kit with stand and pump', 800.00, 10, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(18, 'Red Carpet Runner (10 meters)', 'decorations', 'Red carpet for formal entrances, 10 meters', 600.00, 5, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(19, 'LED Projector + Screen Package', 'audio_visual', 'LED projector with 10ft x 8ft projector screen', 2000.00, 4, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(20, 'LED TV (55 inch)', 'audio_visual', '55-inch LED TV with mobile stand', 1500.00, 6, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(21, 'Portable PA System', 'audio_visual', 'Portable PA system with battery, perfect for outdoor events', 1800.00, 4, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(22, 'Industrial Fan', 'utilities', 'Large industrial fan for ventilation', 200.00, 20, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(23, 'Extension Cord (50 meters)', 'utilities', 'Heavy-duty extension cord, 50 meters', 150.00, 25, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(25, 'Tent (10x10 meters)', 'utilities', 'Large white tent suitable for outdoor events', 5000.00, 8, 1, NULL, '2025-11-15 09:45:12', '2025-11-15 09:45:12', NULL),
(27, 'Upholstered Chairs', 'chairs', 'Padded upholstered chairs for more formal events.', 50.00, 100, 1, NULL, '2025-11-16 00:36:52', '2026-01-28 11:45:07', NULL),
(29, 'Rectangular Tables (8-seater)', 'tables', 'Long rectangular tables ideal for buffet setups or seating 8 people.', 300.00, 40, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(30, 'Cocktail Tables (Standing)', 'tables', 'High cocktail tables for standing events and networking.', 200.00, 30, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(31, 'PA System (Basic)', 'sound_system', 'Basic PA system with 2 speakers, 2 microphones, and mixer. Good for small to medium events.', 2500.00, 5, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(32, 'PA System (Premium)', 'sound_system', 'Premium PA system with 4 speakers, 4 wireless microphones, mixer, and subwoofer for large events.', 5000.00, 3, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(33, 'Wireless Microphone (Additional)', 'sound_system', 'Extra wireless microphone unit.', 500.00, 10, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(34, 'LED Stage Lights (Set of 4)', 'lighting', 'Colorful LED stage lights for events and performances.', 1500.00, 8, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(35, 'String Lights (50 meters)', 'lighting', 'Warm white string lights for decoration.', 800.00, 15, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(36, 'Table Linens (White)', 'decorations', 'White table cloths for round or rectangular tables.', 100.00, 100, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(37, 'Chair Covers (White)', 'decorations', 'Elegant white chair covers with sash options.', 50.00, 200, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(38, 'Projector and Screen', 'av_equipment', 'HD projector with 10ft projection screen for presentations.', 3000.00, 4, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(39, 'Portable Stage Platform (4x8 ft)', 'staging', 'Modular stage platform sections that can be combined.', 1000.00, 20, 1, NULL, '2025-11-16 00:36:52', '2025-11-16 00:36:52', NULL),
(40, 'Generator (5KVA)', 'power', 'Backup generator for outdoor events.', 3500.00, 3, 1, NULL, '2025-11-16 00:36:52', '2026-01-27 03:34:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_schedules_sent`
--

CREATE TABLE `event_schedules_sent` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `event_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Event name, facility, attendees, date/time' CHECK (json_valid(`event_data`)),
  `external_request_id` varchar(255) DEFAULT NULL,
  `status` enum('sent','acknowledged','failed') NOT NULL DEFAULT 'sent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `external_projects`
--

CREATE TABLE `external_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_project_id` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `contractor_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `project_start_date` date NOT NULL,
  `project_end_date` date NOT NULL,
  `affected_facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Array of facility IDs' CHECK (json_valid(`affected_facilities`)),
  `impact_description` text NOT NULL,
  `status` enum('planned','ongoing','completed','cancelled') NOT NULL DEFAULT 'planned',
  `received_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Full data from external system' CHECK (json_valid(`received_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `lgu_city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `full_address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `rating` decimal(3,2) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `min_capacity` int(11) NOT NULL DEFAULT 1,
  `per_person_rate` decimal(10,2) DEFAULT NULL,
  `per_person_extension_rate` decimal(10,2) DEFAULT NULL,
  `base_hours` int(11) NOT NULL DEFAULT 3,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `rate_per_hour` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL COMMENT 'Facility photo',
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`facility_id`, `lgu_city_id`, `name`, `description`, `address`, `latitude`, `longitude`, `full_address`, `city`, `view_count`, `rating`, `capacity`, `min_capacity`, `per_person_rate`, `per_person_extension_rate`, `base_hours`, `hourly_rate`, `rate_per_hour`, `image_path`, `is_available`, `created_at`, `updated_at`, `deleted_at`) VALUES
(11, 1, 'Buena Park', 'Open-air community park suitable for outdoor gatherings and events. Perfect for weekend private events and community celebrations.', 'South Caloocan City', 14.75660000, 121.04500000, 'South Caloocan City, Metro Manila', 'Caloocan City', 134, 4.00, 200, 30, 135.00, 20.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2026-01-27 05:57:39', NULL),
(12, 1, 'Sports Complex', 'Multi-purpose indoor sports facility for athletic events and large gatherings. Ideal for tournaments, competitions, and sporting events.', 'South Caloocan City', 14.75050000, 121.02080000, 'South Caloocan City, Metro Manila', 'Caloocan City', 179, 3.90, 500, 50, 140.00, 20.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL),
(13, 1, 'Bulwagan Katipunan', 'Convention hall primarily used for city events and formal gatherings. Air-conditioned venue suitable for conferences, assemblies, and official functions.', 'South Caloocan City', 14.64880000, 120.99060000, 'South Caloocan City, Metro Manila', 'Caloocan City', 466, 3.80, 300, 35, 145.00, 25.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL),
(14, 1, 'Pacquiao Court', 'Covered basketball court suitable for sports events and tournaments. Named after Manny Pacquiao. Weather-protected facility for basketball games and small sporting events.', 'South Caloocan City', 14.75050000, 121.02080000, 'South Caloocan City, Metro Manila', 'Caloocan City', 483, 4.90, 150, 25, 130.00, 20.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL),
(15, 2, 'QC M.I.C.E. Convention & Exhibit Hall', 'Large-scale venue for conventions, exhibits, and major events. Professional-grade 4-storey M.I.C.E. Center facility. Currently restricted to QC-LGU departments pending ordinance approval for public use.', 'Quezon City M.I.C.E. Center', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Quezon City, Metro Manila', 'Quezon City', 452, 4.80, 1000, 50, 150.00, 30.00, 4, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL),
(16, 2, 'M.I.C.E. Breakout Room 1', 'Intimate meeting space perfect for seminars and training sessions. Most requested facility! Over 200 bookings in 8 months (Jan-Aug 2024). Equipped with projector and whiteboard.', 'Quezon City M.I.C.E. Center, Floor 1', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Floor 1, Quezon City, Metro Manila', 'Quezon City', 386, 4.30, 50, 15, 125.00, 15.00, 2, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL),
(17, 2, 'M.I.C.E. Breakout Room 2', 'Intimate meeting space perfect for seminars and training sessions. High demand facility for workshops and trainings. Equipped with projector and whiteboard.', 'Quezon City M.I.C.E. Center, Floor 2', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Floor 2, Quezon City, Metro Manila', 'Quezon City', 400, 4.80, 40, 15, 125.00, 15.00, 2, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL),
(18, 2, 'QC M.I.C.E. Auditorium', 'Fixed-seating venue with stage for performances and presentations. Professional audio/visual setup ideal for lectures, performances, and formal presentations.', 'Quezon City M.I.C.E. Center', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Quezon City, Metro Manila', 'Quezon City', 171, 3.60, 300, 35, 140.00, 25.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `facility_images`
--

CREATE TABLE `facility_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facility_reviews`
--

CREATE TABLE `facility_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL COMMENT '1-5 stars',
  `review` text DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Only for completed bookings',
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `admin_response` text DEFAULT NULL,
  `admin_responder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facility_reviews`
--

INSERT INTO `facility_reviews` (`id`, `facility_id`, `booking_id`, `user_id`, `user_name`, `rating`, `review`, `is_verified`, `is_visible`, `admin_response`, `admin_responder_id`, `admin_responded_at`, `created_at`, `updated_at`) VALUES
(1, 11, 4, 5, 'Cristian Mark Angelo Llaneta', 5, 'All goods.', 1, 1, NULL, NULL, NULL, '2026-01-02 06:59:46', '2026-01-02 06:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `government_program_bookings`
--

CREATE TABLE `government_program_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_system` varchar(100) NOT NULL DEFAULT 'Energy Efficiency',
  `source_seminar_id` varchar(50) NOT NULL,
  `source_database` varchar(100) NOT NULL DEFAULT 'ener_nova_capri',
  `organizer_user_id` varchar(50) NOT NULL,
  `organizer_name` varchar(255) NOT NULL,
  `organizer_contact` varchar(20) NOT NULL,
  `organizer_email` varchar(255) DEFAULT NULL,
  `organizer_area` varchar(255) DEFAULT NULL,
  `program_title` varchar(255) NOT NULL,
  `program_type` enum('seminar','training','workshop','community_event','other') NOT NULL DEFAULT 'seminar',
  `program_description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `expected_attendees` int(11) NOT NULL DEFAULT 0,
  `actual_attendees` int(11) NOT NULL DEFAULT 0,
  `requested_location` varchar(255) DEFAULT NULL,
  `assigned_facility_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coordination_status` enum('pending_review','organizer_contacted','speaker_coordinating','fund_requested','fund_approved','facility_assigned','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending_review',
  `call_log` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`call_log`)),
  `coordination_notes` text DEFAULT NULL,
  `number_of_speakers` int(11) NOT NULL DEFAULT 1,
  `speaker_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`speaker_details`)),
  `speaker_coordination_notes` text DEFAULT NULL,
  `equipment_provided` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`equipment_provided`)),
  `speakers_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `requested_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `approved_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `actual_spent` decimal(15,2) NOT NULL DEFAULT 0.00,
  `fund_breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fund_breakdown`)),
  `is_fee_waived` tinyint(1) NOT NULL DEFAULT 1,
  `finance_request_id` varchar(50) DEFAULT NULL,
  `finance_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `finance_approved_date` date DEFAULT NULL,
  `finance_check_number` varchar(50) DEFAULT NULL,
  `pre_event_transparency_published` tinyint(1) NOT NULL DEFAULT 0,
  `post_event_transparency_published` tinyint(1) NOT NULL DEFAULT 0,
  `is_public_display` tinyint(1) NOT NULL DEFAULT 1,
  `liquidation_required` tinyint(1) NOT NULL DEFAULT 1,
  `liquidation_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `liquidation_date` date DEFAULT NULL,
  `event_rating` decimal(3,2) DEFAULT NULL,
  `feedback_summary` text DEFAULT NULL,
  `attendance_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attendance_data`)),
  `assigned_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `government_program_bookings`
--

INSERT INTO `government_program_bookings` (`id`, `source_system`, `source_seminar_id`, `source_database`, `organizer_user_id`, `organizer_name`, `organizer_contact`, `organizer_email`, `organizer_area`, `program_title`, `program_type`, `program_description`, `event_date`, `start_time`, `end_time`, `expected_attendees`, `actual_attendees`, `requested_location`, `assigned_facility_id`, `coordination_status`, `call_log`, `coordination_notes`, `number_of_speakers`, `speaker_details`, `speaker_coordination_notes`, `equipment_provided`, `speakers_confirmed`, `requested_amount`, `approved_amount`, `actual_spent`, `fund_breakdown`, `is_fee_waived`, `finance_request_id`, `finance_status`, `finance_approved_date`, `finance_check_number`, `pre_event_transparency_published`, `post_event_transparency_published`, `is_public_display`, `liquidation_required`, `liquidation_submitted`, `liquidation_date`, `event_rating`, `feedback_summary`, `attendance_data`, `assigned_admin_id`, `assigned_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'energy_efficiency', '28', 'ener_nova_capri', '82', 'Christian Cando', '9085919898', 'piyasigno@gmail.com', 'AREA 4', 'Energy Conservation Awareness ', 'seminar', 'A seminar focused on teaching residents how to reduce electricity consumption and use energy-efficient appliances.', '2025-10-23', '20:00:00', '22:00:00', 0, 0, 'Multi-Purpose Hall', 17, 'confirmed', NULL, NULL, 1, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', NULL, NULL, 1, 1500.00, 1500.00, 0.00, '{\"1\": {\"item\": \"Catering\", \"amount\": \"1500\"}}', 1, NULL, 'pending', NULL, NULL, 0, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2026-01-09 09:37:53', '2026-01-09 09:37:53', '2026-01-09 09:37:53', NULL),
(2, 'energy_efficiency', '33', 'ener_nova_capri', '82', 'Christian Cando', '9085919898', 'piyasigno@gmail.com', 'AREA 4', 'Electric Vehicle Home Charging: Cost Analysis', 'seminar', 'Planning to buy an electric vehicle? This seminar covers home charging setup costs, electricity consumption calculations, time-of-use rates, and how to optimize charging schedules to minimize your electric bill.', '2026-03-20', '15:00:00', '18:00:00', 2, 0, 'Conference room preferred', 17, 'confirmed', NULL, NULL, 1, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', NULL, NULL, 1, 1500.00, 1500.00, 0.00, '{\"1\": {\"item\": \"Catering\", \"amount\": \"1500\"}}', 1, NULL, 'pending', NULL, NULL, 0, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2026-01-09 20:49:17', '2026-01-09 20:49:17', '2026-01-09 20:49:17', NULL),
(3, 'energy_efficiency', '32', 'ener_nova_capri', '82', 'Christian Cando', '9085919898', 'piyasigno@gmail.com', 'AREA 4', 'Home Appliance Energy Audit and Optimization', 'seminar', 'Learn how to conduct your own home energy audit. Identify vampire power loads, understand appliance energy ratings, and get tips on optimal usage patterns for refrigerators, air conditioners, washing machines, and more.', '2026-03-12', '10:00:00', '13:00:00', 3, 0, 'Large venue needed for 200+ participants', 17, 'confirmed', NULL, NULL, 1, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', NULL, NULL, 1, 1500.00, 1500.00, 0.00, '{\"1\": {\"item\": \"Handbook\", \"amount\": \"1500\"}}', 1, NULL, 'pending', NULL, NULL, 0, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2026-01-09 21:01:45', '2026-01-09 21:01:45', '2026-01-09 21:01:45', NULL),
(4, 'energy_efficiency', '31', 'ener_nova_capri', '82', 'Christian Cando', '9085919898', 'piyasigno@gmail.com', 'AREA 4', 'Smart Home Technology for Energy Savings', 'seminar', 'Explore modern smart home devices that help monitor and reduce energy consumption. Topics include smart thermostats, automated lighting, energy monitoring apps, and IoT integration. Hands-on demos with actual devices included.', '2026-03-05', '13:00:00', '17:00:00', 3, 0, 'Multi-Purpose Hall or Conference Room needed', 18, 'confirmed', NULL, NULL, 1, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', NULL, NULL, 1, 1500.00, 1500.00, 0.00, '{\"1\": {\"item\": \"Jollibee\", \"amount\": \"1500\"}}', 1, NULL, 'pending', NULL, NULL, 0, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2026-01-09 21:07:54', '2026-01-09 21:07:54', '2026-01-09 21:07:54', NULL),
(5, 'energy_efficiency', '30', 'ener_nova_capri', '82', 'Christian Cando', '9085919898', 'piyasigno@gmail.com', 'AREA 4', 'LED Lighting: Save Money, Save Energy', 'seminar', 'Discover how switching to LED lighting can reduce your electricity bill by up to 80%. This seminar covers LED types, pricing comparison, lifespan calculations, and FREE LED bulb distribution to all attendees!', '2026-02-20', '14:00:00', '16:30:00', 3, 0, 'Barangay Hall (Facility needed)', 18, 'confirmed', NULL, NULL, 1, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', NULL, '{\"1\": {\"name\": \"LCD Screen\", \"quantity\": \"1\"}, \"2\": {\"name\": \"Laptop\", \"quantity\": \"1\"}, \"3\": {\"name\": \"Chairs\", \"quantity\": \"50\"}}', 1, 10000.00, 10000.00, 0.00, '{\"1\": {\"item\": \"Catering\", \"amount\": \"5000\"}, \"2\": {\"item\": \"Handbook\", \"amount\": \"1000\"}, \"3\": {\"item\": \"Spoke Person\", \"amount\": \"4000\"}}', 1, NULL, 'pending', NULL, NULL, 0, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2026-01-09 22:41:01', '2026-01-09 22:41:01', '2026-01-09 22:41:01', NULL),
(6, 'energy_efficiency', '29', 'ener_nova_capri', '82', 'Christian Cando', '9085919898', 'piyasigno@gmail.com', 'AREA 4', 'Solar Energy Benefits and Installation Guide', 'seminar', 'Learn about the benefits of solar energy, government incentives for solar panel installation, and step-by-step guide on how to install residential solar systems. This seminar includes demonstrations and Q&A with certified solar technicians.', '2026-02-15', '09:00:00', '12:00:00', 2, 0, 'Community Center (To be assigned by LGU)', 17, 'confirmed', NULL, NULL, 1, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', NULL, '{\"1\": {\"name\": \"Projector\", \"quantity\": \"1\"}, \"2\": {\"name\": \"Laptop\", \"quantity\": \"1\"}, \"3\": {\"name\": \"Chairs\", \"quantity\": \"100\"}}', 1, 6000.00, 6000.00, 0.00, '{\"1\": {\"item\": \"Catering\", \"amount\": \"5000\"}, \"2\": {\"item\": \"Handbook\", \"amount\": \"1000\"}}', 1, NULL, 'pending', NULL, NULL, 0, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, '2026-01-09 22:48:45', '2026-01-09 22:48:45', '2026-01-09 22:48:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `infrastructure_project_requests`
--

CREATE TABLE `infrastructure_project_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_project_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Project ID from Infrastructure PM system',
  `requesting_office` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `project_title` varchar(255) NOT NULL,
  `project_category` varchar(255) NOT NULL,
  `project_location` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `problem_identified` text NOT NULL,
  `scope_item1` varchar(255) DEFAULT NULL,
  `scope_item2` varchar(255) DEFAULT NULL,
  `scope_item3` varchar(255) DEFAULT NULL,
  `estimated_budget` decimal(15,2) DEFAULT NULL,
  `priority_level` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `requested_start_date` date DEFAULT NULL,
  `prepared_by` varchar(255) DEFAULT NULL,
  `prepared_position` varchar(255) DEFAULT NULL,
  `status` enum('draft','submitted','received','under_review','approved','rejected','in_progress','completed') NOT NULL DEFAULT 'submitted',
  `bid_status` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `submitted_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `infrastructure_project_requests`
--

INSERT INTO `infrastructure_project_requests` (`id`, `external_project_id`, `requesting_office`, `contact_person`, `position`, `contact_number`, `contact_email`, `project_title`, `project_category`, `project_location`, `latitude`, `longitude`, `problem_identified`, `scope_item1`, `scope_item2`, `scope_item3`, `estimated_budget`, `priority_level`, `requested_start_date`, `prepared_by`, `prepared_position`, `status`, `bid_status`, `rejection_reason`, `submitted_by_user_id`, `submitted_at`, `approved_at`, `completed_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 16, 'Sangguniang Bayan', 'Cristian', NULL, NULL, NULL, 'asd', 'Public Facilities', NULL, NULL, NULL, 'qwerty', NULL, NULL, NULL, NULL, 'low', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-01-31 13:34:49', '2026-01-31 16:59:23', NULL),
(5, 17, 'Municipal Planning and Development Office', 'Cristian', NULL, NULL, NULL, 'qwertyuiop', 'Drainage System', NULL, NULL, NULL, 'zxcvbnm', NULL, NULL, NULL, NULL, 'medium', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-01-31 13:35:34', '2026-01-31 16:59:23', NULL),
(6, 18, 'Municipal Social Welfare and Development Office', 'Cristian', NULL, NULL, NULL, 'zxcvbnm', 'Road Construction', NULL, NULL, NULL, 'asdfghjkl', NULL, NULL, NULL, 5000000.00, 'high', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-01-31 13:36:47', '2026-01-31 16:59:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lgu_cities`
--

CREATE TABLE `lgu_cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `city_name` varchar(255) NOT NULL COMMENT 'Caloocan, Quezon City, etc.',
  `city_code` varchar(255) NOT NULL COMMENT 'CLCN, QC, etc.',
  `description` text DEFAULT NULL,
  `status` enum('active','coming_soon','inactive') NOT NULL DEFAULT 'active',
  `has_external_integration` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Integrated with their systems',
  `integration_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'API URLs, keys, etc.' CHECK (json_valid(`integration_config`)),
  `facility_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Cached count of facilities',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lgu_cities`
--

INSERT INTO `lgu_cities` (`id`, `city_name`, `city_code`, `description`, `status`, `has_external_integration`, `integration_config`, `facility_count`, `created_at`, `updated_at`) VALUES
(1, 'Caloocan City', 'CLCN', 'The primary LGU operating this reservation system. Residents receive automatic discounts.', 'active', 0, NULL, 0, '2025-11-16 00:22:52', '2025-11-16 00:22:52'),
(2, 'Quezon City', 'QC', 'Largest city in Metro Manila by population. Integrated facilities available for booking.', 'active', 0, NULL, 0, '2025-11-16 00:22:52', '2025-11-16 00:22:52'),
(3, 'Manila', 'MNL', 'The capital city of the Philippines.', 'active', 0, NULL, 0, '2025-11-16 00:22:52', '2025-11-16 00:22:52'),
(4, 'Makati', 'MKT', 'Central business district of Metro Manila.', 'coming_soon', 0, NULL, 0, '2025-11-16 00:22:52', '2025-11-16 00:22:52'),
(5, 'Pasig', 'PSG', 'Highly urbanized city in Metro Manila.', 'coming_soon', 0, NULL, 0, '2025-11-16 00:22:52', '2025-11-16 00:22:52'),
(6, 'Taguig', 'TGG', 'Home to Bonifacio Global City.', 'coming_soon', 0, NULL, 0, '2025-11-16 00:22:52', '2025-11-16 00:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `liquidation_items`
--

CREATE TABLE `liquidation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `government_program_booking_id` bigint(20) UNSIGNED NOT NULL,
  `category` enum('refreshments','materials','transportation','miscellaneous') NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `official_receipt_number` varchar(100) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `receipt_image_url` varchar(500) DEFAULT NULL,
  `item_description` varchar(255) NOT NULL,
  `item_specification` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `is_public_display` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests_sent`
--

CREATE TABLE `maintenance_requests_sent` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Facility name, time, maintenance type' CHECK (json_valid(`request_data`)),
  `external_request_id` varchar(255) DEFAULT NULL,
  `status` enum('sent','acknowledged','failed') NOT NULL DEFAULT 'sent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_schedules`
--

CREATE TABLE `maintenance_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL COMMENT 'Maintenance start date',
  `end_date` date NOT NULL COMMENT 'Maintenance end date',
  `start_time` time DEFAULT NULL COMMENT 'Maintenance start time (optional - affects whole day if null)',
  `end_time` time DEFAULT NULL COMMENT 'Maintenance end time (optional)',
  `maintenance_type` varchar(255) NOT NULL COMMENT 'routine, repair, renovation, inspection',
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `recurring_pattern` varchar(255) DEFAULT NULL COMMENT 'daily, weekly, monthly, yearly',
  `created_by` bigint(20) UNSIGNED NOT NULL COMMENT 'Admin user ID who scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_schedules_received`
--

CREATE TABLE `maintenance_schedules_received` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `external_schedule_id` varchar(255) NOT NULL,
  `maintenance_start` datetime NOT NULL,
  `maintenance_end` datetime NOT NULL,
  `maintenance_team` varchar(255) DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `received_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Full data from external system' CHECK (json_valid(`received_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_15_171833_add_discount_fields_to_bookings_table', 1),
(2, '2025_11_16_000001_add_image_path_to_facilities_table', 2),
(3, '2025_11_16_154306_create_facility_reviews_table', 3),
(4, '2025_11_18_082732_add_valid_id_type_to_bookings_table', 4),
(5, '2026_01_01_073401_create_budget_allocations_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `payment_slips`
--

CREATE TABLE `payment_slips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slip_number` varchar(50) NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `payment_deadline` timestamp NOT NULL,
  `status` enum('unpaid','paid','expired') NOT NULL DEFAULT 'unpaid',
  `payment_method` enum('cash','gcash','paymaya','bank_transfer','credit_card') DEFAULT NULL,
  `payment_gateway` varchar(255) DEFAULT NULL COMMENT 'gcash, paymaya, bank, cash',
  `gateway_transaction_id` varchar(255) DEFAULT NULL COMMENT 'Transaction ID from payment gateway',
  `gateway_reference_number` varchar(255) DEFAULT NULL,
  `paymongo_checkout_id` varchar(100) DEFAULT NULL,
  `payment_receipt_url` varchar(255) DEFAULT NULL COMMENT 'URL to payment receipt',
  `gateway_webhook_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Full webhook data from gateway' CHECK (json_valid(`gateway_webhook_payload`)),
  `treasurer_reference` varchar(255) DEFAULT NULL COMMENT 'Reference number from Treasurer system',
  `or_number` varchar(255) DEFAULT NULL COMMENT 'Official Receipt number',
  `treasurer_status` varchar(255) DEFAULT NULL COMMENT 'confirmed, pending, rejected from Treasurer',
  `sent_to_treasurer_at` timestamp NULL DEFAULT NULL,
  `reminder_24h_sent_at` timestamp NULL DEFAULT NULL,
  `reminder_6h_sent_at` timestamp NULL DEFAULT NULL,
  `confirmed_by_treasurer_at` timestamp NULL DEFAULT NULL,
  `treasurer_cashier_name` varchar(255) DEFAULT NULL,
  `treasurer_cashier_id` varchar(255) DEFAULT NULL,
  `payment_intent_id` varchar(255) DEFAULT NULL,
  `payment_source_id` varchar(255) DEFAULT NULL,
  `payment_source_type` varchar(255) DEFAULT NULL,
  `paymongo_response` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `is_test_transaction` tinyint(1) NOT NULL DEFAULT 0,
  `payment_channel` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_slips`
--

INSERT INTO `payment_slips` (`id`, `slip_number`, `booking_id`, `amount_due`, `payment_deadline`, `status`, `payment_method`, `payment_gateway`, `gateway_transaction_id`, `gateway_reference_number`, `paymongo_checkout_id`, `payment_receipt_url`, `gateway_webhook_payload`, `treasurer_reference`, `or_number`, `treasurer_status`, `sent_to_treasurer_at`, `reminder_24h_sent_at`, `reminder_6h_sent_at`, `confirmed_by_treasurer_at`, `treasurer_cashier_name`, `treasurer_cashier_id`, `payment_intent_id`, `payment_source_id`, `payment_source_type`, `paymongo_response`, `paid_at`, `verified_by`, `transaction_reference`, `is_test_transaction`, `payment_channel`, `account_name`, `account_number`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'PS-2025-000001', 4, 3720.00, '2025-12-27 01:36:50', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-25 10:47:55', 7, 'OR-2025-0001', 0, NULL, NULL, NULL, NULL, '2025-12-25 02:37:09', '2025-12-25 10:47:55'),
(2, 'PS-2025-000002', 5, 11250.00, '2025-12-29 07:25:28', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-27 07:36:15', 7, 'OR-2025-0002', 0, NULL, NULL, NULL, NULL, '2025-12-27 07:25:28', '2025-12-27 07:36:15'),
(3, 'PS-2025-000003', 6, 16250.00, '2025-12-29 22:43:22', 'paid', 'gcash', NULL, NULL, 'TEST-123456789', NULL, NULL, NULL, NULL, 'OR-2025-0003', NULL, '2025-12-27 22:57:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-27 22:57:40', 7, 'OR-2025-0003', 1, 'gcash', NULL, NULL, NULL, '2025-12-27 22:43:22', '2025-12-27 22:57:40'),
(4, 'PS-2025-000004', 7, 55800.00, '2025-12-30 05:08:10', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-28 06:01:02', 7, NULL, 0, NULL, NULL, NULL, 'All goods', '2025-12-28 05:08:10', '2025-12-28 06:01:02'),
(6, 'PS-2025-000005', 8, 64100.00, '2025-12-30 09:14:14', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-28 10:08:40', 7, NULL, 0, NULL, NULL, NULL, NULL, '2025-12-28 09:14:14', '2025-12-28 10:08:40'),
(7, 'PS-2025-000006', 9, 5200.00, '2025-12-30 09:14:59', 'paid', 'paymaya', NULL, NULL, 'TEST-123456789', NULL, NULL, NULL, NULL, 'OR-2025-0006', NULL, '2025-12-28 09:56:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-29 08:00:23', 7, 'TEST-123456789', 1, 'maya', NULL, NULL, NULL, '2025-12-28 09:14:59', '2025-12-29 08:00:23'),
(8, 'PS-2025-000007', 10, 3000.00, '2025-12-31 23:15:25', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-29 23:17:52', 7, NULL, 0, NULL, NULL, NULL, NULL, '2025-12-29 23:15:25', '2025-12-29 23:17:52'),
(9, 'PS-2026-000001', 15, 4840.00, '2026-01-09 03:20:35', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-07 03:23:46', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-07 03:20:35', '2026-01-07 03:23:46'),
(10, 'PS-2026-000002', 16, 4455.00, '2026-01-15 03:03:05', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-13 03:06:13', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 03:03:05', '2026-01-13 03:06:13'),
(11, 'PS-2026-000003', 17, 5510.00, '2026-01-15 03:14:41', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0003', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-13 03:16:35', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 03:14:41', '2026-01-13 03:16:35'),
(12, 'PS-2026-000004', 18, 23250.00, '2026-01-15 19:19:26', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-13 19:22:02', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 19:19:26', '2026-01-13 19:22:02'),
(13, 'PS-2026-000005', 19, 4050.00, '2026-01-15 19:30:28', 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 19:30:28', '2026-01-16 21:25:35'),
(14, 'PS-2026-000006', 20, 4050.00, '2026-01-15 19:34:12', 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 19:34:12', '2026-01-16 21:25:35'),
(15, 'PS-2026-000007', 21, 4050.00, '2026-01-15 19:37:02', 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 19:37:02', '2026-01-16 21:25:35'),
(16, 'PS-2026-000008', 24, 3250.00, '2026-01-18 21:05:42', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-16 21:14:47', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-16 21:05:42', '2026-01-16 21:14:47'),
(17, 'PS-2026-000009', 22, 4050.00, '2026-01-18 21:10:51', 'unpaid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-16 21:10:51', '2026-01-16 21:10:51'),
(18, 'PS-2026-000010', 23, 5075.00, '2026-01-18 21:59:38', 'paid', 'gcash', NULL, NULL, 'pay_eUAaZgEAQgNMq8pxRM8XBXXA', 'cs_vbYbYJcJMKTe7vyCDgKZfLGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-28 00:34:15', NULL, 'pay_eUAaZgEAQgNMq8pxRM8XBXXA', 0, 'paymongo', NULL, NULL, NULL, '2026-01-16 21:59:38', '2026-01-28 00:34:15'),
(19, 'PS-2026-000011', 25, 5600.00, '2026-01-30 09:01:01', 'paid', 'gcash', NULL, NULL, 'pay_Vh4njvBP3n6Co1BJCCbr2nrD', 'cs_AJwCtY8R5QH1kVnsMbP7CAwL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-28 09:02:35', NULL, 'pay_Vh4njvBP3n6Co1BJCCbr2nrD', 0, 'paymongo', NULL, NULL, NULL, '2026-01-28 09:01:01', '2026-01-28 09:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `road_maintenance_received`
--

CREATE TABLE `road_maintenance_received` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_maintenance_id` varchar(255) NOT NULL,
  `road_name` varchar(255) NOT NULL,
  `road_status` enum('open','closed','partial') NOT NULL DEFAULT 'open',
  `closure_start` datetime DEFAULT NULL,
  `closure_end` datetime DEFAULT NULL,
  `alternative_routes` text DEFAULT NULL,
  `traffic_advisory` text DEFAULT NULL,
  `received_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Full data from external system' CHECK (json_valid(`received_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_type` enum('food_service','printing','transportation','supplies','other') NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `business_address` text DEFAULT NULL,
  `business_permit_number` varchar(100) DEFAULT NULL,
  `tin_number` varchar(50) DEFAULT NULL,
  `bir_registration` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_preferred_supplier` tinyint(1) NOT NULL DEFAULT 0,
  `created_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_products`
--

CREATE TABLE `supplier_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `product_code` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_category` enum('meal','beverage','printing','material','service','other') NOT NULL,
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `unit_of_measure` varchar(50) NOT NULL DEFAULT 'piece',
  `current_price` decimal(10,2) NOT NULL,
  `price_effective_date` date NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `product_photo_url` varchar(500) DEFAULT NULL,
  `price_list_document_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usage_reports_sent`
--

CREATE TABLE `usage_reports_sent` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `usage_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Duration, attendees, equipment' CHECK (json_valid(`usage_data`)),
  `external_report_id` varchar(255) DEFAULT NULL,
  `status` enum('sent','acknowledged','failed') NOT NULL DEFAULT 'sent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_is_active_start_date_end_date_index` (`is_active`,`start_date`,`end_date`),
  ADD KEY `announcements_type_priority_index` (`type`,`priority`),
  ADD KEY `announcements_target_audience_is_pinned_index` (`target_audience`,`is_pinned`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_facility_id_foreign` (`facility_id`),
  ADD KEY `bookings_is_resident_index` (`is_resident`),
  ADD KEY `bookings_special_discount_type_index` (`special_discount_type`);

--
-- Indexes for table `booking_conflicts`
--
ALTER TABLE `booking_conflicts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_conflicts_booking_id_index` (`booking_id`),
  ADD KEY `booking_conflicts_city_event_id_index` (`city_event_id`),
  ADD KEY `booking_conflicts_status_index` (`status`),
  ADD KEY `booking_conflicts_response_deadline_index` (`response_deadline`);

--
-- Indexes for table `booking_equipment`
--
ALTER TABLE `booking_equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_equipment_booking_id_index` (`booking_id`),
  ADD KEY `booking_equipment_equipment_item_id_index` (`equipment_item_id`);

--
-- Indexes for table `budget_allocations`
--
ALTER TABLE `budget_allocations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `budget_allocations_fiscal_year_category_unique` (`fiscal_year`,`category`),
  ADD KEY `budget_allocations_fiscal_year_index` (`fiscal_year`),
  ADD KEY `budget_allocations_category_index` (`category`);

--
-- Indexes for table `budget_expenditures`
--
ALTER TABLE `budget_expenditures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_expenditures_budget_allocation_id_index` (`budget_allocation_id`),
  ADD KEY `budget_expenditures_expenditure_date_index` (`expenditure_date`),
  ADD KEY `budget_expenditures_facility_id_index` (`facility_id`);

--
-- Indexes for table `citizen_program_registrations`
--
ALTER TABLE `citizen_program_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_citizen_registration` (`government_program_booking_id`,`citizen_id`),
  ADD KEY `citizen_program_registrations_registration_status_index` (`registration_status`);

--
-- Indexes for table `city_events`
--
ALTER TABLE `city_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_events_facility_id_index` (`facility_id`),
  ADD KEY `city_events_start_time_index` (`start_time`),
  ADD KEY `city_events_end_time_index` (`end_time`),
  ADD KEY `city_events_created_by_index` (`created_by`),
  ADD KEY `city_events_status_index` (`status`);

--
-- Indexes for table `community_maintenance_requests`
--
ALTER TABLE `community_maintenance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_external_report_id` (`external_report_id`),
  ADD KEY `idx_facility_id` (`facility_id`),
  ADD KEY `idx_resident_name` (`resident_name`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `energy_reports_received`
--
ALTER TABLE `energy_reports_received`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `energy_reports_received_external_report_id_unique` (`external_report_id`),
  ADD KEY `energy_reports_received_facility_id_foreign` (`facility_id`),
  ADD KEY `energy_reports_received_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `equipment_inventory`
--
ALTER TABLE `equipment_inventory`
  ADD PRIMARY KEY (`equipment_id`),
  ADD KEY `equipment_inventory_equipment_name_index` (`equipment_name`),
  ADD KEY `equipment_inventory_category_index` (`category`);

--
-- Indexes for table `equipment_items`
--
ALTER TABLE `equipment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_items_category_index` (`category`),
  ADD KEY `equipment_items_is_available_index` (`is_available`);

--
-- Indexes for table `event_schedules_sent`
--
ALTER TABLE `event_schedules_sent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_schedules_sent_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `external_projects`
--
ALTER TABLE `external_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_projects_external_project_id_unique` (`external_project_id`),
  ADD KEY `ext_proj_dates_idx` (`project_start_date`,`project_end_date`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`facility_id`),
  ADD UNIQUE KEY `facilities_name_unique` (`name`),
  ADD KEY `facilities_lgu_city_id_index` (`lgu_city_id`),
  ADD KEY `idx_lat_lng` (`latitude`,`longitude`),
  ADD KEY `idx_city` (`city`);

--
-- Indexes for table `facility_images`
--
ALTER TABLE `facility_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_images_facility_id_index` (`facility_id`);

--
-- Indexes for table `facility_reviews`
--
ALTER TABLE `facility_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_reviews_booking_id_foreign` (`booking_id`),
  ADD KEY `facility_reviews_facility_id_index` (`facility_id`),
  ADD KEY `facility_reviews_user_id_index` (`user_id`),
  ADD KEY `facility_reviews_rating_index` (`rating`),
  ADD KEY `facility_reviews_is_visible_index` (`is_visible`);

--
-- Indexes for table `government_program_bookings`
--
ALTER TABLE `government_program_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `government_program_bookings_source_seminar_id_index` (`source_seminar_id`),
  ADD KEY `government_program_bookings_coordination_status_index` (`coordination_status`),
  ADD KEY `government_program_bookings_event_date_index` (`event_date`),
  ADD KEY `government_program_bookings_finance_status_index` (`finance_status`);

--
-- Indexes for table `infrastructure_project_requests`
--
ALTER TABLE `infrastructure_project_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `infrastructure_project_requests_external_project_id_index` (`external_project_id`),
  ADD KEY `infrastructure_project_requests_status_index` (`status`),
  ADD KEY `infrastructure_project_requests_submitted_by_user_id_index` (`submitted_by_user_id`),
  ADD KEY `infrastructure_project_requests_created_at_index` (`created_at`);

--
-- Indexes for table `lgu_cities`
--
ALTER TABLE `lgu_cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lgu_cities_city_name_unique` (`city_name`),
  ADD UNIQUE KEY `lgu_cities_city_code_unique` (`city_code`),
  ADD KEY `lgu_cities_status_index` (`status`);

--
-- Indexes for table `liquidation_items`
--
ALTER TABLE `liquidation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_liquidation_program` (`government_program_booking_id`),
  ADD KEY `liquidation_items_category_index` (`category`);

--
-- Indexes for table `maintenance_requests_sent`
--
ALTER TABLE `maintenance_requests_sent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_requests_sent_facility_id_foreign` (`facility_id`),
  ADD KEY `maintenance_requests_sent_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_schedules_facility_id_index` (`facility_id`),
  ADD KEY `maintenance_schedules_start_date_index` (`start_date`),
  ADD KEY `maintenance_schedules_end_date_index` (`end_date`),
  ADD KEY `maintenance_schedules_maintenance_type_index` (`maintenance_type`),
  ADD KEY `maintenance_schedules_is_recurring_index` (`is_recurring`);

--
-- Indexes for table `maintenance_schedules_received`
--
ALTER TABLE `maintenance_schedules_received`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `maintenance_schedules_received_external_schedule_id_unique` (`external_schedule_id`),
  ADD KEY `maintenance_schedules_received_facility_id_foreign` (`facility_id`),
  ADD KEY `maint_sched_dates_idx` (`maintenance_start`,`maintenance_end`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_slips`
--
ALTER TABLE `payment_slips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_slips_slip_number_unique` (`slip_number`),
  ADD UNIQUE KEY `payment_slips_booking_id_unique` (`booking_id`),
  ADD KEY `payment_slips_status_index` (`status`),
  ADD KEY `payment_slips_payment_deadline_index` (`payment_deadline`),
  ADD KEY `payment_slips_paid_at_index` (`paid_at`),
  ADD KEY `payment_slips_gateway_transaction_id_index` (`gateway_transaction_id`),
  ADD KEY `payment_slips_or_number_index` (`or_number`),
  ADD KEY `payment_slips_treasurer_status_index` (`treasurer_status`);

--
-- Indexes for table `road_maintenance_received`
--
ALTER TABLE `road_maintenance_received`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `road_maintenance_received_external_maintenance_id_unique` (`external_maintenance_id`),
  ADD KEY `road_maint_closure_idx` (`closure_start`,`closure_end`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_supplier_type_index` (`supplier_type`),
  ADD KEY `suppliers_is_active_index` (`is_active`);

--
-- Indexes for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_products_supplier_id_index` (`supplier_id`),
  ADD KEY `supplier_products_is_available_index` (`is_available`),
  ADD KEY `supplier_products_product_category_index` (`product_category`);

--
-- Indexes for table `usage_reports_sent`
--
ALTER TABLE `usage_reports_sent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usage_reports_sent_facility_id_foreign` (`facility_id`),
  ADD KEY `usage_reports_sent_booking_id_foreign` (`booking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `booking_conflicts`
--
ALTER TABLE `booking_conflicts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_equipment`
--
ALTER TABLE `booking_equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `budget_allocations`
--
ALTER TABLE `budget_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_expenditures`
--
ALTER TABLE `budget_expenditures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `citizen_program_registrations`
--
ALTER TABLE `citizen_program_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_events`
--
ALTER TABLE `city_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `community_maintenance_requests`
--
ALTER TABLE `community_maintenance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `energy_reports_received`
--
ALTER TABLE `energy_reports_received`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_inventory`
--
ALTER TABLE `equipment_inventory`
  MODIFY `equipment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `equipment_items`
--
ALTER TABLE `equipment_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `event_schedules_sent`
--
ALTER TABLE `event_schedules_sent`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `external_projects`
--
ALTER TABLE `external_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `facility_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `facility_images`
--
ALTER TABLE `facility_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facility_reviews`
--
ALTER TABLE `facility_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `government_program_bookings`
--
ALTER TABLE `government_program_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `infrastructure_project_requests`
--
ALTER TABLE `infrastructure_project_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lgu_cities`
--
ALTER TABLE `lgu_cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `liquidation_items`
--
ALTER TABLE `liquidation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_requests_sent`
--
ALTER TABLE `maintenance_requests_sent`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_schedules_received`
--
ALTER TABLE `maintenance_schedules_received`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment_slips`
--
ALTER TABLE `payment_slips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `road_maintenance_received`
--
ALTER TABLE `road_maintenance_received`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usage_reports_sent`
--
ALTER TABLE `usage_reports_sent`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`facility_id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_equipment`
--
ALTER TABLE `booking_equipment`
  ADD CONSTRAINT `booking_equipment_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_equipment_equipment_item_id_foreign` FOREIGN KEY (`equipment_item_id`) REFERENCES `equipment_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budget_expenditures`
--
ALTER TABLE `budget_expenditures`
  ADD CONSTRAINT `budget_expenditures_budget_allocation_id_foreign` FOREIGN KEY (`budget_allocation_id`) REFERENCES `budget_allocations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `citizen_program_registrations`
--
ALTER TABLE `citizen_program_registrations`
  ADD CONSTRAINT `fk_registration_program` FOREIGN KEY (`government_program_booking_id`) REFERENCES `government_program_bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `energy_reports_received`
--
ALTER TABLE `energy_reports_received`
  ADD CONSTRAINT `energy_reports_received_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `energy_reports_received_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`facility_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_schedules_sent`
--
ALTER TABLE `event_schedules_sent`
  ADD CONSTRAINT `event_schedules_sent_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_lgu_city_id_foreign` FOREIGN KEY (`lgu_city_id`) REFERENCES `lgu_cities` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `facility_reviews`
--
ALTER TABLE `facility_reviews`
  ADD CONSTRAINT `facility_reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `facility_reviews_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`facility_id`) ON DELETE CASCADE;

--
-- Constraints for table `liquidation_items`
--
ALTER TABLE `liquidation_items`
  ADD CONSTRAINT `fk_liquidation_program` FOREIGN KEY (`government_program_booking_id`) REFERENCES `government_program_bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_requests_sent`
--
ALTER TABLE `maintenance_requests_sent`
  ADD CONSTRAINT `maintenance_requests_sent_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenance_requests_sent_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`facility_id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_schedules_received`
--
ALTER TABLE `maintenance_schedules_received`
  ADD CONSTRAINT `maintenance_schedules_received_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`facility_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_slips`
--
ALTER TABLE `payment_slips`
  ADD CONSTRAINT `payment_slips_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD CONSTRAINT `supplier_products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `usage_reports_sent`
--
ALTER TABLE `usage_reports_sent`
  ADD CONSTRAINT `usage_reports_sent_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usage_reports_sent_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`facility_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
