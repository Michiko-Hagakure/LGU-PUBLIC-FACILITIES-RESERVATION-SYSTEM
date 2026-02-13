-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 13, 2026 at 03:58 PM
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) DEFAULT 0,
  `last_synced_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `type`, `priority`, `target_audience`, `is_active`, `is_pinned`, `start_date`, `end_date`, `created_by`, `attachment_path`, `additional_info`, `created_at`, `updated_at`, `is_synced`, `last_synced_at`) VALUES
(1, 'Welcome to LGU Facility Reservation System', 'We are pleased to announce the launch of our new online facility reservation system. You can now book facilities, make payments, and track your reservations all in one place! Experience a seamless booking process with real-time availability checking and instant confirmation.', 'general', 'high', 'citizens', 1, 1, '2025-11-20', '2026-02-20', 1, NULL, 'For any questions or assistance, please contact our support team at support@lgu.gov.ph', '2025-11-20 06:39:14', '2025-11-20 06:39:14', 1, '2026-02-13 14:45:12'),
(2, 'Facility Maintenance Schedule - December 2025', 'Please be advised that the Main Conference Hall will undergo scheduled maintenance from December 15-20, 2025. The facility will be temporarily unavailable for booking during this period. We apologize for any inconvenience this may cause.', 'maintenance', 'medium', 'all', 1, 0, '2025-11-20', NULL, 1, NULL, 'Maintenance includes electrical upgrades, air conditioning servicing, and interior refurbishment.', '2025-11-20 06:39:14', '2025-11-20 06:39:14', 1, '2026-02-13 14:45:12'),
(3, 'Special Holiday Rates - Christmas Season', 'Enjoy special discounted rates for facility bookings during the Christmas season! Book now and get 20% off on all venues from December 1-31, 2025. Perfect for your holiday parties and celebrations. Limited slots available!', 'event', 'medium', 'citizens', 1, 0, '2025-11-20', '2025-12-31', 1, NULL, NULL, '2025-11-20 06:39:14', '2025-11-20 06:39:14', 1, '2026-02-13 14:45:12'),
(4, 'New Facility Added: Sports Complex', 'We are excited to announce the addition of our brand new Sports Complex to the reservation system. Features include basketball courts, volleyball courts, badminton courts, and a fully-equipped fitness center. Book now and enjoy world-class sports facilities!', 'facility_update', 'high', 'all', 1, 1, '2025-11-20', NULL, 1, NULL, 'Opening special: First 50 bookings get 30% discount!', '2025-11-20 06:39:14', '2025-11-20 06:39:14', 1, '2026-02-13 14:45:12'),
(5, 'URGENT: Payment Deadline Extension', 'Due to technical issues, we are extending the payment deadline for all pending reservations by 48 hours. Please ensure your payments are settled before the new deadline to avoid cancellation.', 'urgent', 'urgent', 'citizens', 1, 0, '2025-11-20', '2025-11-23', 1, NULL, NULL, '2025-11-20 06:39:14', '2025-11-20 06:39:14', 1, '2026-02-13 14:45:12'),
(6, 'New Equipment Available for Rent', 'Great news! We have added new equipment to our inventory including premium sound systems, LED screens, and professional lighting equipment. Perfect for your events and conferences.', 'general', 'low', 'all', 1, 0, '2025-11-20', NULL, 1, NULL, 'Check our equipment catalog for pricing and availability.', '2025-11-15 06:39:14', '2025-11-15 06:39:14', 1, '2026-02-13 14:45:12');

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
  `payment_rejection_reason` text DEFAULT NULL,
  `payment_rejected_at` timestamp NULL DEFAULT NULL,
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
  `payment_tier` int(10) UNSIGNED DEFAULT NULL,
  `down_payment_amount` decimal(10,2) DEFAULT 0.00,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `amount_remaining` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `down_payment_paid_at` timestamp NULL DEFAULT NULL,
  `paymongo_checkout_id` varchar(255) DEFAULT NULL,
  `paymongo_payment_id` varchar(255) DEFAULT NULL,
  `payment_recorded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejection_type` varchar(50) DEFAULT NULL,
  `rejection_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rejection_fields`)),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `source_system` varchar(100) DEFAULT NULL COMMENT 'External system that created the booking',
  `external_reference_id` varchar(100) DEFAULT NULL COMMENT 'Reference ID from external system',
  `booking_reference` varchar(50) DEFAULT NULL,
  `is_synced` tinyint(1) DEFAULT 0,
  `last_synced_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `facility_id`, `start_time`, `end_time`, `base_rate`, `extension_rate`, `equipment_total`, `subtotal`, `city_of_residence`, `is_resident`, `resident_discount_rate`, `resident_discount_amount`, `special_discount_type`, `special_discount_id_path`, `special_discount_rate`, `special_discount_amount`, `total_discount`, `total_amount`, `purpose`, `expected_attendees`, `special_requests`, `valid_id_type`, `valid_id_front_path`, `valid_id_back_path`, `valid_id_selfie_path`, `supporting_doc_path`, `rejected_reason`, `canceled_reason`, `canceled_at`, `user_name`, `applicant_name`, `applicant_email`, `applicant_phone`, `applicant_address`, `event_name`, `event_description`, `event_date`, `status`, `payment_rejection_reason`, `payment_rejected_at`, `staff_verified_by`, `staff_verified_at`, `staff_notes`, `admin_approved_by`, `admin_approved_at`, `admin_approval_notes`, `reserved_until`, `created_at`, `updated_at`, `expired_at`, `payment_tier`, `down_payment_amount`, `amount_paid`, `amount_remaining`, `payment_method`, `down_payment_paid_at`, `paymongo_checkout_id`, `paymongo_payment_id`, `payment_recorded_by`, `rejection_type`, `rejection_fields`, `deleted_at`, `deleted_by`, `source_system`, `external_reference_id`, `booking_reference`, `is_synced`, `last_synced_at`) VALUES
(1, 11, 13, '2026-02-23 08:00:00', '2026-02-23 11:00:00', 43500.00, 0.00, 0.00, 43500.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 8700.00, 8700.00, 34800.00, 'Special Event', 300, NULL, 'School ID', 'bookings/valid_ids/front/IFXYgmGZKxDoSytU19Do473TGh6OhiiIWv99jfgz.jpg', 'bookings/valid_ids/back/HxKn4KQEkBqqEqj1Fxb2CO2q5dPks44UXKi1lcKd.png', 'bookings/valid_ids/selfie/uvxfQtoAQbgiiN6DWEic7DCVKcQW2LLaYhDJdEi0.png', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Miyuki Hagakure', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 21:31:38', '2026-02-13 04:31:37', '2026-02-13 04:31:37', 100, 34800.00, 0.00, 34800.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(3, 11, 16, '2026-03-07 08:00:00', '2026-03-07 11:00:00', 6250.00, 750.00, 0.00, 7000.00, 'Quezon City', 1, 30.00, 2100.00, NULL, NULL, 0.00, 0.00, 2100.00, 4900.00, 'Community Meeting', 50, NULL, 'National ID (PhilSys)', 'bookings/valid_ids/front/0MNO5PT5rRrW4JaDAwVqmjriyJz5TLcSkVR0ybvy.jpg', 'bookings/valid_ids/back/VuIV3nPwOwXMbNdeR6DRDu3FNiSTpRb9T4TPWq0Q.png', 'bookings/valid_ids/selfie/58Q2phNHlkJXblnFv9Co4cYHMnqECqmS6KtifuRq.png', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Miyuki Hagakure', NULL, 'shaneyrhainey@gmail.com', NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-13 12:49:32', '2026-02-13 14:54:53', '2026-02-13 14:54:53', 100, 4900.00, 0.00, 4900.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:02'),
(4, 5, 11, '2026-01-02 08:00:00', '2026-01-02 13:00:00', 4050.00, 600.00, 0.00, 4650.00, 'Quezon City', 0, 0.00, 0.00, 'senior', NULL, 20.00, 930.00, 930.00, 3720.00, 'Wedding Reception', 30, NULL, 'Senior Citizen ID', 'bookings/valid_ids/front/a6Jwc3Fz7ykmuODz9Rxd4YOIns0JW8kG4MXVDXau.jpg', 'bookings/valid_ids/back/06zURp2MmpZDgH0eKdPybtNClVxiscJJfr26liNj.jpg', 'bookings/valid_ids/selfie/VKWm1biv7mf9euvnIpTpT76YOGkr5fj87iSa2agp.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2025-12-25 01:36:50', NULL, 2, '2025-12-27 07:57:03', NULL, NULL, '2025-12-24 23:51:41', '2026-02-11 11:37:11', NULL, NULL, 0.00, 3720.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(5, 5, 13, '2026-01-05 08:00:00', '2026-01-05 11:00:00', 7250.00, 0.00, 4000.00, 11250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 11250.00, 'Educational Workshop', 50, NULL, 'SSS ID', 'bookings/valid_ids/front/YZzhEExri5HZYddmAhkpWDm8Ufrp7Vk0aoNV9WI6.jpg', 'bookings/valid_ids/back/AMSUToKjpAHLdaQZhTrukakweKf5bGpHIbStDI2S.jpg', 'bookings/valid_ids/selfie/GPjJY8xnPvEnsfAn52Rx1l4NawG68j3Nbu0LzMS4.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2025-12-27 07:25:28', NULL, 2, '2025-12-27 07:56:44', NULL, NULL, '2025-12-27 07:24:32', '2026-02-11 11:37:11', NULL, NULL, 0.00, 11250.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(6, 5, 13, '2026-01-05 13:00:00', '2026-01-05 18:00:00', 5075.00, 875.00, 10300.00, 16250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 16250.00, 'Charity Event', 35, NULL, 'PhilHealth ID', 'bookings/valid_ids/front/MHmQK4HZYkCY5mfmFr0h6WE7L7zYZdEZhmAdzxEE.jpg', 'bookings/valid_ids/back/KJPASj4DUJHkIusGGYPBDkj5EJiM9YUtdPRBA3tk.jpg', 'bookings/valid_ids/selfie/w3LnMkc2AIu8mpyEbNj3rGy79Q0UmvxaPB5Rp2ID.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2025-12-27 22:43:22', NULL, 2, '2025-12-29 09:39:30', NULL, NULL, '2025-12-27 22:42:19', '2026-02-11 11:37:11', NULL, NULL, 0.00, 16250.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(7, 5, 11, '2026-01-05 08:00:00', '2026-01-05 13:00:00', 6750.00, 1000.00, 48050.00, 55800.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 55800.00, 'Cultural Event', 50, 'Please arrange and prepare the equipment well.\r\n\r\nThank you!', 'Passport', 'bookings/valid_ids/front/RrR1HQcPYqwSuea3khwPElqvFzrmC9vna9uzI4wm.jpg', 'bookings/valid_ids/back/ZAyZsekJmpz05cuSWvILFZuf6wQldVzgP0CfxJWl.jpg', 'bookings/valid_ids/selfie/zUYcct6mmPu9zLbKc1cyvlAKVZmoPGMO0sLPyO8D.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2025-12-28 05:22:45', NULL, 2, '2025-12-28 06:02:37', NULL, NULL, '2025-12-28 04:24:57', '2026-02-11 11:37:11', NULL, NULL, 0.00, 55800.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(8, 5, 14, '2026-01-06 08:00:00', '2026-01-06 11:00:00', 19500.00, 0.00, 44600.00, 64100.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 64100.00, 'Boxing Match', 150, 'Let\'s Gooooo.', 'SSS ID', 'bookings/valid_ids/front/J0WLEYduNKIxmjuoBHhaXv9RzqjC4r4RWBnEiJvV.jpg', 'bookings/valid_ids/back/LKdUinnrggXhurC60rsCvhWXQIyLlqAsgvInazr4.jpg', 'bookings/valid_ids/selfie/EGMvrjajDi9cFEJOQ34guFRq3mPfJi2b0BZzVpBW.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2025-12-28 09:14:14', NULL, 2, '2025-12-29 23:20:50', NULL, NULL, '2025-12-28 08:31:14', '2026-02-11 11:37:11', NULL, NULL, 0.00, 64100.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(9, 5, 14, '2026-01-06 13:00:00', '2026-01-06 16:00:00', 6500.00, 0.00, 0.00, 6500.00, 'Quezon City', 0, 0.00, 0.00, 'senior', NULL, 20.00, 1300.00, 1300.00, 5200.00, 'Family Reunion', 50, NULL, 'Senior Citizen ID', 'bookings/valid_ids/front/r1yc7Sw63XRTNpGgTlW71VuJ1vU84CE5WYSMzV2k.jpg', 'bookings/valid_ids/back/fX6DlRG1Dtlk6IORagZcrZK4q4rthfjGcjIGiG0m.jpg', 'bookings/valid_ids/selfie/YzM5cT1r1jJn2ER1Y8L4pJife1Z07fEyZFocmRI7.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2025-12-28 09:14:59', NULL, 2, '2025-12-29 23:20:22', NULL, NULL, '2025-12-28 08:58:30', '2026-02-11 11:37:11', NULL, NULL, 0.00, 5200.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(10, 5, 14, '2026-01-07 08:00:00', '2026-01-07 13:00:00', 3250.00, 500.00, 0.00, 3750.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 750.00, 750.00, 3000.00, 'Sports Event', 25, NULL, 'School ID', 'bookings/valid_ids/front/EqLx4aE87e7pW2ZfTFlhVKIvcIvf9fslBpgVtc6B.jpg', 'bookings/valid_ids/back/hhzQUZNn8bgghFMe6l92O9RwDXaDCAMnTWzscnLB.jpg', 'bookings/valid_ids/selfie/QVTEMKkJ4rav7KaoZ7GVIgP9R8KOtaf2kalUnCak.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'refunded', NULL, NULL, 3, '2025-12-29 23:15:25', NULL, 2, '2025-12-29 23:19:43', NULL, NULL, '2025-12-29 23:13:12', '2026-02-11 11:37:11', NULL, NULL, 0.00, 3000.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(11, 5, 13, '2026-01-07 08:00:00', '2026-01-07 11:00:00', 5075.00, 0.00, 0.00, 5075.00, 'Quezon City', 0, 0.00, 0.00, 'senior', NULL, 20.00, 1015.00, 1015.00, 4060.00, 'Company Seminar/Training', 35, NULL, 'Senior Citizen ID', 'bookings/valid_ids/front/wkHOe0Y3zUEfVXV6IVBKH9ROwAtEKztwA1Xlz83m.jpg', 'bookings/valid_ids/back/TG1auN7tohLSgBgaxIelqNowS4YpXDm5icwV4HKZ.jpg', 'bookings/valid_ids/selfie/8St6WNPsJSpx09P3Pwq9wjnfmcRYlHQhHjMYsn9o.jpg', NULL, 'Wrong id', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rejected', NULL, NULL, 3, '2025-12-31 21:26:12', NULL, NULL, NULL, NULL, NULL, '2025-12-29 23:28:35', '2025-12-31 21:26:12', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(12, 5, 11, '2026-01-09 08:00:00', '2026-01-09 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 810.00, 810.00, 3240.00, 'Birthday Celebration', 30, NULL, 'School ID', 'bookings/valid_ids/front/CF2hDaE5hVmfPBdZnQjeOnSCVTIzfhNej3HxIHPV.jpg', 'bookings/valid_ids/back/9AjymiqenuSvvhZ9BvJVtTexWRp37TZeq6tIltyY.jpg', 'bookings/valid_ids/selfie/HnNLOnDbEc1fYIDWbNAlB0cXgHXKWeGCN3tKOhLz.jpg', NULL, 'Wrong id', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rejected', NULL, NULL, 3, '2025-12-31 21:37:21', NULL, NULL, NULL, NULL, NULL, '2025-12-31 21:15:27', '2025-12-31 21:37:21', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(13, 5, 13, '2026-01-09 08:00:00', '2026-01-09 11:00:00', 5075.00, 0.00, 0.00, 5075.00, 'Quezon City', 0, 0.00, 0.00, 'pwd', NULL, 20.00, 1015.00, 1015.00, 4060.00, 'Birthday Celebration', 35, NULL, 'PWD ID', 'bookings/valid_ids/front/dypRFSx4PSV5KeQ77fkltOWGa5lc6BUys0vM2yLg.jpg', 'bookings/valid_ids/back/GBbiRHeSBd9tsBTwhw49td6Ix7HVuV5OwHiUoYfx.jpg', 'bookings/valid_ids/selfie/7zYPWKFZBp4J2gbtueoAQRBrnyMuSkesluwQRQGB.jpg', NULL, 'Wrong date', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-31 21:40:01', '2025-12-31 21:40:28', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(14, 5, 14, '2026-01-09 08:00:00', '2026-01-09 11:00:00', 3250.00, 0.00, 0.00, 3250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 3250.00, 'Birthday Celebration', 25, NULL, 'SSS ID', 'bookings/valid_ids/front/2aw3x07JpXl6uHfEjoFykF85FG4u2A4AURFXLtlN.jpg', 'bookings/valid_ids/back/QFJP2od0xa6onYhUEy3JEF54eJXItyjrbHGoGUel.jpg', 'bookings/valid_ids/selfie/u9Pi3f4plWjUompYxI0UcG4hTTpD2lpjGKg8EcCR.jpg', NULL, 'Wrong date', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-31 21:46:14', '2025-12-31 21:46:45', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(15, 5, 11, '2026-01-15 08:00:00', '2026-01-15 11:00:00', 4050.00, 0.00, 2000.00, 6050.00, 'Quezon City', 0, 0.00, 0.00, 'pwd', NULL, 20.00, 1210.00, 1210.00, 4840.00, 'Wala lang', 30, NULL, 'PWD ID', 'bookings/valid_ids/front/aqPFsFtbaabTMdC663eLmp83LgRmzLDi8QNa0PI3.jpg', 'bookings/valid_ids/back/dEHFY7maClbekAhZn9iTY2DRrGkPooHpIhesqjlI.jpg', 'bookings/valid_ids/selfie/nRQLGuwIEWa3Me0SCh4oSNc06lmdcTuxQy6CtVhH.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-01-07 03:20:35', NULL, 2, '2026-01-07 03:58:46', NULL, NULL, '2026-01-06 18:44:05', '2026-02-11 11:37:11', NULL, NULL, 0.00, 4840.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(16, 5, 11, '2026-01-21 08:00:00', '2026-01-21 11:00:00', 4455.00, 0.00, 0.00, 4455.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4455.00, 'Wedding Reception', 33, NULL, 'SSS ID', 'bookings/valid_ids/front/il0YlzEUnwh6PkiiLOsno0V0CUQxhsNGo6prVKpI.png', 'bookings/valid_ids/back/1sHbvPEBs3VmzYTyHDHskdNCFle0pRZKm7Nb0tl2.png', 'bookings/valid_ids/selfie/zlZjzIfnOmSHZOJhWVrBYVONC6WbiqKKdTo5vQEF.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-01-13 03:03:05', NULL, 2, '2026-01-13 03:07:19', NULL, NULL, '2026-01-13 03:01:12', '2026-02-11 11:37:11', NULL, NULL, 0.00, 4455.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(17, 5, 13, '2026-01-21 08:00:00', '2026-01-21 11:00:00', 5510.00, 0.00, 0.00, 5510.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 5510.00, 'Birthday Celebration', 38, NULL, 'SSS ID', 'bookings/valid_ids/front/zqXzLjohE6pJA9N7UJJ1QHkQRJjU5smkD26PkjLx.png', 'bookings/valid_ids/back/VfLiSwHrdPQMetzzVyyhLZCptAOuJx5UdIv7Ho0M.png', 'bookings/valid_ids/selfie/AqwxuhD5EzkATtC6M1Hq7HJLzoFAOMs7z7msDIxX.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-01-13 03:14:41', NULL, 2, '2026-01-13 03:17:30', NULL, NULL, '2026-01-13 03:13:28', '2026-02-11 11:37:11', NULL, NULL, 0.00, 5510.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(18, 5, 14, '2026-01-22 08:00:00', '2026-01-22 11:00:00', 3250.00, 0.00, 20000.00, 23250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 23250.00, 'Birthday Celebration', 25, NULL, 'SSS ID', 'bookings/valid_ids/front/KUAwCsDLjo77aUHuiZFrQTVqinok2SQVPkYOb7ib.png', 'bookings/valid_ids/back/tC2Rw7QhoEPorKsk7hLoV6Rp9HLikh6d3bsRPjO7.png', 'bookings/valid_ids/selfie/vE0aqXCyTL1spK1Qla5dYBYdxqQfe9saOEdjF6xU.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-01-13 19:19:26', NULL, 2, '2026-01-13 19:23:14', NULL, NULL, '2026-01-13 19:16:39', '2026-02-11 11:37:11', NULL, NULL, 0.00, 23250.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(19, 5, 11, '2026-01-22 08:00:00', '2026-01-22 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/PrReX91J2PROVmlhzzWEMd4UzXoQsZ7UcMV8UCZI.png', 'bookings/valid_ids/back/NmqpAOAxmNG9g1ve9pkfAp9PIQkUDVwxPnVKk2UI.png', 'bookings/valid_ids/selfie/Q53paABKWVZIGpkLBDbuiPydHP52f6AF7ipegJJ3.png', NULL, NULL, 'Payment deadline exceeded', '2026-01-16 21:25:35', 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'canceled', NULL, NULL, 3, '2026-01-13 19:30:28', NULL, NULL, NULL, NULL, NULL, '2026-01-13 19:27:54', '2026-01-16 21:25:35', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(21, 5, 11, '2026-01-22 18:00:00', '2026-01-22 21:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/EX74RHAog3aPaQ9vIYNe6QWFSGj9Hlfps7SZQPN8.png', 'bookings/valid_ids/back/8GZgFDuH0NB7Pd5c99w5mUDZnBxHUAUULsQbx4My.png', 'bookings/valid_ids/selfie/VERadGBxFnnZ5yrxr0iH36IEI4UFYnVAt4Ho6brB.png', NULL, NULL, 'Payment deadline exceeded', '2026-01-16 21:25:35', 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'canceled', NULL, NULL, 3, '2026-01-13 19:37:01', NULL, NULL, NULL, NULL, NULL, '2026-01-13 19:36:09', '2026-01-16 21:25:35', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(22, 5, 11, '2026-01-26 08:00:00', '2026-01-26 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/jpLjgTTINuGNQoLvOwq5u9wKMOC1JXlds91FXr5C.png', 'bookings/valid_ids/back/0przzM5OVAUM78cdm73ZckJAjc5ohxQBw413GhLf.png', 'bookings/valid_ids/selfie/NDbwuPt1E7MpDCFDqa6omHrYlyBhwkp3YctYUx1M.png', NULL, NULL, 'Payment deadline exceeded (auto-expired)', NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, 3, '2026-01-16 21:10:50', NULL, NULL, NULL, NULL, NULL, '2026-01-16 20:40:21', '2026-02-07 04:31:36', '2026-02-07 04:31:36', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(23, 5, 13, '2026-01-26 08:00:00', '2026-01-26 11:00:00', 5075.00, 0.00, 0.00, 5075.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 5075.00, 'Wedding Reception', 35, NULL, 'SSS ID', 'bookings/valid_ids/front/XYL6TtEvNdpn8f0XA4QWl5XTBImLZfH2nAefs62T.png', 'bookings/valid_ids/back/T2I3kCIBJKYu5Ud0izD4DPVa4m0fTKti0BdSHf5H.png', 'bookings/valid_ids/selfie/tXwadIXCAjAZyvLJnTzNpyHD2ang7a55BiUHUHak.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-01-16 21:59:38', NULL, NULL, NULL, NULL, NULL, '2026-01-16 20:49:43', '2026-02-11 11:37:11', NULL, NULL, 0.00, 5075.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(24, 5, 14, '2026-01-26 08:00:00', '2026-01-26 11:00:00', 3250.00, 0.00, 0.00, 3250.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 3250.00, 'Charity Event', 25, NULL, 'SSS ID', 'bookings/valid_ids/front/SOhZ0pOzjQJyX33be30jByU9aK2z7No4a5lNIGeY.png', 'bookings/valid_ids/back/Pc0cuCAQV5ODny5Wh6YrYn2sxWBU3IODN1XROoYY.png', 'bookings/valid_ids/selfie/wRPaBPaGRLGVlKOXAWuJoWvfq6IXc7zJW5349dzw.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-01-16 21:05:41', NULL, 2, '2026-01-16 21:15:50', NULL, NULL, '2026-01-16 21:03:00', '2026-02-11 11:37:11', NULL, NULL, 0.00, 3250.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(25, 5, 12, '2026-02-05 08:00:00', '2026-02-05 11:00:00', 7000.00, 0.00, 0.00, 7000.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 1400.00, 1400.00, 5600.00, 'Birthday Celebration', 50, NULL, 'School ID', 'bookings/valid_ids/front/UW0BduhqczcTbKKyUCiGr3tKTXDKNbj3jS2Uruac.png', 'bookings/valid_ids/back/eqMbowRY361dGEAUVEtyHvzk91DtOAVryUQUVcH9.png', 'bookings/valid_ids/selfie/bSGGSSQUOYqBVecVBlrrfRW3jtwQxS73mdMGs7O0.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-01-28 09:01:01', NULL, NULL, NULL, NULL, NULL, '2026-01-28 08:59:55', '2026-02-11 11:37:11', NULL, NULL, 0.00, 5600.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(26, NULL, 11, '2026-01-29 08:00:00', '2026-01-29 11:00:00', 13500.00, 0.00, 0.00, 13500.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 13500.00, 'wegsgsgs', 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Event date has passed (auto-expired)', NULL, 'Hawk', 'Hawk', '1hawkeye101010101@gmail.com', '09515691003', 'Area 5a Naval Street', 'My birthday', NULL, NULL, 'expired', NULL, NULL, NULL, NULL, 'Submitted via API from: LGU1_Citizen_Portal (Ref: CP-1769701221-18)', NULL, NULL, NULL, NULL, '2026-01-29 15:40:21', '2026-02-07 04:47:11', '2026-02-07 04:47:11', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(27, 5, 11, '2026-02-10 08:00:00', '2026-02-10 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/IeJvIlvLGvyKm1ivVHGPbkQDib8CjTSUpid2nASx.jpg', 'bookings/valid_ids/back/q14zdeOo3LWCwph362nSQQhJmwsj6keQUBghu3rD.jpg', 'bookings/valid_ids/selfie/Ecx0zBdelGYuge32EvqEoO8rvvZ2kouTJ2nNJE5p.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-02-02 01:34:36', NULL, NULL, NULL, NULL, NULL, '2026-02-02 01:31:13', '2026-02-11 11:37:11', NULL, NULL, 0.00, 4050.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(29, 5, 13, '2026-02-10 08:00:00', '2026-02-10 11:00:00', 5075.00, 0.00, 0.00, 5075.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 5075.00, 'Birthday Celebration', 35, NULL, 'SSS ID', 'bookings/valid_ids/front/E0NJcSIXLMf64yTrIFzYZDzTxhnaZOdSmYhtW2Aw.jpg', 'bookings/valid_ids/back/AXjgEi2jV46KrZYhhNSmgMw810wPczSaVQ7q4HxU.jpg', 'bookings/valid_ids/selfie/Tr2CHArG3NrjzG4eiTHRlzytcBiGHh0z7LpdRoLJ.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', NULL, NULL, 3, '2026-02-02 03:04:51', NULL, 2, '2026-02-02 03:07:33', NULL, NULL, '2026-02-02 03:03:28', '2026-02-11 11:37:11', NULL, NULL, 0.00, 5075.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(30, NULL, 11, '2026-02-17 09:00:00', '2026-02-17 12:00:00', 0.00, NULL, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Test HR Admin (Housing & Resettlement)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 04:02:32', '2026-02-03 04:07:14', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Housing_Resettlement', NULL, NULL, 1, '2026-02-13 14:45:03'),
(31, NULL, 11, '2026-02-22 14:00:00', '2026-02-22 16:00:00', 0.00, NULL, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'Beneficiary Orientation - Test Batch - Test request from Housing and Resettlement Management', 50, 'Projector and sound system needed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Test HR Admin | hradmin@housing.gov.ph | 09171234567', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 04:30:15', '2026-02-03 04:53:29', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Housing_Resettlement', NULL, NULL, 1, '2026-02-13 14:45:03'),
(32, NULL, 11, '2026-03-03 11:00:00', '2026-03-03 17:00:00', 0.00, NULL, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'Beneficiary Orientation - Test Batch - Test request from Housing and Resettlement Management', 50, 'Projector and sound system needed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Test HR Admin | hradmin@housing.gov.ph | 09171234567', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 05:27:00', '2026-02-03 05:51:23', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Housing_Resettlement', NULL, NULL, 1, '2026-02-13 14:45:03'),
(33, NULL, 18, '2026-02-05 09:43:00', '2026-02-05 10:44:00', 0.00, NULL, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'TEST - Housing and Resettlement', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lance Mabato', 'Lance Mabato', 'lancearon74@gmail.com', '09383368682', NULL, NULL, NULL, NULL, 'completed', NULL, NULL, NULL, NULL, NULL, 2, '2026-02-06 15:04:50', NULL, NULL, '2026-02-04 01:43:17', '2026-02-11 15:30:00', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Housing_Resettlement', NULL, NULL, 1, '2026-02-13 14:45:03'),
(34, NULL, 14, '2026-03-28 09:54:00', '2026-03-28 10:55:00', 0.00, NULL, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'TEST - Housing and Resettlement', 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lance Mabato', 'Lance Mabato', 'lancearon74@gmail.com', '09383368682', NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, NULL, NULL, NULL, 2, '2026-02-06 15:04:03', NULL, NULL, '2026-02-04 01:55:01', '2026-02-06 15:04:03', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Housing_Resettlement', NULL, NULL, 1, '2026-02-13 14:45:03'),
(35, 18, 11, '2026-02-12 08:00:00', '2026-02-12 11:00:00', 13500.00, 0.00, 225600.00, 239100.00, 'Quezon City', 0, 0.00, 0.00, 'pwd', NULL, 20.00, 47820.00, 47820.00, 191280.00, 'Wedding Reception', 100, 'Wedding Reception Attend pls.', 'PWD ID', 'bookings/valid_ids/front/7jm1t524WPuNhWYeWFI3NCaE2LCKTA7gpYbPX944.jpg', 'bookings/valid_ids/back/akHd6lQS3FXqnFKQ9dpLcEvjJYpD4m0GtV0M6ltE.jpg', 'bookings/valid_ids/selfie/nUha3OOq2wBj3jk1y68iq0LcJyXdxTQovPzMpA2l.jpg', NULL, NULL, NULL, NULL, 'Jocelyn Pecolera', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-04 09:20:45', 'Can i attend?', 2, '2026-02-04 09:30:39', NULL, NULL, '2026-02-04 08:15:55', '2026-02-11 11:37:11', NULL, NULL, 0.00, 191280.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(36, NULL, 11, '2026-02-16 08:00:00', '2026-02-16 11:00:00', 13500.00, 0.00, 2500.00, 16000.00, NULL, 0, 0.00, 0.00, 'student', NULL, 20.00, 3200.00, 3200.00, 12800.00, 'For celebration', 100, NULL, 'School ID', '/uploads/valid_ids/valid_id_front_1770311285_22.jpg', '/uploads/valid_ids/valid_id_back_1770311285_22.jpg', '/uploads/valid_ids/valid_id_selfie_1770311285_22.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', 'Cristian Mark Angelo Llaneta', 'lcristianmarkangelo@gmail.com', '09515691003', 'Area 5a Naval Street, Sauyo, Quezon City, NCR', NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-05 17:17:37', NULL, 2, '2026-02-06 15:05:13', NULL, NULL, '2026-02-05 17:08:05', '2026-02-11 11:37:11', NULL, NULL, 0.00, 12800.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(37, 5, 13, '2026-02-14 08:00:00', '2026-02-14 11:00:00', 14500.00, 0.00, 0.00, 14500.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 2900.00, 2900.00, 11600.00, 'Birthday Celebration', 100, NULL, 'School ID', 'bookings/valid_ids/front/sSnkv772xdm7ExtZogjmwGL5Z5CBwCpxvcssZGeC.jpg', 'bookings/valid_ids/back/aYNWsTFeUOmaOnTjOOjRCw7lHElDapJmjSr6IEiB.jpg', 'bookings/valid_ids/selfie/n80HaOOBwd9wdtStUL9mN5nCJFEv8onUdigjynLy.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-06 03:33:26', NULL, 2, '2026-02-06 16:24:27', NULL, NULL, '2026-02-06 03:32:22', '2026-02-11 11:37:11', NULL, NULL, 0.00, 11600.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(38, NULL, 16, '2026-02-17 08:00:00', '2026-02-17 13:00:00', 6250.00, 1500.00, 0.00, 7750.00, NULL, 0, 0.00, 0.00, 'student', NULL, 20.00, 1550.00, 1550.00, 6200.00, 'Central to these events are rituals, prayers, and ceremonies (e.g., mass, puja, meditation) that allow adherents to express faith, honor deities, and seek divine blessing.', 50, NULL, 'School ID', '/uploads/valid_ids/valid_id_front_1770441129_22.jpg', '/uploads/valid_ids/valid_id_back_1770441129_22.jpg', '/uploads/valid_ids/valid_id_selfie_1770441129_22.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', 'Cristian Mark Angelo Llaneta', 'lcristianmarkangelo@gmail.com', '09515691003', 'Area 5a Naval Street, Sauyo, Quezon City, NCR', 'Religious Event', NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-07 05:37:36', NULL, 2, '2026-02-07 07:39:27', NULL, NULL, '2026-02-07 05:12:09', '2026-02-11 11:37:11', NULL, NULL, 0.00, 6200.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(39, 5, 17, '2026-02-16 08:00:00', '2026-02-16 11:00:00', 5000.00, 600.00, 6840.00, 12440.00, 'Quezon City', 1, 30.00, 3732.00, 'student', NULL, 20.00, 1741.60, 5473.60, 6966.40, 'Community Meeting', 40, NULL, 'School ID', 'bookings/valid_ids/front/bsmNmvXjjObNk6QmUo9wCEWvHao7PjG9SsLXywAi.jpg', 'bookings/valid_ids/back/Dxxh5kF86WyzYm7Yz9Y3sub89d4SbkmpSpdaLaEq.jpg', 'bookings/valid_ids/selfie/GOg0MLjTZQ7Osyp6MOJ7bdXtPAjRUCeKIAQpB6YN.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-07 08:08:18', NULL, 2, '2026-02-07 08:09:09', NULL, NULL, '2026-02-07 07:56:01', '2026-02-11 11:37:11', NULL, NULL, 0.00, 6966.40, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(40, NULL, 16, '2026-02-17 15:00:00', '2026-02-17 18:00:00', 6250.00, 750.00, 5500.00, 12500.00, NULL, 0, 0.00, 0.00, 'student', NULL, 20.00, 2500.00, 2500.00, 10000.00, 'May pagpupulong lang', 50, NULL, 'School ID', '/uploads/valid_ids/valid_id_front_1770453808_22.jpg', '/uploads/valid_ids/valid_id_back_1770453808_22.jpg', '/uploads/valid_ids/valid_id_selfie_1770453808_22.jpg', NULL, 'Pareserved nalang sa ibang facility', NULL, NULL, 'Cristian Mark Angelo Llaneta', 'Cristian Mark Angelo Llaneta', 'lcristianmarkangelo@gmail.com', '09515691003', 'Area 5a Naval Street, Sauyo, Quezon City, NCR', 'Community Gathering', NULL, NULL, 'rejected', NULL, NULL, 3, '2026-02-07 08:44:31', 'Submitted via API from: LGU1_Citizen_Portal (Ref: CP-1770453808-22)', NULL, NULL, NULL, NULL, '2026-02-07 08:43:28', '2026-02-07 08:44:31', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(41, NULL, 16, '2026-02-17 15:00:00', '2026-02-17 18:00:00', 6250.00, 750.00, 0.00, 7000.00, NULL, 0, 0.00, 0.00, 'student', NULL, 20.00, 1400.00, 1400.00, 5600.00, 'May pagpupulong lang', 50, NULL, 'School ID', '/uploads/valid_ids/valid_id_front_1770453983_22.jpg', '/uploads/valid_ids/valid_id_back_1770453983_22.jpg', '/uploads/valid_ids/valid_id_selfie_1770453983_22.jpg', NULL, 'Hanap nalang po ng ibang facility.\r\nThank you!', NULL, NULL, 'Cristian Mark Angelo Llaneta', 'Cristian Mark Angelo Llaneta', 'lcristianmarkangelo@gmail.com', '09515691003', 'Area 5a Naval Street, Sauyo, Quezon City, NCR', 'Community Gathering', NULL, NULL, 'rejected', NULL, NULL, 3, '2026-02-07 08:46:43', NULL, NULL, NULL, NULL, NULL, '2026-02-07 08:46:23', '2026-02-11 11:37:11', NULL, NULL, 0.00, 5600.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(42, 5, 15, '2026-02-16 08:00:00', '2026-02-16 11:00:00', 7500.00, 0.00, 0.00, 7500.00, 'Quezon City', 1, 30.00, 2250.00, 'student', NULL, 20.00, 1050.00, 3300.00, 4200.00, 'Wedding Reception', 50, NULL, 'School ID', 'bookings/valid_ids/front/UcS97LH4vOg9OWyQtAPTldlX4gY1kAlUyYxpbVz4.jpg', 'bookings/valid_ids/back/5LiOSe98yt34nKT7UBJaLq8peX3rweV3OsUiVuYZ.jpg', 'bookings/valid_ids/selfie/W70eqJwE0mhy0NUxGkgU0Ih4Dh4x6jEdX1BCJiIq.jpg', NULL, 'Hanap nalang po ibang facility.', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rejected', NULL, NULL, 3, '2026-02-07 13:45:00', NULL, NULL, NULL, NULL, NULL, '2026-02-07 13:43:55', '2026-02-07 13:45:00', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(43, 5, 15, '2026-02-16 08:00:00', '2026-02-16 11:00:00', 14400.00, 0.00, 0.00, 14400.00, 'Quezon City', 1, 30.00, 4320.00, NULL, NULL, 0.00, 0.00, 4320.00, 10080.00, 'Birthday Celebration', 96, NULL, 'School ID', 'bookings/valid_ids/front/CeRzbhlu5FhxlNxNvWnhNKgSAFs1i6LKQ3Vbth78.jpg', 'bookings/valid_ids/back/dR4eaeW6Eu15v7GzvVMuV5jv2pUVBDwW3ciCZnuJ.jpg', 'bookings/valid_ids/selfie/TcghLIUe2sDITAGjOYnit8UDHG0QChX2gp8kra4g.jpg', NULL, 'Hanap nalang po ng ibang facility.\r\nThank you!', NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rejected', NULL, NULL, 3, '2026-02-07 13:51:51', NULL, NULL, NULL, NULL, NULL, '2026-02-07 13:50:25', '2026-02-11 11:37:11', NULL, NULL, 0.00, 10080.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(44, 5, 18, '2026-02-16 08:00:00', '2026-02-16 11:00:00', 14000.00, 0.00, 4000.00, 18000.00, 'Quezon City', 1, 30.00, 5400.00, 'student', NULL, 20.00, 2520.00, 7920.00, 10080.00, 'Religious Gathering', 100, NULL, 'School ID', 'bookings/valid_ids/front/U0vinXm1kk6b1idqREm3QwPjbmKwY6hp2sfFvkeo.jpg', 'bookings/valid_ids/back/bvce9ZHdt2ea6vEflFydycQBLvPxfREb96gmVX0a.jpg', 'bookings/valid_ids/selfie/0LOsI5x7mrBT23VAF0OtKT6u9ogzrRqv3SqAlveL.jpg', NULL, NULL, 'Payment deadline exceeded (auto-expired)', NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, 3, '2026-02-09 15:12:58', NULL, NULL, NULL, NULL, NULL, '2026-02-08 15:36:52', '2026-02-12 03:19:01', '2026-02-12 03:19:01', NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(45, 5, 15, '2026-02-17 08:00:00', '2026-02-17 11:00:00', 14550.00, 0.00, 0.00, 14550.00, 'Quezon City', 1, 30.00, 4365.00, 'student', NULL, 20.00, 2037.00, 6402.00, 8148.00, 'Birthday Celebration', 97, NULL, 'School ID', 'bookings/valid_ids/front/tk3RuCGk1q3hwFf1Jkk1oM0AEYKl2AWyMiiWZVK7.jpg', 'bookings/valid_ids/back/IH7LecwhfYFgdG98n5gifIq4RTqlx0PxdWTa51j1.jpg', 'bookings/valid_ids/selfie/eydXRGzAeu1kDOZFwEgjw1GUlDBIY90WkbE0oI4H.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-09 15:13:32', NULL, 2, '2026-02-10 10:11:22', NULL, NULL, '2026-02-09 15:10:36', '2026-02-10 10:11:22', NULL, 25, 2037.00, 8148.00, 0.00, 'cash', '2026-02-09 15:10:36', NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(46, 5, 14, '2026-02-18 08:00:00', '2026-02-18 11:00:00', 6500.00, 0.00, 0.00, 6500.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 1300.00, 1300.00, 5200.00, 'Wedding Reception', 50, NULL, 'School ID', 'bookings/valid_ids/front/4MgiVF171EnUSNv1abd5BuSRwxNFDGmSZomlfc8g.jpg', 'bookings/valid_ids/back/UzcPa742xzHh5pUzKGy9V7h7TjN7f2UHN2BfwPU7.jpg', 'bookings/valid_ids/selfie/y58H1bg37A84r68IUDyUhmLk6s8EMhCt04yPiTyX.jpg', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, NULL, NULL, NULL, 2, '2026-02-12 07:04:50', NULL, NULL, '2026-02-10 12:21:44', '2026-02-12 07:04:50', '2026-02-11 05:36:07', 50, 2600.00, 5200.00, 0.00, 'gcash', NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(47, 21, 12, '2026-02-19 08:00:00', '2026-02-19 13:00:00', 7000.00, 1000.00, 5825.00, 13825.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 13825.00, 'Sports Event', 50, NULL, 'National ID (PhilSys)', 'bookings/valid_ids/front/IyGQGQCoAUCVEvxdgGEpCNtUDgiY7mTmysInDed0.png', 'bookings/valid_ids/back/puYFJHJdgV1LhHOK51MynGc3KB1SGTWoYta8E6TM.png', 'bookings/valid_ids/selfie/yyDvEbb07QGvsmUxd0yXsznbNOOfVqGd5BSxlHEd.jpg', NULL, NULL, 'change date', '2026-02-10 16:08:35', 'Lucchesi Hart', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-10 16:06:00', '2026-02-10 16:08:35', NULL, 25, 3456.25, 3456.25, 10368.75, 'cash', '2026-02-10 16:06:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(48, 21, 11, '2026-02-19 08:00:00', '2026-02-19 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Sports Event', 30, NULL, 'National ID (PhilSys)', 'bookings/valid_ids/front/ALv5hWTH199IzwNhiJSdDbC92x3DrsfutEyT4BH8.png', 'bookings/valid_ids/back/V9nVO7Sj3KZVwXYI6NpPxaRWizpSc1rhufCGWRFD.png', 'bookings/valid_ids/selfie/PPgtvq8mwO6IHqx34Uh8nviQSGmXv8edXAjCyCAI.jpg', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Lucchesi Hart', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-10 16:41:10', '2026-02-12 03:16:11', '2026-02-11 05:36:07', 25, 1012.50, 1012.50, 3037.50, 'cashless', NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(49, 21, 11, '2026-02-19 08:00:00', '2026-02-19 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Birthday Celebration', 30, NULL, 'National ID (PhilSys)', 'bookings/valid_ids/front/rsed80cT9qixWhv1zsBaVqKyrd4IcPd3yXtLyk7u.png', 'bookings/valid_ids/back/J1nKrtaxFtw5yW8g79QyR25N5apRlcUK0AjaRXKe.png', 'bookings/valid_ids/selfie/MBFhYZb9bgesaFbJrtUFe0koPLbOpLnXIw3MYEEo.jpg', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Lucchesi Hart', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-10 16:42:07', '2026-02-12 03:16:04', '2026-02-11 05:36:07', 25, 1012.50, 1012.50, 3037.50, 'cash', NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(50, 5, 14, '2026-02-19 08:00:00', '2026-02-19 11:00:00', 6370.00, 0.00, 0.00, 6370.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 1274.00, 1274.00, 5096.00, 'Birthday Celebration', 49, NULL, 'School ID', 'bookings/valid_ids/front/L2TrirkqOeBEFHL0FhEeAp3ocR21FwGr0DK19u0i.jpg', 'bookings/valid_ids/back/aFhr28zLplp2fuY47K0y1QnUfESMw9wH0WhkMjNQ.jpg', 'bookings/valid_ids/selfie/9igmwJhRnYfdgz02RCWDmPVqcE7xbKm7t3NXEy8E.jpg', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, NULL, NULL, NULL, 2, '2026-02-11 11:41:05', NULL, NULL, '2026-02-10 16:42:51', '2026-02-11 11:41:05', '2026-02-11 05:36:07', 50, 2548.00, 5096.00, 0.00, 'cashless', NULL, 'cs_08c50c3ef9aedfad7cfe628b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(51, 5, 18, '2026-02-19 08:00:00', '2026-02-19 11:00:00', 6580.00, 0.00, 0.00, 6580.00, 'Quezon City', 1, 30.00, 1974.00, 'student', NULL, 20.00, 921.20, 2895.20, 3684.80, 'Birthday Celebration', 47, NULL, 'School ID', 'bookings/valid_ids/front/fwEPuqZOuXymH65T0kEcO2e4rX9UMSq90vCby1nO.jpg', 'bookings/valid_ids/back/ZNYOOvtoKhlczJmqrodLg0SZubPBNKJSzY6Vf2Od.jpg', 'bookings/valid_ids/selfie/jM1wNnc6Z1Z9XyNj7FrJRG4RPx5YgFSR7hmiurRI.jpg', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-10 17:59:36', '2026-02-11 07:04:14', '2026-02-11 05:36:07', 50, 1842.40, 1842.40, 1842.40, 'cashless', NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(52, 5, 18, '2026-02-19 08:00:00', '2026-02-19 11:00:00', 27580.00, 0.00, 0.00, 27580.00, 'Quezon City', 1, 30.00, 8274.00, 'student', NULL, 20.00, 3861.20, 12135.20, 15444.80, 'Birthday Celebration', 197, NULL, 'School ID', 'bookings/valid_ids/front/uJrQOE1GF9sQ7oKnS3WTf4NS4Kfv6zV2nUHA0lOv.jpg', 'bookings/valid_ids/back/sX5Xa7c5w7NQ4UdBA657uXKNeHwOaDAarpeRHhgK.jpg', 'bookings/valid_ids/selfie/jm4gOyY1vGOjKAPjl3DIngg3FhVLyRkZaW84N5Mo.jpg', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-10 18:12:31', '2026-02-11 07:03:54', '2026-02-11 05:36:07', 75, 11583.60, 11583.60, 3861.20, 'cashless', NULL, 'cs_bff498e7ef7a3671d8e01fc3', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(53, 5, 17, '2026-02-19 08:00:00', '2026-02-19 11:00:00', 5000.00, 600.00, 0.00, 5600.00, 'Quezon City', 1, 30.00, 1680.00, 'student', NULL, 20.00, 784.00, 2464.00, 3136.00, 'Birthday Celebration', 40, NULL, 'School ID', 'bookings/valid_ids/front/LMH9JWRhqD6ueMEmRKnwBPmen3Y3Sj2p3aCCGerZ.jpg', 'bookings/valid_ids/back/A7aBm3WwWT8rX0hhYjYhhNi67XzYV99b5prWPwUg.jpg', 'bookings/valid_ids/selfie/TyDSy9dfFATyIGpakgo9XDZCc7HjEh2Zp1223bDG.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-11 05:37:15', NULL, 2, '2026-02-12 06:53:42', NULL, NULL, '2026-02-11 05:34:08', '2026-02-12 06:53:42', NULL, 25, 784.00, 3136.00, 0.00, 'cashless', '2026-02-11 05:34:37', 'cs_a6671ee8b868c4ce77a2bf4e', 'pay_zHNiLEf7xomj3xpsRnC31EPL', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(54, 5, 12, '2026-02-19 08:00:00', '2026-02-19 11:00:00', 21000.00, 0.00, 0.00, 21000.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 4200.00, 4200.00, 16800.00, 'Birthday Celebration', 150, NULL, 'School ID', 'bookings/valid_ids/front/WcofF90teu8unWLYXodsHomSAeHE4IrPFhQJtZan.jpg', 'bookings/valid_ids/back/QvXf7iI2uecyD4Gzb4N8hx0UF6fcjlWCXnnFlJQc.jpg', 'bookings/valid_ids/selfie/Cydylwv9oWs1JBi948OTtk0ypfXPV2XTXz361eS8.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-11 05:57:25', NULL, 2, '2026-02-11 11:34:30', NULL, NULL, '2026-02-11 05:55:32', '2026-02-11 11:34:30', NULL, 75, 12600.00, 16800.00, 0.00, 'cashless', '2026-02-11 05:56:14', 'cs_d74886657dac7170f7535279', 'pay_jWMEnHqwzSatgbLexkureeX4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:45:03'),
(55, 5, 12, '2026-02-19 13:00:00', '2026-02-19 16:00:00', 28000.00, 0.00, 0.00, 28000.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 5600.00, 5600.00, 22400.00, 'Community Meeting', 200, NULL, 'School ID', 'bookings/valid_ids/front/JJl11GBHZZR5ylq7GxJ1YBTVGeBiUwaIJGPgYUBt.jpg', 'bookings/valid_ids/back/NkjldBbu7Cm7VOL1XoPhEqutmxxF7aS2mxHyjEUz.jpg', 'bookings/valid_ids/selfie/dktgUwVGIxAVHtQAmHmIXz6R029AKLcIrEpChkLu.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-11 10:40:04', NULL, 2, '2026-02-11 11:34:57', NULL, NULL, '2026-02-11 10:04:04', '2026-02-11 11:34:57', NULL, 25, 5600.00, 22400.00, 0.00, 'cashless', '2026-02-11 10:08:55', 'cs_c30139ba1115548deb510fc1', 'pay_faQ9Tyq5WR7GYQoMNShHQQpw', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(56, NULL, 11, '2026-02-24 10:00:00', '2026-02-24 13:00:00', 3375.00, 0.00, 37145.00, 40520.00, NULL, 0, 0.00, 0.00, 'pwd', NULL, 20.00, 8104.00, 8104.00, 32416.00, 'Birthday Party', 25, 'hey', 'PWD ID', '/uploads/valid_ids/valid_id_front_1770805066_34.jpeg', '/uploads/valid_ids/valid_id_back_1770805066_34.jpeg', '/uploads/valid_ids/valid_id_selfie_1770805066_34.jpeg', NULL, NULL, NULL, NULL, 'Mark Visto', 'Mark Visto', 'm0d.manilyn.visto2@gmail.com', '9993998985', '85 years of my childhood, Paligui, Apalit, Central Luzon', 'Birthday Party', NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-11 10:18:56', NULL, 2, '2026-02-12 06:54:11', NULL, NULL, '2026-02-11 10:17:46', '2026-02-12 06:54:11', NULL, 25, 8104.00, 32416.00, 0.00, 'cash', NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(57, 5, 12, '2026-02-19 18:00:00', '2026-02-19 21:00:00', 7000.00, 0.00, 0.00, 7000.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 1400.00, 1400.00, 5600.00, 'Birthday Celebration', 50, NULL, 'School ID', 'bookings/valid_ids/front/mxoAVPbiRh9yD06ok6vebhA167iOeM8tA5p2eq8T.jpg', 'bookings/valid_ids/back/cfMuwYVugNybq4gj3fDMYAXpQX7Obw6r8nNMOaQu.jpg', 'bookings/valid_ids/selfie/Lr24D4WhL0Kq8LvWFYzf1xcA3PQPfAbVSmxo5l1A.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-11 10:40:15', NULL, 2, '2026-02-11 11:35:19', NULL, NULL, '2026-02-11 10:26:16', '2026-02-11 11:35:19', NULL, 25, 1400.00, 5600.00, 0.00, 'cashless', '2026-02-11 10:28:59', 'cs_4ba31e3c86efac1fe7c21614', 'pay_wBQLdgGpmiAuHd6uvUDchvvQ', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(59, 5, 11, '2026-02-20 08:00:00', '2026-02-20 11:00:00', 4050.00, 0.00, 0.00, 4050.00, 'Quezon City', 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 4050.00, 'Charity Event', 30, NULL, 'SSS ID', 'bookings/valid_ids/front/TayWy2g4h46MdUG64jcmQAOXCL1Cur9hQTrHTozw.png', 'bookings/valid_ids/back/17cG7COU7skAI788bKh3GD8ketS2qr5wp5X5aqZc.png', 'bookings/valid_ids/selfie/VMLGxywqGD5mBqqa290SVXqVzNaAFfEY9cIGfTK5.png', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-12 03:19:18', NULL, 2, '2026-02-12 06:53:59', NULL, NULL, '2026-02-11 17:03:29', '2026-02-12 06:53:59', NULL, 25, 1012.50, 4050.00, 0.00, 'cashless', '2026-02-11 17:03:49', 'cs_c7c1f13152714ce1bf944ebb', 'pay_D1MoBjNCDoB4YCKB2YP72uQe', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(61, 5, 12, '2026-02-20 08:00:00', '2026-02-20 11:00:00', 69720.00, 0.00, 0.00, 69720.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 13944.00, 13944.00, 55776.00, 'Sports Event', 498, NULL, 'School ID', 'bookings/valid_ids/front/0CtJnZEm6vSd0oQ5JUrpMHyc3Cs0Vh14bk5T8gde.jpg', 'bookings/valid_ids/back/9txftCQiAwSjDzUNPn4ovoa2NTR3VGXkyVw4H4L2.jpg', 'bookings/valid_ids/selfie/czRnZTsajpoZZRNA7PPq8vP4VSlyd6o7a7eB42lM.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-12 03:19:25', NULL, 2, '2026-02-12 06:53:51', NULL, NULL, '2026-02-12 02:53:27', '2026-02-12 06:53:51', NULL, 25, 13944.00, 55776.00, 0.00, 'cash', '2026-02-12 02:55:01', NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(62, 5, 14, '2026-02-20 08:00:00', '2026-02-20 11:00:00', 19240.00, 0.00, 0.00, 19240.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 3848.00, 3848.00, 15392.00, 'Birthday Celebration', 148, NULL, 'School ID', 'bookings/valid_ids/front/Bs9JQPSUamgI5RcHnxDeWp1AS0cvk6nBeu5fXkUz.jpg', 'bookings/valid_ids/back/kyshE23NE2T2NzinNZburOZLpvfYGH9xuVnfrb5U.jpg', 'bookings/valid_ids/selfie/Q1cN3n0h8uEWqFBiqLwWl12IS3LG0z9QlllgSidJ.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-12 03:19:33', NULL, 2, '2026-02-12 06:53:30', NULL, NULL, '2026-02-12 03:05:45', '2026-02-12 06:53:30', NULL, 100, 15392.00, 15392.00, 0.00, 'cashless', '2026-02-12 03:06:33', 'cs_4bee11081de6f127404cc95a', 'pay_nS2msNKYEKYFKeuT7zq4YKyf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(63, 5, 15, '2026-02-20 08:00:00', '2026-02-20 11:00:00', 74550.00, 0.00, 0.00, 74550.00, 'Quezon City', 1, 30.00, 22365.00, 'student', NULL, 20.00, 10437.00, 32802.00, 41748.00, 'Community Meeting', 497, NULL, 'School ID', 'bookings/valid_ids/front/plfenT4INLRomNkAJ19fRahXjaasVhdZ4pLLI7tb.jpg', 'bookings/valid_ids/back/Bn1DYH8QXMBlgjCwAOB1ifFmemExXGaMYNZEKrAR.jpg', 'bookings/valid_ids/selfie/xlakGt8V7GoqRlI9OkVnCHn9CQUchttpXkuOYUud.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-12 03:37:21', NULL, 2, '2026-02-12 06:53:21', NULL, NULL, '2026-02-12 03:23:43', '2026-02-12 06:53:21', NULL, 25, 10437.00, 41748.00, 0.00, 'cash', '2026-02-12 03:29:07', NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(64, 5, 16, '2026-02-20 08:00:00', '2026-02-20 11:00:00', 6250.00, 750.00, 0.00, 7000.00, 'Quezon City', 1, 30.00, 2100.00, 'student', NULL, 20.00, 980.00, 3080.00, 3920.00, 'Cultural Event', 50, NULL, 'School ID', 'bookings/valid_ids/front/quhauq7qZ94yYaVy677RNVkcZa2rkWCKvs3A0elQ.jpg', 'bookings/valid_ids/back/nJks4N7EdLpklQHcyb4fXfHhT1QQrWTJIXSAzFij.jpg', 'bookings/valid_ids/selfie/zMjjctSDEcyHKz6qFybdM4wv2duy0Bpci5WbbdcD.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-12 04:02:05', NULL, 2, '2026-02-12 06:53:08', NULL, NULL, '2026-02-12 03:36:31', '2026-02-12 06:53:08', NULL, 50, 1960.00, 3920.00, 0.00, 'cashless', '2026-02-12 03:36:51', 'cs_bf185628251b58f9713a853d', 'pay_BcScFXVKYSFKghJCQgQXcuwt', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01');
INSERT INTO `bookings` (`id`, `user_id`, `facility_id`, `start_time`, `end_time`, `base_rate`, `extension_rate`, `equipment_total`, `subtotal`, `city_of_residence`, `is_resident`, `resident_discount_rate`, `resident_discount_amount`, `special_discount_type`, `special_discount_id_path`, `special_discount_rate`, `special_discount_amount`, `total_discount`, `total_amount`, `purpose`, `expected_attendees`, `special_requests`, `valid_id_type`, `valid_id_front_path`, `valid_id_back_path`, `valid_id_selfie_path`, `supporting_doc_path`, `rejected_reason`, `canceled_reason`, `canceled_at`, `user_name`, `applicant_name`, `applicant_email`, `applicant_phone`, `applicant_address`, `event_name`, `event_description`, `event_date`, `status`, `payment_rejection_reason`, `payment_rejected_at`, `staff_verified_by`, `staff_verified_at`, `staff_notes`, `admin_approved_by`, `admin_approved_at`, `admin_approval_notes`, `reserved_until`, `created_at`, `updated_at`, `expired_at`, `payment_tier`, `down_payment_amount`, `amount_paid`, `amount_remaining`, `payment_method`, `down_payment_paid_at`, `paymongo_checkout_id`, `paymongo_payment_id`, `payment_recorded_by`, `rejection_type`, `rejection_fields`, `deleted_at`, `deleted_by`, `source_system`, `external_reference_id`, `booking_reference`, `is_synced`, `last_synced_at`) VALUES
(65, 5, 17, '2026-02-20 08:00:00', '2026-02-20 11:00:00', 5000.00, 600.00, 0.00, 5600.00, 'Quezon City', 1, 30.00, 1680.00, NULL, NULL, 0.00, 0.00, 1680.00, 3920.00, 'Birthday Celebration', 40, NULL, 'SSS ID', 'bookings/valid_ids/front/ZjGF99reVgr3gWadjJ3Ltbm6Wxd1LksY6u2LuNBU.jpg', 'bookings/valid_ids/back/oIiyftUGcyKCLGrX8bL0ow3ruhkhQbZSUH9wvTye.jpg', 'bookings/valid_ids/selfie/qf3Qb8vm5Ae89C1u1VukETTBiJ6VI6j3u5npkFt1.jpg', NULL, NULL, NULL, NULL, 'Cristian Mark Angelo Llaneta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'confirmed', NULL, NULL, 3, '2026-02-12 08:13:04', NULL, 2, '2026-02-12 08:13:30', NULL, NULL, '2026-02-12 07:06:41', '2026-02-12 08:13:30', NULL, 50, 1960.00, 3920.00, 0.00, 'cash', '2026-02-12 07:07:24', NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(66, 22, 11, '2026-02-23 08:00:00', '2026-02-23 11:00:00', 27000.00, 0.00, 0.00, 27000.00, NULL, 0, 0.00, 0.00, 'student', NULL, 20.00, 5400.00, 5400.00, 21600.00, 'Kasal ko', 200, NULL, 'School ID', '/uploads/valid_ids/valid_id_front_1770921191_22.jpg', '/uploads/valid_ids/valid_id_back_1770921191_22.jpg', '/uploads/valid_ids/valid_id_selfie_1770921191_22.jpg', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Cristian Mark Angelo Llaneta', 'Cristian Mark Angelo Llaneta', 'lcristianmarkangelo@gmail.com', '09515691003', 'Area 5a Naval Street, Sauyo, Quezon City, NCR', 'Wedding Reception', NULL, NULL, 'expired', NULL, NULL, NULL, NULL, 'Submitted via API from: LGU1_Citizen_Portal (Ref: CP-1770921191-22)', NULL, NULL, NULL, NULL, '2026-02-12 18:33:11', '2026-02-12 19:49:01', '2026-02-12 19:49:01', 25, 5400.00, 0.00, 21600.00, 'cashless', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(67, 22, 11, '2026-02-23 13:00:00', '2026-02-23 16:00:00', 27000.00, 0.00, 0.00, 27000.00, NULL, 0, 0.00, 0.00, 'student', NULL, 20.00, 5400.00, 5400.00, 21600.00, 'birthday ko baki?', 200, NULL, 'School ID', '/uploads/valid_ids/valid_id_front_1770921368_22.jpg', '/uploads/valid_ids/valid_id_back_1770921368_22.jpg', '/uploads/valid_ids/valid_id_selfie_1770921368_22.jpg', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Cristian Mark Angelo Llaneta', 'Cristian Mark Angelo Llaneta', 'lcristianmarkangelo@gmail.com', '09515691003', 'Area 5a Naval Street, Sauyo, Quezon City, NCR', 'Birthday Party', NULL, NULL, 'expired', NULL, NULL, NULL, NULL, 'Submitted via API from: LGU1_Citizen_Portal (Ref: CP-1770921368-22)', NULL, NULL, NULL, NULL, '2026-02-12 18:36:08', '2026-02-12 19:49:01', '2026-02-12 19:49:01', 25, 5400.00, 0.00, 21600.00, 'cashless', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(68, 20, 12, '2026-02-28 08:00:00', '2026-02-28 11:00:00', 70000.00, 0.00, 0.00, 70000.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 14000.00, 14000.00, 56000.00, 'Company Seminar/Training', 500, NULL, 'School ID', 'bookings/valid_ids/front/ig9qOv2p5igZ9fIInknhgZ1ZnpAAY55XlM2JxkHL.jpg', 'bookings/valid_ids/back/Hzfv5fCHCUlEQIvtdw5q80ql7XTF2dMP10geqytM.png', 'bookings/valid_ids/selfie/4hOOgvLPyE24Z8wM5au9b7rNfyXmVUH5RzdJq7ZW.png', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Mahiru Shiina', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-13 00:52:23', '2026-02-13 04:31:37', '2026-02-13 04:31:37', 100, 56000.00, 0.00, 56000.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(69, 11, 11, '2026-03-02 08:00:00', '2026-03-02 11:00:00', 27000.00, 0.00, 0.00, 27000.00, 'Quezon City', 0, 0.00, 0.00, 'student', NULL, 20.00, 5400.00, 5400.00, 21600.00, 'Seminar Event Special', 200, NULL, 'School ID', 'bookings/valid_ids/front/hclQQp87EbNzf7fmBB1s1qQedZYrdtlSn5uqcnxH.jpg', 'bookings/valid_ids/back/iF2aHzPAR25aLPIUoNbygV8wESNftmXiqYYXjjQ6.png', 'bookings/valid_ids/selfie/YggmsOdwzCQEeMA2d49wbSKuRiaSvaEcGKv7hNiF.png', NULL, NULL, 'Cashless payment not completed within 1 hour (auto-expired)', NULL, 'Miyuki Hagakure', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-13 01:05:43', '2026-02-13 04:31:37', '2026-02-13 04:31:37', 100, 21600.00, 0.00, 21600.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 14:46:01'),
(70, NULL, 16, '2026-02-12 06:51:00', '2026-02-12 08:51:00', 0.00, 0.00, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'seminar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Christian Cando', 'Christian Cando', 'lcristianmarkangelo@gmail.com', '09123456789', NULL, 'KURYENTIPID', 'seminar', NULL, 'confirmed', NULL, NULL, NULL, NULL, 'Energy Efficiency Facility Request #4. Session: Orientation. Organizer: Energy Efficiency Division', NULL, NULL, NULL, NULL, '2026-02-13 05:09:56', '2026-02-13 05:09:56', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Energy_Efficiency_FacilityRequest', NULL, NULL, 1, '2026-02-13 14:46:01'),
(71, NULL, 16, '2026-02-12 06:51:00', '2026-02-12 08:51:00', 0.00, 0.00, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'The purpose of an energy efficiency seminar is to educate participants on the principles, strategies, and best practices of energy efficiency and conservation. These seminars aim to improve knowledge and skills in energy management, monitoring, reporting, and verification, as well as to introduce municipal energy management as part of the national monitoring, reporting, and verification system. They also cover energy audits, multicriteria analysis, and best practices for funding energy efficiency measures. By providing these insights, seminars help participants understand the importance of energy efficiency in reducing carbon emissions and improving overall energy system efficiency. \r\nrewrap.eu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Christian Cando', 'Christian Cando', 'lcristianmarkangelo@gmail.com', '09123456789', NULL, 'KURYENTIPID', 'The purpose of an energy efficiency seminar is to educate participants on the principles, strategies, and best practices of energy efficiency and conservation. These seminars aim to improve knowledge and skills in energy management, monitoring, reporting, and verification, as well as to introduce municipal energy management as part of the national monitoring, reporting, and verification system. They also cover energy audits, multicriteria analysis, and best practices for funding energy efficiency measures. By providing these insights, seminars help participants understand the importance of energy efficiency in reducing carbon emissions and improving overall energy system efficiency. \r\nrewrap.eu', NULL, 'confirmed', NULL, NULL, NULL, NULL, 'Energy Efficiency Facility Request #3. Session: Orientation. Organizer: Energy Efficiency Division', NULL, NULL, NULL, NULL, '2026-02-13 05:32:18', '2026-02-13 05:32:18', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Energy_Efficiency_FacilityRequest', NULL, NULL, 1, '2026-02-13 14:46:01'),
(72, NULL, 16, '2026-02-28 08:00:00', '2026-02-28 11:00:00', 0.00, 0.00, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'The purpose of an energy efficiency seminar is to educate participants on the principles, strategies, and best practices of energy efficiency and conservation. These seminars aim to improve knowledge and skills in energy management, monitoring, reporting, and verification, as well as to introduce municipal energy management as part of the national monitoring, reporting, and verification system. They also cover energy audits, multicriteria analysis, and best practices for funding energy efficiency measures. By providing these insights, seminars help participants understand the importance of energy efficiency in reducing carbon emissions and improving overall energy system efficiency. \r\nrewrap.eu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Christian Cando', 'Christian Cando', 'lcristianmarkangelo@gmail.com', '09123456789', NULL, 'KURYENTIPID', 'The purpose of an energy efficiency seminar is to educate participants on the principles, strategies, and best practices of energy efficiency and conservation. These seminars aim to improve knowledge and skills in energy management, monitoring, reporting, and verification, as well as to introduce municipal energy management as part of the national monitoring, reporting, and verification system. They also cover energy audits, multicriteria analysis, and best practices for funding energy efficiency measures. By providing these insights, seminars help participants understand the importance of energy efficiency in reducing carbon emissions and improving overall energy system efficiency. \r\nrewrap.eu', NULL, 'confirmed', NULL, NULL, NULL, NULL, 'Energy Efficiency Facility Request #2. Session: Orientation. Organizer: Energy Efficiency Division', NULL, NULL, NULL, NULL, '2026-02-13 05:48:44', '2026-02-13 05:48:44', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Energy_Efficiency_FacilityRequest', NULL, NULL, 1, '2026-02-13 14:46:01'),
(73, NULL, 16, '2026-02-12 06:51:00', '2026-02-12 08:51:00', 0.00, 0.00, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'The purpose of an energy efficiency seminar is to educate participants on the principles, strategies, and best practices of energy efficiency and conservation. These seminars aim to improve knowledge and skills in energy management, monitoring, reporting, and verification, as well as to introduce municipal energy management as part of the national monitoring, reporting, and verification system. They also cover energy audits, multicriteria analysis, and best practices for funding energy efficiency measures. By providing these insights, seminars help participants understand the importance of energy efficiency in reducing carbon emissions and improving overall energy system efficiency. \r\nrewrap.eu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Christian Cando', 'Christian Cando', 'lcristianmarkangelo@gmail.com', '09123456789', NULL, 'KURYENTIPID', 'The purpose of an energy efficiency seminar is to educate participants on the principles, strategies, and best practices of energy efficiency and conservation. These seminars aim to improve knowledge and skills in energy management, monitoring, reporting, and verification, as well as to introduce municipal energy management as part of the national monitoring, reporting, and verification system. They also cover energy audits, multicriteria analysis, and best practices for funding energy efficiency measures. By providing these insights, seminars help participants understand the importance of energy efficiency in reducing carbon emissions and improving overall energy system efficiency. \r\nrewrap.eu', NULL, 'confirmed', NULL, NULL, NULL, NULL, 'Energy Efficiency Facility Request #1. Session: Orientation. Organizer: Energy Efficiency Division', NULL, NULL, NULL, NULL, '2026-02-13 05:49:17', '2026-02-13 05:49:17', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Energy_Efficiency_FacilityRequest', NULL, NULL, 1, '2026-02-13 14:46:01'),
(74, NULL, 16, '2026-02-12 06:51:00', '2026-02-12 08:51:00', 0.00, 0.00, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'seminar2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Christian Cando', 'Christian Cando', 'lcristianmarkangelo@gmail.com', '09123456789', NULL, 'KURYENTIPID', 'seminar2', NULL, 'confirmed', NULL, NULL, NULL, NULL, 'Energy Efficiency Facility Request #5. Session: Orientation. Organizer: Energy Efficiency Division', NULL, NULL, NULL, NULL, '2026-02-13 05:58:21', '2026-02-13 05:58:21', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Energy_Efficiency_FacilityRequest', NULL, NULL, 1, '2026-02-13 14:46:01'),
(75, NULL, 18, '2026-02-12 06:51:00', '2026-02-12 08:51:00', 0.00, 0.00, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'needed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Christian Cando', 'Christian Cando', 'jhalesarizosantiago@gmail.com', '09123456789', NULL, 'KURYENTIPID', 'needed', NULL, 'confirmed', NULL, NULL, NULL, NULL, 'Energy Efficiency Facility Request #6. Session: Orientation. Organizer: Energy Efficiency Division', NULL, NULL, NULL, NULL, '2026-02-13 06:30:01', '2026-02-13 06:30:01', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Energy_Efficiency_FacilityRequest', NULL, NULL, 1, '2026-02-13 14:46:01'),
(76, NULL, 17, '2026-02-13 19:31:00', '2026-02-13 20:32:00', 0.00, NULL, 0.00, 0.00, NULL, 0, 0.00, 0.00, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 'Housing Rules and Regulations - Housing and Resettlement', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lance Mabato', 'Lance Mabato', 'lancearon74@gmail.com', '09383368682', NULL, 'Housing Rules and Regulations', NULL, NULL, 'confirmed', NULL, NULL, NULL, NULL, NULL, 2, '2026-02-13 15:28:27', NULL, NULL, '2026-02-13 11:31:55', '2026-02-13 15:28:27', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Housing_Resettlement', NULL, NULL, 1, '2026-02-13 14:46:01');

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
(43, 18, 17, 10, 800.00, 8000.00, '2026-01-13 19:16:39', '2026-01-13 19:16:39'),
(44, 35, 19, 4, 2000.00, 8000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(45, 35, 20, 5, 1500.00, 7500.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(46, 35, 21, 4, 1800.00, 7200.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(47, 35, 38, 4, 3000.00, 12000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(48, 35, 3, 4, 75.00, 300.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(49, 35, 1, 100, 25.00, 2500.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(50, 35, 37, 100, 50.00, 5000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(51, 35, 18, 5, 600.00, 3000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(52, 35, 16, 5, 1200.00, 6000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(53, 35, 36, 5, 100.00, 500.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(54, 35, 13, 6, 1500.00, 9000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(55, 35, 34, 8, 1500.00, 12000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(56, 35, 15, 8, 500.00, 4000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(57, 35, 14, 20, 350.00, 7000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(58, 35, 35, 6, 800.00, 4800.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(59, 35, 9, 5, 2500.00, 12500.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(60, 35, 31, 5, 2500.00, 12500.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(61, 35, 32, 3, 5000.00, 15000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(62, 35, 10, 3, 4500.00, 13500.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(63, 35, 12, 10, 800.00, 8000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(64, 35, 33, 10, 500.00, 5000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(65, 35, 39, 7, 1000.00, 7000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(66, 35, 8, 11, 200.00, 2200.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(67, 35, 30, 10, 200.00, 2000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(68, 35, 7, 14, 250.00, 3500.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(69, 35, 29, 9, 300.00, 2700.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(70, 35, 6, 11, 450.00, 4950.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(71, 35, 5, 11, 300.00, 3300.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(72, 35, 23, 19, 150.00, 2850.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(73, 35, 22, 9, 200.00, 1800.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(74, 35, 25, 8, 5000.00, 40000.00, '2026-02-04 08:15:55', '2026-02-04 08:15:55'),
(75, 36, 1, 50, 25.00, 1250.00, '2026-02-05 17:08:05', '2026-02-05 17:08:05'),
(76, 36, 7, 5, 250.00, 1250.00, '2026-02-05 17:08:05', '2026-02-05 17:08:05'),
(77, 39, 19, 1, 2000.00, 2000.00, '2026-02-07 07:56:01', '2026-02-07 07:56:01'),
(78, 39, 21, 1, 1800.00, 1800.00, '2026-02-07 07:56:01', '2026-02-07 07:56:01'),
(79, 39, 38, 1, 3000.00, 3000.00, '2026-02-07 07:56:01', '2026-02-07 07:56:01'),
(80, 39, 4, 1, 40.00, 40.00, '2026-02-07 07:56:01', '2026-02-07 07:56:01'),
(81, 40, 2, 50, 30.00, 1500.00, '2026-02-07 08:43:28', '2026-02-07 08:43:28'),
(82, 40, 12, 2, 800.00, 1600.00, '2026-02-07 08:43:28', '2026-02-07 08:43:28'),
(83, 40, 33, 2, 500.00, 1000.00, '2026-02-07 08:43:28', '2026-02-07 08:43:28'),
(84, 40, 7, 5, 250.00, 1250.00, '2026-02-07 08:43:28', '2026-02-07 08:43:28'),
(85, 40, 23, 1, 150.00, 150.00, '2026-02-07 08:43:28', '2026-02-07 08:43:28'),
(86, 44, 20, 1, 1500.00, 1500.00, '2026-02-08 15:36:52', '2026-02-08 15:36:52'),
(87, 44, 1, 100, 25.00, 2500.00, '2026-02-08 15:36:52', '2026-02-08 15:36:52'),
(88, 47, 19, 1, 2000.00, 2000.00, '2026-02-10 16:06:00', '2026-02-10 16:06:00'),
(89, 47, 20, 1, 1500.00, 1500.00, '2026-02-10 16:06:00', '2026-02-10 16:06:00'),
(90, 47, 21, 1, 1800.00, 1800.00, '2026-02-10 16:06:00', '2026-02-10 16:06:00'),
(91, 47, 3, 7, 75.00, 525.00, '2026-02-10 16:06:00', '2026-02-10 16:06:00'),
(92, 56, 19, 2, 2000.00, 4000.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46'),
(93, 56, 20, 4, 1500.00, 6000.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46'),
(94, 56, 21, 2, 1800.00, 3600.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46'),
(95, 56, 38, 1, 3000.00, 3000.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46'),
(96, 56, 3, 1, 75.00, 75.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46'),
(97, 56, 4, 1, 40.00, 40.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46'),
(98, 56, 2, 1, 30.00, 30.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46'),
(99, 56, 22, 2, 200.00, 400.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46'),
(100, 56, 25, 4, 5000.00, 20000.00, '2026-02-11 10:17:46', '2026-02-11 10:17:46');

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

--
-- Dumping data for table `budget_allocations`
--

INSERT INTO `budget_allocations` (`id`, `fiscal_year`, `category`, `category_name`, `allocated_amount`, `spent_amount`, `remaining_amount`, `notes`, `approved_by`, `approved_at`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 2026, 'maintenance', NULL, 10000.00, 0.00, 10000.00, NULL, 'Llaneta Cristian Pastoril', '2026-02-08 03:00:17', '2026-02-08 03:00:17', '2026-02-08 03:00:17', NULL, NULL);

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
-- Table structure for table `citizen_road_requests`
--

CREATE TABLE `citizen_road_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `external_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_type` varchar(100) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location` varchar(500) NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `citizen_road_requests`
--

INSERT INTO `citizen_road_requests` (`id`, `user_id`, `external_request_id`, `event_type`, `start_datetime`, `end_datetime`, `location`, `landmark`, `description`, `booking_id`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 2, 14, 'traffic_management', '2026-03-03 11:00:00', '2026-03-03 17:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Buena Park. Expected attendees: 50. Purpose: Beneficiary Orientation - Test Batch - Test request from Housing and Resettlement Management', 32, 'pending', NULL, '2026-02-03 16:21:32', '2026-02-13 15:31:23'),
(2, 2, 15, 'traffic_management', '2026-03-03 11:00:00', '2026-03-03 17:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Buena Park. Expected attendees: 50. Purpose: Beneficiary Orientation - Test Batch - Test request from Housing and Resettlement Management', 32, 'pending', NULL, '2026-02-03 16:26:15', '2026-02-13 15:31:23'),
(3, 2, 16, 'traffic_management', '2026-03-03 11:00:00', '2026-03-03 17:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Buena Park. Expected attendees: 50. Purpose: Beneficiary Orientation - Test Batch - Test request from Housing and Resettlement Management', 32, 'pending', NULL, '2026-02-03 16:42:49', '2026-02-13 15:31:23'),
(4, 2, 17, 'traffic_management', '2026-03-03 11:00:00', '2026-03-03 17:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Buena Park. Expected attendees: 50. Purpose: Beneficiary Orientation - Test Batch - Test request from Housing and Resettlement Management', 32, 'pending', NULL, '2026-02-03 16:44:37', '2026-02-13 15:31:23'),
(5, 2, 18, 'traffic_management', '2026-03-03 11:00:00', '2026-03-03 17:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Buena Park. Expected attendees: 50. Purpose: Beneficiary Orientation - Test Batch - Test request from Housing and Resettlement Management', 32, 'pending', NULL, '2026-02-03 16:51:35', '2026-02-13 15:31:23'),
(6, 2, 19, 'traffic_management', '2026-02-05 08:00:00', '2026-02-05 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Sports Complex. Expected attendees: 50. Purpose: Birthday Celebration', 25, 'pending', NULL, '2026-02-04 09:09:16', '2026-02-13 15:31:23'),
(7, 2, 20, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-12 16:21:53', '2026-02-13 15:31:23'),
(8, 2, 21, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-12 16:24:01', '2026-02-13 15:31:23'),
(9, 2, 22, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-12 16:36:38', '2026-02-13 15:31:23'),
(10, 2, 23, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-13 08:08:09', '2026-02-13 15:31:23'),
(11, 2, 24, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-13 14:17:12', '2026-02-13 15:31:23'),
(12, 2, 25, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-13 14:27:04', '2026-02-13 15:31:23'),
(13, 2, 26, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-13 15:16:41', '2026-02-13 15:31:23'),
(14, 2, 27, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-13 15:18:47', '2026-02-13 15:31:23'),
(15, 2, 28, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'pending', NULL, '2026-02-13 15:19:23', '2026-02-13 15:31:23'),
(16, 2, 13, 'traffic_management', '2026-02-14 08:00:00', '2026-02-14 11:00:00', 'South Caloocan City', NULL, 'Road assistance needed for facility booking at Bulwagan Katipunan. Expected attendees: 100. Purpose: Birthday Celebration', 37, 'approved', '', '2026-02-13 15:25:37', '2026-02-13 15:57:37');

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
  `external_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `issue_type` varchar(255) DEFAULT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `resident_name` varchar(255) NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(500) DEFAULT NULL,
  `reporter_name` varchar(255) DEFAULT NULL,
  `reporter_contact` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `unit_number` varchar(255) DEFAULT NULL,
  `report_type` enum('maintenance','complaint','suggestion','emergency') DEFAULT 'maintenance',
  `priority` varchar(50) DEFAULT 'medium',
  `status` varchar(50) DEFAULT 'Pending',
  `submitted_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `community_maintenance_requests`
--

INSERT INTO `community_maintenance_requests` (`id`, `external_report_id`, `external_request_id`, `category`, `issue_type`, `facility_id`, `facility_name`, `resident_name`, `contact_info`, `subject`, `description`, `location`, `reporter_name`, `reporter_contact`, `photo_path`, `unit_number`, `report_type`, `priority`, `status`, `submitted_by_user_id`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, NULL, NULL, 16, 'M.I.C.E. Breakout Room 1', 'Llaneta Cristian Pastoril', '09123456789', 'hrtjrhr', 'dxhzhsdh', NULL, NULL, NULL, NULL, 'M.I.C.E. Breakout Room 1, Quezon City M.I.C.E. Center, Floor 1, Quezon City, Metro Manila', 'maintenance', 'medium', 'Pending', 2, '2026-01-29 15:34:38', '2026-01-29 15:34:38'),
(2, 6, NULL, NULL, NULL, 11, 'Buena Park', 'Llaneta Cristian Pastoril', '09123456789', 'qwertyuiop', 'qwertyuiop', NULL, NULL, NULL, NULL, 'Buena Park, South Caloocan City, Metro Manila', 'maintenance', 'medium', 'Pending', 2, '2026-02-03 13:14:20', '2026-02-03 13:14:41'),
(3, 2, 2, 'Facilities', 'Structural Damage', 11, 'Buena Park', 'Llaneta Cristian Pastoril', '09123456789', 'Structural Damage - Facilities', 'May crack ang sahig ng facility na ito.', 'Buena Park, South Caloocan City, Metro Manila', 'Llaneta Cristian Pastoril', '09123456789', NULL, 'Buena Park, South Caloocan City, Metro Manila', 'maintenance', 'medium', 'Pending', 2, '2026-02-12 12:05:59', '2026-02-12 12:51:02');

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
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) DEFAULT 0,
  `last_synced_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`facility_id`, `lgu_city_id`, `name`, `description`, `address`, `latitude`, `longitude`, `full_address`, `city`, `view_count`, `rating`, `capacity`, `min_capacity`, `per_person_rate`, `per_person_extension_rate`, `base_hours`, `hourly_rate`, `rate_per_hour`, `image_path`, `is_available`, `created_at`, `updated_at`, `deleted_at`, `is_synced`, `last_synced_at`) VALUES
(11, 1, 'Buena Park', 'Open-air community park suitable for outdoor gatherings and events. Perfect for weekend private events and community celebrations.', 'South Caloocan City', 14.75660000, 121.04500000, 'South Caloocan City, Metro Manila', 'Caloocan City', 134, 4.00, 200, 30, 135.00, 20.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2026-01-27 05:57:39', NULL, 1, '2026-02-13 14:45:13'),
(12, 1, 'Sports Complex', 'Multi-purpose indoor sports facility for athletic events and large gatherings. Ideal for tournaments, competitions, and sporting events.', 'South Caloocan City', 14.75050000, 121.02080000, 'South Caloocan City, Metro Manila', 'Caloocan City', 179, 3.90, 500, 50, 140.00, 20.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL, 1, '2026-02-13 14:45:13'),
(13, 1, 'Bulwagan Katipunan', 'Convention hall primarily used for city events and formal gatherings. Air-conditioned venue suitable for conferences, assemblies, and official functions.', 'South Caloocan City', 14.64880000, 120.99060000, 'South Caloocan City, Metro Manila', 'Caloocan City', 466, 3.80, 300, 35, 145.00, 25.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL, 1, '2026-02-13 14:45:13'),
(14, 1, 'Pacquiao Court', 'Covered basketball court suitable for sports events and tournaments. Named after Manny Pacquiao. Weather-protected facility for basketball games and small sporting events.', 'South Caloocan City', 14.75050000, 121.02080000, 'South Caloocan City, Metro Manila', 'Caloocan City', 483, 4.90, 150, 25, 130.00, 20.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL, 1, '2026-02-13 14:45:13'),
(15, 2, 'QC M.I.C.E. Convention & Exhibit Hall', 'Large-scale venue for conventions, exhibits, and major events. Professional-grade 4-storey M.I.C.E. Center facility. Currently restricted to QC-LGU departments pending ordinance approval for public use.', 'Quezon City M.I.C.E. Center', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Quezon City, Metro Manila', 'Quezon City', 452, 4.80, 1000, 50, 150.00, 30.00, 4, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL, 1, '2026-02-13 14:45:13'),
(16, 2, 'M.I.C.E. Breakout Room 1', 'Intimate meeting space perfect for seminars and training sessions. Most requested facility! Over 200 bookings in 8 months (Jan-Aug 2024). Equipped with projector and whiteboard.', 'Quezon City M.I.C.E. Center, Floor 1', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Floor 1, Quezon City, Metro Manila', 'Quezon City', 386, 4.30, 50, 15, 125.00, 15.00, 2, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL, 1, '2026-02-13 14:45:13'),
(17, 2, 'M.I.C.E. Breakout Room 2', 'Intimate meeting space perfect for seminars and training sessions. High demand facility for workshops and trainings. Equipped with projector and whiteboard.', 'Quezon City M.I.C.E. Center, Floor 2', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Floor 2, Quezon City, Metro Manila', 'Quezon City', 400, 4.80, 40, 15, 125.00, 15.00, 2, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL, 1, '2026-02-13 14:45:13'),
(18, 2, 'QC M.I.C.E. Auditorium', 'Fixed-seating venue with stage for performances and presentations. Professional audio/visual setup ideal for lectures, performances, and formal presentations.', 'Quezon City M.I.C.E. Center', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Quezon City, Metro Manila', 'Quezon City', 171, 3.60, 300, 35, 140.00, 25.00, 3, NULL, NULL, NULL, 1, '2025-12-17 08:53:30', '2025-12-17 08:53:30', NULL, 1, '2026-02-13 14:45:13');

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
(8, 25, 'Municipal Planning and Development Office', 'Cristian', NULL, NULL, NULL, 'Healthcare Facilities', 'Public Facilities', NULL, NULL, NULL, 'On behalf of constituency, I am putting forward our request to your honourable Government. Name of the hospital - was built in the year approximately about 8 decades back. Since my childhood and childhood of my parents, we have been visiting this hospital for medical care as well as regular checkup and treatment of our illness. While there is lots of improvement by way of modern medicine and equipment little care has been given to its infrastructure.', NULL, NULL, NULL, 500000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-04 04:40:30', '2026-02-13 15:35:46', NULL),
(9, 26, 'Municipal Planning and Development Office', 'Cristian', NULL, NULL, NULL, 'Libraries', 'Public Facilities', NULL, NULL, NULL, 'Dear [City Council Member Name or Mayor],\r\nI am writing as a concerned resident of [Your Community Name] to request the establishment of a public library branch in our area. As our community grows, we lack a dedicated \"third place\"—a safe, free space for education, digital access, and community bonding.\r\nA local library would offer vital resources:\r\nEducation & Literacy: Access to books, children\'s story times, and study spaces.\r\nDigital Inclusion: Free Wi-Fi and computers for residents to work, study, or apply for jobs.\r\nCommunity Hub: A gathering place for seniors, book clubs, and local meetings.\r\nI believe this is a necessary investment in the future of our residents. I look forward to discussing how we can make this a reality.\r\nSincerely,\r\n[Your Name]\r\n[Your Address/Contact Information]', NULL, NULL, NULL, 1000000.00, 'medium', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-04 05:41:07', '2026-02-13 15:35:46', NULL),
(10, 27, 'Barangay Office', 'Cristian', NULL, NULL, NULL, 'Playground', 'Infrastructure', NULL, NULL, NULL, 'I am writing on behalf of the residents of [Your Neighborhood] to formally request the installation of a public playground at [Specific Location/Address].\r\nCurrently, our area lacks a safe, dedicated space for children to play, forcing them to use unsafe areas or travel long distances for recreation. A new playground would provide a vital, safe environment that promotes physical health, cognitive development, and social interaction among children.', NULL, NULL, NULL, 800000.00, 'low', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-04 05:59:26', '2026-02-13 15:35:47', NULL),
(11, 28, 'Mayor\'s Office', 'Michiko', NULL, NULL, NULL, 'Public Libraries', 'Infrastructure', NULL, NULL, NULL, 'i want to build this immediately !!!', NULL, NULL, NULL, 5000000000000.00, 'high', NULL, NULL, NULL, 'approved', 'awarded', NULL, 2, NULL, NULL, NULL, '2026-02-04 08:19:25', '2026-02-13 15:35:47', NULL),
(12, 3, 'Sangguniang Bayan', 'Cristian Mark Angelo Pastoril Llaneta', NULL, NULL, NULL, 'Basketball Court', 'Public Facilities', NULL, NULL, NULL, 'Need i-enhanced basketball court dito samin', NULL, NULL, NULL, NULL, 'medium', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-12 07:14:09', '2026-02-13 15:35:47', NULL),
(13, 4, 'Mayor\'s Office', 'Cristian Mark Angelo Pastoril Llaneta', NULL, NULL, NULL, 'Multi Purpose Hall', 'Building Construction', NULL, NULL, NULL, 'Wala kasi samin nyan dito', NULL, NULL, NULL, NULL, 'high', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-12 07:15:25', '2026-02-13 15:35:47', NULL),
(14, 5, 'Municipal Administrator\'s Office', 'Cristian Mark Angelo Pastoril Llaneta', NULL, NULL, NULL, 'Public Library', 'Infrastructure', NULL, NULL, NULL, 'Need namin ito sa barangay namin', NULL, NULL, NULL, NULL, 'low', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-12 08:18:00', '2026-02-13 15:35:47', NULL),
(15, 6, 'Municipal Social Welfare and Development Office', 'Cristian Mark Angelo Pastoril Llaneta', NULL, NULL, NULL, 'Animal Shelter', 'Rehabilitation', NULL, NULL, NULL, 'Mas need namin ito', NULL, NULL, NULL, NULL, 'high', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-12 08:19:01', '2026-02-13 15:35:47', NULL),
(16, 12, 'Municipal Assessor\'s Office', 'Cristian Mark Angelo Pastoril Llaneta', NULL, NULL, NULL, 'Healthcare Facility', 'Public Facilities', NULL, NULL, NULL, 'Pinaka need samin', NULL, NULL, NULL, NULL, 'high', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-12 08:19:51', '2026-02-13 15:35:47', NULL),
(17, 16, 'Municipal Engineering Office', 'Cristian', NULL, NULL, NULL, 'Playground', 'Public Facilities', NULL, NULL, NULL, 'Needed', NULL, NULL, NULL, NULL, 'low', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 06:33:43', '2026-02-13 15:35:47', NULL),
(18, 19, 'Municipal Engineering Office', 'Engr. Cristian Pastoril Llaneta', NULL, NULL, NULL, 'Road Rehabilitation and Drainage Improvement – Barangay Commonwealth Avenue', 'Infrastructure', NULL, NULL, NULL, 'The existing road section along Commonwealth Avenue in Barangay Commonwealth shows severe surface cracks, potholes, and inadequate drainage. These issues cause frequent flooding during heavy rains, traffic congestion, and safety hazards for motorists and pedestrians. Immediate rehabilitation and drainage improvement are required to restore road usability and reduce flood risk.', NULL, NULL, NULL, 2500000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:08:21', '2026-02-13 15:35:47', NULL),
(19, 20, 'Municipal Engineering Office', 'Engr. Cristian Pastoril Llaneta', NULL, NULL, NULL, 'Road Rehabilitation and Drainage Improvement', 'Infrastructure', NULL, NULL, NULL, 'Damaged pavement, potholes, and flooding affecting traffic and safety.', NULL, NULL, NULL, 2500000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:12:07', '2026-02-13 15:35:47', NULL),
(20, 21, 'Barangay Office', 'Maria Teresa Dela Cruz', NULL, NULL, NULL, 'Solar Street Light Installation', 'Electrical System', NULL, NULL, NULL, 'Poor night visibility increasing crime and accident risks.', NULL, NULL, NULL, 1200000.00, 'medium', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:14:09', '2026-02-13 15:35:47', NULL),
(21, 24, 'Municipal Environment and Natural Resources Office', 'Engr. Paolo Ramos', NULL, NULL, NULL, 'Flood Control Drainage Canal Rehabilitation', 'Drainage System', NULL, NULL, NULL, 'Recurrent flooding caused by clogged and undersized canals.', NULL, NULL, NULL, 3800000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:15:48', '2026-02-13 15:35:47', NULL),
(22, 27, 'Municipal Health Office', 'Dr. Liza Fernandez', NULL, NULL, NULL, 'Renovation of Barangay Health Center', 'Public Facilities', NULL, NULL, NULL, 'Aging facility and insufficient patient capacity.', NULL, NULL, NULL, 4500000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:18:05', '2026-02-13 15:35:47', NULL),
(23, 30, 'Barangay Office', 'Marites L. Santos', NULL, NULL, NULL, 'Barangay Hall Repair and Renovation', 'Public Facilities', NULL, NULL, NULL, 'Leaking roof and deteriorating interior walls affecting staff work areas and public service counters.', NULL, NULL, NULL, 1650000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:26:11', '2026-02-13 15:35:47', NULL),
(24, 31, 'Municipal Administrator\'s Office', 'Jonathan P. Reyes', NULL, NULL, NULL, 'Public Market Sanitation and Drainage Improvement', 'Public Facilities', NULL, NULL, NULL, 'Identified: Poor drainage and aging wash areas causing foul odor, puddling, and sanitation risks.', NULL, NULL, NULL, 3200000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:27:52', '2026-02-13 15:35:48', NULL),
(25, 32, 'Barangay Office', 'Roberto D. Villanueva', NULL, NULL, NULL, 'Rehabilitation of Barangay Covered Court', 'Rehabilitation', NULL, NULL, NULL, 'Damaged roofing and cracked flooring; lighting is insufficient for night activities.', NULL, NULL, NULL, 2250000.00, 'medium', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:29:22', '2026-02-13 15:35:48', NULL),
(26, 33, 'Municipal Planning and Development Office', 'Anna Mae D. Cruz', NULL, NULL, NULL, 'Public Library with Reading Area Expansion and Wi-Fi Upgrade', 'Public Facilities', NULL, NULL, NULL, 'Limited seating and unreliable connectivity affecting students and community users.', NULL, NULL, NULL, 1100000.00, 'low', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:31:42', '2026-02-13 15:35:48', NULL),
(27, 34, 'Municipal Social Welfare and Development Office', 'Mr. Jonathan M. Reyes', NULL, NULL, NULL, 'Construction of 2-Storey Public Elementary School', 'Infrastructure', NULL, NULL, NULL, 'Increasing student population with insufficient classroom availability in nearby schools.', NULL, NULL, NULL, 18000000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:36:17', '2026-02-13 15:35:48', NULL),
(28, 35, 'Municipal Health Office', 'Dr. Liza A. Fernandez', NULL, NULL, NULL, 'Construction of New Barangay Health Center', 'Public Facilities', NULL, NULL, NULL, 'Existing nearby health centers are overcrowded and unable to meet growing community healthcare demand.', NULL, NULL, NULL, 6500000.00, 'medium', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:37:55', '2026-02-13 15:35:48', NULL),
(29, 36, 'Barangay Office', 'Roberto D. Villanueva', NULL, NULL, NULL, 'Construction of Multipurpose Covered Court', 'Public Facilities', NULL, NULL, NULL, 'Lack of safe venue for sports, assemblies, and disaster evacuation activities.', NULL, NULL, NULL, 7200000.00, 'medium', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:39:24', '2026-02-13 15:35:48', NULL),
(30, 37, 'Municipal Social Welfare and Development Office', 'Grace P. Mendoza', NULL, NULL, NULL, 'Construction of Public Day Care Center', 'Public Facilities', NULL, NULL, NULL, 'Limited early childhood facilities serving a rapidly growing residential population.', NULL, NULL, NULL, 3800000.00, 'high', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:40:52', '2026-02-13 15:35:48', NULL),
(31, 38, 'Municipal Social Welfare and Development Office', 'Anna Mae D. Cruz', NULL, NULL, NULL, 'Construction of Community Public Library', 'Public Facilities', NULL, NULL, NULL, 'Residents in northern QC lack accessible study and learning spaces.', NULL, NULL, NULL, 9500000.00, 'medium', NULL, NULL, NULL, 'submitted', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:42:32', '2026-02-13 15:35:48', NULL),
(32, 39, 'Municipal Planning and Development Office', 'Noel P. Garcia', NULL, NULL, NULL, 'Construction of Public Market Annex', 'Public Facilities', NULL, NULL, NULL, 'Existing market stalls are overcrowded and cannot accommodate new vendors.', NULL, NULL, NULL, 14500000.00, 'medium', NULL, NULL, NULL, 'approved', NULL, NULL, 2, NULL, NULL, NULL, '2026-02-13 11:45:34', '2026-02-13 15:35:48', NULL);

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) DEFAULT 0
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
  `payment_method` varchar(50) DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) DEFAULT 0,
  `last_synced_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_slips`
--

INSERT INTO `payment_slips` (`id`, `slip_number`, `booking_id`, `amount_due`, `payment_deadline`, `status`, `payment_method`, `payment_gateway`, `gateway_transaction_id`, `gateway_reference_number`, `paymongo_checkout_id`, `payment_receipt_url`, `gateway_webhook_payload`, `treasurer_reference`, `or_number`, `treasurer_status`, `sent_to_treasurer_at`, `reminder_24h_sent_at`, `reminder_6h_sent_at`, `confirmed_by_treasurer_at`, `treasurer_cashier_name`, `treasurer_cashier_id`, `payment_intent_id`, `payment_source_id`, `payment_source_type`, `paymongo_response`, `paid_at`, `verified_by`, `transaction_reference`, `is_test_transaction`, `payment_channel`, `account_name`, `account_number`, `notes`, `created_at`, `updated_at`, `is_synced`, `last_synced_at`) VALUES
(1, 'PS-2025-000001', 4, 3720.00, '2025-12-27 01:36:50', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-25 10:47:55', 7, 'OR-2025-0001', 0, NULL, NULL, NULL, NULL, '2025-12-25 02:37:09', '2025-12-25 10:47:55', 1, '2026-02-13 14:45:10'),
(2, 'PS-2025-000002', 5, 11250.00, '2025-12-29 07:25:28', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-27 07:36:15', 7, 'OR-2025-0002', 0, NULL, NULL, NULL, NULL, '2025-12-27 07:25:28', '2025-12-27 07:36:15', 1, '2026-02-13 14:45:10'),
(3, 'PS-2025-000003', 6, 16250.00, '2025-12-29 22:43:22', 'paid', 'gcash', NULL, NULL, 'TEST-123456789', NULL, NULL, NULL, NULL, 'OR-2025-0003', NULL, '2025-12-27 22:57:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-27 22:57:40', 7, 'OR-2025-0003', 1, 'gcash', NULL, NULL, NULL, '2025-12-27 22:43:22', '2025-12-27 22:57:40', 1, '2026-02-13 14:45:10'),
(4, 'PS-2025-000004', 7, 55800.00, '2025-12-30 05:08:10', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-28 06:01:02', 7, NULL, 0, NULL, NULL, NULL, 'All goods', '2025-12-28 05:08:10', '2025-12-28 06:01:02', 1, '2026-02-13 14:45:10'),
(6, 'PS-2025-000005', 8, 64100.00, '2025-12-30 09:14:14', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-28 10:08:40', 7, NULL, 0, NULL, NULL, NULL, NULL, '2025-12-28 09:14:14', '2025-12-28 10:08:40', 1, '2026-02-13 14:45:10'),
(7, 'PS-2025-000006', 9, 5200.00, '2025-12-30 09:14:59', 'paid', 'paymaya', NULL, NULL, 'TEST-123456789', NULL, NULL, NULL, NULL, 'OR-2025-0006', NULL, '2025-12-28 09:56:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-29 08:00:23', 7, 'TEST-123456789', 1, 'maya', NULL, NULL, NULL, '2025-12-28 09:14:59', '2025-12-29 08:00:23', 1, '2026-02-13 14:45:10'),
(8, 'PS-2025-000007', 10, 3000.00, '2025-12-31 23:15:25', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2025-0007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-29 23:17:52', 7, NULL, 0, NULL, NULL, NULL, NULL, '2025-12-29 23:15:25', '2025-12-29 23:17:52', 1, '2026-02-13 14:45:10'),
(9, 'PS-2026-000001', 15, 4840.00, '2026-01-09 03:20:35', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-07 03:23:46', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-07 03:20:35', '2026-01-07 03:23:46', 1, '2026-02-13 14:45:10'),
(10, 'PS-2026-000002', 16, 4455.00, '2026-01-15 03:03:05', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-13 03:06:13', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 03:03:05', '2026-01-13 03:06:13', 1, '2026-02-13 14:45:10'),
(11, 'PS-2026-000003', 17, 5510.00, '2026-01-15 03:14:41', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0003', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-13 03:16:35', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 03:14:41', '2026-01-13 03:16:35', 1, '2026-02-13 14:45:10'),
(12, 'PS-2026-000004', 18, 23250.00, '2026-01-15 19:19:26', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-13 19:22:02', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 19:19:26', '2026-01-13 19:22:02', 1, '2026-02-13 14:45:10'),
(13, 'PS-2026-000005', 19, 4050.00, '2026-01-15 19:30:28', 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 19:30:28', '2026-01-16 21:25:35', 1, '2026-02-13 14:45:10'),
(15, 'PS-2026-000007', 21, 4050.00, '2026-01-15 19:37:02', 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-13 19:37:02', '2026-01-16 21:25:35', 1, '2026-02-13 14:45:10'),
(16, 'PS-2026-000008', 24, 3250.00, '2026-01-18 21:05:42', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-16 21:14:47', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-16 21:05:42', '2026-01-16 21:14:47', 1, '2026-02-13 14:45:10'),
(17, 'PS-2026-000009', 22, 4050.00, '2026-01-18 21:10:51', 'expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-01-16 21:10:51', '2026-02-07 04:31:36', 1, '2026-02-13 14:45:10'),
(18, 'PS-2026-000010', 23, 5075.00, '2026-01-18 21:59:38', 'paid', 'gcash', NULL, NULL, 'pay_eUAaZgEAQgNMq8pxRM8XBXXA', 'cs_vbYbYJcJMKTe7vyCDgKZfLGA', NULL, NULL, NULL, 'OR-2026-0008', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-28 00:34:15', NULL, 'pay_eUAaZgEAQgNMq8pxRM8XBXXA', 0, 'paymongo', NULL, NULL, NULL, '2026-01-16 21:59:38', '2026-01-28 00:34:15', 1, '2026-02-13 14:45:10'),
(19, 'PS-2026-000011', 25, 5600.00, '2026-01-30 09:01:01', 'paid', 'gcash', NULL, NULL, 'pay_Vh4njvBP3n6Co1BJCCbr2nrD', 'cs_AJwCtY8R5QH1kVnsMbP7CAwL', NULL, NULL, NULL, 'OR-2026-0009', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-28 09:02:35', NULL, 'pay_Vh4njvBP3n6Co1BJCCbr2nrD', 0, 'paymongo', NULL, NULL, NULL, '2026-01-28 09:01:01', '2026-01-28 09:02:35', 1, '2026-02-13 14:45:10'),
(20, 'PS-2026-000012', 27, 4050.00, '2026-02-04 01:34:36', 'paid', 'gcash', NULL, NULL, 'pay_8HrT3LLmEDqAKEjMcND5DakZ', 'cs_kUDSmz6Fogvw3NKvFwnEcgBo', NULL, NULL, NULL, 'OR-2026-0010', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-02 01:39:13', NULL, 'pay_8HrT3LLmEDqAKEjMcND5DakZ', 0, 'paymongo', NULL, NULL, NULL, '2026-02-02 01:34:36', '2026-02-02 01:39:13', 1, '2026-02-13 14:45:10'),
(22, 'PS-2026-000014', 29, 5075.00, '2026-02-04 03:04:51', 'paid', 'gcash', NULL, NULL, 'pay_Zfsv4Z5YgDpnWhNYtsHKavzo', 'cs_aDeUYLTJN3A4m1SpiJ5XhTK9', NULL, NULL, NULL, 'OR-2026-0012', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-02 03:06:16', NULL, 'pay_Zfsv4Z5YgDpnWhNYtsHKavzo', 0, 'paymongo', NULL, NULL, NULL, '2026-02-02 03:04:51', '2026-02-02 03:06:16', 1, '2026-02-13 14:45:10'),
(23, 'PS-2026-000015', 35, 191280.00, '2026-02-06 09:20:45', 'paid', 'gcash', NULL, NULL, 'pay_soy1Bs2RUiyFk8ha4z6PrVjD', 'cs_qjWcbgJFC7njd7QhLfn824md', NULL, NULL, NULL, 'OR-2026-0013', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-04 09:26:14', NULL, 'pay_soy1Bs2RUiyFk8ha4z6PrVjD', 0, 'paymongo', NULL, NULL, NULL, '2026-02-04 09:20:45', '2026-02-04 09:26:14', 1, '2026-02-13 14:45:10'),
(24, 'PS-2026-000016', 36, 12800.00, '2026-02-07 17:17:37', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0006', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-06 07:47:33', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-05 17:17:37', '2026-02-06 07:47:33', 1, '2026-02-13 14:45:10'),
(25, 'PS-2026-000017', 37, 11600.00, '2026-02-08 03:33:26', 'paid', 'cash', NULL, NULL, NULL, 'cs_b68d6f6eebf6d981fb144c34', NULL, NULL, NULL, 'OR-2026-0007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-06 07:47:51', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-06 03:33:26', '2026-02-06 07:47:51', 1, '2026-02-13 14:45:10'),
(26, 'PS-2026-000018', 38, 6200.00, '2026-02-09 05:37:36', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0014', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-07 07:37:22', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-07 05:37:36', '2026-02-07 07:37:22', 1, '2026-02-13 14:45:10'),
(27, 'PS-2026-000019', 39, 6966.40, '2026-02-09 08:08:18', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0015', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-07 08:08:44', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-07 08:08:18', '2026-02-07 08:08:44', 1, '2026-02-13 14:45:10'),
(28, 'PS-2026-000020', 41, 5600.00, '2026-02-09 08:46:43', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0016', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-07 08:47:17', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-07 08:46:43', '2026-02-07 08:47:17', 1, '2026-02-13 14:45:10'),
(29, 'PS-2026-000021', 43, 10080.00, '2026-02-09 13:51:51', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0017', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-07 13:52:17', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-07 13:51:51', '2026-02-07 13:52:17', 1, '2026-02-13 14:45:10'),
(30, 'PS-2026-000025', 45, 6111.00, '2026-02-16 15:52:04', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0018', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-09 16:05:09', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-09 15:52:04', '2026-02-09 16:05:09', 1, '2026-02-13 14:45:10'),
(31, 'PS-2026-000026', 45, 2037.00, '2026-02-09 16:22:50', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0019', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-09 15:10:36', 3, NULL, 0, NULL, NULL, NULL, 'Down payment (25%) collected at booking time', '2026-02-09 16:22:50', '2026-02-09 16:22:50', 1, '2026-02-13 14:45:10'),
(33, 'PS-2026-000027', 46, 2600.00, '2026-02-13 19:05:35', 'paid', 'gcash', NULL, NULL, 'pay_FRENUYVyiazFQdUdjypjXaFB', 'cs_df24c460418f24883a013675', NULL, NULL, NULL, 'OR-2026-000007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 06:49:01', NULL, 'pay_FRENUYVyiazFQdUdjypjXaFB', 0, 'paymongo', NULL, NULL, 'Down payment (50%) - pay at City Treasurers Office to submit booking for staff review.', '2026-02-10 19:05:35', '2026-02-11 06:49:01', 1, '2026-02-13 14:45:10'),
(34, 'PS-2026-000028', 48, 1012.50, '2026-02-13 19:05:35', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0027', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 03:16:11', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-10 19:05:35', '2026-02-12 03:16:11', 1, '2026-02-13 14:45:10'),
(35, 'PS-2026-000029', 49, 1012.50, '2026-02-13 19:05:35', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0026', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 03:16:04', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-10 19:05:35', '2026-02-12 03:16:04', 1, '2026-02-13 14:45:10'),
(36, 'PS-2026-000030', 50, 2548.00, '2026-02-13 19:05:35', 'paid', 'grab_pay', NULL, NULL, 'pay_ZBmxhJJwpApyp157nR3KXobW', 'cs_671521a45af8287289101992', NULL, NULL, NULL, 'OR-2026-0020', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 07:02:43', NULL, 'pay_ZBmxhJJwpApyp157nR3KXobW', 0, 'paymongo', NULL, NULL, 'Down payment (50%) - pay at City Treasurers Office to submit booking for staff review.', '2026-02-10 19:05:35', '2026-02-11 07:02:43', 1, '2026-02-13 14:45:10'),
(37, 'PS-2026-000031', 51, 1842.40, '2026-02-13 19:05:35', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 07:04:14', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-10 19:05:35', '2026-02-11 07:04:14', 1, '2026-02-13 14:45:10'),
(38, 'PS-2026-000032', 52, 11583.60, '2026-02-13 19:05:35', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 07:03:54', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-10 19:05:35', '2026-02-11 07:03:54', 1, '2026-02-13 14:45:10'),
(40, 'PS-2026-000033', 54, 4200.00, '2026-02-18 05:57:25', 'paid', 'gcash', NULL, NULL, 'pay_JCoSVCgawQTwzuNiNRYXgZUa', 'cs_7db1a00ed1c9bdac8ae1a2a8', NULL, NULL, NULL, 'OR-2026-000007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 06:45:12', NULL, 'pay_JCoSVCgawQTwzuNiNRYXgZUa', 0, 'paymongo', NULL, NULL, 'Remaining balance (25%) to collect. Down payment of ₱12,600.00 already received.', '2026-02-11 05:57:25', '2026-02-11 06:45:12', 1, '2026-02-13 14:45:10'),
(41, 'PS-2026-000034', 55, 16800.00, '2026-02-18 10:40:04', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 10:43:55', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-11 10:40:04', '2026-02-11 10:43:55', 1, '2026-02-13 14:45:10'),
(42, 'PS-2026-000035', 57, 4200.00, '2026-02-18 10:40:15', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0024', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 11:20:28', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-11 10:40:15', '2026-02-11 11:20:28', 1, '2026-02-13 14:45:10'),
(43, 'PS-2026-000036', 61, 13944.00, '2026-02-15 02:53:27', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0025', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 02:55:01', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 02:53:27', '2026-02-12 02:55:01', 1, '2026-02-13 14:45:10'),
(44, 'PS-2026-000037', 59, 3037.50, '2026-02-19 03:19:18', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0029', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 03:29:17', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 03:19:18', '2026-02-12 03:29:17', 1, '2026-02-13 14:45:10'),
(45, 'PS-2026-000038', 61, 41832.00, '2026-02-19 03:19:25', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 03:29:28', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 03:19:25', '2026-02-12 03:29:28', 1, '2026-02-13 14:45:10'),
(46, 'PS-2026-000039', 63, 10437.00, '2026-02-15 03:23:43', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0028', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 03:29:07', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 03:23:43', '2026-02-12 03:29:07', 1, '2026-02-13 14:45:10'),
(47, 'PS-2026-000040', 63, 31311.00, '2026-02-19 03:37:21', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0032', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 04:06:25', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 03:37:21', '2026-02-12 04:06:25', 1, '2026-02-13 14:45:10'),
(48, 'PS-2026-000041', 64, 1960.00, '2026-02-19 04:02:05', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0031', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 04:06:13', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 04:02:05', '2026-02-12 04:06:13', 1, '2026-02-13 14:45:10'),
(49, 'PS-2026-000042', 56, 8104.00, '2026-02-15 05:01:39', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0033', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 06:52:22', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 05:01:39', '2026-02-12 06:52:22', 1, '2026-02-13 14:45:10'),
(50, 'PS-2026-000043', 53, 784.00, '2026-02-15 06:45:40', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0035', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 06:52:40', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 06:45:40', '2026-02-12 06:52:40', 1, '2026-02-13 14:45:10'),
(51, 'PS-2026-000044', 56, 24312.00, '2026-02-15 06:52:24', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0034', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 06:52:31', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 06:52:24', '2026-02-12 06:52:31', 1, '2026-02-13 14:45:10'),
(52, 'PS-2026-000045', 53, 1568.00, '2026-02-15 06:52:43', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0036', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 06:52:49', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 06:52:43', '2026-02-12 06:52:49', 1, '2026-02-13 14:45:10'),
(53, 'PS-2026-000046', 46, 2600.00, '2026-02-19 07:04:13', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0037', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 07:04:39', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 07:04:13', '2026-02-12 07:04:39', 1, '2026-02-13 14:45:10'),
(54, 'PS-2026-000047', 65, 1960.00, '2026-02-15 07:06:41', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0038', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 07:07:24', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 07:06:41', '2026-02-12 07:07:24', 1, '2026-02-13 14:45:10'),
(55, 'PS-2026-000048', 65, 1960.00, '2026-02-19 07:16:16', 'paid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OR-2026-0039', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 07:16:42', 7, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-12 07:16:16', '2026-02-12 07:16:42', 1, '2026-02-13 14:45:10'),
(56, 'PS-2026-000049', 1, 34800.00, '2026-02-15 21:31:38', 'unpaid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'Down payment (100%) — pay at City Treasurer\'s Office to submit booking for staff review.', '2026-02-12 21:31:38', '2026-02-12 21:31:38', 1, '2026-02-13 14:50:02'),
(57, 'PS-2026-000050', 69, 21600.00, '2026-02-16 01:05:43', 'unpaid', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'Down payment (100%) — pay at City Treasurer\'s Office to submit booking for staff review.', '2026-02-13 01:05:43', '2026-02-13 01:05:43', 1, '2026-02-13 14:45:10');

-- --------------------------------------------------------

--
-- Table structure for table `refund_requests`
--

CREATE TABLE `refund_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `booking_reference` varchar(20) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email` varchar(255) DEFAULT NULL,
  `applicant_phone` varchar(255) DEFAULT NULL,
  `facility_name` varchar(255) DEFAULT NULL,
  `original_amount` decimal(10,2) NOT NULL,
  `refund_percentage` decimal(5,2) NOT NULL DEFAULT 100.00,
  `refund_amount` decimal(10,2) NOT NULL,
  `refund_type` enum('admin_rejected','citizen_cancelled') NOT NULL DEFAULT 'admin_rejected',
  `reason` text DEFAULT NULL,
  `refund_method` enum('cash','gcash','maya','bank_transfer') DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `status` enum('pending_method','pending_processing','processing','completed','failed') NOT NULL DEFAULT 'pending_method',
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `or_number` varchar(255) DEFAULT NULL,
  `treasurer_notes` text DEFAULT NULL,
  `initiated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `refund_requests`
--

INSERT INTO `refund_requests` (`id`, `booking_id`, `user_id`, `booking_reference`, `applicant_name`, `applicant_email`, `applicant_phone`, `facility_name`, `original_amount`, `refund_percentage`, `refund_amount`, `refund_type`, `reason`, `refund_method`, `account_name`, `account_number`, `bank_name`, `status`, `processed_by`, `processed_at`, `or_number`, `treasurer_notes`, `initiated_by`, `created_at`, `updated_at`) VALUES
(1, 41, NULL, 'BK000041', 'Cristian Mark Angelo Llaneta', 'lcristianmarkangelo@gmail.com', '09515691003', 'M.I.C.E. Breakout Room 1', 5600.00, 100.00, 5600.00, 'admin_rejected', 'Hanap nalang po ng ibang facility.\r\nThank you!', 'cash', NULL, NULL, NULL, 'completed', 7, '2026-02-07 13:39:01', NULL, NULL, 2, '2026-02-07 13:07:11', '2026-02-07 13:39:01'),
(2, 43, 5, 'BK000043', 'Cristian Mark Angelo Llaneta', '1hawkeye10101010l@gmail.com', '09515691003', 'QC M.I.C.E. Convention & Exhibit Hall', 10080.00, 100.00, 10080.00, 'admin_rejected', 'Hanap nalang po ng ibang facility.\r\nThank you!', 'cash', NULL, NULL, NULL, 'completed', 7, '2026-02-07 14:19:01', NULL, NULL, 2, '2026-02-07 13:53:10', '2026-02-07 14:19:01');

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
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `sync_status` enum('pending','synced','conflict') DEFAULT NULL,
  `source_server` enum('LOCAL','CLOUD') DEFAULT NULL,
  `version` int(11) DEFAULT 1,
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) DEFAULT 0
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
-- Indexes for table `citizen_road_requests`
--
ALTER TABLE `citizen_road_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citizen_road_requests_user_id_index` (`user_id`),
  ADD KEY `citizen_road_requests_status_index` (`status`);

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
  ADD KEY `idx_status` (`status`),
  ADD KEY `community_maintenance_requests_category_index` (`category`),
  ADD KEY `community_maintenance_requests_external_request_id_index` (`external_request_id`);

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
  ADD KEY `payment_slips_status_index` (`status`),
  ADD KEY `payment_slips_payment_deadline_index` (`payment_deadline`),
  ADD KEY `payment_slips_paid_at_index` (`paid_at`),
  ADD KEY `payment_slips_gateway_transaction_id_index` (`gateway_transaction_id`),
  ADD KEY `payment_slips_or_number_index` (`or_number`),
  ADD KEY `payment_slips_treasurer_status_index` (`treasurer_status`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refund_requests_booking_id_index` (`booking_id`),
  ADD KEY `refund_requests_user_id_index` (`user_id`),
  ADD KEY `refund_requests_status_index` (`status`);

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
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `booking_conflicts`
--
ALTER TABLE `booking_conflicts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_equipment`
--
ALTER TABLE `booking_equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `budget_allocations`
--
ALTER TABLE `budget_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `citizen_road_requests`
--
ALTER TABLE `citizen_road_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `city_events`
--
ALTER TABLE `city_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `community_maintenance_requests`
--
ALTER TABLE `community_maintenance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
