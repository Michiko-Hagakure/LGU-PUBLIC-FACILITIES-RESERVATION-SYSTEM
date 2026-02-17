-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 14, 2026 at 12:21 PM
-- Server version: 8.0.42
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lgu1_auth`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) DEFAULT '0',
  `last_synced_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `event`, `causer_id`, `causer_type`, `properties`, `ip_address`, `user_agent`, `created_at`, `updated_at`, `deleted_at`, `is_synced`, `last_synced_at`) VALUES
(1, 'cityevent', 'Created new record', 3, 'App\\Models\\CityEvent', 'created', NULL, NULL, '{\"attributes\": {\"id\": 3, \"status\": \"scheduled\", \"end_time\": \"2026-01-17T16:00:00.000000Z\", \"created_at\": \"2026-01-17T04:51:06.000000Z\", \"created_by\": 2, \"event_type\": \"government\", \"start_time\": \"2026-01-17T13:00:00.000000Z\", \"updated_at\": \"2026-01-17T04:51:06.000000Z\", \"event_title\": \"Founding Anniversary\", \"facility_id\": \"11\", \"event_description\": null}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-16 20:51:06', '2026-01-16 20:51:06', NULL, 1, '2026-02-13 20:31:24'),
(2, 'cityevent', 'Updated record', 3, 'App\\Models\\CityEvent', 'updated', NULL, NULL, '{\"attributes\": {\"id\": 3, \"status\": \"scheduled\", \"end_time\": \"2026-01-17T16:00:00.000000Z\", \"created_at\": \"2026-01-17T04:51:06.000000Z\", \"created_by\": 2, \"event_type\": \"government\", \"start_time\": \"2026-01-17T13:00:00.000000Z\", \"updated_at\": \"2026-01-17T04:51:06.000000Z\", \"event_title\": \"Founding Anniversary\", \"facility_id\": \"11\", \"event_description\": null, \"affected_bookings_count\": 0}, \"affected_bookings_count\": 0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-16 20:51:06', '2026-01-16 20:51:06', NULL, 1, '2026-02-13 20:31:24'),
(3, 'booking', 'Created new record', 24, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 24, \"status\": \"pending\", \"purpose\": \"Charity Event\", \"user_id\": 5, \"end_time\": \"2026-01-26T11:00:00.000000Z\", \"subtotal\": \"3250.00\", \"base_rate\": \"3250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-17T05:03:00.000000Z\", \"start_time\": \"2026-01-26T08:00:00.000000Z\", \"updated_at\": \"2026-01-17T05:03:00.000000Z\", \"facility_id\": \"14\", \"is_resident\": false, \"total_amount\": \"3250.00\", \"valid_id_type\": \"SSS ID\", \"extension_rate\": \"0.00\", \"total_discount\": \"0.00\", \"equipment_total\": \"0.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 25, \"valid_id_back_path\": \"bookings/valid_ids/back/Pc0cuCAQV5ODny5Wh6YrYn2sxWBU3IODN1XROoYY.png\", \"valid_id_front_path\": \"bookings/valid_ids/front/SOhZ0pOzjQJyX33be30jByU9aK2z7No4a5lNIGeY.png\", \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/wRPaBPaGRLGVlKOXAWuJoWvfq6IXc7zJW5349dzw.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-16 21:03:00', '2026-01-16 21:03:00', NULL, 1, '2026-02-13 20:31:24'),
(4, 'booking', 'Updated record', 22, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"staff_verified\", \"attributes\": {\"id\": 22, \"status\": \"staff_verified\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-01-26T11:00:00.000000Z\", \"subtotal\": \"4050.00\", \"base_rate\": \"4050.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-17T04:40:21.000000Z\", \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-26T08:00:00.000000Z\", \"updated_at\": \"2026-01-17T05:10:50.000000Z\", \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"4050.00\", \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-17T05:10:50.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 30, \"valid_id_back_path\": \"bookings/valid_ids/back/0przzM5OVAUM78cdm73ZckJAjc5ohxQBw413GhLf.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/jpLjgTTINuGNQoLvOwq5u9wKMOC1JXlds91FXr5C.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/NDbwuPt1E7MpDCFDqa6omHrYlyBhwkp3YctYUx1M.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-01-17 05:10:50\", \"staff_verified_at\": \"2026-01-17 05:10:50\", \"staff_verified_by\": 3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-16 21:10:51', '2026-01-16 21:10:51', NULL, 1, '2026-02-13 20:31:24'),
(5, 'booking', 'Updated record', 24, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"paid\", \"attributes\": {\"id\": 24, \"status\": \"paid\", \"purpose\": \"Charity Event\", \"user_id\": 5, \"end_time\": \"2026-01-26T11:00:00.000000Z\", \"subtotal\": \"3250.00\", \"base_rate\": \"3250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-17T05:03:00.000000Z\", \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-26T08:00:00.000000Z\", \"updated_at\": \"2026-01-17T05:14:47.000000Z\", \"facility_id\": 14, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"3250.00\", \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-17T05:05:41.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 25, \"valid_id_back_path\": \"bookings/valid_ids/back/Pc0cuCAQV5ODny5Wh6YrYn2sxWBU3IODN1XROoYY.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/SOhZ0pOzjQJyX33be30jByU9aK2z7No4a5lNIGeY.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/wRPaBPaGRLGVlKOXAWuJoWvfq6IXc7zJW5349dzw.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-01-17 05:14:47\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-16 21:14:47', '2026-01-16 21:14:47', NULL, 1, '2026-02-13 20:31:24'),
(6, 'booking', 'Updated record', 24, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"confirmed\", \"attributes\": {\"id\": 24, \"status\": \"confirmed\", \"purpose\": \"Charity Event\", \"user_id\": 5, \"end_time\": \"2026-01-26T11:00:00.000000Z\", \"subtotal\": \"3250.00\", \"base_rate\": \"3250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-17T05:03:00.000000Z\", \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-26T08:00:00.000000Z\", \"updated_at\": \"2026-01-17T05:15:50.000000Z\", \"facility_id\": 14, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"3250.00\", \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2026-01-17T05:15:50.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-17T05:05:41.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 25, \"valid_id_back_path\": \"bookings/valid_ids/back/Pc0cuCAQV5ODny5Wh6YrYn2sxWBU3IODN1XROoYY.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/SOhZ0pOzjQJyX33be30jByU9aK2z7No4a5lNIGeY.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/wRPaBPaGRLGVlKOXAWuJoWvfq6IXc7zJW5349dzw.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-01-17 05:15:50\", \"admin_approved_at\": \"2026-01-17 05:15:50\", \"admin_approved_by\": 2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-16 21:15:50', '2026-01-16 21:15:50', NULL, 1, '2026-02-13 20:31:24'),
(7, 'booking', 'Updated record', 19, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"canceled\", \"attributes\": {\"id\": 19, \"status\": \"canceled\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-01-22T11:00:00.000000Z\", \"subtotal\": \"4050.00\", \"base_rate\": \"4050.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-14T03:27:54.000000Z\", \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-22T08:00:00.000000Z\", \"updated_at\": \"2026-01-17T05:25:35.000000Z\", \"canceled_at\": \"2026-01-17T05:25:35.000000Z\", \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"4050.00\", \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": \"Payment deadline exceeded\", \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-14T03:30:28.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 30, \"valid_id_back_path\": \"bookings/valid_ids/back/NmqpAOAxmNG9g1ve9pkfAp9PIQkUDVwxPnVKk2UI.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/PrReX91J2PROVmlhzzWEMd4UzXoQsZ7UcMV8UCZI.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/Q53paABKWVZIGpkLBDbuiPydHP52f6AF7ipegJJ3.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-01-17 05:25:35\", \"canceled_at\": \"2026-01-17 05:25:35\", \"canceled_reason\": \"Payment deadline exceeded\"}', '127.0.0.1', 'Symfony', '2026-01-16 21:25:35', '2026-01-16 21:25:35', NULL, 1, '2026-02-13 20:31:24'),
(8, 'booking', 'Updated record', 20, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"canceled\", \"attributes\": {\"id\": 20, \"status\": \"canceled\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-01-22T16:00:00.000000Z\", \"subtotal\": \"4050.00\", \"base_rate\": \"4050.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-14T03:33:15.000000Z\", \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-22T13:00:00.000000Z\", \"updated_at\": \"2026-01-17T05:25:35.000000Z\", \"canceled_at\": \"2026-01-17T05:25:35.000000Z\", \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"4050.00\", \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": \"Payment deadline exceeded\", \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-14T03:34:12.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 30, \"valid_id_back_path\": \"bookings/valid_ids/back/G2gurtZYQSdsCgofltf2NaEPlKBvS0QF2xjRrtxG.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/RChKnqwq4Eedg3fP4waGP3wE6Ow6bpKiWIrTLE5D.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/hkBCiraZFGzc6QW1JcumQKHG3leIkMdp2sOjMJDC.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-01-17 05:25:35\", \"canceled_at\": \"2026-01-17 05:25:35\", \"canceled_reason\": \"Payment deadline exceeded\"}', '127.0.0.1', 'Symfony', '2026-01-16 21:25:35', '2026-01-16 21:25:35', NULL, 1, '2026-02-13 20:31:24'),
(9, 'booking', 'Updated record', 21, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"canceled\", \"attributes\": {\"id\": 21, \"status\": \"canceled\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-01-22T21:00:00.000000Z\", \"subtotal\": \"4050.00\", \"base_rate\": \"4050.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-14T03:36:09.000000Z\", \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-22T18:00:00.000000Z\", \"updated_at\": \"2026-01-17T05:25:35.000000Z\", \"canceled_at\": \"2026-01-17T05:25:35.000000Z\", \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"4050.00\", \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": \"Payment deadline exceeded\", \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-14T03:37:01.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 30, \"valid_id_back_path\": \"bookings/valid_ids/back/8GZgFDuH0NB7Pd5c99w5mUDZnBxHUAUULsQbx4My.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/EX74RHAog3aPaQ9vIYNe6QWFSGj9Hlfps7SZQPN8.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/VERadGBxFnnZ5yrxr0iH36IEI4UFYnVAt4Ho6brB.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-01-17 05:25:35\", \"canceled_at\": \"2026-01-17 05:25:35\", \"canceled_reason\": \"Payment deadline exceeded\"}', '127.0.0.1', 'Symfony', '2026-01-16 21:25:35', '2026-01-16 21:25:35', NULL, 1, '2026-02-13 20:31:24'),
(10, 'booking', 'Updated record', 23, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"staff_verified\", \"attributes\": {\"id\": 23, \"status\": \"staff_verified\", \"purpose\": \"Wedding Reception\", \"user_id\": 5, \"end_time\": \"2026-01-26T11:00:00.000000Z\", \"subtotal\": \"5075.00\", \"base_rate\": \"5075.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-17T04:49:43.000000Z\", \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-26T08:00:00.000000Z\", \"updated_at\": \"2026-01-17T05:59:38.000000Z\", \"canceled_at\": null, \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"5075.00\", \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-17T05:59:38.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 35, \"valid_id_back_path\": \"bookings/valid_ids/back/T2I3kCIBJKYu5Ud0izD4DPVa4m0fTKti0BdSHf5H.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/XYL6TtEvNdpn8f0XA4QWl5XTBImLZfH2nAefs62T.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/tXwadIXCAjAZyvLJnTzNpyHD2ang7a55BiUHUHak.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-01-17 05:59:38\", \"staff_verified_at\": \"2026-01-17 05:59:38\", \"staff_verified_by\": 3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-16 21:59:38', '2026-01-16 21:59:38', NULL, 1, '2026-02-13 20:31:24'),
(11, 'user', 'Updated record', 2, 'App\\Models\\User', 'updated', NULL, NULL, '{\"attributes\": {\"id\": 2, \"email\": \"llanetacristianpastoril@gmail.com\", \"gender\": null, \"status\": \"active\", \"city_id\": null, \"role_id\": null, \"username\": \"admin\", \"zip_code\": null, \"birthdate\": null, \"full_name\": \"Llaneta Cristian Pastoril\", \"region_id\": null, \"created_at\": \"2025-12-15T02:26:11.000000Z\", \"last_login\": null, \"updated_at\": \"2026-01-24T06:45:40.000000Z\", \"barangay_id\": null, \"district_id\": null, \"nationality\": \"Filipino\", \"province_id\": null, \"reviewed_at\": null, \"reviewed_by\": null, \"selfie_hash\": null, \"civil_status\": null, \"id_back_hash\": null, \"subsystem_id\": 4, \"id_front_hash\": null, \"mobile_number\": null, \"password_hash\": \"$2y$12$9nhFiUWu/WsWbU31jcQVCO37zSE3Etq8iS7Aa9Y9NA/q4WSLHbZ1y\", \"valid_id_type\": null, \"id_verified_at\": null, \"id_verified_by\": null, \"liveness_score\": null, \"two_factor_pin\": null, \"current_address\": null, \"face_match_score\": null, \"email_verified_at\": \"2025-12-15T02:26:11.000000Z\", \"is_email_verified\": 1, \"subsystem_role_id\": 1, \"profile_photo_path\": \"uploads/avatars/1769237140.jpg\", \"profile_visibility\": \"private\", \"show_booking_count\": 0, \"two_factor_enabled\": 0, \"manual_review_notes\": null, \"valid_id_back_image\": null, \"ai_verification_data\": null, \"duplicate_of_user_id\": null, \"manual_review_status\": null, \"selfie_with_id_image\": null, \"valid_id_front_image\": null, \"ai_verification_notes\": null, \"id_authenticity_score\": null, \"id_verification_notes\": null, \"show_reviews_publicly\": 1, \"ai_verification_status\": \"pending\", \"id_verification_status\": \"pending\", \"email_verification_token\": null, \"is_duplicate_id_detected\": 0}, \"updated_at\": \"2026-01-24 06:45:40\", \"profile_photo_path\": \"uploads/avatars/1769237140.jpg\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 22:45:41', '2026-01-23 22:45:41', NULL, 1, '2026-02-13 20:31:24'),
(12, 'user', 'Updated record', 2, 'App\\Models\\User', 'updated', NULL, NULL, '{\"attributes\": {\"id\": 2, \"email\": \"llanetacristianpastoril@gmail.com\", \"gender\": null, \"status\": \"active\", \"city_id\": null, \"role_id\": null, \"username\": \"admin\", \"zip_code\": null, \"birthdate\": null, \"full_name\": \"Llaneta Cristian Pastoril\", \"region_id\": null, \"created_at\": \"2025-12-15T02:26:11.000000Z\", \"last_login\": null, \"updated_at\": \"2026-01-24T07:03:28.000000Z\", \"barangay_id\": null, \"district_id\": null, \"nationality\": \"Filipino\", \"province_id\": null, \"reviewed_at\": null, \"reviewed_by\": null, \"selfie_hash\": null, \"civil_status\": null, \"id_back_hash\": null, \"subsystem_id\": 4, \"id_front_hash\": null, \"mobile_number\": null, \"password_hash\": \"$2y$12$9nhFiUWu/WsWbU31jcQVCO37zSE3Etq8iS7Aa9Y9NA/q4WSLHbZ1y\", \"valid_id_type\": null, \"id_verified_at\": null, \"id_verified_by\": null, \"liveness_score\": null, \"two_factor_pin\": null, \"current_address\": null, \"face_match_score\": null, \"email_verified_at\": \"2025-12-15T02:26:11.000000Z\", \"is_email_verified\": 1, \"subsystem_role_id\": 1, \"profile_photo_path\": null, \"profile_visibility\": \"private\", \"show_booking_count\": 0, \"two_factor_enabled\": 0, \"manual_review_notes\": null, \"valid_id_back_image\": null, \"ai_verification_data\": null, \"duplicate_of_user_id\": null, \"manual_review_status\": null, \"selfie_with_id_image\": null, \"valid_id_front_image\": null, \"ai_verification_notes\": null, \"id_authenticity_score\": null, \"id_verification_notes\": null, \"show_reviews_publicly\": 1, \"ai_verification_status\": \"pending\", \"id_verification_status\": \"pending\", \"email_verification_token\": null, \"is_duplicate_id_detected\": 0}, \"updated_at\": \"2026-01-24 07:03:28\", \"profile_photo_path\": null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 23:03:28', '2026-01-23 23:03:28', NULL, 1, '2026-02-13 20:31:24'),
(13, 'booking', 'Created new record', 25, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 25, \"status\": \"pending\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-02-05T03:00:00.000000Z\", \"subtotal\": \"7000.00\", \"base_rate\": \"7000.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-28T08:59:55.000000Z\", \"start_time\": \"2026-02-05T00:00:00.000000Z\", \"updated_at\": \"2026-01-28T08:59:55.000000Z\", \"facility_id\": \"12\", \"is_resident\": false, \"total_amount\": \"5600.00\", \"valid_id_type\": \"School ID\", \"extension_rate\": \"0.00\", \"total_discount\": \"1400.00\", \"equipment_total\": \"0.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/eqMbowRY361dGEAUVEtyHvzk91DtOAVryUQUVcH9.png\", \"valid_id_front_path\": \"bookings/valid_ids/front/UW0BduhqczcTbKKyUCiGr3tKTXDKNbj3jS2Uruac.png\", \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/bSGGSSQUOYqBVecVBlrrfRW3jtwQxS73mdMGs7O0.png\", \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"1400.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-28 08:59:55', '2026-01-28 08:59:55', NULL, 1, '2026-02-13 20:31:24'),
(14, 'booking', 'Updated record', 25, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"staff_verified\", \"attributes\": {\"id\": 25, \"status\": \"staff_verified\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-02-05T03:00:00.000000Z\", \"subtotal\": \"7000.00\", \"base_rate\": \"7000.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-28T08:59:55.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-02-05T00:00:00.000000Z\", \"updated_at\": \"2026-01-28T09:01:01.000000Z\", \"canceled_at\": null, \"facility_id\": 12, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"5600.00\", \"valid_id_type\": \"School ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"1400.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-28T09:01:01.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/eqMbowRY361dGEAUVEtyHvzk91DtOAVryUQUVcH9.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/UW0BduhqczcTbKKyUCiGr3tKTXDKNbj3jS2Uruac.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/bSGGSSQUOYqBVecVBlrrfRW3jtwQxS73mdMGs7O0.png\", \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"1400.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-01-28 17:01:01\", \"staff_verified_at\": \"2026-01-28 17:01:01\", \"staff_verified_by\": 3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-01-28 09:01:01', '2026-01-28 09:01:01', NULL, 1, '2026-02-13 20:31:24'),
(15, 'booking', 'Updated record', 5, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 5, \"status\": \"completed\", \"purpose\": \"Educational Workshop\", \"user_id\": 5, \"end_time\": \"2026-01-05T03:00:00.000000Z\", \"subtotal\": \"11250.00\", \"base_rate\": \"7250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2025-12-27T07:24:32.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-05T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:48.000000Z\", \"canceled_at\": null, \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"11250.00\", \"source_system\": null, \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"4000.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2025-12-27T07:56:44.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2025-12-27T07:25:28.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/AMSUToKjpAHLdaQZhTrukakweKf5bGpHIbStDI2S.jpg\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/YZzhEExri5HZYddmAhkpWDm8Ufrp7Vk0aoNV9WI6.jpg\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/GPjJY8xnPvEnsfAn52Rx1l4NawG68j3Nbu0LzMS4.jpg\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:48\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:50', '2026-02-03 15:14:50', NULL, 1, '2026-02-13 20:31:24'),
(16, 'booking', 'Updated record', 6, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 6, \"status\": \"completed\", \"purpose\": \"Charity Event\", \"user_id\": 5, \"end_time\": \"2026-01-05T10:00:00.000000Z\", \"subtotal\": \"16250.00\", \"base_rate\": \"5075.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2025-12-27T22:42:19.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-05T05:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:50.000000Z\", \"canceled_at\": null, \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"16250.00\", \"source_system\": null, \"valid_id_type\": \"PhilHealth ID\", \"applicant_name\": null, \"extension_rate\": \"875.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"10300.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2025-12-29T09:39:30.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2025-12-27T22:43:22.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 35, \"valid_id_back_path\": \"bookings/valid_ids/back/KJPASj4DUJHkIusGGYPBDkj5EJiM9YUtdPRBA3tk.jpg\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/MHmQK4HZYkCY5mfmFr0h6WE7L7zYZdEZhmAdzxEE.jpg\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/w3LnMkc2AIu8mpyEbNj3rGy79Q0UmvxaPB5Rp2ID.jpg\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:50\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:50', '2026-02-03 15:14:50', NULL, 1, '2026-02-13 20:31:24'),
(17, 'booking', 'Updated record', 7, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 7, \"status\": \"completed\", \"purpose\": \"Cultural Event\", \"user_id\": 5, \"end_time\": \"2026-01-05T05:00:00.000000Z\", \"subtotal\": \"55800.00\", \"base_rate\": \"6750.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2025-12-28T04:24:57.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-05T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:50.000000Z\", \"canceled_at\": null, \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"55800.00\", \"source_system\": null, \"valid_id_type\": \"Passport\", \"applicant_name\": null, \"extension_rate\": \"1000.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"48050.00\", \"rejected_reason\": null, \"special_requests\": \"Please arrange and prepare the equipment well.\\r\\n\\r\\nThank you!\", \"admin_approved_at\": \"2025-12-28T06:02:37.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2025-12-28T05:22:45.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/ZAyZsekJmpz05cuSWvILFZuf6wQldVzgP0CfxJWl.jpg\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/RrR1HQcPYqwSuea3khwPElqvFzrmC9vna9uzI4wm.jpg\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/zUYcct6mmPu9zLbKc1cyvlAKVZmoPGMO0sLPyO8D.jpg\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:50\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:50', '2026-02-03 15:14:50', NULL, 1, '2026-02-13 20:31:24'),
(18, 'booking', 'Updated record', 8, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 8, \"status\": \"completed\", \"purpose\": \"Boxing Match\", \"user_id\": 5, \"end_time\": \"2026-01-06T03:00:00.000000Z\", \"subtotal\": \"64100.00\", \"base_rate\": \"19500.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2025-12-28T08:31:14.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-06T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:50.000000Z\", \"canceled_at\": null, \"facility_id\": 14, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"64100.00\", \"source_system\": null, \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"44600.00\", \"rejected_reason\": null, \"special_requests\": \"Let\'s Gooooo.\", \"admin_approved_at\": \"2025-12-29T23:20:50.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2025-12-28T09:14:14.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 150, \"valid_id_back_path\": \"bookings/valid_ids/back/LKdUinnrggXhurC60rsCvhWXQIyLlqAsgvInazr4.jpg\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/J0WLEYduNKIxmjuoBHhaXv9RzqjC4r4RWBnEiJvV.jpg\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/EGMvrjajDi9cFEJOQ34guFRq3mPfJi2b0BZzVpBW.jpg\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:50\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:51', '2026-02-03 15:14:51', NULL, 1, '2026-02-13 20:31:24'),
(19, 'booking', 'Updated record', 9, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 9, \"status\": \"completed\", \"purpose\": \"Family Reunion\", \"user_id\": 5, \"end_time\": \"2026-01-06T08:00:00.000000Z\", \"subtotal\": \"6500.00\", \"base_rate\": \"6500.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2025-12-28T08:58:30.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-06T05:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:51.000000Z\", \"canceled_at\": null, \"facility_id\": 14, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"5200.00\", \"source_system\": null, \"valid_id_type\": \"Senior Citizen ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"1300.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2025-12-29T23:20:22.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2025-12-28T09:14:59.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/fX6DlRG1Dtlk6IORagZcrZK4q4rthfjGcjIGiG0m.jpg\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/r1yc7Sw63XRTNpGgTlW71VuJ1vU84CE5WYSMzV2k.jpg\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/YzM5cT1r1jJn2ER1Y8L4pJife1Z07fEyZFocmRI7.jpg\", \"external_reference_id\": null, \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"senior\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"1300.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:51\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:51', '2026-02-03 15:14:51', NULL, 1, '2026-02-13 20:31:24'),
(20, 'booking', 'Updated record', 15, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 15, \"status\": \"completed\", \"purpose\": \"Wala lang\", \"user_id\": 5, \"end_time\": \"2026-01-15T03:00:00.000000Z\", \"subtotal\": \"6050.00\", \"base_rate\": \"4050.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-06T18:44:05.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-15T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:51.000000Z\", \"canceled_at\": null, \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"4840.00\", \"source_system\": null, \"valid_id_type\": \"PWD ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"1210.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"2000.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2026-01-07T03:58:46.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-07T03:20:35.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 30, \"valid_id_back_path\": \"bookings/valid_ids/back/dEHFY7maClbekAhZn9iTY2DRrGkPooHpIhesqjlI.jpg\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/aqPFsFtbaabTMdC663eLmp83LgRmzLDi8QNa0PI3.jpg\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/nRQLGuwIEWa3Me0SCh4oSNc06lmdcTuxQy6CtVhH.jpg\", \"external_reference_id\": null, \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"pwd\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"1210.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:51\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:51', '2026-02-03 15:14:51', NULL, 1, '2026-02-13 20:31:24'),
(21, 'booking', 'Updated record', 16, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 16, \"status\": \"completed\", \"purpose\": \"Wedding Reception\", \"user_id\": 5, \"end_time\": \"2026-01-21T03:00:00.000000Z\", \"subtotal\": \"4455.00\", \"base_rate\": \"4455.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-13T03:01:12.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-21T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:51.000000Z\", \"canceled_at\": null, \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"4455.00\", \"source_system\": null, \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2026-01-13T03:07:19.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-13T03:03:05.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 33, \"valid_id_back_path\": \"bookings/valid_ids/back/1sHbvPEBs3VmzYTyHDHskdNCFle0pRZKm7Nb0tl2.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/il0YlzEUnwh6PkiiLOsno0V0CUQxhsNGo6prVKpI.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/zlZjzIfnOmSHZOJhWVrBYVONC6WbiqKKdTo5vQEF.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:51\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:51', '2026-02-03 15:14:51', NULL, 1, '2026-02-13 20:31:24'),
(22, 'booking', 'Updated record', 17, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 17, \"status\": \"completed\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-01-21T03:00:00.000000Z\", \"subtotal\": \"5510.00\", \"base_rate\": \"5510.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-13T03:13:28.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-21T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:51.000000Z\", \"canceled_at\": null, \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"5510.00\", \"source_system\": null, \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2026-01-13T03:17:30.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-13T03:14:41.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 38, \"valid_id_back_path\": \"bookings/valid_ids/back/VfLiSwHrdPQMetzzVyyhLZCptAOuJx5UdIv7Ho0M.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/zqXzLjohE6pJA9N7UJJ1QHkQRJjU5smkD26PkjLx.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/AqwxuhD5EzkATtC6M1Hq7HJLzoFAOMs7z7msDIxX.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:51\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:51', '2026-02-03 15:14:51', NULL, 1, '2026-02-13 20:31:24'),
(23, 'booking', 'Updated record', 18, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 18, \"status\": \"completed\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-01-22T03:00:00.000000Z\", \"subtotal\": \"23250.00\", \"base_rate\": \"3250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-13T19:16:39.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-22T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:51.000000Z\", \"canceled_at\": null, \"facility_id\": 14, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"23250.00\", \"source_system\": null, \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"20000.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2026-01-13T19:23:14.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-13T19:19:26.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 25, \"valid_id_back_path\": \"bookings/valid_ids/back/tC2Rw7QhoEPorKsk7hLoV6Rp9HLikh6d3bsRPjO7.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/KUAwCsDLjo77aUHuiZFrQTVqinok2SQVPkYOb7ib.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/vE0aqXCyTL1spK1Qla5dYBYdxqQfe9saOEdjF6xU.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:51\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:51', '2026-02-03 15:14:51', NULL, 1, '2026-02-13 20:31:24'),
(24, 'booking', 'Updated record', 23, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 23, \"status\": \"completed\", \"purpose\": \"Wedding Reception\", \"user_id\": 5, \"end_time\": \"2026-01-26T03:00:00.000000Z\", \"subtotal\": \"5075.00\", \"base_rate\": \"5075.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-16T20:49:43.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-26T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:51.000000Z\", \"canceled_at\": null, \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"5075.00\", \"source_system\": null, \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-16T21:59:38.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 35, \"valid_id_back_path\": \"bookings/valid_ids/back/T2I3kCIBJKYu5Ud0izD4DPVa4m0fTKti0BdSHf5H.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/XYL6TtEvNdpn8f0XA4QWl5XTBImLZfH2nAefs62T.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/tXwadIXCAjAZyvLJnTzNpyHD2ang7a55BiUHUHak.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:51\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:51', '2026-02-03 15:14:51', NULL, 1, '2026-02-13 20:31:24'),
(25, 'booking', 'Updated record', 24, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 24, \"status\": \"completed\", \"purpose\": \"Charity Event\", \"user_id\": 5, \"end_time\": \"2026-01-26T03:00:00.000000Z\", \"subtotal\": \"3250.00\", \"base_rate\": \"3250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-16T21:03:00.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-01-26T00:00:00.000000Z\", \"updated_at\": \"2026-02-03T15:14:51.000000Z\", \"canceled_at\": null, \"facility_id\": 14, \"is_resident\": false, \"staff_notes\": null, \"total_amount\": \"3250.00\", \"source_system\": null, \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"special_requests\": null, \"admin_approved_at\": \"2026-01-16T21:15:50.000000Z\", \"admin_approved_by\": 2, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-16T21:05:41.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 25, \"valid_id_back_path\": \"bookings/valid_ids/back/Pc0cuCAQV5ODny5Wh6YrYn2sxWBU3IODN1XROoYY.png\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/SOhZ0pOzjQJyX33be30jByU9aK2z7No4a5lNIGeY.png\", \"admin_approval_notes\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/wRPaBPaGRLGVlKOXAWuJoWvfq6IXc7zJW5349dzw.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-03 23:14:51\"}', '127.0.0.1', 'Symfony', '2026-02-03 15:14:51', '2026-02-03 15:14:51', NULL, 1, '2026-02-13 20:31:24');
INSERT INTO `activity_logs` (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `event`, `causer_id`, `causer_type`, `properties`, `ip_address`, `user_agent`, `created_at`, `updated_at`, `deleted_at`, `is_synced`, `last_synced_at`) VALUES
(26, 'booking', 'Updated record', 25, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"completed\", \"attributes\": {\"id\": 25, \"status\": \"completed\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-02-05T03:00:00.000000Z\", \"subtotal\": \"7000.00\", \"base_rate\": \"7000.00\", \"is_synced\": 0, \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-28T08:59:55.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-02-05T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T15:15:03.000000Z\", \"amount_paid\": \"0.00\", \"canceled_at\": null, \"facility_id\": 12, \"is_resident\": false, \"staff_notes\": null, \"payment_tier\": null, \"total_amount\": \"5600.00\", \"source_system\": null, \"valid_id_type\": \"School ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"payment_method\": null, \"rejection_type\": null, \"reserved_until\": null, \"total_discount\": \"1400.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"amount_remaining\": \"0.00\", \"rejection_fields\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-28T09:01:01.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/eqMbowRY361dGEAUVEtyHvzk91DtOAVryUQUVcH9.png\", \"down_payment_amount\": \"0.00\", \"payment_recorded_by\": null, \"paymongo_payment_id\": null, \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/UW0BduhqczcTbKKyUCiGr3tKTXDKNbj3jS2Uruac.png\", \"admin_approval_notes\": null, \"down_payment_paid_at\": null, \"paymongo_checkout_id\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/bSGGSSQUOYqBVecVBlrrfRW3jtwQxS73mdMGs7O0.png\", \"external_reference_id\": null, \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"1400.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-11 23:15:03\"}', '127.0.0.1', 'Symfony', '2026-02-11 15:15:03', '2026-02-11 15:15:03', NULL, 1, '2026-02-13 20:31:24'),
(27, 'booking', 'Created new record', 26, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 26, \"status\": \"awaiting_payment\", \"purpose\": \"wal lang\", \"user_id\": 5, \"end_time\": \"2026-02-19T03:00:00.000000Z\", \"subtotal\": \"7250.00\", \"base_rate\": \"7250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T15:22:14.000000Z\", \"start_time\": \"2026-02-19T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T15:22:14.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": \"13\", \"is_resident\": false, \"payment_tier\": 100, \"total_amount\": \"5800.00\", \"valid_id_type\": \"School ID\", \"extension_rate\": \"0.00\", \"payment_method\": \"cash\", \"total_discount\": \"1450.00\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"5800.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/nMykhQEtQ1HJznROUhLdNuWZuf0oRNYFi3ZJP3Bj.png\", \"down_payment_amount\": \"5800.00\", \"valid_id_front_path\": \"bookings/valid_ids/front/yH2cEfVYN8WJIUiybbNoEl8gKicND8be9xo0O4A1.png\", \"down_payment_paid_at\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/PM6xoTWjUUcktd4kCBN1E0bFfQhJPxa6rZteGxob.png\", \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"1450.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 15:22:14', '2026-02-11 15:22:14', NULL, 1, '2026-02-13 20:31:24'),
(28, 'booking', 'Created new record', 27, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 27, \"status\": \"awaiting_payment\", \"purpose\": \"wal lang\", \"user_id\": 5, \"end_time\": \"2026-02-19T03:00:00.000000Z\", \"subtotal\": \"7250.00\", \"base_rate\": \"7250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T15:22:44.000000Z\", \"start_time\": \"2026-02-19T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T15:22:44.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": \"13\", \"is_resident\": false, \"payment_tier\": 100, \"total_amount\": \"7250.00\", \"valid_id_type\": \"School ID\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"0.00\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"7250.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/x02o6RRxFYt5WPWEpH6yS7jrZGsJO5SpE6PxnS23.png\", \"down_payment_amount\": \"7250.00\", \"valid_id_front_path\": \"bookings/valid_ids/front/anGB2CWcdAOwAFzl6BtfbhB0kaLyA29LJoafyXam.png\", \"down_payment_paid_at\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/W8ogpmz8jesmu3SKcpC0PHcYvzsH0hFoAVOdRyoD.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 15:22:44', '2026-02-11 15:22:44', NULL, 1, '2026-02-13 20:31:24'),
(29, 'booking', 'Updated record', 27, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"attributes\": {\"id\": 27, \"status\": \"awaiting_payment\", \"purpose\": \"wal lang\", \"user_id\": 5, \"end_time\": \"2026-02-19T03:00:00.000000Z\", \"subtotal\": \"7250.00\", \"base_rate\": \"7250.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T15:22:44.000000Z\", \"start_time\": \"2026-02-19T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T15:22:45.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": \"13\", \"is_resident\": false, \"payment_tier\": 100, \"total_amount\": \"7250.00\", \"valid_id_type\": \"School ID\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"0.00\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"7250.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/x02o6RRxFYt5WPWEpH6yS7jrZGsJO5SpE6PxnS23.png\", \"down_payment_amount\": \"7250.00\", \"valid_id_front_path\": \"bookings/valid_ids/front/anGB2CWcdAOwAFzl6BtfbhB0kaLyA29LJoafyXam.png\", \"down_payment_paid_at\": null, \"paymongo_checkout_id\": \"cs_2e257c7d3270dcfbf6e9208c\", \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/W8ogpmz8jesmu3SKcpC0PHcYvzsH0hFoAVOdRyoD.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-11 23:22:45\", \"paymongo_checkout_id\": \"cs_2e257c7d3270dcfbf6e9208c\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 15:22:45', '2026-02-11 15:22:45', NULL, 1, '2026-02-13 20:31:24'),
(30, 'booking', 'Updated record', 27, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"pending\", \"attributes\": {\"id\": 27, \"status\": \"pending\", \"purpose\": \"wal lang\", \"user_id\": 5, \"end_time\": \"2026-02-19T03:00:00.000000Z\", \"subtotal\": \"7250.00\", \"base_rate\": \"7250.00\", \"is_synced\": 0, \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T15:22:44.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-02-19T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T15:23:51.000000Z\", \"amount_paid\": \"7250.00\", \"canceled_at\": null, \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": null, \"payment_tier\": 100, \"total_amount\": \"7250.00\", \"source_system\": null, \"valid_id_type\": \"School ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"rejection_type\": null, \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"amount_remaining\": \"0.00\", \"rejection_fields\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": null, \"staff_verified_by\": null, \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/x02o6RRxFYt5WPWEpH6yS7jrZGsJO5SpE6PxnS23.png\", \"down_payment_amount\": \"7250.00\", \"payment_recorded_by\": null, \"paymongo_payment_id\": \"pay_scckcccsiMgKpVS6AHE2GpfV\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/anGB2CWcdAOwAFzl6BtfbhB0kaLyA29LJoafyXam.png\", \"admin_approval_notes\": null, \"down_payment_paid_at\": \"2026-02-11T15:23:51.000000Z\", \"paymongo_checkout_id\": \"cs_2e257c7d3270dcfbf6e9208c\", \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/W8ogpmz8jesmu3SKcpC0PHcYvzsH0hFoAVOdRyoD.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-11 23:23:51\", \"amount_paid\": \"7250.00\", \"amount_remaining\": 0, \"paymongo_payment_id\": \"pay_scckcccsiMgKpVS6AHE2GpfV\", \"down_payment_paid_at\": \"2026-02-11 23:23:51\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 15:23:51', '2026-02-11 15:23:51', NULL, 1, '2026-02-13 20:31:24'),
(31, 'booking', 'Updated record', 22, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"expired\", \"attributes\": {\"id\": 22, \"status\": \"expired\", \"purpose\": \"Birthday Celebration\", \"user_id\": 5, \"end_time\": \"2026-01-26T03:00:00.000000Z\", \"subtotal\": \"4050.00\", \"base_rate\": \"4050.00\", \"is_synced\": 0, \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-01-16T20:40:21.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": \"2026-02-11T16:00:03.644612Z\", \"start_time\": \"2026-01-26T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T16:00:03.000000Z\", \"amount_paid\": \"0.00\", \"canceled_at\": null, \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"payment_tier\": null, \"total_amount\": \"4050.00\", \"source_system\": null, \"valid_id_type\": \"SSS ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"payment_method\": null, \"rejection_type\": null, \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": \"Payment deadline exceeded (auto-expired)\", \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"amount_remaining\": \"0.00\", \"rejection_fields\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": \"2026-01-16T21:10:50.000000Z\", \"staff_verified_by\": 3, \"expected_attendees\": 30, \"valid_id_back_path\": \"bookings/valid_ids/back/0przzM5OVAUM78cdm73ZckJAjc5ohxQBw413GhLf.png\", \"down_payment_amount\": \"0.00\", \"payment_recorded_by\": null, \"paymongo_payment_id\": null, \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/jpLjgTTINuGNQoLvOwq5u9wKMOC1JXlds91FXr5C.png\", \"admin_approval_notes\": null, \"down_payment_paid_at\": null, \"paymongo_checkout_id\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/NDbwuPt1E7MpDCFDqa6omHrYlyBhwkp3YctYUx1M.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"expired_at\": \"2026-02-11T16:00:03.644612Z\", \"updated_at\": \"2026-02-12 00:00:03\", \"canceled_reason\": \"Payment deadline exceeded (auto-expired)\"}', '127.0.0.1', 'Symfony', '2026-02-11 16:00:03', '2026-02-11 16:00:03', NULL, 1, '2026-02-13 20:31:24'),
(32, 'booking', 'Created new record', 28, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 28, \"status\": \"awaiting_payment\", \"purpose\": \"wala lang\", \"user_id\": 5, \"end_time\": \"2026-02-25T03:00:00.000000Z\", \"subtotal\": \"6500.00\", \"base_rate\": \"6500.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T16:14:01.000000Z\", \"start_time\": \"2026-02-25T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T16:14:01.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": \"14\", \"is_resident\": false, \"payment_tier\": 100, \"total_amount\": \"6500.00\", \"valid_id_type\": \"Company ID\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"0.00\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"6500.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/ISKghKv3cXrfLFOaMveAmxVKjUmdGuEAVHZp2uG0.png\", \"down_payment_amount\": \"6500.00\", \"valid_id_front_path\": \"bookings/valid_ids/front/Vh79LlJ9QIldMbQJ3FdeHrzQTnEi0MFuJO2KZb5h.png\", \"down_payment_paid_at\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/okIY6T94rbnXfg4bXlHFfDuoeQHOxH41CaCthsV2.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}}', '136.158.7.63', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 16:14:02', '2026-02-11 16:14:02', NULL, 1, '2026-02-13 20:31:24'),
(33, 'booking', 'Updated record', 28, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"attributes\": {\"id\": 28, \"status\": \"awaiting_payment\", \"purpose\": \"wala lang\", \"user_id\": 5, \"end_time\": \"2026-02-25T03:00:00.000000Z\", \"subtotal\": \"6500.00\", \"base_rate\": \"6500.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T16:14:01.000000Z\", \"start_time\": \"2026-02-25T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T16:14:02.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": \"14\", \"is_resident\": false, \"payment_tier\": 100, \"total_amount\": \"6500.00\", \"valid_id_type\": \"Company ID\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"0.00\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"6500.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/ISKghKv3cXrfLFOaMveAmxVKjUmdGuEAVHZp2uG0.png\", \"down_payment_amount\": \"6500.00\", \"valid_id_front_path\": \"bookings/valid_ids/front/Vh79LlJ9QIldMbQJ3FdeHrzQTnEi0MFuJO2KZb5h.png\", \"down_payment_paid_at\": null, \"paymongo_checkout_id\": \"cs_6d19fa0faf9e38e18f59cd8f\", \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/okIY6T94rbnXfg4bXlHFfDuoeQHOxH41CaCthsV2.png\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-12 00:14:02\", \"paymongo_checkout_id\": \"cs_6d19fa0faf9e38e18f59cd8f\"}', '136.158.7.63', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 16:14:02', '2026-02-11 16:14:02', NULL, 1, '2026-02-13 20:31:24'),
(34, 'booking', 'Updated record', 28, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"pending\", \"attributes\": {\"id\": 28, \"status\": \"pending\", \"purpose\": \"wala lang\", \"user_id\": 5, \"end_time\": \"2026-02-25T03:00:00.000000Z\", \"subtotal\": \"6500.00\", \"base_rate\": \"6500.00\", \"is_synced\": 0, \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T16:14:01.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-02-25T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T16:14:49.000000Z\", \"amount_paid\": \"6500.00\", \"canceled_at\": null, \"facility_id\": 14, \"is_resident\": false, \"staff_notes\": null, \"payment_tier\": 100, \"total_amount\": \"6500.00\", \"source_system\": null, \"valid_id_type\": \"Company ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"rejection_type\": null, \"reserved_until\": null, \"total_discount\": \"0.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"amount_remaining\": \"0.00\", \"rejection_fields\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": null, \"staff_verified_by\": null, \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/ISKghKv3cXrfLFOaMveAmxVKjUmdGuEAVHZp2uG0.png\", \"down_payment_amount\": \"6500.00\", \"payment_recorded_by\": null, \"paymongo_payment_id\": \"pay_sxg239yPZoc4UbV6Wf83KFQv\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/Vh79LlJ9QIldMbQJ3FdeHrzQTnEi0MFuJO2KZb5h.png\", \"admin_approval_notes\": null, \"down_payment_paid_at\": \"2026-02-11T16:14:49.000000Z\", \"paymongo_checkout_id\": \"cs_6d19fa0faf9e38e18f59cd8f\", \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/okIY6T94rbnXfg4bXlHFfDuoeQHOxH41CaCthsV2.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-12 00:14:49\", \"amount_paid\": \"6500.00\", \"amount_remaining\": 0, \"paymongo_payment_id\": \"pay_sxg239yPZoc4UbV6Wf83KFQv\", \"down_payment_paid_at\": \"2026-02-12 00:14:49\"}', '136.158.7.63', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 16:14:49', '2026-02-11 16:14:49', NULL, 1, '2026-02-13 20:31:24'),
(35, 'booking', 'Created new record', 29, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 29, \"status\": \"awaiting_payment\", \"purpose\": \"Government Program\", \"user_id\": 5, \"end_time\": \"2026-02-20T03:00:00.000000Z\", \"subtotal\": \"14000.00\", \"base_rate\": \"14000.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T16:57:53.000000Z\", \"start_time\": \"2026-02-20T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T16:57:53.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": \"12\", \"is_resident\": false, \"payment_tier\": 100, \"total_amount\": \"11200.00\", \"valid_id_type\": \"School ID\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"2800.00\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"11200.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 100, \"valid_id_back_path\": \"bookings/valid_ids/back/0kYMwQZ6T2UMDL6Z5R2qKI77grIETzA8wyt6ZB3Q.png\", \"down_payment_amount\": \"11200.00\", \"valid_id_front_path\": \"bookings/valid_ids/front/g1Ay3dfqUUUHe502BmPiMrLDa0j2Wx0HaHhgN3Vl.png\", \"down_payment_paid_at\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/kMab4euMiCl4MRQHkmL24yatnj9f3gcurkXIrF6F.png\", \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"2800.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}}', '136.158.7.63', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 16:57:53', '2026-02-11 16:57:53', NULL, 1, '2026-02-13 20:31:24'),
(36, 'booking', 'Updated record', 29, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"attributes\": {\"id\": 29, \"status\": \"awaiting_payment\", \"purpose\": \"Government Program\", \"user_id\": 5, \"end_time\": \"2026-02-20T03:00:00.000000Z\", \"subtotal\": \"14000.00\", \"base_rate\": \"14000.00\", \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T16:57:53.000000Z\", \"start_time\": \"2026-02-20T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T16:57:55.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": \"12\", \"is_resident\": false, \"payment_tier\": 100, \"total_amount\": \"11200.00\", \"valid_id_type\": \"School ID\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"2800.00\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"11200.00\", \"special_requests\": null, \"city_of_residence\": \"Quezon City\", \"expected_attendees\": 100, \"valid_id_back_path\": \"bookings/valid_ids/back/0kYMwQZ6T2UMDL6Z5R2qKI77grIETzA8wyt6ZB3Q.png\", \"down_payment_amount\": \"11200.00\", \"valid_id_front_path\": \"bookings/valid_ids/front/g1Ay3dfqUUUHe502BmPiMrLDa0j2Wx0HaHhgN3Vl.png\", \"down_payment_paid_at\": null, \"paymongo_checkout_id\": \"cs_0b7c8de5d018b3460210852a\", \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/kMab4euMiCl4MRQHkmL24yatnj9f3gcurkXIrF6F.png\", \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"2800.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-12 00:57:55\", \"paymongo_checkout_id\": \"cs_0b7c8de5d018b3460210852a\"}', '136.158.7.63', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 16:57:55', '2026-02-11 16:57:55', NULL, 1, '2026-02-13 20:31:24'),
(37, 'booking', 'Updated record', 29, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"pending\", \"attributes\": {\"id\": 29, \"status\": \"pending\", \"purpose\": \"Government Program\", \"user_id\": 5, \"end_time\": \"2026-02-20T03:00:00.000000Z\", \"subtotal\": \"14000.00\", \"base_rate\": \"14000.00\", \"is_synced\": 0, \"user_name\": \"Cristian Mark Angelo Llaneta\", \"created_at\": \"2026-02-11T16:57:53.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": null, \"start_time\": \"2026-02-20T00:00:00.000000Z\", \"updated_at\": \"2026-02-11T16:58:40.000000Z\", \"amount_paid\": \"11200.00\", \"canceled_at\": null, \"facility_id\": 12, \"is_resident\": false, \"staff_notes\": null, \"payment_tier\": 100, \"total_amount\": \"11200.00\", \"source_system\": null, \"valid_id_type\": \"School ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"rejection_type\": null, \"reserved_until\": null, \"total_discount\": \"2800.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": null, \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"amount_remaining\": \"0.00\", \"rejection_fields\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": null, \"staff_verified_by\": null, \"expected_attendees\": 100, \"valid_id_back_path\": \"bookings/valid_ids/back/0kYMwQZ6T2UMDL6Z5R2qKI77grIETzA8wyt6ZB3Q.png\", \"down_payment_amount\": \"11200.00\", \"payment_recorded_by\": null, \"paymongo_payment_id\": \"pay_aikJfZCACrunSus2QHJoSG9g\", \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/g1Ay3dfqUUUHe502BmPiMrLDa0j2Wx0HaHhgN3Vl.png\", \"admin_approval_notes\": null, \"down_payment_paid_at\": \"2026-02-11T16:58:40.000000Z\", \"paymongo_checkout_id\": \"cs_0b7c8de5d018b3460210852a\", \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/kMab4euMiCl4MRQHkmL24yatnj9f3gcurkXIrF6F.png\", \"external_reference_id\": null, \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"2800.00\", \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"updated_at\": \"2026-02-12 00:58:40\", \"amount_paid\": \"11200.00\", \"amount_remaining\": 0, \"paymongo_payment_id\": \"pay_aikJfZCACrunSus2QHJoSG9g\", \"down_payment_paid_at\": \"2026-02-12 00:58:40\"}', '136.158.7.63', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 16:58:40', '2026-02-11 16:58:40', NULL, 1, '2026-02-13 20:31:24'),
(182, 'booking', 'Updated record', 77, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"expired\", \"attributes\": {\"id\": 77, \"status\": \"expired\", \"purpose\": \"Birthday Celebration\", \"user_id\": 13, \"end_time\": \"2026-03-11T03:00:00.000000Z\", \"subtotal\": \"27000.00\", \"base_rate\": \"27000.00\", \"is_synced\": 1, \"user_name\": \"Mahiru Shiina\", \"created_at\": \"2026-02-13T16:52:27.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": \"2026-02-13T18:12:25.010614Z\", \"start_time\": \"2026-03-11T00:00:00.000000Z\", \"updated_at\": \"2026-02-13T18:12:25.000000Z\", \"amount_paid\": \"0.00\", \"canceled_at\": null, \"facility_id\": 11, \"is_resident\": false, \"staff_notes\": null, \"payment_tier\": 100, \"total_amount\": \"21600.00\", \"source_system\": null, \"valid_id_type\": \"School ID\", \"applicant_name\": null, \"extension_rate\": \"0.00\", \"last_synced_at\": \"2026-02-14 00:53:01\", \"payment_method\": \"cash\", \"rejection_type\": null, \"reserved_until\": null, \"total_discount\": \"5400.00\", \"applicant_email\": null, \"applicant_phone\": null, \"canceled_reason\": \"Cashless payment not completed within 1 hour (auto-expired)\", \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"amount_remaining\": \"21600.00\", \"rejection_fields\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"booking_reference\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": null, \"staff_verified_by\": null, \"expected_attendees\": 200, \"valid_id_back_path\": \"bookings/valid_ids/back/JXsebQnFWMECM9cYmMKYgH0wXYnEHm3mhLYcbTyq.png\", \"down_payment_amount\": \"21600.00\", \"payment_recorded_by\": null, \"payment_rejected_at\": null, \"paymongo_payment_id\": null, \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/bFPjtVzL0CX7CG0tua4A5nXesJbMhbbYeMTZqZ9x.jpg\", \"admin_approval_notes\": null, \"down_payment_paid_at\": null, \"paymongo_checkout_id\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/q61cHiwED2SbzsJ5jpsASJQa5yKuKX22YG93FO2L.png\", \"external_reference_id\": null, \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"5400.00\", \"payment_rejection_reason\": null, \"resident_discount_amount\": \"0.00\", \"special_discount_id_path\": null}, \"expired_at\": \"2026-02-13T18:12:25.010614Z\", \"updated_at\": \"2026-02-14 02:12:25\", \"canceled_reason\": \"Cashless payment not completed within 1 hour (auto-expired)\"}', '136.158.39.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-13 18:12:25', '2026-02-13 18:12:25', NULL, 1, '2026-02-13 20:31:24'),
(183, 'booking', 'Updated record', 78, 'App\\Models\\Booking', 'updated', NULL, NULL, '{\"status\": \"expired\", \"attributes\": {\"id\": 78, \"status\": \"expired\", \"purpose\": \"Corporate Event\", \"user_id\": 13, \"end_time\": \"2026-03-13T03:00:00.000000Z\", \"subtotal\": \"7000.00\", \"base_rate\": \"6250.00\", \"is_synced\": 1, \"user_name\": \"Mahiru Shiina\", \"created_at\": \"2026-02-13T16:58:55.000000Z\", \"deleted_at\": null, \"deleted_by\": null, \"event_date\": null, \"event_name\": null, \"expired_at\": \"2026-02-13T18:12:25.021115Z\", \"start_time\": \"2026-03-13T00:00:00.000000Z\", \"updated_at\": \"2026-02-13T18:12:25.000000Z\", \"amount_paid\": \"0.00\", \"canceled_at\": null, \"facility_id\": 16, \"is_resident\": true, \"staff_notes\": null, \"payment_tier\": 100, \"total_amount\": \"4900.00\", \"source_system\": null, \"valid_id_type\": \"Company ID\", \"applicant_name\": null, \"extension_rate\": \"750.00\", \"last_synced_at\": \"2026-02-14 00:59:01\", \"payment_method\": \"cash\", \"rejection_type\": null, \"reserved_until\": null, \"total_discount\": \"2100.00\", \"applicant_email\": \"yeshahagakure@gmail.com\", \"applicant_phone\": null, \"canceled_reason\": \"Cashless payment not completed within 1 hour (auto-expired)\", \"equipment_total\": \"0.00\", \"rejected_reason\": null, \"amount_remaining\": \"4900.00\", \"rejection_fields\": null, \"special_requests\": null, \"admin_approved_at\": null, \"admin_approved_by\": null, \"applicant_address\": null, \"booking_reference\": null, \"city_of_residence\": \"Quezon City\", \"event_description\": null, \"staff_verified_at\": null, \"staff_verified_by\": null, \"expected_attendees\": 50, \"valid_id_back_path\": \"bookings/valid_ids/back/VUGFG1UZ4V08bCc7a49KE0kENbSmvQmre1MdTpy2.png\", \"down_payment_amount\": \"4900.00\", \"payment_recorded_by\": null, \"payment_rejected_at\": null, \"paymongo_payment_id\": null, \"supporting_doc_path\": null, \"valid_id_front_path\": \"bookings/valid_ids/front/18tiIDmlmAArHJvfzJWvFhCuyFXZf17ixH9xWcMx.jpg\", \"admin_approval_notes\": null, \"down_payment_paid_at\": null, \"paymongo_checkout_id\": null, \"valid_id_selfie_path\": \"bookings/valid_ids/selfie/EbZUkno28JyaK158bMrzIYj0eZP0Bg1ByUOR5Bp1.png\", \"external_reference_id\": null, \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"30.00\", \"special_discount_amount\": \"0.00\", \"payment_rejection_reason\": null, \"resident_discount_amount\": \"2100.00\", \"special_discount_id_path\": null}, \"expired_at\": \"2026-02-13T18:12:25.021115Z\", \"updated_at\": \"2026-02-14 02:12:25\", \"canceled_reason\": \"Cashless payment not completed within 1 hour (auto-expired)\"}', '136.158.39.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-13 18:12:25', '2026-02-13 18:12:25', NULL, 1, '2026-02-13 20:31:24'),
(184, 'booking', 'Created new record', 79, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 79, \"status\": \"awaiting_payment\", \"purpose\": \"Debute\", \"user_id\": 46, \"end_time\": \"2026-02-24T03:00:00.000000Z\", \"subtotal\": \"43500.00\", \"base_rate\": \"43500.00\", \"user_name\": \"lazy lazy\", \"created_at\": \"2026-02-13T18:14:07.000000Z\", \"event_name\": \"Birthday Party\", \"start_time\": \"2026-02-24T00:00:00.000000Z\", \"updated_at\": \"2026-02-13T18:14:07.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": \"Submitted via API from: LGU1_Citizen_Portal (Ref: CP-1771006447-46)\", \"payment_tier\": 25, \"total_amount\": \"34800.00\", \"valid_id_type\": \"School ID\", \"applicant_name\": \"lazy lazy\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"8700.00\", \"applicant_email\": \"lazysloths001@gmail.com\", \"applicant_phone\": \"09515691003\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"34800.00\", \"special_requests\": null, \"applicant_address\": \"Area 5a Naval Street, Sauyo, Quezon City, NCR\", \"city_of_residence\": null, \"event_description\": null, \"expected_attendees\": 300, \"valid_id_back_path\": \"/uploads/valid_ids/valid_id_back_1771006447_46.jpg\", \"down_payment_amount\": \"8700.00\", \"valid_id_front_path\": \"/uploads/valid_ids/valid_id_front_1771006447_46.jpg\", \"down_payment_paid_at\": null, \"valid_id_selfie_path\": \"/uploads/valid_ids/valid_id_selfie_1771006447_46.jpg\", \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"8700.00\", \"resident_discount_amount\": \"0.00\"}}', '23.94.230.146', NULL, '2026-02-13 18:14:07', '2026-02-13 18:14:07', NULL, 1, '2026-02-13 20:31:24'),
(185, 'booking', 'Created new record', 80, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 80, \"status\": \"awaiting_payment\", \"purpose\": \"Debute\", \"user_id\": 46, \"end_time\": \"2026-02-24T03:00:00.000000Z\", \"subtotal\": \"43500.00\", \"base_rate\": \"43500.00\", \"user_name\": \"lazy lazy\", \"created_at\": \"2026-02-13T18:15:29.000000Z\", \"event_name\": \"Birthday Party\", \"start_time\": \"2026-02-24T00:00:00.000000Z\", \"updated_at\": \"2026-02-13T18:15:29.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": \"Submitted via API from: LGU1_Citizen_Portal (Ref: CP-1771006529-46)\", \"payment_tier\": 25, \"total_amount\": \"43500.00\", \"valid_id_type\": \"School ID\", \"applicant_name\": \"lazy lazy\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"0.00\", \"applicant_email\": \"lazysloths001@gmail.com\", \"applicant_phone\": \"09515691003\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"43500.00\", \"special_requests\": null, \"applicant_address\": \"Area 5a Naval Street, Bagbag, Quezon City, NCR\", \"city_of_residence\": null, \"event_description\": null, \"expected_attendees\": 300, \"valid_id_back_path\": \"/uploads/valid_ids/valid_id_back_1771006529_46.jpg\", \"down_payment_amount\": \"10875.00\", \"valid_id_front_path\": \"/uploads/valid_ids/valid_id_front_1771006529_46.jpg\", \"down_payment_paid_at\": null, \"valid_id_selfie_path\": \"/uploads/valid_ids/valid_id_selfie_1771006529_46.jpg\", \"special_discount_rate\": \"0.00\", \"special_discount_type\": null, \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"0.00\", \"resident_discount_amount\": \"0.00\"}}', '23.94.230.146', NULL, '2026-02-13 18:15:29', '2026-02-13 18:15:29', NULL, 1, '2026-02-13 20:31:24'),
(186, 'booking', 'Created new record', 81, 'App\\Models\\Booking', 'created', NULL, NULL, '{\"attributes\": {\"id\": 81, \"status\": \"awaiting_payment\", \"purpose\": \"debu\", \"user_id\": 46, \"end_time\": \"2026-02-24T03:00:00.000000Z\", \"subtotal\": \"29000.00\", \"base_rate\": \"29000.00\", \"user_name\": \"lazy lazy\", \"created_at\": \"2026-02-13T19:14:33.000000Z\", \"event_name\": \"Birthday Party\", \"start_time\": \"2026-02-24T00:00:00.000000Z\", \"updated_at\": \"2026-02-13T19:14:33.000000Z\", \"amount_paid\": \"0.00\", \"facility_id\": 13, \"is_resident\": false, \"staff_notes\": \"Submitted via API from: LGU1_Citizen_Portal (Ref: CP-1771010073-46)\", \"payment_tier\": 25, \"total_amount\": \"23200.00\", \"valid_id_type\": \"School ID\", \"applicant_name\": \"lazy lazy\", \"extension_rate\": \"0.00\", \"payment_method\": \"cashless\", \"total_discount\": \"5800.00\", \"applicant_email\": \"lazysloths001@gmail.com\", \"applicant_phone\": \"09515691003\", \"equipment_total\": \"0.00\", \"amount_remaining\": \"23200.00\", \"special_requests\": null, \"applicant_address\": \"Area 5a Naval Street, Bagbag, Quezon City, NCR\", \"city_of_residence\": null, \"event_description\": null, \"expected_attendees\": 200, \"valid_id_back_path\": \"/uploads/valid_ids/valid_id_back_1771010073_46.png\", \"down_payment_amount\": \"5800.00\", \"valid_id_front_path\": \"/uploads/valid_ids/valid_id_front_1771010073_46.png\", \"down_payment_paid_at\": null, \"valid_id_selfie_path\": \"/uploads/valid_ids/valid_id_selfie_1771010073_46.png\", \"special_discount_rate\": \"20.00\", \"special_discount_type\": \"student\", \"resident_discount_rate\": \"0.00\", \"special_discount_amount\": \"5800.00\", \"resident_discount_amount\": \"0.00\"}}', '23.94.230.146', NULL, '2026-02-13 19:14:33', '2026-02-13 19:14:33', NULL, 1, '2026-02-13 20:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `changes` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model`, `model_id`, `changes`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 3, 'verify', 'Booking', 3, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-19 01:27:00', '2025-12-19 01:27:00'),
(2, 3, 'approve', 'Booking', 3, '{\"status\":{\"staff_verified\":\"confirmed\"},\"approved_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-19 03:27:00', '2025-12-19 03:27:00'),
(3, 3, 'verify', 'Booking', 4, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-25 01:24:00', '2025-12-25 01:24:00'),
(4, 3, 'approve', 'Booking', 4, '{\"status\":{\"staff_verified\":\"confirmed\"},\"approved_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-25 04:24:00', '2025-12-25 04:24:00'),
(5, 3, 'verify', 'Booking', 5, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-27 01:53:00', '2025-12-27 01:53:00'),
(6, 3, 'approve', 'Booking', 5, '{\"status\":{\"staff_verified\":\"confirmed\"},\"approved_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-27 03:53:00', '2025-12-27 03:53:00'),
(7, 3, 'verify', 'Booking', 6, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-29 01:48:00', '2025-12-29 01:48:00'),
(8, 3, 'approve', 'Booking', 6, '{\"status\":{\"staff_verified\":\"confirmed\"},\"approved_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-29 04:48:00', '2025-12-29 04:48:00'),
(9, 3, 'verify', 'Booking', 7, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-29 01:56:00', '2025-12-29 01:56:00'),
(10, 3, 'approve', 'Booking', 7, '{\"status\":{\"staff_verified\":\"confirmed\"},\"approved_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-29 05:56:00', '2025-12-29 05:56:00'),
(11, 3, 'verify', 'Booking', 8, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-29 01:34:00', '2025-12-29 01:34:00'),
(12, 3, 'approve', 'Booking', 8, '{\"status\":{\"staff_verified\":\"confirmed\"},\"approved_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-29 03:34:00', '2025-12-29 03:34:00'),
(13, 3, 'verify', 'Booking', 9, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-29 01:39:00', '2025-12-29 01:39:00'),
(14, 3, 'approve', 'Booking', 9, '{\"status\":{\"staff_verified\":\"confirmed\"},\"approved_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-29 05:39:00', '2025-12-29 05:39:00'),
(15, 3, 'verify', 'Booking', 10, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-30 01:39:00', '2025-12-30 01:39:00'),
(16, 3, 'approve', 'Booking', 10, '{\"status\":{\"staff_verified\":\"confirmed\"},\"approved_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-30 05:39:00', '2025-12-30 05:39:00'),
(17, 3, 'verify', 'Booking', 11, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2025-12-30 02:38:00', '2025-12-30 02:38:00'),
(18, 3, 'reject', 'Booking', 11, '{\"status\":{\"staff_verified\":\"rejected\"},\"rejected_by\":3,\"reason\":\"Incomplete requirements\"}', '127.0.0.1', 'Mozilla/5.0', '2025-12-30 04:04:00', '2025-12-30 04:04:00'),
(19, 3, 'verify', 'Booking', 12, '{\"status\":{\"pending\":\"staff_verified\"},\"verified_by\":3}', '127.0.0.1', 'Mozilla/5.0', '2026-01-01 02:36:00', '2026-01-01 02:36:00'),
(20, 3, 'reject', 'Booking', 12, '{\"status\":{\"staff_verified\":\"rejected\"},\"rejected_by\":3,\"reason\":\"Incomplete requirements\"}', '127.0.0.1', 'Mozilla/5.0', '2026-01-01 03:22:00', '2026-01-01 03:22:00'),
(21, 3, 'verify', 'Booking', 24, '{\"status\":\"staff_verified\",\"staff_notes\":null,\"payment_slip\":\"PS-2026-000008\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-16 21:05:42', '2026-01-16 21:05:42'),
(22, 2, 'update', 'Facility', 11, '{\"name\":\"Buena Park\",\"city_id\":\"1\",\"is_available\":\"1\",\"capacity\":\"200\",\"description\":\"Open-air community park suitable for outdoor gatherings and events. Perfect for weekend private events and community celebrations.\",\"address\":\"South Caloocan City\",\"per_person_rate\":\"135.00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:23:27', '2026-01-27 11:23:27'),
(23, 2, 'update', 'Equipment', 40, '{\"is_available\":false}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:34:16', '2026-01-27 11:34:16'),
(24, 2, 'update', 'Equipment', 40, '{\"is_available\":true}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:34:21', '2026-01-27 11:34:21'),
(25, 2, 'update', 'Facility', 11, '{\"name\":\"Buena Park\",\"city_id\":\"1\",\"is_available\":\"1\",\"capacity\":\"200\",\"description\":\"Open-air community park suitable for outdoor gatherings and events. Perfect for weekend private events and community celebrations.\",\"address\":\"South Caloocan City\",\"per_person_rate\":\"135.00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:57:39', '2026-01-27 13:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `backup_downloads`
--

CREATE TABLE `backup_downloads` (
  `id` bigint UNSIGNED NOT NULL,
  `backup_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `otp_expires_at` timestamp NOT NULL,
  `downloaded` tinyint(1) NOT NULL DEFAULT '0',
  `downloaded_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `backup_downloads`
--

INSERT INTO `backup_downloads` (`id`, `backup_file`, `otp_hash`, `requested_by`, `otp_expires_at`, `downloaded`, `downloaded_at`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, '2026-01-17-11-11-59.zip', '$2y$12$GvlTPhBUhTz.SrQezyxiFu94M7N.UT96e/mD27vjhK/EsuxkVFNKG', 2, '2026-01-17 04:08:17', 0, NULL, '127.0.0.1', '2026-01-17 03:53:17', '2026-01-17 03:53:17'),
(2, '2026-01-17-10-31-38.zip', '$2y$12$pq.YeL7pE4i2or0JIoihQOuKccHPnO/5cZyCnbOkGcC4eBphMhsbe', 2, '2026-01-27 05:31:10', 0, NULL, '127.0.0.1', '2026-01-27 05:16:11', '2026-01-27 05:16:11'),
(3, '2026-01-17-10-31-38.zip', '$2y$12$GlmzlpEraNZ/GIvNr0CBAOm2qyNXMch6Wvv4whVrV2iq6H3BssmNu', 2, '2026-01-27 05:31:21', 0, NULL, '127.0.0.1', '2026-01-27 05:16:22', '2026-01-27 05:16:22'),
(4, '2026-01-17-10-31-38.zip', '$2y$12$hve4k2JwvFvkh9ozvP9Qsu0zqsxyDJgM2DJUyuOFdKWoEg3gFYLBu', 2, '2026-01-27 05:36:02', 1, '2026-01-27 05:21:34', '127.0.0.1', '2026-01-27 05:21:03', '2026-01-27 05:21:34');

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `id` bigint UNSIGNED NOT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `district_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternate_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`id`, `city_id`, `district_id`, `name`, `alternate_name`, `zip_code`) VALUES
(1, 2, 7, 'Alicia', 'Bago Bantay', '1105'),
(2, 2, 7, 'Bagong Pag-asa', 'North-EDSA, Diliman', '1105'),
(3, 2, 7, 'Bahay Toro', 'Project 8, Pugadlawin', '1106'),
(4, 2, 7, 'Balingasa', 'Balintawak, Cloverleaf', '1115'),
(5, 2, 7, 'Bungad', 'Project 7', '1105'),
(6, 2, 7, 'Damayan', 'San Francisco del Monte', '1105'),
(7, 2, 7, 'Del Monte', 'San Francisco del Monte', '1105'),
(8, 2, 7, 'Katipunan', 'Muñoz', '1102'),
(9, 2, 7, 'Lourdes', 'Santa Mesa Heights', '1114'),
(10, 2, 8, 'Bagong Silangan', 'Payatas', '1119'),
(11, 2, 8, 'Batasan Hills', 'Constitution Hills', '1126'),
(12, 2, 8, 'Commonwealth', 'Manggahan, Litex', '1121'),
(13, 2, 8, 'Holy Spirit', 'Don Antonio, Luzon', '1127'),
(14, 2, 8, 'Payatas', 'Litex', '1119'),
(15, 2, 9, 'Amihan', 'Project 3', '1102'),
(16, 2, 9, 'Bagumbayan', 'Eastwood, Libis', '1110'),
(17, 2, 9, 'Bagumbuhay', 'Project 4', '1109'),
(18, 2, 9, 'Bayanihan', 'Project 4', '1109'),
(19, 2, 9, 'Blue Ridge A', 'Project 4', '1109'),
(20, 2, 9, 'Libis', 'Camp Atienza, Eastwood', '1110'),
(21, 2, 9, 'Loyola Heights', 'Katipunan', '1108'),
(22, 2, 9, 'Mangga', 'Cubao, Anonas', '1109'),
(23, 2, 9, 'Socorro', 'Cubao, Araneta City', '1109'),
(24, 2, 9, 'White Plains', 'Camp Aguinaldo', '1110'),
(25, 2, 10, 'Bagong Lipunan ng Crame', 'Camp Crame, PNP', '1111'),
(26, 2, 10, 'Botocan', 'Diliman (northern half)', '1101'),
(27, 2, 10, 'Central', 'Diliman, QC Hall', '1100'),
(28, 2, 10, 'Damayang Lagi', 'New Manila', '1112'),
(29, 2, 10, 'Kamuning', 'Project 1, Scout Area', '1103'),
(30, 2, 10, 'Krus na Ligas', 'Diliman', '1101'),
(31, 2, 10, 'Malaya', 'Diliman', '1101'),
(32, 2, 10, 'U.P. Campus', 'Diliman', '1101'),
(33, 2, 10, 'U.P. Village', 'Diliman', '1101'),
(34, 2, 10, 'Valencia', 'New Manila, Gilmore', '1112'),
(35, 2, 11, 'Bagbag', 'Novaliches District, Sauyo', '1116'),
(36, 2, 11, 'Capri', 'Novaliches District', '1117'),
(37, 2, 11, 'Fairview', 'Novaliches District', '1118'),
(38, 2, 11, 'Gulod', 'Novaliches District', '1117'),
(39, 2, 11, 'Greater Lagro', 'Novaliches District', '1118'),
(40, 2, 11, 'Kaligayahan', 'Novaliches District', '1124'),
(41, 2, 11, 'Nagkaisang Nayon', 'Novaliches District', '1125'),
(42, 2, 11, 'North Fairview', 'Novaliches District', '1121'),
(43, 2, 11, 'Novaliches Proper', 'Novaliches Bayan', '1123'),
(44, 2, 11, 'Pasong Putik Proper', 'Novaliches District', '1118'),
(45, 2, 12, 'Apolonio Samson', 'Balintawak, Kaingin', '1106'),
(46, 2, 12, 'Baesa', 'Project 8, Novaliches District', '1128'),
(47, 2, 12, 'Balon Bato', 'Balintawak', '1106'),
(48, 2, 12, 'Culiat', 'Tandang Sora', '1128'),
(49, 2, 12, 'New Era', 'Iglesia ni Cristo/Central', '1107'),
(50, 2, 12, 'Pasong Tamo', 'Pingkian, Philand', '1107'),
(51, 2, 12, 'Sangandaan', 'Project 8', '1105'),
(52, 2, 12, 'Sauyo', 'Novaliches District', '1116'),
(53, 2, 12, 'Talipapa', 'Novaliches District', '1116'),
(54, 2, 12, 'Tandang Sora', 'Banlat', '1116'),
(55, 2, 12, 'Unang Sigaw', 'Balintawak, Cloverleaf', '1106');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `province_id` bigint UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('city','municipality') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'municipality',
  `has_districts` tinyint(1) NOT NULL DEFAULT '0',
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `psgc_code` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `province_id`, `code`, `name`, `type`, `has_districts`, `zip_code`, `psgc_code`, `created_at`, `updated_at`) VALUES
(1, 1, 'MNL', 'Manila', 'city', 1, '1000', NULL, NULL, NULL),
(2, 1, 'QC', 'Quezon City', 'city', 1, '1100', NULL, NULL, NULL),
(3, 1, 'CAL', 'Caloocan', 'city', 0, '1400', NULL, NULL, NULL),
(4, 1, 'LAS', 'Las Piñas', 'city', 0, '1740', NULL, NULL, NULL),
(5, 1, 'MAK', 'Makati', 'city', 0, '1200', NULL, NULL, NULL),
(6, 1, 'MAL', 'Malabon', 'city', 0, '1470', NULL, NULL, NULL),
(7, 1, 'MAN', 'Mandaluyong', 'city', 0, '1550', NULL, NULL, NULL),
(8, 1, 'MAR', 'Marikina', 'city', 0, '1800', NULL, NULL, NULL),
(9, 1, 'MUN', 'Muntinlupa', 'city', 0, '1770', NULL, NULL, NULL),
(10, 1, 'NAV', 'Navotas', 'city', 0, '1485', NULL, NULL, NULL),
(11, 1, 'PAR', 'Parañaque', 'city', 0, '1700', NULL, NULL, NULL),
(12, 1, 'PAS', 'Pasay', 'city', 0, '1300', NULL, NULL, NULL),
(13, 1, 'PAC', 'Pasig', 'city', 0, '1600', NULL, NULL, NULL),
(14, 1, 'PAT', 'Pateros', 'municipality', 0, '1620', NULL, NULL, NULL),
(15, 1, 'SJU', 'San Juan', 'city', 0, '1500', NULL, NULL, NULL),
(16, 1, 'TAC', 'Taguig', 'city', 0, '1630', NULL, NULL, NULL),
(17, 1, 'VAL', 'Valenzuela', 'city', 0, '1440', NULL, NULL, NULL),
(18, 2, 'BANG', 'Bangued', 'municipality', 0, NULL, NULL, NULL, NULL),
(19, 3, 'KABUGAO', 'Kabugao', 'municipality', 0, NULL, NULL, NULL, NULL),
(20, 4, 'BAGUIO', 'Baguio', 'city', 0, NULL, NULL, NULL, NULL),
(21, 4, 'LABAG', 'La Trinidad', 'municipality', 0, NULL, NULL, NULL, NULL),
(22, 5, 'LAGAWE', 'Lagawe', 'municipality', 0, NULL, NULL, NULL, NULL),
(23, 6, 'TABUK', 'Tabuk', 'city', 0, NULL, NULL, NULL, NULL),
(24, 7, 'BONTOC', 'Bontoc', 'municipality', 0, NULL, NULL, NULL, NULL),
(25, 8, 'LAOAG', 'Laoag', 'city', 0, NULL, NULL, NULL, NULL),
(26, 8, 'BATAC', 'Batac', 'city', 0, NULL, NULL, NULL, NULL),
(27, 9, 'VIGAN', 'Vigan', 'city', 0, NULL, NULL, NULL, NULL),
(28, 9, 'CANDON', 'Candon', 'city', 0, NULL, NULL, NULL, NULL),
(29, 10, 'SANFERNANDO_LU', 'San Fernando', 'city', 0, NULL, NULL, NULL, NULL),
(30, 11, 'LINGAYEN', 'Lingayen', 'municipality', 0, NULL, NULL, NULL, NULL),
(31, 11, 'DAGUPAN', 'Dagupan', 'city', 0, NULL, NULL, NULL, NULL),
(32, 11, 'ALAMINOS', 'Alaminos', 'city', 0, NULL, NULL, NULL, NULL),
(33, 11, 'URDANETA', 'Urdaneta', 'city', 0, NULL, NULL, NULL, NULL),
(34, 12, 'BASCO', 'Basco', 'municipality', 0, NULL, NULL, NULL, NULL),
(35, 13, 'TUGUEGARAO', 'Tuguegarao', 'city', 0, NULL, NULL, NULL, NULL),
(36, 14, 'ILAGAN', 'Ilagan', 'city', 0, NULL, NULL, NULL, NULL),
(37, 14, 'SANTIAGO', 'Santiago', 'city', 0, NULL, NULL, NULL, NULL),
(38, 14, 'CAUAYAN', 'Cauayan', 'city', 0, NULL, NULL, NULL, NULL),
(39, 15, 'BAYOMBONG', 'Bayombong', 'municipality', 0, NULL, NULL, NULL, NULL),
(40, 16, 'CABARROGUIS', 'Cabarroguis', 'municipality', 0, NULL, NULL, NULL, NULL),
(41, 17, 'BALER', 'Baler', 'municipality', 0, NULL, NULL, NULL, NULL),
(42, 18, 'BALANGA', 'Balanga', 'city', 0, NULL, NULL, NULL, NULL),
(43, 19, 'MALOLOS', 'Malolos', 'city', 0, NULL, NULL, NULL, NULL),
(44, 19, 'MEYCAUAYAN', 'Meycauayan', 'city', 0, NULL, NULL, NULL, NULL),
(45, 19, 'SJDM', 'San Jose del Monte', 'city', 0, NULL, NULL, NULL, NULL),
(46, 20, 'PALAYAN', 'Palayan', 'city', 0, NULL, NULL, NULL, NULL),
(47, 20, 'CABANATUAN', 'Cabanatuan', 'city', 0, NULL, NULL, NULL, NULL),
(48, 20, 'GAPAN', 'Gapan', 'city', 0, NULL, NULL, NULL, NULL),
(49, 21, 'SANFERNANDO_PAM', 'San Fernando', 'city', 0, NULL, NULL, NULL, NULL),
(50, 21, 'ANGELES', 'Angeles', 'city', 0, NULL, NULL, NULL, NULL),
(51, 21, 'MABALACAT', 'Mabalacat', 'city', 0, NULL, NULL, NULL, NULL),
(52, 22, 'TARLAC_CITY', 'Tarlac City', 'city', 0, NULL, NULL, NULL, NULL),
(53, 23, 'IBA', 'Iba', 'municipality', 0, NULL, NULL, NULL, NULL),
(54, 23, 'OLONGAPO', 'Olongapo', 'city', 0, NULL, NULL, NULL, NULL),
(55, 24, 'BATANGAS_CITY', 'Batangas City', 'city', 0, NULL, NULL, NULL, NULL),
(56, 24, 'LIPA', 'Lipa', 'city', 0, NULL, NULL, NULL, NULL),
(57, 24, 'TANAUAN', 'Tanauan', 'city', 0, NULL, NULL, NULL, NULL),
(58, 25, 'TRECE', 'Trece Martires', 'city', 0, NULL, NULL, NULL, NULL),
(59, 25, 'CAVITE_CITY', 'Cavite City', 'city', 0, NULL, NULL, NULL, NULL),
(60, 25, 'DASMARINAS', 'Dasmariñas', 'city', 0, NULL, NULL, NULL, NULL),
(61, 25, 'BACOOR', 'Bacoor', 'city', 0, NULL, NULL, NULL, NULL),
(62, 25, 'IMUS', 'Imus', 'city', 0, NULL, NULL, NULL, NULL),
(63, 25, 'TAGAYTAY', 'Tagaytay', 'city', 0, NULL, NULL, NULL, NULL),
(64, 26, 'SANTACRUZ', 'Santa Cruz', 'municipality', 0, NULL, NULL, NULL, NULL),
(65, 26, 'CALAMBA', 'Calamba', 'city', 0, NULL, NULL, NULL, NULL),
(66, 26, 'BINAN', 'Biñan', 'city', 0, NULL, NULL, NULL, NULL),
(67, 26, 'SANPEDRO', 'San Pedro', 'city', 0, NULL, NULL, NULL, NULL),
(68, 26, 'CABUYAO', 'Cabuyao', 'city', 0, NULL, NULL, NULL, NULL),
(69, 27, 'LUCENA', 'Lucena', 'city', 0, NULL, NULL, NULL, NULL),
(70, 27, 'TAYABAS', 'Tayabas', 'city', 0, NULL, NULL, NULL, NULL),
(71, 28, 'ANTIPOLO', 'Antipolo', 'city', 0, NULL, NULL, NULL, NULL),
(72, 28, 'CAINTA', 'Cainta', 'municipality', 0, NULL, NULL, NULL, NULL),
(73, 28, 'TAYTAY', 'Taytay', 'municipality', 0, NULL, NULL, NULL, NULL),
(74, 29, 'BOAC', 'Boac', 'municipality', 0, NULL, NULL, NULL, NULL),
(75, 30, 'MAMBURAO', 'Mamburao', 'municipality', 0, NULL, NULL, NULL, NULL),
(76, 31, 'CALAPAN', 'Calapan', 'city', 0, NULL, NULL, NULL, NULL),
(77, 32, 'PUERTO', 'Puerto Princesa', 'city', 0, NULL, NULL, NULL, NULL),
(78, 33, 'ROMBLON', 'Romblon', 'municipality', 0, NULL, NULL, NULL, NULL),
(79, 34, 'LEGAZPI', 'Legazpi', 'city', 0, NULL, NULL, NULL, NULL),
(80, 34, 'LIGAO', 'Ligao', 'city', 0, NULL, NULL, NULL, NULL),
(81, 34, 'TABACO', 'Tabaco', 'city', 0, NULL, NULL, NULL, NULL),
(82, 35, 'DAET', 'Daet', 'municipality', 0, NULL, NULL, NULL, NULL),
(83, 36, 'PILI', 'Pili', 'municipality', 0, NULL, NULL, NULL, NULL),
(84, 36, 'NAGA', 'Naga', 'city', 0, NULL, NULL, NULL, NULL),
(85, 36, 'IRIGA', 'Iriga', 'city', 0, NULL, NULL, NULL, NULL),
(86, 37, 'VIRAC', 'Virac', 'municipality', 0, NULL, NULL, NULL, NULL),
(87, 38, 'MASBATE_CITY', 'Masbate City', 'city', 0, NULL, NULL, NULL, NULL),
(88, 39, 'SORSOGON_CITY', 'Sorsogon City', 'city', 0, NULL, NULL, NULL, NULL),
(89, 40, 'KALIBO', 'Kalibo', 'municipality', 0, NULL, NULL, NULL, NULL),
(90, 41, 'SANJOS', 'San Jose de Buenavista', 'municipality', 0, NULL, NULL, NULL, NULL),
(91, 42, 'ROXAS', 'Roxas City', 'city', 0, NULL, NULL, NULL, NULL),
(92, 43, 'JORDAN', 'Jordan', 'municipality', 0, NULL, NULL, NULL, NULL),
(93, 44, 'ILOILO_CITY', 'Iloilo City', 'city', 0, NULL, NULL, NULL, NULL),
(94, 44, 'PASSI', 'Passi', 'city', 0, NULL, NULL, NULL, NULL),
(95, 45, 'BACOLOD', 'Bacolod', 'city', 0, NULL, NULL, NULL, NULL),
(96, 45, 'SILAY', 'Silay', 'city', 0, NULL, NULL, NULL, NULL),
(97, 45, 'TALISAY', 'Talisay', 'city', 0, NULL, NULL, NULL, NULL),
(98, 46, 'TAGBILARAN', 'Tagbilaran', 'city', 0, NULL, NULL, NULL, NULL),
(99, 47, 'CEBU_CITY', 'Cebu City', 'city', 0, NULL, NULL, NULL, NULL),
(100, 47, 'LAPULAPU', 'Lapu-Lapu', 'city', 0, NULL, NULL, NULL, NULL),
(101, 47, 'MANDAUE', 'Mandaue', 'city', 0, NULL, NULL, NULL, NULL),
(102, 47, 'TOLEDO', 'Toledo', 'city', 0, NULL, NULL, NULL, NULL),
(103, 48, 'DUMAGUETE', 'Dumaguete', 'city', 0, NULL, NULL, NULL, NULL),
(104, 48, 'BAYAWAN', 'Bayawan', 'city', 0, NULL, NULL, NULL, NULL),
(105, 49, 'SIQUIJOR', 'Siquijor', 'municipality', 0, NULL, NULL, NULL, NULL),
(106, 50, 'NAVAL', 'Naval', 'municipality', 0, NULL, NULL, NULL, NULL),
(107, 51, 'BORONGAN', 'Borongan', 'city', 0, NULL, NULL, NULL, NULL),
(108, 52, 'TACLOBAN', 'Tacloban', 'city', 0, NULL, NULL, NULL, NULL),
(109, 52, 'ORMOC', 'Ormoc', 'city', 0, NULL, NULL, NULL, NULL),
(110, 53, 'CATARMAN', 'Catarman', 'municipality', 0, NULL, NULL, NULL, NULL),
(111, 54, 'CALBAYOG', 'Calbayog', 'city', 0, NULL, NULL, NULL, NULL),
(112, 54, 'CATBALOGAN', 'Catbalogan', 'city', 0, NULL, NULL, NULL, NULL),
(113, 55, 'MAASIN', 'Maasin', 'city', 0, NULL, NULL, NULL, NULL),
(114, 56, 'DIPOLOG', 'Dipolog', 'city', 0, NULL, NULL, NULL, NULL),
(115, 56, 'DAPITAN', 'Dapitan', 'city', 0, NULL, NULL, NULL, NULL),
(116, 57, 'PAGADIAN', 'Pagadian', 'city', 0, NULL, NULL, NULL, NULL),
(117, 57, 'ZAMBOANGA', 'Zamboanga City', 'city', 0, NULL, NULL, NULL, NULL),
(118, 58, 'IPIL', 'Ipil', 'municipality', 0, NULL, NULL, NULL, NULL),
(119, 59, 'MALAYBALAY', 'Malaybalay', 'city', 0, NULL, NULL, NULL, NULL),
(120, 59, 'VALENCIA', 'Valencia', 'city', 0, NULL, NULL, NULL, NULL),
(121, 60, 'MAMBAJAO', 'Mambajao', 'municipality', 0, NULL, NULL, NULL, NULL),
(122, 61, 'TUBOD', 'Tubod', 'municipality', 0, NULL, NULL, NULL, NULL),
(123, 61, 'ILIGAN', 'Iligan', 'city', 0, NULL, NULL, NULL, NULL),
(124, 62, 'OROQUIETA', 'Oroquieta', 'city', 0, NULL, NULL, NULL, NULL),
(125, 62, 'OZAMIZ', 'Ozamiz', 'city', 0, NULL, NULL, NULL, NULL),
(126, 62, 'TANGUB', 'Tangub', 'city', 0, NULL, NULL, NULL, NULL),
(127, 63, 'CAGAYAN', 'Cagayan de Oro', 'city', 0, NULL, NULL, NULL, NULL),
(128, 63, 'GINGOOG', 'Gingoog', 'city', 0, NULL, NULL, NULL, NULL),
(129, 64, 'NABUNTURAN', 'Nabunturan', 'municipality', 0, NULL, NULL, NULL, NULL),
(130, 65, 'TAGUM', 'Tagum', 'city', 0, NULL, NULL, NULL, NULL),
(131, 66, 'DIGOS', 'Digos', 'city', 0, NULL, NULL, NULL, NULL),
(132, 66, 'DAVAO', 'Davao City', 'city', 0, NULL, NULL, NULL, NULL),
(133, 67, 'MATI', 'Mati', 'city', 0, NULL, NULL, NULL, NULL),
(134, 68, 'MALITA', 'Malita', 'municipality', 0, NULL, NULL, NULL, NULL),
(135, 69, 'KIDAPAWAN', 'Kidapawan', 'city', 0, NULL, NULL, NULL, NULL),
(136, 70, 'ALABEL', 'Alabel', 'municipality', 0, NULL, NULL, NULL, NULL),
(137, 70, 'GENERAL', 'General Santos', 'city', 0, NULL, NULL, NULL, NULL),
(138, 71, 'KORONADAL', 'Koronadal', 'city', 0, NULL, NULL, NULL, NULL),
(139, 72, 'ISULAN', 'Isulan', 'municipality', 0, NULL, NULL, NULL, NULL),
(140, 72, 'TACURONG', 'Tacurong', 'city', 0, NULL, NULL, NULL, NULL),
(141, 73, 'CABADBARAN', 'Cabadbaran', 'city', 0, NULL, NULL, NULL, NULL),
(142, 73, 'BUTUAN', 'Butuan', 'city', 0, NULL, NULL, NULL, NULL),
(143, 74, 'BAYUGAN', 'Bayugan', 'city', 0, NULL, NULL, NULL, NULL),
(144, 75, 'DAPA', 'San Jose', 'municipality', 0, NULL, NULL, NULL, NULL),
(145, 76, 'SURIGAO', 'Surigao City', 'city', 0, NULL, NULL, NULL, NULL),
(146, 77, 'TANDAG', 'Tandag', 'city', 0, NULL, NULL, NULL, NULL),
(147, 77, 'BISLIG', 'Bislig', 'city', 0, NULL, NULL, NULL, NULL),
(148, 78, 'ISABELA', 'Isabela City', 'city', 0, NULL, NULL, NULL, NULL),
(149, 79, 'MARAWI', 'Marawi', 'city', 0, NULL, NULL, NULL, NULL),
(150, 80, 'COTABATO', 'Cotabato City', 'city', 0, NULL, NULL, NULL, NULL),
(151, 81, 'JOLO', 'Jolo', 'municipality', 0, NULL, NULL, NULL, NULL),
(152, 82, 'BONGAO', 'Bongao', 'municipality', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `citizen_payment_methods`
--

CREATE TABLE `citizen_payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `payment_type` enum('gcash','paymaya','bank_transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `citizen_road_requests`
--

CREATE TABLE `citizen_road_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `external_request_id` bigint UNSIGNED DEFAULT NULL,
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `landmark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('general','booking_issue','payment_issue','technical_issue','complaint','suggestion','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachments` json DEFAULT NULL,
  `status` enum('new','open','pending','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `staff_notes` text COLLATE utf8mb4_unicode_ci,
  `resolution` text COLLATE utf8mb4_unicode_ci,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint UNSIGNED NOT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `district_number` int DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'city'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `city_id`, `district_number`, `name`, `type`) VALUES
(1, 1, 1, 'District 1', 'congressional'),
(2, 1, 2, 'District 2', 'congressional'),
(3, 1, 3, 'District 3', 'congressional'),
(4, 1, 4, 'District 4', 'congressional'),
(5, 1, 5, 'District 5', 'congressional'),
(6, 1, 6, 'District 6', 'congressional'),
(7, 2, 1, 'District 1', 'congressional'),
(8, 2, 2, 'District 2', 'congressional'),
(9, 2, 3, 'District 3', 'congressional'),
(10, 2, 4, 'District 4', 'congressional'),
(11, 2, 5, 'District 5', 'congressional'),
(12, 2, 6, 'District 6', 'congressional'),
(13, 3, 1, 'District 1 (North Caloocan)', 'congressional'),
(14, 3, 2, 'District 2 (South Caloocan)', 'congressional'),
(15, 4, 1, 'Lone District', 'congressional'),
(16, 5, 1, 'District 1', 'congressional'),
(17, 5, 2, 'District 2', 'congressional'),
(18, 6, 1, 'Lone District', 'congressional'),
(19, 7, 1, 'Lone District', 'congressional'),
(20, 8, 1, 'District 1', 'congressional'),
(21, 8, 2, 'District 2', 'congressional'),
(22, 9, 1, 'Lone District', 'congressional'),
(23, 10, 1, 'Lone District', 'congressional'),
(24, 11, 1, 'District 1', 'congressional'),
(25, 11, 2, 'District 2', 'congressional'),
(26, 12, 1, 'Lone District', 'congressional'),
(27, 13, 1, 'District 1', 'congressional'),
(28, 13, 2, 'District 2', 'congressional'),
(29, 14, 1, 'Lone District', 'municipal'),
(30, 15, 1, 'Lone District', 'congressional'),
(31, 16, 1, 'District 1', 'congressional'),
(32, 16, 2, 'District 2', 'congressional'),
(33, 17, 1, 'District 1', 'congressional'),
(34, 17, 2, 'District 2', 'congressional');

-- --------------------------------------------------------

--
-- Table structure for table `energy_facility_requests`
--

CREATE TABLE `energy_facility_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `event_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci,
  `organizer_office` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point_person` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `alternative_date` date DEFAULT NULL,
  `alternative_start_time` time DEFAULT NULL,
  `alternative_end_time` time DEFAULT NULL,
  `audience_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'employees, public, students, mixed',
  `session_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'orientation, training, workshop, briefing, meeting',
  `facility_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'small, medium, large',
  `needs_projector` tinyint(1) NOT NULL DEFAULT '0',
  `laptop_option` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no' COMMENT 'yes, no, bringing_own',
  `needs_sound_system` tinyint(1) NOT NULL DEFAULT '0',
  `needs_microphone` tinyint(1) NOT NULL DEFAULT '0',
  `microphone_count` int NOT NULL DEFAULT '0',
  `microphone_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'handheld, lapel, both',
  `needs_wifi` tinyint(1) NOT NULL DEFAULT '0',
  `needs_extension_cords` tinyint(1) NOT NULL DEFAULT '0',
  `additional_power_needs` text COLLATE utf8mb4_unicode_ci,
  `other_equipment` text COLLATE utf8mb4_unicode_ci,
  `needs_handouts` tinyint(1) NOT NULL DEFAULT '0',
  `handouts_format` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'softcopy, hardcopy, both',
  `needs_certificates` tinyint(1) NOT NULL DEFAULT '0',
  `certificates_provider` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'us, them, both',
  `needs_refreshments` tinyint(1) NOT NULL DEFAULT '0',
  `dietary_notes` text COLLATE utf8mb4_unicode_ci,
  `delivery_instructions` text COLLATE utf8mb4_unicode_ci,
  `special_requests` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending, approved, rejected, completed',
  `admin_feedback` text COLLATE utf8mb4_unicode_ci,
  `response_data` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON: assigned facility, equipment, schedule, budget, etc.',
  `booking_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Link to bookings table if approved',
  `user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Energy system user ID',
  `seminar_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Energy system seminar ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `category` enum('city_event','facility_news','promotion','announcement','holiday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_attendees` int DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `view_count` int NOT NULL DEFAULT '0',
  `tags` json DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint UNSIGNED NOT NULL,
  `location_id` bigint UNSIGNED NOT NULL,
  `facility_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facility_type` enum('gymnasium','convention_center','function_hall','sports_complex','auditorium','meeting_room','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `capacity` int NOT NULL COMMENT 'Maximum number of people',
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `per_person_rate` decimal(10,2) DEFAULT NULL,
  `per_person_extension_rate` decimal(10,2) DEFAULT NULL,
  `base_hours` int NOT NULL DEFAULT '3',
  `deposit_amount` decimal(10,2) DEFAULT NULL,
  `amenities` json DEFAULT NULL COMMENT 'List of available amenities',
  `rules` text COLLATE utf8mb4_unicode_ci,
  `terms_and_conditions` text COLLATE utf8mb4_unicode_ci,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `advance_booking_days` int NOT NULL DEFAULT '180' COMMENT 'How many days in advance can book',
  `min_booking_hours` int NOT NULL DEFAULT '2' COMMENT 'Minimum booking duration',
  `max_booking_hours` int NOT NULL DEFAULT '12' COMMENT 'Maximum booking duration',
  `operating_hours` json DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `full_address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_count` int NOT NULL DEFAULT '0',
  `rating` decimal(3,2) DEFAULT NULL,
  `google_maps_url` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','under_construction','under_maintenance','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `location_id`, `facility_name`, `facility_type`, `description`, `capacity`, `hourly_rate`, `per_person_rate`, `per_person_extension_rate`, `base_hours`, `deposit_amount`, `amenities`, `rules`, `terms_and_conditions`, `is_available`, `advance_booking_days`, `min_booking_hours`, `max_booking_hours`, `operating_hours`, `address`, `latitude`, `longitude`, `full_address`, `city`, `view_count`, `rating`, `google_maps_url`, `status`, `display_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Buena Park Sports Complex', 'sports_complex', 'A multi-purpose sports complex ideal for sports events, tournaments, and large gatherings. Features outdoor courts and ample space for various activities.', 500, NULL, 150.00, 30.00, 3, 1500.00, '[\"basketball_court\", \"parking\", \"restrooms\", \"lighting\", \"sound_system\"]', 'No smoking inside the premises. Maintain cleanliness. No alcohol allowed. Sports equipment must be handled with care.', 'Booking requires 48-hour advance notice. Cancellations must be made at least 48 hours before the event. Deposit is non-refundable but can be used for rescheduling.', 1, 90, 3, 8, '\"{\\\"monday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"tuesday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"wednesday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"thursday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"friday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"saturday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"sunday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"}}\"', 'Buena Park, Caloocan City, Metro Manila', 14.75660000, 121.04500000, 'Barangay 177, Camarin, Caloocan City, North Manila', 'Caloocan City', 0, NULL, NULL, 'active', 1, '2025-12-14 19:29:25', '2025-12-14 19:29:25', NULL),
(2, 1, 'Bulwagan Function Hall', 'function_hall', 'An elegant function hall perfect for weddings, birthdays, corporate events, and seminars. Fully air-conditioned with modern amenities.', 300, NULL, 120.00, 30.00, 3, 1000.00, '[\"air_conditioning\", \"sound_system\", \"projector\", \"wifi\", \"parking\", \"kitchen\", \"restrooms\", \"tables_and_chairs\"]', 'No confetti or glitter decorations. Smoking is prohibited indoors. Must coordinate with catering services 48 hours in advance.', 'Maximum booking is 6 hours. Additional hours charged at regular rate. Setup and cleanup time is included in booking hours.', 1, 90, 3, 6, '\"{\\\"monday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"tuesday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"wednesday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"thursday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"friday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"saturday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"sunday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"22:00\\\"}}\"', 'City General Services Department, Caloocan City', 14.64880000, 120.99060000, 'New Caloocan City Hall, 8th Avenue, Grace Park, Barangay 130, Caloocan City', 'Caloocan City', 0, NULL, NULL, 'active', 2, '2025-12-14 19:29:25', '2025-12-14 19:29:25', NULL),
(3, 1, 'Pacquiao Court', 'sports_complex', 'A covered basketball court named after the boxing legend. Perfect for basketball tournaments, community sports events, and recreational activities.', 200, NULL, 100.00, 30.00, 3, 600.00, '[\"basketball_court\", \"lighting\", \"covered\", \"restrooms\", \"parking\"]', 'Proper sports attire required. No street shoes on the court. Clean up after use. Maximum 2 teams at a time.', 'Booking slots are in 2-hour increments. Courts must be vacated 15 minutes after booking ends.', 1, 90, 2, 5, '\"{\\\"monday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"tuesday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"wednesday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"thursday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"friday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"saturday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"sunday\\\":{\\\"open\\\":\\\"06:00\\\",\\\"close\\\":\\\"22:00\\\"}}\"', 'Caloocan City, Metro Manila', 14.75780000, 121.03660000, 'Bagumbong Road, Corner Malapitan Road, Barangay 171, Caloocan City', 'Caloocan City', 0, NULL, NULL, 'active', 3, '2025-12-14 19:29:25', '2025-12-14 19:29:25', NULL),
(4, 1, 'Katipunan Hall', 'auditorium', 'A formal auditorium primarily used for city events, but available for community organizations and large-scale seminars.', 400, NULL, 130.00, 30.00, 3, 1200.00, '[\"air_conditioning\", \"stage\", \"sound_system\", \"projector\", \"lighting\", \"parking\", \"restrooms\"]', 'Government and LGU events have priority. Formal attire recommended. No food or drinks inside the main hall.', 'Bookings require advance approval from the City Mayor\'s office. Non-profit organizations may receive discounted rates.', 1, 90, 3, 8, '\"{\\\"monday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"tuesday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"wednesday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"thursday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"friday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"22:00\\\"},\\\"saturday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"20:00\\\"},\\\"sunday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"18:00\\\"}}\"', 'Caloocan City Hall Complex, Caloocan City', 14.64880000, 120.99060000, 'New Caloocan City Hall, 8th Avenue, Grace Park, Barangay 130, Caloocan City', 'Caloocan City', 0, NULL, NULL, 'active', 4, '2025-12-14 19:29:25', '2025-12-14 19:29:25', NULL),
(5, 2, 'QC M.I.C.E. Convention & Exhibit Hall', 'convention_center', 'A state-of-the-art convention and exhibit hall suitable for large-scale conferences, trade shows, exhibitions, and city-sponsored programs. Features spacious modern interiors with flexible layout options.', 1000, NULL, 150.00, 30.00, 3, NULL, '[\"air_conditioning\", \"wifi\", \"projector\", \"sound_system\", \"stage\", \"exhibition_booths\", \"parking\", \"restrooms\", \"accessibility_features\"]', 'LGU and government-sponsored events have priority. Exhibition materials must be fire-rated. No smoking anywhere in the building.', 'Advance booking required with detailed event proposal. Quezon City LGU events receive priority scheduling.', 1, 180, 4, 12, '\"{\\\"monday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"tuesday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"wednesday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"thursday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"friday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"saturday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"20:00\\\"},\\\"sunday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"18:00\\\"}}\"', 'Elliptical Road, Quezon City, Metro Manila', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Quezon City Hall Compound, Elliptical Road, Diliman, Quezon City', 'Quezon City', 0, NULL, NULL, 'active', 1, '2025-12-14 19:29:25', '2025-12-14 19:29:25', NULL),
(6, 2, 'M.I.C.E. Breakout Room 1', 'meeting_room', 'A modern breakout room perfect for small to medium-sized seminars, training sessions, and workshops. Fully equipped with presentation facilities.', 50, NULL, 100.00, 30.00, 3, NULL, '[\"air_conditioning\", \"wifi\", \"projector\", \"whiteboard\", \"sound_system\", \"tables_and_chairs\", \"restrooms\"]', 'Maintain room cleanliness. Equipment must be returned to original positions. No food with strong odors.', 'Breakout rooms are in high demand. Booking confirmation sent within 48 hours of request. Government seminars receive priority.', 1, 180, 2, 8, '\"{\\\"monday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"tuesday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"wednesday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"thursday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"friday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"saturday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"18:00\\\"},\\\"sunday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"18:00\\\"}}\"', 'QC M.I.C.E. Center, 2nd Floor, Quezon City', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Quezon City Hall Compound, Elliptical Road, Diliman, Quezon City', 'Quezon City', 0, NULL, NULL, 'active', 2, '2025-12-14 19:29:25', '2025-12-14 19:29:25', NULL),
(7, 2, 'M.I.C.E. Breakout Room 2', 'meeting_room', 'Another versatile breakout room ideal for corporate meetings, team building activities, and educational seminars with smaller groups.', 40, NULL, 100.00, 30.00, 3, NULL, '[\"air_conditioning\", \"wifi\", \"projector\", \"whiteboard\", \"sound_system\", \"tables_and_chairs\", \"restrooms\"]', 'Respect scheduled time slots. Report any equipment damage immediately. Keep noise levels reasonable.', 'Same terms as Breakout Room 1. Can be combined with Breakout Room 1 for larger events with prior approval.', 1, 180, 2, 8, '\"{\\\"monday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"tuesday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"wednesday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"thursday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"friday\\\":{\\\"open\\\":\\\"07:00\\\",\\\"close\\\":\\\"21:00\\\"},\\\"saturday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"18:00\\\"},\\\"sunday\\\":{\\\"open\\\":\\\"08:00\\\",\\\"close\\\":\\\"18:00\\\"}}\"', 'QC M.I.C.E. Center, 2nd Floor, Quezon City', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Quezon City Hall Compound, Elliptical Road, Diliman, Quezon City', 'Quezon City', 0, NULL, NULL, 'active', 3, '2025-12-14 19:29:25', '2025-12-14 19:29:25', NULL),
(8, 2, 'QC M.I.C.E. Auditorium', 'auditorium', 'A large-capacity auditorium currently under construction. Once completed, it will serve as a premier venue for major conferences, concerts, and city-wide events.', 800, NULL, 200.00, 30.00, 3, NULL, '[\"air_conditioning\", \"stage\", \"professional_sound_system\", \"lighting\", \"projection\", \"backstage_area\", \"vip_lounge\", \"parking\", \"accessibility_features\"]', 'Facility not yet available. Rules and guidelines will be announced upon completion.', 'Expected to open in 2026. Pre-booking not yet available. Updates will be posted on the QC LGU website.', 0, 180, 4, 12, NULL, 'QC M.I.C.E. Center, 3rd & 4th Floor, Quezon City', 14.65480000, 121.05050000, 'Quezon City M.I.C.E. Center, Quezon City Hall Compound, Elliptical Road, Diliman, Quezon City', 'Quezon City', 0, NULL, NULL, 'under_construction', 4, '2025-12-14 19:29:25', '2025-12-14 19:29:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `view_count` int NOT NULL DEFAULT '0',
  `helpful_count` int NOT NULL DEFAULT '0',
  `not_helpful_count` int NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `category_id`, `question`, `answer`, `sort_order`, `is_published`, `view_count`, `helpful_count`, `not_helpful_count`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 'How do I reserve a public facility?', 'To reserve a facility, log in to your account and navigate to the \"Browse Facilities\" page. Select your preferred facility, choose an available date and time slot, fill out the booking form with the required details, and submit your reservation request. Your booking will be reviewed by staff before confirmation.', 1, 1, 45, 12, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(2, 1, 'How far in advance can I book a facility?', 'You may submit a reservation request up to 90 days in advance. We recommend booking at least 2 weeks ahead for large events to ensure availability and allow sufficient time for staff verification and document processing.', 2, 1, 32, 8, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(3, 1, 'What documents do I need to submit with my reservation?', 'You will need to provide a valid government-issued ID (front and back), a selfie with your ID for identity verification, and any supporting documents relevant to your event. For senior citizens, PWDs, or solo parents, please upload your corresponding discount ID for applicable rate reductions.', 3, 1, 28, 10, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(4, 1, 'How long does it take for my reservation to be approved?', 'After submission, your booking undergoes staff verification within 1–3 business days. Once verified, you will receive a notification with your payment slip and instructions. After payment is confirmed by the City Treasurer\'s Office, your booking status will be updated to \"Confirmed.\"', 4, 1, 38, 14, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(5, 1, 'Can I reserve multiple facilities for the same event?', 'Yes, you may submit separate reservation requests for different facilities. Each booking is processed independently. Please ensure the schedules do not conflict and that each booking meets the respective facility requirements.', 5, 1, 15, 5, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(6, 2, 'What are the accepted payment methods?', 'We accept payments through GCash, Maya (PayMaya), bank transfer, and over-the-counter payment at the City Treasurer\'s Office (CTO). You will receive detailed payment instructions along with your payment slip after your booking has been verified by staff.', 1, 1, 52, 18, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(7, 2, 'How much does it cost to reserve a facility?', 'Rental rates vary depending on the facility, duration, and any additional equipment requested. City residents receive a discounted rate. You can view the specific rates on each facility\'s detail page before submitting your booking. The total amount, including any applicable discounts, will be reflected on your payment slip.', 2, 1, 41, 11, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(8, 2, 'What is the payment deadline after my booking is verified?', 'You must complete payment within 72 hours (3 days) after receiving your payment slip. If payment is not received within this period, your reservation will automatically expire and the time slot will be released for other applicants.', 3, 1, 36, 9, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(9, 2, 'Do residents receive a discount on facility rentals?', 'Yes, registered residents of the city are eligible for a resident discount on facility rental fees. Additionally, senior citizens, persons with disabilities (PWDs), and solo parents may qualify for further discounts upon presentation of a valid discount ID during the booking process.', 4, 1, 22, 7, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(10, 3, 'How do I create an account?', 'You can sign up using your Google account through our secure authentication system. Simply click \"Sign in with Google\" on the login page, and your account will be created automatically. Your name and email address will be imported from your Google profile.', 1, 1, 60, 20, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(11, 3, 'How can I update my profile information?', 'Navigate to the \"Profile\" section from the sidebar menu. You can update your contact number, address, and other personal details. Some information linked to your Google account (such as your name and email) may require changes through your Google account settings.', 2, 1, 18, 6, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(12, 3, 'How do I check my booking history?', 'Go to \"Transaction History\" in the sidebar to view all your past and current reservations. You can filter bookings by status (pending, confirmed, completed, etc.) and view details such as payment receipts, booking references, and facility information.', 3, 1, 25, 8, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(13, 4, 'Can I cancel my reservation?', 'Yes, you may request cancellation of your booking. However, please note that cancellations made after payment verification may be subject to the city\'s refund policy. Contact the facility management office or submit a cancellation request through your booking details page.', 1, 1, 30, 10, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(14, 4, 'How do I request a refund?', 'If your booking is eligible for a refund, a refund request will be automatically generated upon cancellation or rejection. You will be asked to select your preferred refund method (GCash, bank transfer, or over-the-counter). Refund processing typically takes 5–10 business days depending on the selected method.', 2, 1, 27, 9, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(15, 4, 'What happens if my booking is rejected?', 'If your booking is rejected during the verification process, you will receive a notification explaining the reason. Common reasons include incomplete documentation, schedule conflicts, or facility unavailability. If you have already made payment, you will be eligible for a full refund.', 3, 1, 19, 6, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(16, 5, 'What are the general rules for using public facilities?', 'All facility users must adhere to the following: arrive on time for your scheduled reservation, maintain cleanliness and orderliness, avoid bringing prohibited items (alcoholic beverages, hazardous materials), comply with the maximum capacity limit, and vacate the premises by the end of your reserved time slot. Any damage to the facility or equipment may result in additional charges.', 1, 1, 35, 12, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(17, 5, 'Can I bring my own equipment to the facility?', 'Yes, you may bring your own equipment such as sound systems, projectors, or decorations. However, all equipment must be declared during the booking process and is subject to staff approval. Electrical equipment must be in good working condition to prevent safety hazards.', 2, 1, 14, 4, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(18, 5, 'Is there available parking at the facilities?', 'Parking availability varies by facility. Most large venues such as the Main Conference Hall and Sports Complex have designated parking areas. Please check the specific facility details page for parking information. We recommend carpooling or using public transportation for events with large attendance.', 3, 1, 20, 7, 0, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq_categories`
--

INSERT INTO `faq_categories` (`id`, `name`, `slug`, `description`, `icon`, `sort_order`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 'Facility Reservations', 'facility-reservations', 'Questions about booking and reserving public facilities', 'calendar', 1, 1, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(2, 'Payments & Fees', 'payments-fees', 'Information about payment methods, fees, and billing', 'credit-card', 2, 1, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(3, 'Account Management', 'account-management', 'Help with your account settings and profile', 'user', 3, 1, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(4, 'Cancellations & Refunds', 'cancellations-refunds', 'Policies and procedures for cancellations and refund requests', 'rotate-ccw', 4, 1, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL),
(5, 'Facility Guidelines', 'facility-guidelines', 'Rules, regulations, and usage guidelines for public facilities', 'book-open', 5, 1, 1, NULL, '2026-02-08 03:44:35', '2026-02-08 03:44:35', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fund_requests`
--

CREATE TABLE `fund_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `requester_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logistics` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `response_data` json DEFAULT NULL,
  `seminar_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seminar_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seminar_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `government_program_bookings`
--

CREATE TABLE `government_program_bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED DEFAULT NULL,
  `source_system` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Energy Efficiency',
  `source_seminar_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_database` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ener_nova_capri',
  `organizer_user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizer_contact` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizer_area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_type` enum('seminar','training','workshop','community_event','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'seminar',
  `program_description` text COLLATE utf8mb4_unicode_ci,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `expected_attendees` int NOT NULL DEFAULT '0',
  `actual_attendees` int NOT NULL DEFAULT '0',
  `requested_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_facility_id` bigint UNSIGNED DEFAULT NULL,
  `coordination_status` enum('pending_review','organizer_contacted','speaker_coordinating','fund_requested','fund_approved','facility_assigned','confirmed','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_review',
  `call_log` json DEFAULT NULL,
  `coordination_notes` text COLLATE utf8mb4_unicode_ci,
  `number_of_speakers` int NOT NULL DEFAULT '1',
  `speaker_details` json DEFAULT NULL,
  `speaker_coordination_notes` text COLLATE utf8mb4_unicode_ci,
  `speakers_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `requested_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `approved_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `actual_spent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `fund_breakdown` json DEFAULT NULL,
  `is_fee_waived` tinyint(1) NOT NULL DEFAULT '1',
  `finance_request_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finance_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `finance_approved_date` date DEFAULT NULL,
  `finance_check_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pre_event_transparency_published` tinyint(1) NOT NULL DEFAULT '0',
  `post_event_transparency_published` tinyint(1) NOT NULL DEFAULT '0',
  `is_public_display` tinyint(1) NOT NULL DEFAULT '1',
  `liquidation_required` tinyint(1) NOT NULL DEFAULT '1',
  `liquidation_submitted` tinyint(1) NOT NULL DEFAULT '0',
  `liquidation_date` date DEFAULT NULL,
  `event_rating` decimal(3,2) DEFAULT NULL,
  `feedback_summary` text COLLATE utf8mb4_unicode_ci,
  `attendance_data` json DEFAULT NULL,
  `assigned_admin_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_articles`
--

CREATE TABLE `help_articles` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('booking','payment','facility_info','account','troubleshooting') COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `screenshots` json DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `view_count` int NOT NULL DEFAULT '0',
  `helpful_count` int NOT NULL DEFAULT '0',
  `not_helpful_count` int NOT NULL DEFAULT '0',
  `tags` json DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `help_articles`
--

INSERT INTO `help_articles` (`id`, `title`, `slug`, `excerpt`, `content`, `category`, `video_url`, `screenshots`, `sort_order`, `is_published`, `view_count`, `helpful_count`, `not_helpful_count`, `tags`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 'Getting Started: How to Reserve a Public Facility', 'getting-started-reserve-facility', 'A step-by-step guide to creating your first facility reservation through the online booking system.', '<h2>Step 1: Sign In to Your Account</h2><p>Visit the facility reservation portal and sign in using your Google account. If you are a first-time user, your account will be created automatically upon login.</p><h2>Step 2: Browse Available Facilities</h2><p>Navigate to the \"Browse Facilities\" page from the sidebar. You can view all available facilities along with photos, amenities, pricing, and available time slots. Use the calendar view to check availability for your preferred date.</p><h2>Step 3: Select a Facility and Time Slot</h2><p>Click on your preferred facility to view its details. Select your desired date and time slot from the availability calendar. The system will show you the applicable rates based on the duration and your residency status.</p><h2>Step 4: Complete the Booking Form</h2><p>Fill in the required information including event name, purpose, expected number of attendees, and any special requests. Upload the required documents: a valid government ID (front and back) and a selfie with your ID.</p><h2>Step 5: Review and Submit</h2><p>Review all your booking details, including the estimated total amount. Once submitted, your reservation will be queued for staff verification. You will receive a notification once your booking has been reviewed.</p><h2>Step 6: Make Payment</h2><p>After staff verification, you will receive a payment slip with the exact amount due and payment instructions. Complete payment within 72 hours using any of the accepted methods (GCash, Maya, bank transfer, or over-the-counter at the CTO).</p><h2>Step 7: Booking Confirmed</h2><p>Once your payment has been verified by the City Treasurer\'s Office, your booking status will be updated to \"Confirmed\" and you will receive an official receipt via email notification.</p>', 'booking', NULL, NULL, 1, 1, 78, 25, 0, '\"[\\\"reservation\\\",\\\"booking\\\",\\\"guide\\\",\\\"getting started\\\"]\"', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(2, 'Understanding Payment Methods and Procedures', 'payment-methods-procedures', 'Learn about the different payment options available and how to complete your facility rental payment.', '<h2>Accepted Payment Methods</h2><p>The facility reservation system supports multiple payment channels for your convenience:</p><ul><li><strong>GCash</strong> — Send payment to the designated GCash number provided on your payment slip.</li><li><strong>Maya (PayMaya)</strong> — Transfer to the official Maya account listed on your payment slip.</li><li><strong>Bank Transfer</strong> — Deposit to the city government\'s official bank account. Details are included on your payment slip.</li><li><strong>Over-the-Counter (CTO)</strong> — Visit the City Treasurer\'s Office during business hours (Monday–Friday, 8:00 AM – 5:00 PM) and present your payment slip for processing.</li></ul><h2>Payment Timeline</h2><p>After your booking is verified by staff, you will have 72 hours to complete payment. A reminder notification will be sent 24 hours and 6 hours before the deadline. If payment is not received within this window, your reservation will expire automatically.</p><h2>Payment Verification</h2><p>For cashless payments, the City Treasurer\'s Office will verify your payment within 1–2 business days. Once verified, an Official Receipt (OR) will be issued and you will be notified via email and in-app notification.</p>', 'payment', NULL, NULL, 2, 1, 54, 17, 0, '\"[\\\"payment\\\",\\\"gcash\\\",\\\"maya\\\",\\\"bank transfer\\\",\\\"fees\\\"]\"', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(3, 'How to Check Facility Availability Using the Calendar', 'check-facility-availability-calendar', 'Use the calendar view to find open time slots and plan your reservation around existing bookings.', '<h2>Accessing the Calendar View</h2><p>From the sidebar, click on \"Calendar View\" under the Booking Management section. This displays a monthly overview of all facility bookings across all venues.</p><h2>Filtering by Facility</h2><p>Use the facility filter dropdown at the top of the calendar to narrow down the view to a specific venue. This is helpful when you are interested in a particular facility and want to see its availability at a glance.</p><h2>Reading the Calendar</h2><p>Color-coded events indicate different booking statuses:</p><ul><li><strong>Green</strong> — Confirmed bookings</li><li><strong>Yellow</strong> — Pending or under review</li><li><strong>Red</strong> — Rejected or cancelled</li><li><strong>Blue</strong> — Staff verified, awaiting payment</li></ul><h2>Checking Available Slots</h2><p>Click on any date to see the detailed schedule for that day. Available time slots will be clearly indicated. You can also click directly on a facility\'s detail page and use the built-in date picker to check availability for specific dates.</p>', 'booking', NULL, NULL, 3, 1, 33, 11, 0, '\"[\\\"calendar\\\",\\\"availability\\\",\\\"schedule\\\",\\\"booking\\\"]\"', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(4, 'Managing Your Account and Profile Settings', 'managing-account-profile', 'Learn how to update your personal information, view transaction history, and manage notification preferences.', '<h2>Updating Personal Information</h2><p>Navigate to \"Profile\" in the sidebar to access your account settings. You can update your contact number, address, and other personal details. Your name and email are linked to your Google account and may need to be updated through Google\'s account settings.</p><h2>Viewing Transaction History</h2><p>Access \"Transaction History\" to see a complete record of all your bookings, including current status, payment details, and booking references. You can filter by date range and booking status for easier navigation.</p><h2>Payment History</h2><p>Under \"Payment Methods,\" you can view all your payment transactions, including payment slips, official receipts, and refund records. Each transaction shows the payment method used, amount, and current verification status.</p><h2>Managing Notifications</h2><p>The notification bell icon in the top navigation bar shows your recent notifications. Click on any notification to view details. You can mark individual notifications as read or use \"Mark All as Read\" to clear them all at once.</p>', 'account', NULL, NULL, 4, 1, 22, 8, 0, '\"[\\\"account\\\",\\\"profile\\\",\\\"settings\\\",\\\"notifications\\\"]\"', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(5, 'Cancellation and Refund Policies', 'cancellation-refund-policies', 'Understand the city\'s cancellation procedures and how refunds are processed for facility reservations.', '<h2>Cancellation Policy</h2><p>Reservations may be cancelled at any point before the scheduled event date. The refund amount depends on when the cancellation is made:</p><ul><li><strong>Before payment verification</strong> — Full refund, no processing required.</li><li><strong>After payment verification, 7+ days before event</strong> — Full refund of the rental fee.</li><li><strong>After payment verification, less than 7 days before event</strong> — Subject to review by the facility management office.</li></ul><h2>How to Request a Cancellation</h2><p>Go to your booking details page and click \"Request Cancellation.\" Provide a reason for the cancellation. The request will be processed by the admin, and if a refund is applicable, you will be prompted to select your preferred refund method.</p><h2>Refund Methods</h2><p>Available refund methods include GCash, bank transfer, and over-the-counter collection at the City Treasurer\'s Office. Refunds are typically processed within 5–10 business days after approval.</p><h2>Tracking Your Refund</h2><p>You can track the status of your refund request under \"My Refunds\" in the sidebar. The system will notify you at each stage of the refund process.</p>', 'facility_info', NULL, NULL, 5, 1, 40, 13, 0, '\"[\\\"cancellation\\\",\\\"refund\\\",\\\"policy\\\",\\\"guidelines\\\"]\"', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(6, 'Troubleshooting Common Booking Issues', 'troubleshooting-booking-issues', 'Solutions for common problems encountered during the reservation process.', '<h2>I Cannot See Available Time Slots</h2><p>If no time slots appear for your selected date, the facility may be fully booked or under maintenance. Try selecting a different date or check the bulletin board for any scheduled maintenance announcements.</p><h2>My Booking Was Rejected</h2><p>Bookings may be rejected due to incomplete documentation, schedule conflicts, or policy violations. Check the rejection reason in your notification and resubmit with the corrected information.</p><h2>I Did Not Receive My Payment Slip</h2><p>Payment slips are generated after staff verification. Check your notifications and email (including spam folder). If you still cannot find it, visit \"Payment Methods\" in the sidebar to view all your pending payment slips.</p><h2>My Payment Was Not Verified</h2><p>Payment verification by the City Treasurer\'s Office may take 1–2 business days. Ensure your payment reference matches the details on your payment slip. If verification is delayed beyond 3 business days, please contact the facility management office.</p><h2>The Calendar Shows My Slot as Available But I Cannot Book</h2><p>This may occur if another user is in the process of booking the same slot. The system holds time slots temporarily during the booking process. Please try again after a few minutes or select an alternative time slot.</p>', 'troubleshooting', NULL, NULL, 6, 1, 29, 9, 0, '\"[\\\"troubleshooting\\\",\\\"issues\\\",\\\"help\\\",\\\"problems\\\"]\"', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `location_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` json DEFAULT NULL COMMENT 'Location-specific settings',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`, `location_code`, `address`, `city`, `province`, `zip_code`, `phone`, `email`, `config`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'South Caloocan City', 'CAL', '10th Ave, Caloocan, Metro Manila', 'Caloocan City', 'Metro Manila', '1400', '(02) 8-288-8181', 'info@caloocan.gov.ph', '\"{\\\"payment_mode\\\":\\\"per_person\\\",\\\"base_rate\\\":150,\\\"currency\\\":\\\"PHP\\\",\\\"operating_hours\\\":{\\\"start\\\":\\\"06:00\\\",\\\"end\\\":\\\"22:00\\\"},\\\"advance_booking_days\\\":90,\\\"cancellation_deadline_hours\\\":48,\\\"approval_levels\\\":[\\\"staff\\\",\\\"admin\\\"],\\\"discount_tiers\\\":{\\\"pwd\\\":20,\\\"senior\\\":20,\\\"student\\\":20},\\\"requires_full_payment\\\":true,\\\"payment_policy\\\":\\\"Full payment required before reservation confirmation\\\"}\"', 1, '2025-12-14 19:28:43', '2025-12-14 19:28:43', NULL),
(2, 'Quezon City M.I.C.E. Center', 'QC', 'Elliptical Road, Quezon City, Metro Manila', 'Quezon City', 'Metro Manila', '1100', '(02) 8-988-4242', 'mice@quezoncity.gov.ph', '{\"currency\": \"PHP\", \"base_rate\": 150, \"payment_mode\": \"per_person\", \"discount_tiers\": {\"pwd\": 20, \"senior\": 20, \"student\": 20}, \"payment_policy\": \"Full payment required before reservation confirmation\", \"approval_levels\": [\"staff\", \"admin\"], \"operating_hours\": {\"end\": \"21:00\", \"start\": \"07:00\"}, \"ordinance_status\": \"approved\", \"advance_booking_days\": 180, \"public_booking_status\": \"available\", \"requires_full_payment\": true, \"cancellation_deadline_hours\": 72}', 1, '2025-12-14 19:28:43', '2025-12-14 19:28:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `device_name` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `failure_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required_2fa` tinyint(1) NOT NULL DEFAULT '0',
  `attempted_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `device_name`, `ip_address`, `country`, `city`, `status`, `failure_reason`, `required_2fa`, `attempted_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-20 07:48:33', '2026-01-20 07:48:33', '2026-01-20 07:48:33'),
(2, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-20 08:20:10', '2026-01-20 08:20:10', '2026-01-20 08:20:10'),
(3, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-20 08:23:30', '2026-01-20 08:23:30', '2026-01-20 08:23:30'),
(4, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-20 08:43:51', '2026-01-20 08:43:51', '2026-01-20 08:43:51'),
(5, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-20 09:49:00', '2026-01-20 09:49:00', '2026-01-20 09:49:00'),
(6, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-20 09:56:15', '2026-01-20 09:56:15', '2026-01-20 09:56:15'),
(7, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-20 09:57:00', '2026-01-20 09:57:00', '2026-01-20 09:57:00'),
(8, 3, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-20 09:58:00', '2026-01-20 09:58:00', '2026-01-20 09:58:00'),
(9, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-21 05:01:25', '2026-01-21 05:01:25', '2026-01-21 05:01:25'),
(10, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-21 07:29:01', '2026-01-21 07:29:01', '2026-01-21 07:29:01'),
(11, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-23 18:53:42', '2026-01-23 18:53:42', '2026-01-23 18:53:42'),
(12, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-23 19:15:07', '2026-01-23 19:15:07', '2026-01-23 19:15:07'),
(13, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-23 20:17:58', '2026-01-23 20:17:58', '2026-01-23 20:17:58'),
(14, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-23 20:28:57', '2026-01-23 20:28:57', '2026-01-23 20:28:57'),
(15, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-23 22:34:23', '2026-01-23 22:34:23', '2026-01-23 22:34:23'),
(16, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-24 01:05:35', '2026-01-24 01:05:35', '2026-01-24 01:05:35'),
(17, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-24 06:36:00', '2026-01-24 06:36:00', '2026-01-24 06:36:00'),
(18, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-24 19:55:43', '2026-01-24 19:55:43', '2026-01-24 19:55:43'),
(19, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-24 21:18:51', '2026-01-24 21:18:51', '2026-01-24 21:18:51'),
(20, 17, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-25 22:31:05', '2026-01-25 22:31:05', '2026-01-25 22:31:05'),
(21, 17, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-26 00:42:53', '2026-01-26 00:42:53', '2026-01-26 00:42:53'),
(22, 17, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-26 00:50:28', '2026-01-26 00:50:28', '2026-01-26 00:50:28'),
(23, 17, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-26 00:52:01', '2026-01-26 00:52:01', '2026-01-26 00:52:01'),
(24, 17, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-26 00:56:56', '2026-01-26 00:56:56', '2026-01-26 00:56:56'),
(25, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-27 02:48:25', '2026-01-27 02:48:25', '2026-01-27 02:48:25'),
(26, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-27 03:24:46', '2026-01-27 03:24:46', '2026-01-27 03:24:46'),
(27, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-27 09:09:49', '2026-01-27 09:09:49', '2026-01-27 09:09:49'),
(28, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-27 09:10:58', '2026-01-27 09:10:58', '2026-01-27 09:10:58'),
(29, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-27 10:44:19', '2026-01-27 10:44:19', '2026-01-27 10:44:19'),
(30, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-27 20:53:03', '2026-01-27 20:53:03', '2026-01-27 20:53:03'),
(31, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-27 23:15:29', '2026-01-27 23:15:29', '2026-01-27 23:15:29'),
(32, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-28 08:54:17', '2026-01-28 08:54:17', '2026-01-28 08:54:17'),
(33, 3, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-28 08:55:18', '2026-01-28 08:55:18', '2026-01-28 08:55:18'),
(34, 7, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-28 08:58:21', '2026-01-28 08:58:21', '2026-01-28 08:58:21'),
(35, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-28 11:16:27', '2026-01-28 11:16:27', '2026-01-28 11:16:27'),
(36, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 0, '2026-01-28 15:07:33', '2026-01-28 15:07:33', '2026-01-28 15:07:33'),
(37, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-01-28 15:14:45', '2026-01-28 15:14:45', '2026-01-28 15:14:45'),
(38, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', 'success', NULL, 1, '2026-02-11 15:20:10', '2026-02-11 15:20:10', '2026-02-11 15:20:10'),
(39, 5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '136.158.7.63', 'Philippines', 'Quezon City', 'success', NULL, 1, '2026-02-11 16:09:48', '2026-02-11 16:09:48', '2026-02-11 16:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `message_templates`
--

CREATE TABLE `message_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `variables` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `version` int NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_templates`
--

INSERT INTO `message_templates` (`id`, `name`, `category`, `type`, `subject`, `body`, `variables`, `is_active`, `version`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(2, 'Booking Confirmed', 'booking', 'sms', NULL, 'Hi {{citizen_name}}! Your booking at {{facility_name}} on {{booking_date}} is confirmed. Booking ID: {{booking_id}}', '[\"citizen_name\", \"booking_id\", \"facility_name\", \"booking_date\"]', 1, 1, NULL, NULL, '2026-01-20 09:06:20', '2026-01-20 09:06:20', NULL, NULL),
(3, 'Payment Received', 'payment', 'email', 'Payment Receipt - {{booking_id}}', 'Dear {{citizen_name}},<br><br>We have received your payment of ₱{{amount}} for booking {{booking_id}}.<br><br>Transaction ID: {{transaction_id}}<br>Payment Date: {{payment_date}}<br><br>Thank you!', '[\"citizen_name\", \"booking_id\", \"amount\", \"transaction_id\", \"payment_date\"]', 1, 1, NULL, NULL, '2026-01-20 09:06:20', '2026-01-20 09:06:20', NULL, NULL),
(4, 'Booking Reminder', 'reminder', 'sms', NULL, 'Reminder: Your booking at {{facility_name}} is tomorrow at {{booking_time}}. See you there!', '[\"facility_name\", \"booking_time\"]', 1, 1, NULL, NULL, '2026-01-20 09:06:20', '2026-01-20 09:06:20', NULL, NULL),
(5, 'Booking Confirmed', 'booking', 'email', 'Booking Confirmation - {{facility_name}}', 'Dear {{citizen_name}},<br><br>Your booking has been confirmed!<br><br>Booking ID: {{booking_id}}<br>Facility: {{facility_name}}<br>Date: {{booking_date}}<br>Time: {{booking_time}}<br><br>Thank you for using our facility reservation system!', '[\"citizen_name\", \"booking_id\", \"facility_name\", \"booking_date\", \"booking_time\"]', 1, 1, NULL, NULL, '2026-01-21 05:51:18', '2026-01-21 05:51:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_04_052230_create_sessions_table', 1),
(2, '2025_11_04_052234_create_cache_table', 2),
(3, '2025_11_04_052206_create_roles_table', 3),
(4, '2025_11_04_052215_create_subsystems_table', 4),
(5, '2025_11_04_052218_create_subsystem_roles_table', 5),
(6, '2025_11_04_052159_create_districts_table', 6),
(7, '2025_11_04_052203_create_barangays_table', 6),
(8, '2025_11_04_052211_create_permissions_table', 6),
(9, '2025_11_04_052228_create_user_otps_table', 7),
(10, '2025_11_06_094837_create_philippine_regions_table', 8),
(11, '2025_11_06_094857_create_philippine_provinces_table', 9),
(12, '2025_11_06_094858_create_philippine_cities_table', 10),
(13, '2025_11_06_124859_add_zip_code_to_cities_table', 11),
(14, '2025_11_06_131059_update_districts_table_for_all_cities', 12),
(15, '2025_11_06_131256_remove_unique_constraint_from_districts', 13),
(16, '2025_11_06_094859_update_barangays_add_city_id', 14),
(17, '2025_11_06_133000_add_zip_code_to_barangays_table', 15),
(18, '2025_11_06_094900_update_users_add_philippine_address_fields', 16),
(19, '2025_11_06_140809_add_ai_verification_fields_to_users_table', 17),
(20, '2025_12_10_125156_create_locations_table', 18),
(21, '2025_12_10_124226_create_facilities_table', 19),
(22, '2025_12_15_050748_add_pricing_columns_to_facilities_db_table', 20),
(23, '2025_12_16_104428_add_extension_pricing_to_facilities_table', 21),
(24, '2025_12_16_104452_add_extension_pricing_to_facilities_db_table', 22),
(25, '2025_12_24_070613_add_expired_at_to_bookings_table', 23),
(26, '2025_12_25_101506_create_payment_slips_table', 24),
(27, '2025_12_27_162055_add_test_mode_to_payment_slips_table', 25),
(28, '2025_12_26_164600_add_paymongo_fields_to_payment_slips_table', 26),
(29, '2025_12_17_165500_add_is_available_to_facilities_table', 26),
(30, '2025_11_15_180006_add_payment_integration_fields_to_payment_slips', 27),
(31, '2025_12_28_085748_create_notifications_table', 28),
(32, '2025_12_28_085935_add_reminder_tracking_to_payment_slips_table', 29),
(33, '2026_01_02_080941_add_deleted_at_to_equipment_items_table', 30),
(34, '2026_01_02_082645_create_maintenance_schedules_table', 31),
(35, '2026_01_03_122646_create_audit_logs_table', 32),
(36, '2026_01_04_043221_create_citizen_payment_methods_table', 33),
(37, '2026_01_04_110817_create_city_events_table', 34),
(38, '2026_01_04_110835_create_booking_conflicts_table', 35),
(39, '2026_01_06_134516_add_refund_details_to_booking_conflicts_table', 36),
(40, '2026_01_09_000001_create_government_program_bookings_table', 37),
(41, '2026_01_09_000002_create_suppliers_table', 38),
(42, '2026_01_09_000003_create_supplier_products_table', 39),
(43, '2026_01_09_000004_create_liquidation_items_table', 40),
(44, '2026_01_09_000005_create_citizen_program_registrations_table', 41),
(45, '2026_01_10_060318_add_equipment_to_government_program_bookings', 42),
(46, '2026_01_10_063159_create_equipment_inventory_table', 43),
(47, '2026_01_17_000001_create_activity_logs_table', 44),
(48, '2026_01_17_052102_add_cancellation_fields_to_bookings_table', 45),
(49, '2026_01_17_053631_create_system_settings_table', 46),
(50, '2026_01_17_095433_add_announcement_image_to_system_settings', 47),
(51, '2026_01_17_112442_create_backup_downloads_table', 48),
(52, '2026_01_20_232500_add_security_features_to_users_table', 49),
(53, '2026_01_20_232503_create_login_history_table', 50),
(54, '2026_01_20_232502_create_user_sessions_table', 51),
(55, '2026_01_20_232501_create_trusted_devices_table', 52),
(56, '2026_01_20_234600_increase_device_name_length', 53),
(57, '2026_01_21_010000_add_communication_settings', 54),
(58, '2026_01_21_010100_create_message_templates_table', 55),
(59, '2026_01_21_010200_create_notification_campaigns_table', 56),
(60, '2026_01_21_133142_add_soft_deletes_to_message_templates_table', 57),
(61, '2026_01_21_140101_add_soft_deletes_to_bookings_table', 58),
(62, '2026_01_21_140104_add_soft_deletes_to_budget_allocations_table', 58),
(63, '2026_01_21_140110_add_soft_deletes_to_citizen_payment_methods_table', 58),
(64, '2026_01_21_140110_add_soft_deletes_to_security_tables', 58),
(65, '2026_01_21_151732_create_contact_inquiries_table', 59),
(66, '2026_01_21_151732_create_events_table', 59),
(67, '2026_01_21_151732_create_faq_categories_table', 59),
(68, '2026_01_21_151732_create_faqs_table', 59),
(69, '2026_01_21_151732_create_help_articles_table', 59),
(70, '2026_01_21_151732_create_news_table', 59),
(71, '2026_01_24_041515_create_user_favorites_table', 60),
(72, '2026_01_24_041537_add_geocoding_to_facilities_table', 61),
(73, '2026_01_24_052828_fix_user_favorites_foreign_key', 62),
(74, '2026_01_24_064403_add_profile_photo_path_to_users_table', 63),
(75, '2026_01_24_184900_update_facility_coordinates', 64),
(76, '2026_01_24_215000_add_notification_preferences_to_favorites', 65),
(77, '2026_01_25_130500_create_infrastructure_project_requests_table', 66),
(78, '2026_01_26_044031_add_google_id_to_users_table', 67),
(79, '2026_01_26_140000_add_profile_incomplete_to_users_table', 68),
(80, '2026_01_27_134632_create_facility_images_table', 69),
(81, '2026_01_28_143000_add_paymongo_checkout_id_to_payment_slips_table', 70),
(82, '2026_01_28_180600_enable_mice_facilities_for_public_booking', 71),
(83, '2025_01_28_150000_create_community_maintenance_requests_table', 72),
(85, '2026_01_31_010000_create_fund_requests_table', 73),
(86, '2026_01_31_131000_add_response_data_to_fund_requests_table', 74),
(87, '2026_01_31_234000_add_seminar_id_to_fund_requests_table', 75),
(88, '2026_02_01_220000_create_road_assistance_requests_table', 76),
(89, '2026_02_02_221500_add_external_system_columns_to_bookings_table', 77),
(90, '2026_02_03_233705_create_citizen_road_requests_table', 78),
(91, '2026_02_04_113100_add_applicant_fields_to_bookings_table', 79),
(92, '2026_02_07_170000_create_refund_requests_table', 80),
(93, '2026_02_09_171500_add_down_payment_system_to_bookings_table', 81),
(94, '2026_02_10_190000_add_paymongo_fields_to_bookings', 82),
(95, '2026_02_11_030059_fix_existing_bookings_without_real_payment', 83),
(96, '2026_02_11_134541_add_cashless_to_payment_slips_payment_method_enum', 84),
(97, '2026_02_11_145500_change_payment_method_to_varchar_in_payment_slips', 85),
(98, '2025_02_12_180000_update_community_maintenance_for_general_request_api', 86),
(99, '2025_02_12_200000_create_energy_facility_requests_table', 87),
(100, '2026_02_14_140000_create_water_connection_requests_table', 88);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('general','facility_update','policy_change','maintenance','emergency') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `view_count` int NOT NULL DEFAULT '0',
  `tags` json DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `excerpt`, `content`, `category`, `image_path`, `is_published`, `is_featured`, `is_urgent`, `view_count`, `tags`, `published_at`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 'Online Facility Reservation System Now Available for All Residents', 'online-facility-reservation-system-launch', 'The city government is pleased to announce the official launch of the Online Public Facility Reservation System, allowing residents to conveniently book venues through a digital platform.', '<p>The Local Government Unit is proud to introduce the Online Public Facility Reservation System, a digital platform designed to streamline the booking process for community facilities across the city.</p><p>Through this system, residents can browse available facilities, view pricing and amenities, check real-time availability, and submit reservation requests — all from the comfort of their homes or offices.</p><h3>Key Features</h3><ul><li>Real-time facility availability and calendar view</li><li>Secure online payment through GCash, Maya, and bank transfer</li><li>Automated notifications for booking status updates</li><li>Digital payment slips and official receipts</li><li>Resident discount automatically applied for eligible users</li></ul><p>The system aims to reduce processing time, eliminate manual paperwork, and provide a transparent and efficient booking experience for all citizens.</p><p>For assistance, visit the Help Center or contact the facility management office during business hours.</p>', 'general', NULL, 1, 1, 0, 120, '\"[\\\"launch\\\",\\\"online booking\\\",\\\"facilities\\\"]\"', '2026-01-09 03:45:25', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(2, 'Main Conference Hall Renovation Complete — Now Accepting Reservations', 'main-conference-hall-renovation-complete', 'The recently renovated Main Conference Hall is now open for reservations with upgraded audio-visual equipment and improved seating capacity.', '<p>We are pleased to announce that the renovation of the Main Conference Hall has been completed. The facility is now open and accepting reservations through the online booking system.</p><h3>Upgrades Include</h3><ul><li>New high-definition projector and LED display panels</li><li>Upgraded wireless microphone and sound system</li><li>Improved air conditioning units for better ventilation</li><li>Expanded seating capacity from 150 to 200 persons</li><li>Newly installed Wi-Fi access for event attendees</li></ul><p>The renovated hall is ideal for seminars, workshops, community assemblies, and formal events. Residents are encouraged to book early as demand is expected to be high following the reopening.</p><p>Rental rates remain unchanged during the introductory period. Visit the facility details page for complete pricing and availability information.</p>', 'facility_update', NULL, 1, 0, 0, 85, '\"[\\\"conference hall\\\",\\\"renovation\\\",\\\"facility update\\\"]\"', '2026-01-24 03:45:25', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(3, 'Updated Facility Rental Rates Effective January 2026', 'updated-facility-rental-rates-2026', 'Please be informed of the revised rental rates for public facilities, effective January 1, 2026, as approved by the City Council.', '<p>In accordance with City Ordinance No. 2025-089, the rental rates for public facilities have been revised effective January 1, 2026. The updated rates reflect adjustments for maintenance costs and facility improvements.</p><h3>Summary of Changes</h3><ul><li>Base rental rates adjusted by 5–10% depending on the facility</li><li>Resident discount maintained at the current rate</li><li>Senior citizen, PWD, and solo parent discounts remain in effect</li><li>Extension rates standardized across all venues</li><li>Equipment rental fees updated to reflect current market rates</li></ul><p>The specific rates for each facility can be viewed on their respective detail pages in the booking system. All bookings made prior to January 1, 2026, will be honored at the previous rates.</p><p>For questions regarding the rate adjustments, please contact the City Treasurer\'s Office or visit the Help Center.</p>', 'policy_change', NULL, 1, 0, 0, 65, '\"[\\\"rates\\\",\\\"policy\\\",\\\"pricing\\\",\\\"update\\\"]\"', '2025-12-25 03:45:25', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(4, 'Scheduled Maintenance: Sports Complex Closed February 10–14', 'sports-complex-maintenance-february-2026', 'The Sports Complex will be temporarily closed for scheduled maintenance from February 10 to 14, 2026. Existing reservations during this period will be rescheduled.', '<p>Please be advised that the Sports Complex will undergo scheduled preventive maintenance from <strong>February 10 to 14, 2026</strong>. The facility will be closed to the public during this period.</p><h3>Maintenance Activities</h3><ul><li>Floor resurfacing and repair of the indoor court</li><li>Inspection and servicing of electrical systems</li><li>Plumbing maintenance and restroom repairs</li><li>Repainting of common areas and exterior walls</li></ul><h3>Affected Reservations</h3><p>All existing reservations during the maintenance window have been identified and the affected applicants have been contacted individually. Options for rescheduling or refund have been provided.</p><p>We apologize for any inconvenience and appreciate your understanding as we work to maintain the quality and safety of our public facilities.</p><p>The facility is expected to reopen on <strong>February 15, 2026</strong>. Reservations for dates after the maintenance period are being accepted as usual.</p>', 'maintenance', NULL, 1, 0, 1, 92, '\"[\\\"maintenance\\\",\\\"sports complex\\\",\\\"closure\\\",\\\"schedule\\\"]\"', '2026-02-03 03:45:25', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL),
(5, 'GCash and Maya Payments Now Accepted for Facility Reservations', 'gcash-maya-payments-accepted', 'In line with the city\'s digital transformation initiative, cashless payment options via GCash and Maya are now available for facility rental payments.', '<p>The City Treasurer\'s Office, in partnership with the facility reservation management team, now accepts GCash and Maya as official payment channels for facility rental fees.</p><h3>How to Pay via GCash or Maya</h3><ol><li>Complete your booking and wait for staff verification.</li><li>Once verified, you will receive a payment slip with the GCash/Maya account details.</li><li>Send the exact amount indicated on your payment slip.</li><li>Use your booking reference number as the payment description.</li><li>The Treasurer\'s Office will verify your payment within 1–2 business days.</li></ol><h3>Benefits of Cashless Payment</h3><ul><li>No need to visit the City Treasurer\'s Office in person</li><li>Instant payment confirmation via transaction receipt</li><li>Secure and traceable digital transactions</li><li>Available 24/7 for your convenience</li></ul><p>Over-the-counter and bank transfer payment options remain available for those who prefer traditional methods.</p>', 'general', NULL, 1, 0, 0, 73, '\"[\\\"gcash\\\",\\\"maya\\\",\\\"payment\\\",\\\"cashless\\\",\\\"digital\\\"]\"', '2025-12-10 03:45:25', 1, NULL, '2026-02-08 03:45:25', '2026-02-08 03:45:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('02ae1f6b-b283-4ebe-85fb-a3561b9ffbbe', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":22,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000022\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 20:40:34', '2026-01-16 20:40:34'),
('03414430-3066-4607-8ccc-07753ad6b1bd', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 07:50:34', '2025-12-29 07:50:34'),
('04b7b375-fbd5-434b-b1d4-14d49efaa336', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":11,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000011\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-29 23:28:39', '2025-12-29 23:28:39'),
('0860eaf2-c48f-4d47-bef2-efc85a54abd1', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2026-0002\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 03:06:17', '2026-01-13 03:06:17'),
('08a006b0-5ea4-42e1-977b-691eec66a9ad', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":29,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000029\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 16:58:48', '2026-02-11 16:58:48'),
('08a043f1-62b3-4b26-9470-1ea26b69fd7e', 'App\\Notifications\\BookingRejected', 'App\\Models\\User', 5, '{\"booking_id\":12,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000012\",\"reason\":\"Wrong id\",\"message\":\"Your booking request has been declined.\"}', '2025-12-31 21:38:41', '2025-12-31 21:37:28', '2025-12-31 21:37:28'),
('08ae72a8-ef73-4700-9af5-c3dff54989bd', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2025-12-29 23:17:58', '2025-12-29 23:17:58'),
('09c53b90-efdc-4c38-80c4-382c1692600f', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":20,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000020\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-13 19:33:20', '2026-01-13 19:33:20'),
('0b6089a3-ed56-4742-8ee6-40bd5709185e', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2026-0002\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2026-01-13 03:06:18', '2026-01-13 03:06:18'),
('0bd672da-bce3-43ae-8088-9bc9c8817473', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2025-12-29 08:00:30', '2025-12-29 08:00:30'),
('0f136941-0dc4-44f0-aba7-9b1557212033', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000015\",\"payment_deadline\":\"2026-01-09T11:20:35.000000Z\",\"amount_due\":\"4840.00\",\"payment_slip_id\":9,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-07 03:20:54', '2026-01-07 03:20:54'),
('0f1e2ceb-9442-4941-9c0e-155a3790e673', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2025-12-29 04:41:18', '2025-12-29 04:41:18'),
('14935f9f-a557-449a-b854-3d7b27dd7fca', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"or_number\":\"OR-2026-0003\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 03:16:39', '2026-01-13 03:16:39'),
('1807bd27-9b76-4a66-88d9-aa2abf4fa1d8', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":25,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000025\",\"payment_deadline\":\"2026-01-30T09:01:01.000000Z\",\"amount_due\":\"5600.00\",\"payment_slip_id\":19,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-28 09:01:05', '2026-01-28 09:01:05'),
('1971ed3c-dcb3-4696-96c3-83185e8edb69', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2026-0001\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2026-01-07 03:23:52', '2026-01-07 03:23:52'),
('205512ec-7fb5-4523-9dad-5c48120b2bb2', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 07:50:35', '2025-12-29 07:50:35'),
('20d08fc3-acfb-41e4-88f1-dc3d883d5317', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000016\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 03:01:34', '2026-01-13 03:01:34'),
('220fbe6c-7403-4d8b-8ed3-b6cd5c73b6ef', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"or_number\":\"OR-2026-0003\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 03:16:43', '2026-01-13 03:16:43'),
('24d32e57-e347-4237-8fd6-b309de69e04f', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":11,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000011\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-29 23:28:42', '2025-12-29 23:28:42'),
('26fb3372-dcbd-4163-a033-36b73caa86f6', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000010\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-29 23:13:25', '2025-12-29 23:13:25'),
('270152cc-de27-4439-9e44-ee120b740a57', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 08:00:28', '2025-12-29 08:00:28'),
('27d5f2f4-8c49-45ec-b4af-de02d2e16c90', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2026-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 19:22:06', '2026-01-13 19:22:06'),
('2941a340-fc01-48de-9d2d-f2d0b02c5472', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2026-0001\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-07 03:23:51', '2026-01-07 03:23:51'),
('2a21077e-5d1c-4959-b432-a27f5f49fa20', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000018\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:16:52', '2026-01-13 19:16:52'),
('2a526c9e-a989-4b41-92ab-e21008727304', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000010\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-29 23:13:30', '2025-12-29 23:13:30'),
('2b740a7b-2122-4d7d-81f4-be629e63ae13', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":27,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000027\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 15:24:03', '2026-02-11 15:24:03'),
('2d13ab7a-8c6b-485e-a986-7f2e9e9dca97', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2026-0001\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-07 03:23:55', '2026-01-07 03:23:55'),
('300d0783-a870-4202-b5d1-d33294554c1d', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 23:18:00', '2025-12-29 23:18:00'),
('30f2e131-02ca-4f03-9a0b-dd191a4d4397', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":19,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000019\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:28:06', '2026-01-13 19:28:06'),
('326a2a28-e000-434f-a5b6-8f9e30b3371c', 'App\\Notifications\\BookingExpired', 'App\\Models\\User', 5, '{\"booking_id\":22,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000022\",\"message\":\"Your booking has expired due to non-payment within 48 hours.\"}', NULL, '2026-02-11 16:00:09', '2026-02-11 16:00:09'),
('32918e62-7d70-4f46-9eb7-7574e6c2b7cf', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 07:50:30', '2025-12-29 07:50:30'),
('33d706cb-0f9b-4825-9dfe-24772b8e5693', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":13,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000013\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2025-12-31 21:40:06', '2025-12-31 21:40:06'),
('34285f2a-1e28-441e-954e-c759c1e4a077', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":13,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000013\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2025-12-31 21:40:07', '2025-12-31 21:40:07'),
('38cb9d4a-1bf8-438a-b598-f9ec888baa42', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-28 10:23:12', '2025-12-28 10:23:12'),
('3a92bdd9-aaaa-4224-8378-ce8304037e61', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":28,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000028\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 16:14:56', '2026-02-11 16:14:56'),
('3ec98b8f-9f4d-4bbe-86db-411eb435397f', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":20,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000020\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:33:22', '2026-01-13 19:33:22'),
('4171fc64-2d58-4b9d-b36b-e9c21c92aadd', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 23:17:59', '2025-12-29 23:17:59'),
('419249b5-03d6-48e9-8464-179ceaefff9d', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":21,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000021\",\"payment_deadline\":\"2026-01-16T03:37:02.000000Z\",\"amount_due\":\"4050.00\",\"payment_slip_id\":15,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-13 19:37:05', '2026-01-13 19:37:05'),
('4467e56c-427b-4405-9ac0-73332c25aac2', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 04:41:19', '2025-12-29 04:41:19'),
('458d41ec-00aa-4c96-8517-1ebc4aa8c89d', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":25,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000025\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-28 09:00:10', '2026-01-28 09:00:10'),
('45b5d63c-a77a-47b2-a439-eb52963a4175', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":21,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000021\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-13 19:36:14', '2026-01-13 19:36:14'),
('47807b0b-2f7d-48c9-8139-1d2816a0a6ac', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":11,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000011\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', '2025-12-29 23:29:36', '2025-12-29 23:28:40', '2025-12-29 23:28:40'),
('47c08c74-f186-465f-bba8-c4294562cba5', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000018\",\"payment_deadline\":\"2026-01-16T03:19:26.000000Z\",\"amount_due\":\"23250.00\",\"payment_slip_id\":12,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-13 19:19:31', '2026-01-13 19:19:31'),
('4a0e721d-2b83-41e8-a300-9e1e1a9d8dd2', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000024\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 21:03:06', '2026-01-16 21:03:06'),
('4be878ea-e897-4e5f-af97-2fb9b4c76b58', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000024\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 21:03:08', '2026-01-16 21:03:08'),
('4cb0471f-4d5f-4ddf-90e8-818917ab7204', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":11,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000011\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-29 23:28:44', '2025-12-29 23:28:44'),
('4ce25544-a2fd-4c8a-a42d-f07433fa7eb5', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000015\",\"event_date\":\"2026-01-15 08:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', NULL, '2026-01-07 03:58:51', '2026-01-07 03:58:51'),
('4eaa0b8c-f2ec-4b17-a255-bfd97f30d967', 'App\\Notifications\\PaymentSubmitted', 'App\\Models\\User', 11, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"payment_slip_number\":\"PS-2025-000006\",\"message\":\"Your payment submission has been received and is under review by the treasurer.\"}', NULL, '2025-12-28 09:56:15', '2025-12-28 09:56:15'),
('51a835ea-c65e-478d-bc56-d6b2acdeb2a0', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":25,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000025\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-28 09:00:08', '2026-01-28 09:00:08'),
('54435bcb-abdd-4c3d-9cec-dd7ae4fa8ec8', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":28,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000028\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 16:14:59', '2026-02-11 16:14:59'),
('581469a8-1390-4e6d-9b21-95c73d90a0f4', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2026-0005\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-16 21:14:55', '2026-01-16 21:14:55'),
('58cea05e-445c-4215-b6ce-500a75ccbe42', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":14,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000014\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-31 21:46:18', '2025-12-31 21:46:18'),
('593148f6-e4cf-4747-b7ad-675bff3a24cc', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":12,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000012\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', '2025-12-31 21:17:11', '2025-12-31 21:15:43', '2025-12-31 21:15:43'),
('5a823817-e433-416c-9991-1f7dad8c2940', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000018\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-13 19:16:50', '2026-01-13 19:16:50'),
('5cbeff04-5236-4d2f-a27f-c298382f0a5d', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2026-0002\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 03:06:20', '2026-01-13 03:06:20'),
('6056dd3c-8ef7-418b-bf8b-18a20c9b22cb', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000024\",\"event_date\":\"2026-01-26 08:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', NULL, '2026-01-16 21:15:53', '2026-01-16 21:15:53'),
('60ac71c2-f010-4b94-ab41-ac049aebfe3f', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":23,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000023\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 20:49:52', '2026-01-16 20:49:52'),
('60c4c274-68a0-40eb-81b4-95764c66b3bd', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2026-0005\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2026-01-16 21:14:53', '2026-01-16 21:14:53'),
('61e8446a-f769-47a9-ad4c-79f43c8ae6dc', 'App\\Notifications\\BookingCancelled', 'App\\Models\\User', 10, '{\"booking_id\":14,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000014\",\"citizen_name\":\"Cristian Mark Angelo Llaneta\",\"reason\":\"Wrong date\",\"message\":\"A booking has been cancelled by the citizen.\"}', NULL, '2025-12-31 21:46:52', '2025-12-31 21:46:52'),
('6306859e-90a7-4a98-ad51-336291ce700f', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000018\",\"event_date\":\"2026-01-22 08:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', NULL, '2026-01-13 19:23:18', '2026-01-13 19:23:18'),
('64027701-69d9-4615-923b-f9f47e1b9744', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 23:17:56', '2025-12-29 23:17:56'),
('64f6274a-718f-42ad-a324-cda0571db60b', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":7,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2025-12-28 08:27:18', '2025-12-28 06:33:36', '2025-12-28 06:33:36'),
('66aed6d5-3d6a-485b-ab46-eaf6b0b97563', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":21,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000021\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:36:16', '2026-01-13 19:36:16'),
('691e6569-bf46-4ef9-ab7e-4e24b02d065f', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 07:56:26', '2025-12-29 07:56:26'),
('69c11935-773f-4e44-a2fb-bea6211942a5', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000009\",\"event_date\":\"2026-01-06 13:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', NULL, '2025-12-29 23:20:26', '2025-12-29 23:20:26'),
('6a25bd8e-5fe2-445d-ab7d-07e008de6ceb', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":21,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000021\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:36:18', '2026-01-13 19:36:18'),
('6ab9cc8a-58c4-4fd4-a97c-eeed7f5d9fd2', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2025-12-28 10:23:37', '2025-12-28 10:23:04', '2025-12-28 10:23:04'),
('6cb418dd-8532-4624-92cf-1b5616c4df45', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 04:41:21', '2025-12-29 04:41:21'),
('6f9256f2-099d-4f5f-a2c8-87ae1c4360fb', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2025-12-28 10:25:14', '2025-12-28 10:23:00', '2025-12-28 10:23:00'),
('703fe167-9a78-4079-8038-a2698fc03ef9', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000017\",\"payment_deadline\":\"2026-01-15T11:14:41.000000Z\",\"amount_due\":\"5510.00\",\"payment_slip_id\":11,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-13 03:14:44', '2026-01-13 03:14:44'),
('71fdf1df-83e8-483b-ae3f-2eb4c85351c9', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000018\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:16:54', '2026-01-13 19:16:54'),
('72bd0bbc-3c6c-4572-95f4-25c2d4156453', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":8,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000008\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', '2025-12-28 08:48:50', '2025-12-28 08:31:23', '2025-12-28 08:31:23'),
('75771ffa-95ee-40d8-9cb3-e2a189869073', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000017\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 03:13:37', '2026-01-13 03:13:37'),
('7780b271-ff14-4b1a-8df1-17b3fa3a1a44', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":13,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000013\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2025-12-31 21:40:08', '2025-12-31 21:40:08'),
('77e3345e-c92b-4834-a6de-b8fb8e032b6c', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000010\",\"event_date\":\"2026-01-07 08:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', NULL, '2025-12-29 23:19:47', '2025-12-29 23:19:47'),
('79fd5528-ebdb-469c-94d3-4259efe802e1', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":19,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000019\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:28:03', '2026-01-13 19:28:03'),
('7a53916b-2a18-4a6b-ac69-17e13b0151af', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":25,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000025\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-28 09:00:05', '2026-01-28 09:00:05'),
('7ba676df-02da-4750-9caa-ce2520576b12', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 07:56:21', '2025-12-29 07:56:21'),
('7f098f81-927d-4a82-838a-ae8e6a12260b', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2026-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 19:22:08', '2026-01-13 19:22:08'),
('805a72cb-16f9-4206-a58c-1b79e14183f6', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000015\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', '2026-01-06 18:44:54', '2026-01-06 18:44:20', '2026-01-06 18:44:20'),
('8067b695-199a-4401-939c-0e03090daf09', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000017\",\"event_date\":\"2026-01-21 08:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', NULL, '2026-01-13 03:17:34', '2026-01-13 03:17:34'),
('8349da76-dc62-456a-9f1b-5a06e2592ffe', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":27,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000027\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-02-11 15:24:01', '2026-02-11 15:24:01'),
('843c02d4-8fef-4b8a-b197-1c847abb1a71', 'App\\Notifications\\BookingCancelled', 'App\\Models\\User', 14, '{\"booking_id\":14,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000014\",\"citizen_name\":\"Cristian Mark Angelo Llaneta\",\"reason\":\"Wrong date\",\"message\":\"A booking has been cancelled by the citizen.\"}', NULL, '2025-12-31 21:46:54', '2025-12-31 21:46:54'),
('846b277d-8241-44f1-80bf-aefb7757a0bb', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":28,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000028\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 16:14:58', '2026-02-11 16:14:58'),
('851f1617-306a-404a-b25c-0d215b9528ea', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2026-0005\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-16 21:14:51', '2026-01-16 21:14:51'),
('861766ef-4f6e-49a1-adf2-ce3846b92f83', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":22,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000022\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 20:40:35', '2026-01-16 20:40:35'),
('864260fb-40a7-4f1d-9631-d8f9b697a30d', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":8,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2025-12-28 10:25:24', '2025-12-28 10:08:43', '2025-12-28 10:08:43'),
('869e377d-b2a5-4b19-83d9-43f995869cb5', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":23,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000023\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 20:49:50', '2026-01-16 20:49:50'),
('874d8c24-2c9a-4fd5-80ae-b2a3eef05f35', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":12,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000012\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2025-12-31 21:15:45', '2025-12-31 21:15:45'),
('880e2575-d9a3-47d1-9c41-e868d7941344', 'App\\Notifications\\BookingCancelled', 'App\\Models\\User', 3, '{\"booking_id\":14,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000014\",\"citizen_name\":\"Cristian Mark Angelo Llaneta\",\"reason\":\"Wrong date\",\"message\":\"A booking has been cancelled by the citizen.\"}', NULL, '2025-12-31 21:46:51', '2025-12-31 21:46:51'),
('8a7c12ff-8fd2-4483-a510-4baaeb7f37ae', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":8,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000008\",\"payment_deadline\":\"2025-12-30T17:14:14.000000Z\",\"amount_due\":\"64100.00\",\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours.\"}', '2025-12-28 09:20:16', '2025-12-28 09:14:20', '2025-12-28 09:14:20'),
('8ac3b306-ff86-4a19-89f1-4fb97db0fc23', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":14,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000014\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2025-12-31 21:46:19', '2025-12-31 21:46:19'),
('904a463c-d1ed-4934-8ded-894cd918b848', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000024\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-16 21:03:04', '2026-01-16 21:03:04'),
('9110020f-19d9-444a-87fc-982a807fd5ec', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":23,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000023\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-16 20:49:48', '2026-01-16 20:49:48'),
('93307287-33f7-4b2e-abc5-13b68abfbe80', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":23,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000023\",\"payment_deadline\":\"2026-01-19T05:59:38.000000Z\",\"amount_due\":\"5075.00\",\"payment_slip_id\":18,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-16 21:59:50', '2026-01-16 21:59:50'),
('93580f3d-c3e7-4d0a-afac-6da5dc464fad', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":13,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000013\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-31 21:40:05', '2025-12-31 21:40:05'),
('945da601-8ee7-4cfd-aedf-2503f8592951', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000010\",\"payment_deadline\":\"2026-01-01T07:15:25.000000Z\",\"amount_due\":\"3000.00\",\"payment_slip_id\":8,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2025-12-29 23:15:29', '2025-12-29 23:15:29'),
('96c4cc16-20e0-4482-84ed-84945ce8b234', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000015\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-06 18:44:23', '2026-01-06 18:44:23'),
('99ce9c15-1eb9-401f-a872-2b2bc4f4f344', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000009\",\"payment_deadline\":\"2025-12-30T17:14:59.000000Z\",\"amount_due\":\"5200.00\",\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours.\"}', '2025-12-28 09:20:03', '2025-12-28 09:15:04', '2025-12-28 09:15:04'),
('9a656242-2cb4-4b1e-9a8c-903153e0c343', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000009\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', '2025-12-28 08:59:23', '2025-12-28 08:58:37', '2025-12-28 08:58:37'),
('9b108437-16ab-476d-86bb-9576d4c10e3a', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2026-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2026-01-13 19:22:07', '2026-01-13 19:22:07'),
('9cce030c-0972-4177-8f77-dc91d74c2b3c', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000017\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 03:13:36', '2026-01-13 03:13:36'),
('9de3886e-cb5a-4b50-bc1c-cf8f82b23025', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":20,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000020\",\"payment_deadline\":\"2026-01-16T03:34:12.000000Z\",\"amount_due\":\"4050.00\",\"payment_slip_id\":14,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-13 19:34:16', '2026-01-13 19:34:16'),
('9fd66eb5-9352-468b-8844-ab2bdf4f4138', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":28,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000028\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-02-11 16:14:55', '2026-02-11 16:14:55'),
('a3afbbdd-d894-46d2-8224-13878d6ed43a', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":25,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000025\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-28 09:00:07', '2026-01-28 09:00:07'),
('a4a93c8c-5ee2-4c0f-b508-72b3bc709e4d', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000017\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-13 03:13:32', '2026-01-13 03:13:32'),
('a629e025-8484-445e-afd6-1372210c5276', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":22,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000022\",\"payment_deadline\":\"2026-01-19T05:10:51.000000Z\",\"amount_due\":\"4050.00\",\"payment_slip_id\":17,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-16 21:10:55', '2026-01-16 21:10:55'),
('a79e0680-00c0-4217-aa1b-94d573822b18', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 08:00:33', '2025-12-29 08:00:33'),
('a80cbe69-4d7d-4e0a-9b7b-78af91c58725', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 08:00:31', '2025-12-29 08:00:31'),
('aa7418a5-e788-4190-a1c9-a0e3fc3c22fb', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000010\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-29 23:13:28', '2025-12-29 23:13:28'),
('ab5224ca-9a63-4869-88b1-387013d62546', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 04:47:22', '2025-12-29 04:47:22'),
('ab93a3a4-151d-4659-aa1c-a70df89e8e4e', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 04:47:19', '2025-12-29 04:47:19'),
('ac53e6ae-9d6b-4e44-93f3-f684618b0c5b', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2025-12-29 07:56:23', '2025-12-29 07:56:23'),
('acbd87b5-00b1-4531-98b9-fbb0fb1d9537', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000009\",\"event_date\":\"2026-01-06 13:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', '2025-12-28 10:25:08', '2025-12-28 10:24:04', '2025-12-28 10:24:04'),
('ad8d0e29-0c89-40da-b1de-df0d199f00ee', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":23,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000023\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 20:49:49', '2026-01-16 20:49:49'),
('b01ea5e3-8ac5-45e4-b2aa-c117a0c644b2', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000016\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 03:01:33', '2026-01-13 03:01:33'),
('b2221c0a-3dbf-4135-a460-7f59d33bb1e0', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000016\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-13 03:01:29', '2026-01-13 03:01:29'),
('b3600d37-8805-4195-90f6-6a712d149de9', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 07:56:24', '2025-12-29 07:56:24'),
('b46f6776-1b18-4482-9f43-92c78c0063c9', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000016\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 03:01:31', '2026-01-13 03:01:31'),
('b5a8920e-f953-4d5c-9178-824063435d9f', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":8,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000008\",\"event_date\":\"2026-01-06 08:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', '2025-12-29 23:22:06', '2025-12-29 23:20:54', '2025-12-29 23:20:54'),
('b7d7b069-e638-478f-8ab4-61f5014e2750', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":12,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000012\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2025-12-31 21:15:44', '2025-12-31 21:15:44'),
('b8fac31b-256c-446b-98d2-5a30f0bf6e0d', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":20,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000020\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:33:24', '2026-01-13 19:33:24'),
('b9806fff-cb41-458f-8322-65574bc2dd04', 'App\\Notifications\\ConflictResolvedNotification', 'App\\Models\\User', 5, '{\"message\":\"Your booking conflict has been resolved. Your booking has been refunded successfully.\",\"conflict_id\":1,\"choice\":\"refund\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/citizen\\/transactions\"}', NULL, '2026-01-06 06:45:46', '2026-01-06 06:45:46'),
('bb45a9b1-58b7-459d-9e18-cdd66ed71c8e', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":12,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000012\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-31 21:15:41', '2025-12-31 21:15:41'),
('bbb88fdc-ce9c-4fd5-ac69-8fad3a4f7e42', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000009\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', '2025-12-28 09:20:19', '2025-12-28 08:58:36', '2025-12-28 08:58:36'),
('bcaab334-01e0-4a6e-bfd7-46301ea6dec8', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":27,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000027\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 15:24:06', '2026-02-11 15:24:06'),
('bcc5f075-b539-4079-a461-5887345f5bc3', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000009\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-28 08:58:40', '2025-12-28 08:58:40'),
('bf919a4c-1a36-47e4-8cea-8878098b51b6', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000017\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 03:13:34', '2026-01-13 03:13:34'),
('c1e7f9d7-42ba-4ce6-bd2d-e2d4160ac56f', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000016\",\"payment_deadline\":\"2026-01-15T11:03:05.000000Z\",\"amount_due\":\"4455.00\",\"payment_slip_id\":10,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-13 03:03:10', '2026-01-13 03:03:10'),
('c1f97a6c-18e9-4cc0-a7f0-d98bffb105f4', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000024\",\"payment_deadline\":\"2026-01-19T05:05:42.000000Z\",\"amount_due\":\"3250.00\",\"payment_slip_id\":16,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-16 21:05:47', '2026-01-16 21:05:47'),
('c3197505-e4a1-42f1-9274-93aac04dd30f', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000016\",\"event_date\":\"2026-01-21 08:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', NULL, '2026-01-13 03:07:23', '2026-01-13 03:07:23'),
('c5812bce-9d94-4331-beb3-e8c86137adde', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":29,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000029\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 16:58:46', '2026-02-11 16:58:46'),
('c62af6e3-172a-4df2-b663-ea1b848f184a', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2026-0005\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-16 21:14:54', '2026-01-16 21:14:54'),
('c70b672e-523b-4838-bcae-96027c70c006', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 5, '{\"booking_id\":6,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000006\",\"event_date\":\"2026-01-05 13:00:00\",\"message\":\"Your booking has been confirmed! Get ready for your event.\"}', NULL, '2025-12-29 09:39:34', '2025-12-29 09:39:34'),
('c79b0321-988d-49cf-b183-1ab9387ab84f', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":19,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000019\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-13 19:28:01', '2026-01-13 19:28:01'),
('c9810ba2-33de-48a5-ae73-ed916bb22997', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":14,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000014\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2025-12-31 21:46:21', '2025-12-31 21:46:21');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('cbcff0e0-9085-49b2-82cf-24227749f9c4', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000015\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-06 18:44:27', '2026-01-06 18:44:27'),
('cc115bc3-b91b-4914-902b-569a1ec16e9c', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":14,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000014\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2025-12-31 21:46:22', '2025-12-31 21:46:22'),
('ccbd588d-c2c3-41f5-85a0-3038d4d23e84', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 04:47:23', '2025-12-29 04:47:23'),
('cd4966a7-93ef-4a6a-acd2-2343e6c1d8c7', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":27,\"facility_name\":\"Bulwagan Katipunan\",\"booking_reference\":\"BK000027\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 15:24:04', '2026-02-11 15:24:04'),
('cdb79e60-6cfd-4d97-9258-f40f9a3ef525', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-29 04:41:16', '2025-12-29 04:41:16'),
('cf74cfa5-5caa-4694-b755-1704a10dc46a', 'App\\Notifications\\StaffVerified', 'App\\Models\\User', 5, '{\"booking_id\":19,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000019\",\"payment_deadline\":\"2026-01-16T03:30:28.000000Z\",\"amount_due\":\"4050.00\",\"payment_slip_id\":13,\"message\":\"Your booking has been verified by staff. Please submit payment within 48 hours. Go to Payment Slips (sidebar) to view your payment slip and pay via Cash at CTO or Cashless (GCash, Maya, Bank Transfer).\"}', NULL, '2026-01-13 19:30:42', '2026-01-13 19:30:42'),
('cf83aae7-e6fe-4bbb-96eb-491d3a57e970', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":22,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000022\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-01-16 20:40:31', '2026-01-16 20:40:31'),
('d0918165-4577-42a8-9e2c-e0b995d7e04f', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"or_number\":\"OR-2026-0003\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2026-01-13 03:16:40', '2026-01-13 03:16:40'),
('d09a297c-07ed-475c-9c48-db2f54db3111', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2025-12-28 10:25:28', '2025-12-28 10:08:21', '2025-12-28 10:08:21'),
('d0e32366-bf85-4bfa-9c8c-212cbc39221d', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":20,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000020\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:33:23', '2026-01-13 19:33:23'),
('d1493b71-4891-40db-8879-3bea6a718708', 'App\\Notifications\\PaymentSubmitted', 'App\\Models\\User', 7, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"payment_slip_number\":\"PS-2025-000006\",\"message\":\"Your payment submission has been received and is under review by the treasurer.\"}', '2025-12-28 09:56:45', '2025-12-28 09:56:14', '2025-12-28 09:56:14'),
('d36543d8-60b9-49db-a934-1d13ecd6fd64', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":21,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000021\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:36:17', '2026-01-13 19:36:17'),
('d6b8b3ed-cbe1-44ac-952c-1e985c3c8b46', 'App\\Notifications\\PaymentSubmitted', 'App\\Models\\User', 15, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"payment_slip_number\":\"PS-2025-000006\",\"message\":\"Your payment submission has been received and is under review by the treasurer.\"}', NULL, '2025-12-28 09:56:17', '2025-12-28 09:56:17'),
('d76eb683-f701-4af0-b2db-037dd52e22e0', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":24,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000024\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 21:03:10', '2026-01-16 21:03:10'),
('dbf44928-9f96-458e-a40c-a4478d4e70aa', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2026-0001\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-07 03:23:53', '2026-01-07 03:23:53'),
('df0bd659-cbc8-4f38-b12e-37cc224b7127', 'App\\Notifications\\PaymentSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"payment_slip_number\":\"PS-2025-000006\",\"message\":\"Your payment submission has been received and is under review by the treasurer.\"}', '2025-12-28 09:56:34', '2025-12-28 09:56:12', '2025-12-28 09:56:12'),
('e2871e80-a5b4-457e-8903-7919709fb862', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":29,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000029\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2026-02-11 16:58:45', '2026-02-11 16:58:45'),
('e2a8a03d-baab-4433-a0d5-69457d3e4ad8', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":16,\"facility_name\":\"Buena Park\",\"or_number\":\"OR-2026-0002\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 03:06:21', '2026-01-13 03:06:21'),
('e314e548-cffc-499c-958c-ea09ddc27cd5', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":19,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000019\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:28:05', '2026-01-13 19:28:05'),
('e4330822-c924-4fba-8fad-e742bcf45ded', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2025-12-28 10:25:19', '2025-12-28 10:14:46', '2025-12-28 10:14:46'),
('e7f3fe91-466f-43a9-9c7f-8bb2a4b180c9', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":15,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000015\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-06 18:44:25', '2026-01-06 18:44:25'),
('e864a79b-0be9-4ed4-888b-945aa04b84f6', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 14, '{\"booking_id\":29,\"facility_name\":\"Sports Complex\",\"booking_reference\":\"BK000029\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-02-11 16:58:50', '2026-02-11 16:58:50'),
('eb83d703-2f52-4579-b730-5e50fb40dbfc', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":17,\"facility_name\":\"Bulwagan Katipunan\",\"or_number\":\"OR-2026-0003\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 03:16:42', '2026-01-13 03:16:42'),
('ecfd2312-0484-41a7-add5-bbd645aeec34', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 9, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2025-12-28 10:23:08', '2025-12-28 10:23:08'),
('ee3bff6a-cfdf-4410-a675-99dd716c7fd2', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 10, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000009\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', NULL, '2025-12-28 08:58:38', '2025-12-28 08:58:38'),
('f01d66b8-6181-486b-8878-ef99f152c3bc', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 13, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2026-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', NULL, '2026-01-13 19:22:10', '2026-01-13 19:22:10'),
('f04ee4ba-96b5-4427-9473-fd1677f7867f', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2025-12-29 07:50:32', '2025-12-29 07:50:32'),
('f440660c-30c3-4bda-8ec8-6239dc128a29', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":22,\"facility_name\":\"Buena Park\",\"booking_reference\":\"BK000022\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-16 20:40:32', '2026-01-16 20:40:32'),
('f4a6a68b-4381-41b1-99f3-208135fd748f', 'App\\Notifications\\PaymentSubmitted', 'App\\Models\\User', 5, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"payment_slip_number\":\"PS-2025-000006\",\"message\":\"Your payment submission has been received and is under review by the treasurer.\"}', '2025-12-28 09:44:19', '2025-12-28 09:43:54', '2025-12-28 09:43:54'),
('f5198978-32bf-4e51-976e-9ff838c234a9', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":10,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000010\",\"message\":\"Your booking request has been submitted and is awaiting staff verification.\"}', '2025-12-29 23:14:27', '2025-12-29 23:13:27', '2025-12-29 23:13:27'),
('f5ed7330-4d85-4e49-9738-a764ecb85f9f', 'App\\Notifications\\PaymentVerified', 'App\\Models\\User', 2, '{\"booking_id\":9,\"facility_name\":\"Pacquiao Court\",\"or_number\":\"OR-2025-0004\",\"message\":\"Your payment has been verified and Official Receipt has been issued.\"}', '2026-01-27 05:15:01', '2025-12-29 04:47:20', '2025-12-29 04:47:20'),
('fae4f1d9-4e07-47fd-8082-91f9330ecf09', 'App\\Notifications\\BookingSubmitted', 'App\\Models\\User', 3, '{\"booking_id\":18,\"facility_name\":\"Pacquiao Court\",\"booking_reference\":\"BK000018\",\"message\":\"New booking request received. Please review and verify the booking details.\"}', NULL, '2026-01-13 19:16:51', '2026-01-13 19:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `notification_campaigns`
--

CREATE TABLE `notification_campaigns` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipients` json NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `sent_count` int NOT NULL DEFAULT '0',
  `failed_count` int NOT NULL DEFAULT '0',
  `delivery_details` json DEFAULT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `sent_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `campaign_id` bigint UNSIGNED NOT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` text COLLATE utf8mb4_unicode_ci,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(1, 'view_users', 'View user list and details'),
(2, 'edit_users', 'Edit user information'),
(3, 'delete_users', 'Delete users'),
(4, 'manage_roles', 'Manage roles and assignments'),
(5, 'view_audit_logs', 'View audit logs'),
(6, 'reset_passwords', 'Reset user passwords'),
(7, 'access_facilities', 'Access facilities subsystem');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` bigint UNSIGNED NOT NULL,
  `region_id` bigint UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `psgc_code` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `region_id`, `code`, `name`, `psgc_code`, `created_at`, `updated_at`) VALUES
(1, 1, 'MNL', 'Metro Manila', 133900000, NULL, NULL),
(2, 2, 'ABR', 'Abra', 140100000, NULL, NULL),
(3, 2, 'APN', 'Apayao', 141100000, NULL, NULL),
(4, 2, 'BEN', 'Benguet', 141400000, NULL, NULL),
(5, 2, 'IFU', 'Ifugao', 142700000, NULL, NULL),
(6, 2, 'KAL', 'Kalinga', 143200000, NULL, NULL),
(7, 2, 'MOU', 'Mountain Province', 144400000, NULL, NULL),
(8, 3, 'ILN', 'Ilocos Norte', 12800000, NULL, NULL),
(9, 3, 'ILS', 'Ilocos Sur', 12900000, NULL, NULL),
(10, 3, 'LUN', 'La Union', 13300000, NULL, NULL),
(11, 3, 'PAN', 'Pangasinan', 15500000, NULL, NULL),
(12, 4, 'BTN', 'Batanes', 20900000, NULL, NULL),
(13, 4, 'CAG', 'Cagayan', 21500000, NULL, NULL),
(14, 4, 'ISA', 'Isabela', 23100000, NULL, NULL),
(15, 4, 'NUV', 'Nueva Vizcaya', 25000000, NULL, NULL),
(16, 4, 'QUI', 'Quirino', 25700000, NULL, NULL),
(17, 5, 'AUR', 'Aurora', 30800000, NULL, NULL),
(18, 5, 'BAN', 'Bataan', 30900000, NULL, NULL),
(19, 5, 'BUL', 'Bulacan', 31400000, NULL, NULL),
(20, 5, 'NUE', 'Nueva Ecija', 34900000, NULL, NULL),
(21, 5, 'PAM', 'Pampanga', 35400000, NULL, NULL),
(22, 5, 'TAR', 'Tarlac', 36900000, NULL, NULL),
(23, 5, 'ZMB', 'Zambales', 37100000, NULL, NULL),
(24, 6, 'BTG', 'Batangas', 41000000, NULL, NULL),
(25, 6, 'CAV', 'Cavite', 42100000, NULL, NULL),
(26, 6, 'LAG', 'Laguna', 43400000, NULL, NULL),
(27, 6, 'QUE', 'Quezon', 45600000, NULL, NULL),
(28, 6, 'RIZ', 'Rizal', 45800000, NULL, NULL),
(29, 7, 'MAD', 'Marinduque', 174000000, NULL, NULL),
(30, 7, 'MDC', 'Occidental Mindoro', 175100000, NULL, NULL),
(31, 7, 'MDR', 'Oriental Mindoro', 175200000, NULL, NULL),
(32, 7, 'PLW', 'Palawan', 175300000, NULL, NULL),
(33, 7, 'ROM', 'Romblon', 175900000, NULL, NULL),
(34, 8, 'ALB', 'Albay', 50500000, NULL, NULL),
(35, 8, 'CAN', 'Camarines Norte', 51600000, NULL, NULL),
(36, 8, 'CAS', 'Camarines Sur', 51700000, NULL, NULL),
(37, 8, 'CAT', 'Catanduanes', 52000000, NULL, NULL),
(38, 8, 'MAS', 'Masbate', 54100000, NULL, NULL),
(39, 8, 'SOR', 'Sorsogon', 56200000, NULL, NULL),
(40, 9, 'AKL', 'Aklan', 60400000, NULL, NULL),
(41, 9, 'ANT', 'Antique', 60600000, NULL, NULL),
(42, 9, 'CAP', 'Capiz', 61900000, NULL, NULL),
(43, 9, 'GUI', 'Guimaras', 67900000, NULL, NULL),
(44, 9, 'ILI', 'Iloilo', 63000000, NULL, NULL),
(45, 9, 'NEC', 'Negros Occidental', 64500000, NULL, NULL),
(46, 10, 'BOH', 'Bohol', 71200000, NULL, NULL),
(47, 10, 'CEB', 'Cebu', 72200000, NULL, NULL),
(48, 10, 'NER', 'Negros Oriental', 74600000, NULL, NULL),
(49, 10, 'SIG', 'Siquijor', 76100000, NULL, NULL),
(50, 11, 'BIL', 'Biliran', 87800000, NULL, NULL),
(51, 11, 'EAS', 'Eastern Samar', 82600000, NULL, NULL),
(52, 11, 'LEY', 'Leyte', 83700000, NULL, NULL),
(53, 11, 'NSA', 'Northern Samar', 84800000, NULL, NULL),
(54, 11, 'WSA', 'Western Samar', 86000000, NULL, NULL),
(55, 11, 'SLE', 'Southern Leyte', 86400000, NULL, NULL),
(56, 12, 'ZAN', 'Zamboanga del Norte', 97200000, NULL, NULL),
(57, 12, 'ZAS', 'Zamboanga del Sur', 97300000, NULL, NULL),
(58, 12, 'ZSI', 'Zamboanga Sibugay', 98300000, NULL, NULL),
(59, 13, 'BUK', 'Bukidnon', 101300000, NULL, NULL),
(60, 13, 'CAM', 'Camiguin', 101800000, NULL, NULL),
(61, 13, 'LAN', 'Lanao del Norte', 103500000, NULL, NULL),
(62, 13, 'MSC', 'Misamis Occidental', 104200000, NULL, NULL),
(63, 13, 'MSR', 'Misamis Oriental', 104300000, NULL, NULL),
(64, 14, 'COM', 'Davao de Oro', 112300000, NULL, NULL),
(65, 14, 'DAV', 'Davao del Norte', 112400000, NULL, NULL),
(66, 14, 'DAS', 'Davao del Sur', 112500000, NULL, NULL),
(67, 14, 'DAO', 'Davao Oriental', 112600000, NULL, NULL),
(68, 14, 'DVO', 'Davao Occidental', 118200000, NULL, NULL),
(69, 15, 'NCO', 'Cotabato', 124700000, NULL, NULL),
(70, 15, 'SAR', 'Sarangani', 126500000, NULL, NULL),
(71, 15, 'SCO', 'South Cotabato', 126300000, NULL, NULL),
(72, 15, 'SUK', 'Sultan Kudarat', 128000000, NULL, NULL),
(73, 16, 'AGN', 'Agusan del Norte', 160200000, NULL, NULL),
(74, 16, 'AGS', 'Agusan del Sur', 160300000, NULL, NULL),
(75, 16, 'DIN', 'Dinagat Islands', 168500000, NULL, NULL),
(76, 16, 'SUN', 'Surigao del Norte', 166700000, NULL, NULL),
(77, 16, 'SUR', 'Surigao del Sur', 166800000, NULL, NULL),
(78, 17, 'BAS', 'Basilan', 150700000, NULL, NULL),
(79, 17, 'LAS', 'Lanao del Sur', 153600000, NULL, NULL),
(80, 17, 'MAG', 'Maguindanao', 153800000, NULL, NULL),
(81, 17, 'SLU', 'Sulu', 156600000, NULL, NULL),
(82, 17, 'TAW', 'Tawi-Tawi', 157000000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `long_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `psgc_code` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `code`, `name`, `long_name`, `psgc_code`, `created_at`, `updated_at`) VALUES
(1, 'NCR', 'National Capital Region', 'National Capital Region (NCR)', 130000000, NULL, NULL),
(2, 'CAR', 'Cordillera Administrative Region', 'Cordillera Administrative Region (CAR)', 140000000, NULL, NULL),
(3, 'I', 'Region I', 'Ilocos Region (Region I)', 10000000, NULL, NULL),
(4, 'II', 'Region II', 'Cagayan Valley (Region II)', 20000000, NULL, NULL),
(5, 'III', 'Region III', 'Central Luzon (Region III)', 30000000, NULL, NULL),
(6, 'IV-A', 'Region IV-A', 'CALABARZON (Region IV-A)', 40000000, NULL, NULL),
(7, 'IV-B', 'Region IV-B', 'MIMAROPA (Region IV-B)', 170000000, NULL, NULL),
(8, 'V', 'Region V', 'Bicol Region (Region V)', 50000000, NULL, NULL),
(9, 'VI', 'Region VI', 'Western Visayas (Region VI)', 60000000, NULL, NULL),
(10, 'VII', 'Region VII', 'Central Visayas (Region VII)', 70000000, NULL, NULL),
(11, 'VIII', 'Region VIII', 'Eastern Visayas (Region VIII)', 80000000, NULL, NULL),
(12, 'IX', 'Region IX', 'Zamboanga Peninsula (Region IX)', 90000000, NULL, NULL),
(13, 'X', 'Region X', 'Northern Mindanao (Region X)', 100000000, NULL, NULL),
(14, 'XI', 'Region XI', 'Davao Region (Region XI)', 110000000, NULL, NULL),
(15, 'XII', 'Region XII', 'SOCCSKSARGEN (Region XII)', 120000000, NULL, NULL),
(16, 'XIII', 'Region XIII', 'Caraga (Region XIII)', 160000000, NULL, NULL),
(17, 'BARMM', 'BARMM', 'Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)', 150000000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `road_assistance_requests`
--

CREATE TABLE `road_assistance_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `requester_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_description` text COLLATE utf8mb4_unicode_ci,
  `event_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_date` date NOT NULL,
  `event_start_time` time DEFAULT NULL,
  `event_end_time` time DEFAULT NULL,
  `expected_attendees` int DEFAULT NULL,
  `affected_roads` text COLLATE utf8mb4_unicode_ci,
  `assistance_type` text COLLATE utf8mb4_unicode_ci,
  `special_requirements` text COLLATE utf8mb4_unicode_ci,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `response_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(2, 'citizen'),
(1, 'super admin');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('qBr2jfIzWQhugrUeIDcnA4wWBEX1QOanuKuJRgSo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3pCZlNncUk1Mk5WZXByQmxGWXpqVEdJUkV4UGdSVGdLRWowREV4OSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1770890951);

-- --------------------------------------------------------

--
-- Table structure for table `subsystems`
--

CREATE TABLE `subsystems` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subsystems`
--

INSERT INTO `subsystems` (`id`, `name`) VALUES
(5, 'Community Infrastructure Maintenance Management'),
(10, 'Energy Efficiency and Conservative Management'),
(8, 'Housing and Resettlement Management'),
(1, 'Infrastructure Project Management'),
(7, 'Land Registration and Titling System'),
(4, 'Public Facilities Reservation System'),
(9, 'Renewable Energy Project Management'),
(3, 'Road and Transportation Infrastructure Monitoring'),
(6, 'Urban Planning and Development'),
(2, 'Utility Billing and Monitoring Management (Water, Electricity)');

-- --------------------------------------------------------

--
-- Table structure for table `subsystem_roles`
--

CREATE TABLE `subsystem_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `subsystem_id` bigint UNSIGNED NOT NULL,
  `role_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subsystem_roles`
--

INSERT INTO `subsystem_roles` (`id`, `subsystem_id`, `role_name`, `description`) VALUES
(1, 4, 'Admin', 'Facilities system administrator'),
(2, 4, 'Facility Manager', 'Manage facility operations'),
(3, 4, 'Reservations Staff', 'Handle reservations'),
(4, 4, 'Applicant', 'Facility reservation applicant'),
(5, 4, 'Treasurer', 'City Treasurer\'s Office - Payment verification and receipt generation'),
(6, 4, 'CBD Staff', 'City Budget Department - Financial reporting and budget oversight');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `announcement_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `description` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `category`, `key`, `value`, `announcement_image`, `type`, `description`, `group`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'booking', 'booking.max_advance_days', '90', NULL, 'integer', 'Maximum days in advance a citizen can book a facility', 'Booking Rules', 1, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(2, 'booking', 'booking.min_advance_hours', '24', NULL, 'integer', 'Minimum hours in advance required for booking', 'Booking Rules', 1, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(3, 'booking', 'booking.cancellation_deadline_hours', '48', NULL, 'integer', 'Hours before event that cancellation is allowed', 'Booking Rules', 1, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(4, 'payment', 'payment.deadline_hours', '48', NULL, 'integer', 'Hours allowed for payment after booking approval', 'Payment', 0, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(5, 'discount', 'discount.resident_percentage', '40', NULL, 'float', 'Discount percentage for city residents', 'Discounts', 1, '2026-01-16 21:39:26', '2026-01-16 21:54:04'),
(6, 'discount', 'discount.senior_percentage', '20', NULL, 'float', 'Discount percentage for senior citizens', 'Discounts', 1, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(7, 'discount', 'discount.pwd_percentage', '20', NULL, 'float', 'Discount percentage for persons with disabilities', 'Discounts', 1, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(8, 'discount', 'discount.student_percentage', '20', NULL, 'float', 'Discount percentage for students', 'Discounts', 1, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(9, 'security', 'security.session_timeout_minutes', '120', NULL, 'integer', 'Minutes of inactivity before automatic logout', 'Security', 0, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(10, 'security', 'security.otp_expiration_minutes', '5', NULL, 'integer', 'Minutes before OTP code expires', 'Security', 0, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(11, 'security', 'security.max_login_attempts', '5', NULL, 'integer', 'Maximum failed login attempts before account lockout', 'Security', 0, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(12, 'notification', 'notification.email_enabled', 'true', NULL, 'boolean', 'Enable email notifications', 'Notifications', 0, '2026-01-16 21:39:26', '2026-01-16 21:58:11'),
(13, 'notification', 'notification.sms_enabled', 'false', NULL, 'boolean', 'Enable SMS notifications', 'Notifications', 0, '2026-01-16 21:39:26', '2026-01-16 21:54:04'),
(14, 'system', 'system.maintenance_mode', 'false', NULL, 'boolean', 'Enable maintenance mode (blocks citizen access)', 'System', 1, '2026-01-16 21:39:26', '2026-01-16 21:54:04'),
(15, 'system', 'system.maintenance_message', 'System is currently under maintenance. Please check back later.', NULL, 'string', 'Message shown during maintenance mode', 'System', 1, '2026-01-16 21:39:26', '2026-01-16 21:39:26'),
(16, 'system', 'system.announcement', NULL, NULL, 'string', 'System-wide announcement banner text', 'System', 1, '2026-01-16 21:39:26', '2026-01-17 02:07:50'),
(17, 'communication', 'email_smtp_host', '', NULL, 'string', 'SMTP server host', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(18, 'communication', 'email_smtp_port', '587', NULL, 'string', 'SMTP server port', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(19, 'communication', 'email_smtp_username', '', NULL, 'string', 'SMTP username', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(20, 'communication', 'email_smtp_password', '', NULL, 'encrypted', 'SMTP password (encrypted)', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(21, 'communication', 'email_smtp_encryption', 'tls', NULL, 'string', 'SMTP encryption (tls/ssl)', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(22, 'communication', 'email_from_address', 'noreply@lgu.gov.ph', NULL, 'string', 'Default from email address', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(23, 'communication', 'email_from_name', 'LGU Facility Reservation System', NULL, 'string', 'Default from name', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(24, 'communication', 'email_signature', '<p>Best regards,<br>LGU Facility Reservation Team</p>', NULL, 'html', 'Email signature HTML', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(25, 'communication', 'sms_provider', 'semaphore', NULL, 'string', 'SMS gateway provider (semaphore/twilio/vonage)', 'sms', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(26, 'communication', 'sms_api_key', '', NULL, 'encrypted', 'SMS API key (encrypted)', 'sms', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(27, 'communication', 'sms_sender_name', 'LGU', NULL, 'string', 'SMS sender name', 'sms', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(28, 'communication', 'sms_enabled', '0', NULL, 'boolean', 'Enable/disable SMS notifications', 'sms', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06'),
(29, 'communication', 'email_enabled', '1', NULL, 'boolean', 'Enable/disable email notifications', 'email', 0, '2026-01-20 09:06:06', '2026-01-20 09:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `trusted_devices`
--

CREATE TABLE `trusted_devices` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `device_fingerprint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_name` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trusted_at` timestamp NOT NULL,
  `last_used_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trusted_devices`
--

INSERT INTO `trusted_devices` (`id`, `user_id`, `device_fingerprint`, `device_name`, `ip_address`, `country`, `city`, `trusted_at`, `last_used_at`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 5, 'e4bbe16ef50608eab5e9a6d0d5beab9a2bec06ef436cdd9cc84decd57e3d1fe5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-20 09:56:15', '2026-02-11 15:20:07', '2026-01-20 09:56:15', '2026-01-20 09:56:15', NULL, NULL),
(2, 5, 'eb7306f5a0e3a21b4a134afc02a82b1979274cdd21d2cd92cb3aeb8cedb9b09b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '136.158.7.63', 'Philippines', 'Quezon City', '2026-02-11 16:09:48', '2026-02-11 16:09:48', '2026-02-11 16:09:48', '2026-02-11 16:09:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_pin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `profile_visibility` enum('public','private') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'private',
  `show_reviews_publicly` tinyint(1) NOT NULL DEFAULT '1',
  `show_booking_count` tinyint(1) NOT NULL DEFAULT '0',
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `subsystem_id` bigint UNSIGNED DEFAULT NULL,
  `subsystem_role_id` bigint UNSIGNED DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `mobile_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `civil_status` enum('single','married','divorced','widowed','separated') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Filipino',
  `region_id` bigint UNSIGNED DEFAULT NULL,
  `province_id` bigint UNSIGNED DEFAULT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `district_id` bigint UNSIGNED DEFAULT NULL,
  `barangay_id` bigint UNSIGNED DEFAULT NULL,
  `current_address` text COLLATE utf8mb4_unicode_ci,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_id_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_id_front_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_front_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_id_back_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_back_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selfie_with_id_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selfie_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `face_match_score` decimal(5,2) DEFAULT NULL COMMENT 'Face similarity: ID vs Selfie',
  `id_verification_status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `id_verified_at` timestamp NULL DEFAULT NULL,
  `id_verified_by` bigint UNSIGNED DEFAULT NULL,
  `id_verification_notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `profile_incomplete` tinyint(1) NOT NULL DEFAULT '0',
  `email_verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_authenticity_score` decimal(5,2) DEFAULT NULL COMMENT 'ID authenticity from Teachable Machine',
  `liveness_score` decimal(5,2) DEFAULT NULL COMMENT 'Selfie liveness detection',
  `ai_verification_data` json DEFAULT NULL COMMENT 'Full AI analysis results',
  `ai_verification_status` enum('pending','passed','failed','manual_review') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `ai_verification_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'AI rejection reasons or notes',
  `reviewed_by` bigint UNSIGNED DEFAULT NULL COMMENT 'Admin user ID who reviewed',
  `manual_review_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manual_review_notes` text COLLATE utf8mb4_unicode_ci,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `is_duplicate_id_detected` tinyint(1) NOT NULL DEFAULT '0',
  `duplicate_of_user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Original user ID if duplicate detected',
  `is_synced` tinyint(1) DEFAULT '0',
  `last_synced_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `google_id`, `profile_photo_path`, `full_name`, `password_hash`, `two_factor_pin`, `two_factor_enabled`, `profile_visibility`, `show_reviews_publicly`, `show_booking_count`, `role_id`, `subsystem_id`, `subsystem_role_id`, `birthdate`, `mobile_number`, `gender`, `civil_status`, `nationality`, `region_id`, `province_id`, `city_id`, `district_id`, `barangay_id`, `current_address`, `zip_code`, `valid_id_type`, `valid_id_front_image`, `id_front_hash`, `valid_id_back_image`, `id_back_hash`, `selfie_with_id_image`, `selfie_hash`, `face_match_score`, `id_verification_status`, `id_verified_at`, `id_verified_by`, `id_verification_notes`, `status`, `last_login`, `is_email_verified`, `profile_incomplete`, `email_verification_token`, `email_verified_at`, `created_at`, `updated_at`, `id_authenticity_score`, `liveness_score`, `ai_verification_data`, `ai_verification_status`, `ai_verification_notes`, `reviewed_by`, `manual_review_status`, `manual_review_notes`, `reviewed_at`, `is_duplicate_id_detected`, `duplicate_of_user_id`, `is_synced`, `last_synced_at`) VALUES
(1, 'superadmin', 'jhonrey.manejo18@gmail.com', NULL, NULL, 'Jhon Rey Manejo', '$2y$12$0PmXrlGvyXPJKEYR3gaGB.YvZtVxkRDlJPv1gL17JD0Lb6GzrvUkC', NULL, 0, 'private', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-14 18:26:10', '2025-12-14 18:26:10', '2025-12-14 18:26:10', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(2, 'admin', 'llanetacristianpastoril@gmail.com', NULL, NULL, 'Llaneta Cristian Pastoril', '$2y$12$9nhFiUWu/WsWbU31jcQVCO37zSE3Etq8iS7Aa9Y9NA/q4WSLHbZ1y', NULL, 0, 'private', 1, 0, NULL, 4, 1, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-14 18:26:11', '2025-12-14 18:26:11', '2026-01-23 23:03:28', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(3, 'staff', 'lcristianmarkangelo@gmail.com', NULL, NULL, 'Cristian Mark Angelo', '$2y$12$lp0iubQwVrAT0zAQKzqHjOEkH4OEKcrTDn3gdopjppzhgD/zPeDQC', NULL, 0, 'private', 1, 0, NULL, 4, 3, NULL, '9934080709', NULL, NULL, 'Filipino', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-14 18:26:11', '2025-12-14 18:26:11', '2026-01-27 04:15:15', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(4, 'citizen', 'citizen@test.com', NULL, NULL, 'Test Citizen', '$2y$12$fh7P8Kr4G2IxGc8v14z6pObnNbvMDlZxFjhuIElZMzbigstasu3AW', NULL, 0, 'private', 1, 0, NULL, 4, 4, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-14 18:47:26', '2025-12-14 18:47:26', '2025-12-14 18:47:26', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(5, 'Cristian', '1hawkeye101010101@gmail.com', NULL, NULL, 'Cristian Mark Angelo Llaneta', '$2y$12$N1siHBaCkEm26e03VwP9fufg3n0WFkD94UjF0IyTVV32hLJf2qH7m', '$2y$12$8RXdAt2RgPiW2Qu/7doruuZBF/2ZUs.6vr9XZhEvQKJGnbeh04ud6', 1, 'private', 1, 0, NULL, NULL, NULL, '2003-12-06', '09515691003', 'male', 'single', 'Filipino', 1, 1, 2, 12, 52, 'Area 5a Naval Street', '1116', 'School ID', 'uploads/ids/1765768768_front_05e26754-ac52-4928-beab-63c5f48927ce.jpg', NULL, 'uploads/ids/1765768768_back_7f46a5cf-4b30-4c3d-87c4-bac97d497192.jpg', NULL, 'uploads/ids/1765768768_selfie_6f85cb43-3a4b-4471-a1f0-93f04a97a929.jpg', NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, '58326832cfe5b564a67bc1834e12cd8b', '2025-12-14 19:22:21', '2025-12-14 19:19:29', '2026-01-27 07:20:39', NULL, NULL, NULL, 'failed', '[\"cURL error 77: error setting certificate file: D:\\\\laragon6\\\\etc\\\\ssl\\\\cacert.pem (see https:\\/\\/curl.haxx.se\\/libcurl\\/c\\/libcurl-errors.html) for https:\\/\\/api-us.faceplusplus.com\\/facepp\\/v3\\/compare\"]', NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(6, 'staff.user', 'staff@test.com', NULL, NULL, 'Staff Test User', '$2y$12$vWuRiJkMMGg2oOQWPbsEWOJpeLBGJRcQ0pap9/eQlLPTSi2IKzKkq', NULL, 0, 'private', 1, 0, 3, NULL, NULL, '1990-01-01', '09171234567', 'male', NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'verified', '2025-12-16 05:05:53', NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-16 05:05:53', '2025-12-16 05:05:53', '2025-12-16 05:05:53', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(7, 'treasurer', 'llanetacrisanto4@gmail.com', NULL, NULL, 'Llaneta Crisanto', '$2y$12$BfKqC0c3c8GhCohK5Bub2uk1QDnrystM/1fI2cNPv2VFVSAwRm6Ea', NULL, 0, 'private', 1, 0, NULL, 4, 5, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 02:14:23', '2025-12-25 02:14:23', '2025-12-25 02:14:23', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(8, 'superadmin', 'jhonrey.manejo18@gmail.com', NULL, NULL, 'Jhon Rey Manejo', '$2y$12$Eb8//zEWjD2ssNywsjVoJuWfzZaFUvLa4SDof4J0UecAlhGzQIv.i', NULL, 0, 'private', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 09:17:51', '2025-12-25 09:17:51', '2025-12-25 09:17:51', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(9, 'admin', 'llanetacristianpastoril@gmail.com', NULL, NULL, 'Llaneta Cristian Pastoril', '$2y$12$Uizx4j8bEdihSLS82k8F0uaKVN45Duaqdvq7AKpGNopsyCUd6TTO.', NULL, 0, 'private', 1, 0, NULL, 4, 1, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 09:17:54', '2025-12-25 09:17:54', '2025-12-25 09:17:54', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(10, 'staff', 'lcristianmarkangelo@gmail.com', NULL, NULL, 'Cristian Mark Angelo', '$2y$12$tX5cS481uoHXflIEGdUpu.6scptEJruj6uD0.srQK2XNV3/c5A9QW', NULL, 0, 'private', 1, 0, NULL, 4, 3, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 09:17:54', '2025-12-25 09:17:54', '2025-12-25 09:17:54', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(11, 'treasurer', 'llanetacrisanto4@gmail.com', NULL, NULL, 'Llaneta Crisanto', '$2y$12$B8dWKmo2dsuW0n.x7XMiaOYGw3LacUItIUvGVBY9EBd1x11635lFO', NULL, 0, 'private', 1, 0, NULL, 4, 5, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 09:17:55', '2025-12-25 09:17:55', '2025-12-25 09:17:55', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(12, 'superadmin', 'jhonrey.manejo18@gmail.com', NULL, NULL, 'Jhon Rey Manejo', '$2y$12$R3rQ1jAjI0lmdVTFHn7Dx.dRx0hZ/1mqm2n.SBb8NSlKciYLwnwlW', NULL, 0, 'private', 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 09:21:47', '2025-12-25 09:21:47', '2025-12-25 09:21:47', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(13, 'admin', 'llanetacristianpastoril@gmail.com', NULL, NULL, 'Llaneta Cristian Pastoril', '$2y$12$bjX4ZlITB6omxke427oKtOSteUO0Np.icTLXhHPv/62BBuVO8NsWC', NULL, 0, 'private', 1, 0, NULL, 4, 1, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 09:21:48', '2025-12-25 09:21:48', '2025-12-25 09:21:48', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(14, 'staff', 'lcristianmarkangelo@gmail.com', NULL, NULL, 'Cristian Mark Angelo', '$2y$12$Rg522QC3vte7FdoZ14ijVOJA2P5yIh3ek5.dEm6vRfR4ZLwUhyXgC', NULL, 0, 'private', 1, 0, NULL, 4, 3, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 09:21:48', '2025-12-25 09:21:48', '2025-12-25 09:21:48', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(15, 'treasurer', 'llanetacrisanto4@gmail.com', NULL, NULL, 'Llaneta Crisanto', '$2y$12$mJ2dNun.2uNhgXmPZf6w6e6dBwMiCARnBUZKzEdiRsaPOriqGQC7.', NULL, 0, 'private', 1, 0, NULL, 4, 5, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-25 09:21:49', '2025-12-25 09:21:49', '2025-12-25 09:21:49', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(16, 'llanetacristianpastoril096@gmail.com', 'llanetacristianpastoril096@gmail.com', NULL, NULL, 'City Budget Department', '$2y$12$cO4t5biMw2/sCijkwpgvhuDqnJfCIqK3U1tmwYJ615GJGrPesVzqi', NULL, 0, 'private', 1, 0, NULL, 4, 6, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 0, NULL, '2025-12-29 08:07:35', '2025-12-29 08:07:35', '2025-12-29 08:07:35', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20'),
(17, 'lazycrazylazy', 'lazysloths006@gmail.com', '108654160794025556367', NULL, 'Lazy crazy Lazy', '$2y$12$eoGgG1rZi5x4it7KaY9f3u0mxvkmnWl1BRqdywODioDCQ7AvHspxG', NULL, 0, 'private', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, 'active', NULL, 1, 1, NULL, NULL, '2026-01-25 22:31:03', '2026-01-25 22:31:03', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-02-13 20:31:20');

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `facility_id` bigint UNSIGNED NOT NULL,
  `notify_updates` tinyint(1) NOT NULL DEFAULT '1',
  `notify_availability` tinyint(1) NOT NULL DEFAULT '1',
  `notify_price_changes` tinyint(1) NOT NULL DEFAULT '0',
  `favorited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_favorites`
--

INSERT INTO `user_favorites` (`id`, `user_id`, `facility_id`, `notify_updates`, `notify_availability`, `notify_price_changes`, `favorited_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 5, 11, 1, 1, 0, '2026-01-23 22:15:34', '2026-01-23 21:30:30', '2026-01-23 22:15:34', NULL),
(6, 5, 16, 1, 1, 0, '2026-01-23 22:12:08', '2026-01-23 22:12:08', '2026-01-23 22:12:20', '2026-01-23 22:12:20'),
(7, 5, 12, 1, 1, 0, '2026-01-23 22:16:14', '2026-01-23 22:16:14', '2026-01-23 22:16:14', NULL),
(8, 5, 13, 1, 1, 0, '2026-01-24 06:37:29', '2026-01-24 06:37:29', '2026-01-24 06:37:38', '2026-01-24 06:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_otps`
--

CREATE TABLE `user_otps` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_otps`
--

INSERT INTO `user_otps` (`id`, `user_id`, `otp_code`, `expires_at`, `used`, `created_at`) VALUES
(1, '2', '687670', '2025-12-14 18:30:41', 1, '2025-12-14 18:29:41'),
(2, '2', '634472', '2025-12-14 18:31:50', 1, '2025-12-14 18:30:50'),
(3, '2', '119591', '2025-12-14 18:39:26', 1, '2025-12-14 18:38:26'),
(4, '2', '362712', '2025-12-14 18:43:21', 1, '2025-12-14 18:42:21'),
(5, '2', '736558', '2025-12-14 18:44:41', 1, '2025-12-14 18:43:41'),
(6, '5', '189453', '2025-12-14 19:20:29', 1, '2025-12-14 19:19:29'),
(7, '5', '213732', '2025-12-14 19:22:54', 1, '2025-12-14 19:21:54'),
(8, '5', '640030', '2025-12-14 19:23:43', 1, '2025-12-14 19:22:43'),
(9, '5', '658008', '2025-12-14 19:26:53', 1, '2025-12-14 19:25:53'),
(10, '5', '564182', '2025-12-14 19:35:43', 1, '2025-12-14 19:34:43'),
(11, '5', '251342', '2025-12-14 19:39:26', 1, '2025-12-14 19:38:26'),
(12, '5', '540513', '2025-12-14 19:50:18', 1, '2025-12-14 19:49:18'),
(13, '5', '844049', '2025-12-14 21:04:25', 1, '2025-12-14 21:03:25'),
(14, '5', '560887', '2025-12-16 02:10:23', 1, '2025-12-16 02:09:23'),
(15, '5', '504955', '2025-12-16 02:29:43', 1, '2025-12-16 02:28:43'),
(16, '5', '718103', '2025-12-16 02:55:02', 1, '2025-12-16 02:54:02'),
(17, '5', '768179', '2025-12-16 03:06:55', 1, '2025-12-16 03:05:55'),
(18, '5', '512941', '2025-12-16 03:08:17', 1, '2025-12-16 03:07:17'),
(19, '5', '676865', '2025-12-16 03:08:25', 1, '2025-12-16 03:07:25'),
(20, '5', '661014', '2025-12-16 03:19:15', 1, '2025-12-16 03:18:15'),
(21, '5', '418350', '2025-12-16 03:20:25', 1, '2025-12-16 03:19:25'),
(22, '5', '191700', '2025-12-16 03:21:04', 1, '2025-12-16 03:20:04'),
(23, '3', '886790', '2025-12-16 04:47:36', 1, '2025-12-16 04:46:36'),
(24, '3', '123875', '2025-12-16 05:30:34', 1, '2025-12-16 05:29:34'),
(25, '3', '725591', '2025-12-16 05:31:30', 1, '2025-12-16 05:30:30'),
(26, '3', '962964', '2025-12-16 05:31:49', 1, '2025-12-16 05:30:49'),
(27, '3', '775746', '2025-12-16 07:45:48', 1, '2025-12-16 07:44:48'),
(28, '3', '731028', '2025-12-17 07:36:42', 1, '2025-12-17 07:35:42'),
(29, '5', '793355', '2025-12-17 08:27:26', 1, '2025-12-17 08:26:26'),
(30, '3', '295177', '2025-12-18 21:19:45', 1, '2025-12-18 21:18:45'),
(31, '5', '656390', '2025-12-18 21:22:38', 1, '2025-12-18 21:21:38'),
(32, '5', '978769', '2025-12-18 22:46:09', 1, '2025-12-18 22:45:09'),
(33, '5', '750750', '2025-12-18 22:47:54', 1, '2025-12-18 22:46:54'),
(34, '3', '532322', '2025-12-18 23:09:50', 1, '2025-12-18 23:08:50'),
(35, '3', '990281', '2025-12-19 05:34:39', 1, '2025-12-19 05:33:39'),
(36, '2', '253964', '2025-12-19 06:17:26', 1, '2025-12-19 06:16:26'),
(37, '2', '361299', '2025-12-19 07:11:15', 1, '2025-12-19 07:10:15'),
(38, '2', '832801', '2025-12-20 03:04:19', 1, '2025-12-20 03:03:19'),
(39, '2', '975133', '2025-12-20 18:11:37', 1, '2025-12-20 18:10:37'),
(40, '5', '186644', '2025-12-22 07:26:18', 1, '2025-12-22 07:25:18'),
(41, '2', '124918', '2025-12-23 22:59:49', 1, '2025-12-23 22:58:49'),
(42, '5', '713742', '2025-12-24 23:40:16', 1, '2025-12-24 23:39:16'),
(43, '3', '352737', '2025-12-24 23:54:30', 1, '2025-12-24 23:53:30'),
(44, '5', '339168', '2025-12-25 00:15:23', 1, '2025-12-25 00:14:23'),
(45, '5', '979439', '2025-12-25 00:27:16', 1, '2025-12-25 00:26:16'),
(46, '5', '687868', '2025-12-25 08:22:44', 1, '2025-12-25 08:21:44'),
(47, '7', '231467', '2025-12-25 09:08:51', 1, '2025-12-25 09:07:51'),
(48, '7', '834947', '2025-12-25 09:09:33', 1, '2025-12-25 09:08:34'),
(49, '7', '489414', '2025-12-25 09:11:03', 1, '2025-12-25 09:10:03'),
(50, '7', '447823', '2025-12-25 09:13:57', 1, '2025-12-25 09:12:57'),
(51, '7', '746662', '2025-12-25 09:23:43', 1, '2025-12-25 09:22:43'),
(52, '7', '307367', '2025-12-25 20:08:48', 1, '2025-12-25 20:07:48'),
(53, '7', '902891', '2025-12-26 05:01:46', 1, '2025-12-26 05:00:46'),
(54, '7', '163927', '2025-12-26 07:49:37', 1, '2025-12-26 07:48:37'),
(55, '5', '966187', '2025-12-27 06:53:23', 1, '2025-12-27 06:52:23'),
(56, '2', '844883', '2025-12-27 06:56:13', 1, '2025-12-27 06:55:13'),
(57, '3', '105853', '2025-12-27 06:58:32', 1, '2025-12-27 06:57:32'),
(58, '7', '903192', '2025-12-27 07:00:09', 1, '2025-12-27 06:59:09'),
(59, '5', '536953', '2025-12-27 07:01:29', 1, '2025-12-27 07:00:29'),
(60, '5', '286707', '2025-12-27 21:09:41', 1, '2025-12-27 21:08:41'),
(61, '2', '621399', '2025-12-27 21:10:32', 1, '2025-12-27 21:09:32'),
(62, '3', '355536', '2025-12-27 21:11:27', 1, '2025-12-27 21:10:27'),
(63, '7', '558537', '2025-12-27 21:12:11', 1, '2025-12-27 21:11:11'),
(64, '7', '645665', '2025-12-27 22:45:08', 1, '2025-12-27 22:44:08'),
(65, '5', '872106', '2025-12-27 23:54:20', 1, '2025-12-27 23:53:20'),
(66, '5', '165794', '2025-12-28 04:02:16', 1, '2025-12-28 04:01:16'),
(67, '3', '898156', '2025-12-28 04:03:15', 1, '2025-12-28 04:02:15'),
(68, '7', '980548', '2025-12-28 05:55:57', 1, '2025-12-28 05:54:57'),
(69, '2', '230707', '2025-12-28 05:57:37', 1, '2025-12-28 05:56:37'),
(70, '7', '558840', '2025-12-28 05:58:41', 1, '2025-12-28 05:57:41'),
(71, '2', '658422', '2025-12-28 05:59:42', 1, '2025-12-28 05:58:42'),
(72, '5', '218624', '2025-12-28 08:22:30', 1, '2025-12-28 08:21:30'),
(73, '2', '962803', '2025-12-28 08:23:19', 1, '2025-12-28 08:22:19'),
(74, '3', '290985', '2025-12-28 08:24:27', 1, '2025-12-28 08:23:27'),
(75, '3', '407492', '2025-12-28 08:25:50', 1, '2025-12-28 08:24:50'),
(76, '7', '591857', '2025-12-28 08:26:25', 1, '2025-12-28 08:25:25'),
(77, '7', '353814', '2025-12-29 04:40:40', 1, '2025-12-29 04:39:40'),
(78, '7', '600621', '2025-12-29 07:50:11', 1, '2025-12-29 07:49:11'),
(79, '16', '101122', '2025-12-29 08:21:45', 1, '2025-12-29 08:20:45'),
(80, '16', '211064', '2025-12-29 08:23:42', 1, '2025-12-29 08:22:42'),
(81, '16', '323496', '2025-12-29 09:23:29', 1, '2025-12-29 09:22:29'),
(82, '2', '316464', '2025-12-29 09:33:56', 1, '2025-12-29 09:32:56'),
(83, '2', '961486', '2025-12-29 21:29:59', 1, '2025-12-29 21:28:59'),
(84, '2', '196114', '2025-12-29 21:31:45', 1, '2025-12-29 21:30:45'),
(85, '5', '168832', '2025-12-29 22:55:10', 1, '2025-12-29 22:54:10'),
(86, '3', '371225', '2025-12-29 22:56:26', 1, '2025-12-29 22:55:26'),
(87, '7', '622588', '2025-12-29 22:57:31', 1, '2025-12-29 22:56:31'),
(88, '7', '124348', '2025-12-31 20:24:18', 1, '2025-12-31 20:23:18'),
(89, '2', '203413', '2025-12-31 20:33:28', 1, '2025-12-31 20:32:28'),
(90, '5', '845872', '2025-12-31 21:14:38', 1, '2025-12-31 21:13:38'),
(91, '3', '817862', '2025-12-31 21:17:27', 1, '2025-12-31 21:16:27'),
(92, '5', '981482', '2025-12-31 21:28:43', 1, '2025-12-31 21:27:43'),
(93, '3', '730370', '2025-12-31 21:37:07', 1, '2025-12-31 21:36:07'),
(94, '3', '748985', '2025-12-31 21:37:41', 1, '2025-12-31 21:36:41'),
(95, '5', '589931', '2025-12-31 21:39:03', 1, '2025-12-31 21:38:03'),
(96, '2', '814678', '2025-12-31 23:49:15', 1, '2025-12-31 23:48:15'),
(97, '16', '921853', '2025-12-31 23:50:18', 1, '2025-12-31 23:49:18'),
(98, '2', '570492', '2026-01-01 08:45:47', 1, '2026-01-01 08:44:47'),
(99, '2', '247376', '2026-01-01 22:11:27', 1, '2026-01-01 22:10:27'),
(100, '3', '725561', '2026-01-02 06:13:48', 1, '2026-01-02 06:12:48'),
(101, '2', '693789', '2026-01-02 06:17:24', 1, '2026-01-02 06:16:24'),
(102, '5', '643439', '2026-01-02 06:27:36', 1, '2026-01-02 06:26:36'),
(103, '2', '946572', '2026-01-02 07:01:56', 1, '2026-01-02 07:00:56'),
(104, '2', '746577', '2026-01-02 07:02:34', 1, '2026-01-02 07:01:34'),
(105, '2', '609231', '2026-01-03 04:15:12', 1, '2026-01-03 04:14:12'),
(106, '3', '580888', '2026-01-03 04:25:48', 1, '2026-01-03 04:24:48'),
(107, '3', '615645', '2026-01-03 05:45:10', 1, '2026-01-03 05:44:10'),
(108, '2', '890165', '2026-01-03 05:52:29', 1, '2026-01-03 05:51:29'),
(109, '5', '593874', '2026-01-03 05:53:19', 1, '2026-01-03 05:52:19'),
(110, '5', '637803', '2026-01-03 05:53:48', 1, '2026-01-03 05:52:48'),
(111, '3', '548846', '2026-01-03 05:57:00', 1, '2026-01-03 05:56:00'),
(112, '2', '136999', '2026-01-03 05:59:12', 1, '2026-01-03 05:58:12'),
(113, '2', '905462', '2026-01-03 20:23:18', 1, '2026-01-03 20:22:18'),
(114, '5', '546360', '2026-01-03 21:03:15', 1, '2026-01-03 21:02:15'),
(115, '5', '757266', '2026-01-03 21:03:47', 1, '2026-01-03 21:02:47'),
(116, '2', '260471', '2026-01-03 21:18:42', 1, '2026-01-03 21:17:42'),
(117, '5', '712881', '2026-01-03 22:02:16', 1, '2026-01-03 22:01:16'),
(118, '2', '758289', '2026-01-03 22:03:35', 1, '2026-01-03 22:02:35'),
(119, '5', '695624', '2026-01-04 01:45:02', 1, '2026-01-04 01:44:02'),
(120, '2', '806072', '2026-01-04 03:42:28', 1, '2026-01-04 03:41:28'),
(121, '2', '858944', '2026-01-05 06:07:24', 1, '2026-01-05 06:06:24'),
(122, '5', '141168', '2026-01-05 06:13:44', 1, '2026-01-05 06:12:44'),
(123, '2', '892499', '2026-01-05 06:29:12', 1, '2026-01-05 06:28:12'),
(124, '2', '410707', '2026-01-06 04:57:40', 1, '2026-01-06 04:56:40'),
(125, '5', '348595', '2026-01-06 04:58:33', 1, '2026-01-06 04:57:33'),
(126, '5', '674438', '2026-01-06 18:36:41', 1, '2026-01-06 18:35:41'),
(127, '2', '458397', '2026-01-07 02:17:41', 1, '2026-01-07 02:16:41'),
(128, '3', '165668', '2026-01-07 03:20:12', 1, '2026-01-07 03:19:12'),
(129, '7', '932501', '2026-01-07 03:23:07', 1, '2026-01-07 03:22:07'),
(130, '3', '150189', '2026-01-07 03:25:48', 1, '2026-01-07 03:24:48'),
(131, '2', '273651', '2026-01-07 03:57:30', 1, '2026-01-07 03:56:30'),
(132, '2', '142948', '2026-01-09 06:12:16', 1, '2026-01-09 06:11:16'),
(133, '2', '239660', '2026-01-09 20:46:41', 1, '2026-01-09 20:45:41'),
(134, '5', '675865', '2026-01-12 04:33:38', 1, '2026-01-12 04:32:38'),
(135, '2', '531111', '2026-01-12 05:37:31', 1, '2026-01-12 05:36:31'),
(136, '2', '786069', '2026-01-12 23:54:19', 1, '2026-01-12 23:53:19'),
(137, '3', '330091', '2026-01-13 00:02:53', 1, '2026-01-13 00:01:53'),
(138, '5', '861122', '2026-01-13 00:04:32', 1, '2026-01-13 00:03:32'),
(139, '2', '240278', '2026-01-13 00:05:32', 1, '2026-01-13 00:04:32'),
(140, '2', '318589', '2026-01-13 02:26:19', 1, '2026-01-13 02:25:19'),
(141, '5', '346158', '2026-01-13 02:53:17', 1, '2026-01-13 02:52:17'),
(142, '3', '861967', '2026-01-13 02:54:58', 1, '2026-01-13 02:53:58'),
(143, '7', '475706', '2026-01-13 02:58:21', 1, '2026-01-13 02:57:21'),
(144, '2', '610771', '2026-01-13 19:13:00', 1, '2026-01-13 19:12:00'),
(145, '5', '921107', '2026-01-13 19:15:24', 1, '2026-01-13 19:14:24'),
(146, '3', '103484', '2026-01-13 19:19:15', 1, '2026-01-13 19:18:15'),
(147, '7', '821087', '2026-01-13 19:22:04', 1, '2026-01-13 19:21:04'),
(148, '2', '145303', '2026-01-16 20:33:54', 1, '2026-01-16 20:32:54'),
(149, '5', '464241', '2026-01-16 20:39:20', 1, '2026-01-16 20:38:20'),
(150, '3', '336218', '2026-01-16 21:05:43', 1, '2026-01-16 21:04:43'),
(151, '7', '860263', '2026-01-16 21:13:52', 1, '2026-01-16 21:12:52'),
(152, '2', '129850', '2026-01-17 00:20:58', 1, '2026-01-17 00:19:58'),
(153, '2', '909363', '2026-01-17 01:13:59', 1, '2026-01-17 01:12:59'),
(154, '2', '561759', '2026-01-17 01:15:09', 1, '2026-01-17 01:14:09'),
(155, '2', '422626', '2026-01-17 01:15:53', 1, '2026-01-17 01:14:53'),
(156, '2', '696150', '2026-01-17 01:17:37', 1, '2026-01-17 01:16:37'),
(157, '2', '834611', '2026-01-17 01:21:51', 1, '2026-01-17 01:20:51'),
(158, '2', '533849', '2026-01-17 01:24:21', 1, '2026-01-17 01:23:21'),
(159, '2', '115614', '2026-01-17 01:26:50', 1, '2026-01-17 01:25:50'),
(160, '2', '388268', '2026-01-17 01:27:28', 1, '2026-01-17 01:26:28'),
(161, '2', '940409', '2026-01-17 01:27:47', 1, '2026-01-17 01:26:47'),
(162, '2', '520422', '2026-01-17 01:33:24', 1, '2026-01-17 01:32:24'),
(163, '2', '606044', '2026-01-17 01:36:41', 1, '2026-01-17 01:35:41'),
(164, '2', '890113', '2026-01-17 01:38:19', 1, '2026-01-17 01:37:19'),
(165, '2', '298766', '2026-01-17 01:38:52', 1, '2026-01-17 01:37:52'),
(166, '2', '643301', '2026-01-17 01:41:04', 1, '2026-01-17 01:40:04'),
(167, '2', '212444', '2026-01-17 01:44:15', 1, '2026-01-17 01:43:15'),
(168, '2', '771858', '2026-01-17 01:50:59', 1, '2026-01-17 01:49:59'),
(169, '2', '657651', '2026-01-17 01:51:46', 1, '2026-01-17 01:50:46'),
(170, '2', '398120', '2026-01-17 02:04:01', 1, '2026-01-17 02:03:01'),
(171, '2', '274347', '2026-01-17 02:04:37', 1, '2026-01-17 02:03:37'),
(172, '2', '246996', '2026-01-17 02:05:03', 1, '2026-01-17 02:04:03'),
(173, '2', '696361', '2026-01-17 02:09:12', 1, '2026-01-17 02:08:12'),
(174, '2', '494235', '2026-01-17 03:49:04', 1, '2026-01-17 03:48:04'),
(175, '2', '351412', '2026-01-18 07:04:11', 1, '2026-01-18 07:03:11'),
(176, '2', '692002', '2026-01-20 04:33:13', 1, '2026-01-20 04:32:13'),
(177, '5', '375977', '2026-01-20 07:46:36', 1, '2026-01-20 07:45:36'),
(178, '5', '880458', '2026-01-20 07:49:17', 1, '2026-01-20 07:48:17'),
(179, '2', '531131', '2026-01-20 08:20:54', 1, '2026-01-20 08:19:54'),
(180, '2', '426605', '2026-01-20 08:24:16', 1, '2026-01-20 08:23:16'),
(181, '5', '651201', '2026-01-20 08:44:37', 1, '2026-01-20 08:43:37'),
(182, '2', '263738', '2026-01-20 09:49:37', 1, '2026-01-20 09:48:37'),
(183, '5', '910938', '2026-01-20 09:56:51', 1, '2026-01-20 09:55:51'),
(184, '5', '765357', '2026-01-20 09:57:45', 1, '2026-01-20 09:56:45'),
(185, '3', '275525', '2026-01-20 09:58:41', 1, '2026-01-20 09:57:41'),
(186, '2', '274522', '2026-01-21 05:01:41', 1, '2026-01-21 05:00:41'),
(187, '5', '914619', '2026-01-21 07:29:38', 1, '2026-01-21 07:28:38'),
(188, '5', '408753', '2026-01-23 18:54:17', 1, '2026-01-23 18:53:17'),
(189, '2', '177960', '2026-01-23 19:15:40', 1, '2026-01-23 19:14:40'),
(190, '5', '411118', '2026-01-23 20:18:18', 1, '2026-01-23 20:17:18'),
(191, '5', '336794', '2026-01-23 20:29:39', 1, '2026-01-23 20:28:39'),
(192, '2', '401562', '2026-01-23 22:34:01', 1, '2026-01-23 22:33:01'),
(193, '2', '393954', '2026-01-23 22:35:07', 1, '2026-01-23 22:34:07'),
(194, '5', '518431', '2026-01-24 01:06:11', 1, '2026-01-24 01:05:11'),
(195, '5', '823052', '2026-01-24 06:13:49', 1, '2026-01-24 06:12:49'),
(196, '5', '561941', '2026-01-24 06:14:53', 1, '2026-01-24 06:13:53'),
(197, '5', '162964', '2026-01-24 06:15:36', 1, '2026-01-24 06:14:36'),
(198, '5', '384834', '2026-01-24 06:16:11', 1, '2026-01-24 06:15:11'),
(199, '5', '820592', '2026-01-24 06:17:31', 1, '2026-01-24 06:16:31'),
(200, '5', '782781', '2026-01-24 06:18:12', 1, '2026-01-24 06:17:13'),
(201, '5', '344720', '2026-01-24 06:20:11', 1, '2026-01-24 06:19:11'),
(202, '5', '660430', '2026-01-24 06:20:43', 1, '2026-01-24 06:19:43'),
(203, '5', '798241', '2026-01-24 06:21:43', 1, '2026-01-24 06:20:43'),
(204, '5', '880796', '2026-01-24 06:23:29', 1, '2026-01-24 06:22:29'),
(205, '5', '903025', '2026-01-24 06:33:33', 1, '2026-01-24 06:32:33'),
(206, '5', '215211', '2026-01-24 06:36:29', 1, '2026-01-24 06:35:29'),
(207, '5', '775806', '2026-01-24 19:56:21', 1, '2026-01-24 19:55:21'),
(208, '2', '322197', '2026-01-24 21:18:04', 1, '2026-01-24 21:17:04'),
(209, '2', '803057', '2026-01-24 21:18:56', 1, '2026-01-24 21:17:56'),
(210, '2', '694715', '2026-01-24 21:19:30', 1, '2026-01-24 21:18:30'),
(211, '2', '562930', '2026-01-27 02:48:56', 1, '2026-01-27 02:47:56'),
(212, '5', '768505', '2026-01-27 03:25:22', 1, '2026-01-27 03:24:22'),
(213, '5', '948207', '2026-01-27 08:55:58', 1, '2026-01-27 08:54:58'),
(214, '5', '190520', '2026-01-27 09:10:11', 1, '2026-01-27 09:09:11'),
(215, '2', '500441', '2026-01-27 09:11:40', 1, '2026-01-27 09:10:40'),
(216, '5', '604301', '2026-01-27 10:45:02', 1, '2026-01-27 10:44:02'),
(217, '2', '699494', '2026-01-27 20:53:38', 1, '2026-01-27 20:52:38'),
(218, '5', '296821', '2026-01-27 23:16:11', 1, '2026-01-27 23:15:11'),
(219, '2', '955089', '2026-01-28 00:42:34', 0, '2026-01-28 00:41:34'),
(220, '2', '678698', '2026-01-28 08:54:58', 1, '2026-01-28 08:53:58'),
(221, '3', '184816', '2026-01-28 08:55:59', 1, '2026-01-28 08:54:59'),
(222, '7', '911599', '2026-01-28 08:56:49', 1, '2026-01-28 08:55:49'),
(223, '7', '772954', '2026-01-28 08:58:56', 1, '2026-01-28 08:57:56'),
(224, '2', '980586', '2026-01-28 11:17:06', 1, '2026-01-28 11:16:06'),
(225, '2', '440194', '2026-01-28 15:08:13', 1, '2026-01-28 15:07:13'),
(226, '5', '968169', '2026-01-28 15:15:28', 1, '2026-01-28 15:14:28'),
(227, '5', '958461', '2026-02-11 15:20:36', 1, '2026-02-11 15:19:36'),
(228, '5', '677338', '2026-02-11 16:08:32', 1, '2026-02-11 16:07:32'),
(229, '5', '104921', '2026-02-11 16:09:40', 1, '2026-02-11 16:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_name` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logged_in_at` timestamp NOT NULL,
  `last_active_at` timestamp NOT NULL,
  `expires_at` timestamp NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `user_id`, `session_id`, `device_name`, `ip_address`, `country`, `city`, `logged_in_at`, `last_active_at`, `expires_at`, `is_current`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(8, 3, 'H5OhIM2JOAA0nzhtp1BVNsAG71guWcoaHs8ZIgDW', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-20 09:58:00', '2026-01-20 09:58:00', '2026-01-20 10:00:00', 1, '2026-01-20 09:58:00', '2026-01-20 09:58:00', NULL, NULL),
(10, 5, 'MxSvwBzTlUcaAITjVvk3RO8aI41qdzwGCqdhvuDK', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-21 07:29:01', '2026-01-21 07:29:01', '2026-01-21 07:31:01', 1, '2026-01-21 07:29:01', '2026-01-21 07:29:01', NULL, NULL),
(15, 2, '7lAJBLm2odyY9Akfm4Yv8eFbISkb0B9IrtrmZ892', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-23 22:34:23', '2026-01-23 22:34:23', '2026-01-23 22:36:23', 1, '2026-01-23 22:34:23', '2026-01-23 22:34:23', NULL, NULL),
(16, 5, '24xR22cRycIFq0xKbGnG2DkHiCQ4EI1MMF4fOxPx', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-24 01:05:35', '2026-01-24 01:05:35', '2026-01-24 01:07:35', 1, '2026-01-24 01:05:35', '2026-01-24 01:05:35', NULL, NULL),
(17, 5, 'VMK1a0UaPX1ZFHCdy3qJ9AF4OGLc5CtlRwwTTDdp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-24 06:36:00', '2026-01-24 06:36:00', '2026-01-24 06:38:00', 1, '2026-01-24 06:36:00', '2026-01-24 06:36:00', NULL, NULL),
(19, 2, 'EvjCbDd9mNVinFHs5JIIZ4A8wSCt9ce4BISF0gim', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-24 21:18:51', '2026-01-24 21:18:51', '2026-01-24 21:20:51', 1, '2026-01-24 21:18:51', '2026-01-24 21:18:51', NULL, NULL),
(24, 17, 'L1URE9yG10Fg5Mxk9XSBQuemSI9wpcxNN1gs0nzO', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-26 00:56:56', '2026-01-26 00:56:56', '2026-01-26 02:56:56', 1, '2026-01-26 00:56:56', '2026-01-26 00:56:56', NULL, NULL),
(26, 5, 'vPSq1cibrNdhrV4XBGIlQxAWUDX2rvmJlhpV7sLG', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-27 03:24:46', '2026-01-27 03:24:46', '2026-01-27 03:26:46', 1, '2026-01-27 03:24:46', '2026-01-27 03:24:46', NULL, NULL),
(29, 5, 'rOColj1wO5bT3hseIQ7WjECrU7C3lhOp66ROVrXW', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-27 10:44:19', '2026-01-27 10:44:19', '2026-01-27 10:46:19', 1, '2026-01-27 10:44:19', '2026-01-27 10:44:19', NULL, NULL),
(31, 5, 'xcwNMnry2WrNeT5Y02PJFPjlxEpdx2zTzVyobkM1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-27 23:15:29', '2026-01-27 23:15:29', '2026-01-27 23:17:29', 1, '2026-01-27 23:15:29', '2026-01-27 23:15:29', NULL, NULL),
(32, 2, '8v2Zv2KhKd0Amp3SkFd8uQ3ERZvhfzQy6HKCGLD8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-28 08:54:17', '2026-01-28 08:54:17', '2026-01-28 08:56:17', 1, '2026-01-28 08:54:17', '2026-01-28 08:54:17', NULL, NULL),
(33, 3, 'futP7OVLoYrk7AzQGxRC82msLk8XD1LdaXKcUVDr', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-28 08:55:18', '2026-01-28 08:55:18', '2026-01-28 08:57:18', 1, '2026-01-28 08:55:18', '2026-01-28 08:55:18', NULL, NULL),
(34, 7, 'dmwRVzucZBjcKF6v07c8oHdG4zt9QhxhDXLzIfpG', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-28 08:58:21', '2026-01-28 08:58:21', '2026-01-28 09:00:21', 1, '2026-01-28 08:58:21', '2026-01-28 08:58:21', NULL, NULL),
(35, 2, 'H6v8MV1siHAuHvKjZboxZYfqXpy1w8X3m8BfDciW', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-28 11:16:27', '2026-01-28 11:16:27', '2026-01-28 11:18:27', 1, '2026-01-28 11:16:27', '2026-01-28 11:16:27', NULL, NULL),
(36, 2, 'gkHj6YVvWAiJPHb53dZT4kpIwnu9eGE292458rxd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-28 15:07:33', '2026-01-28 15:07:33', '2026-01-28 15:09:33', 1, '2026-01-28 15:07:33', '2026-01-28 15:07:33', NULL, NULL),
(37, 5, 'sdZzFojWrqcgD675Wnc9BOD9SDduJ1jhMDNrnhJA', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-01-28 15:14:45', '2026-01-28 15:14:45', '2026-01-28 15:16:45', 1, '2026-01-28 15:14:45', '2026-01-28 15:14:45', NULL, NULL),
(38, 5, 'euAYW5RfRWFeUrNSzKc66KGPkSByhpssInhPoyM2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '127.0.0.1', 'Unknown', 'Unknown', '2026-02-11 15:20:10', '2026-02-11 15:20:10', '2026-02-11 15:22:10', 1, '2026-02-11 15:20:10', '2026-02-11 15:20:10', NULL, NULL),
(39, 5, 'EvUn7DB4z5a6bSjNakud5LvNQfGanMhRBDqU0NII', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '136.158.7.63', 'Philippines', 'Quezon City', '2026-02-11 16:09:48', '2026-02-11 16:09:48', '2026-02-11 16:11:48', 1, '2026-02-11 16:09:48', '2026-02-11 16:09:48', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_log_name_index` (`log_name`),
  ADD KEY `activity_logs_subject_id_index` (`subject_id`),
  ADD KEY `activity_logs_subject_type_index` (`subject_type`),
  ADD KEY `activity_logs_causer_id_index` (`causer_id`),
  ADD KEY `activity_logs_causer_type_index` (`causer_type`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_created_at_index` (`created_at`),
  ADD KEY `audit_logs_user_id_action_index` (`user_id`,`action`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_user_id_index` (`user_id`),
  ADD KEY `audit_logs_action_index` (`action`),
  ADD KEY `audit_logs_model_index` (`model`),
  ADD KEY `audit_logs_model_id_index` (`model_id`);

--
-- Indexes for table `backup_downloads`
--
ALTER TABLE `backup_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backup_downloads_requested_by_foreign` (`requested_by`),
  ADD KEY `backup_downloads_backup_file_index` (`backup_file`),
  ADD KEY `backup_downloads_otp_expires_at_index` (`otp_expires_at`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_district` (`district_id`),
  ADD KEY `barangays_city_id_index` (`city_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cities_code_unique` (`code`),
  ADD KEY `cities_province_id_index` (`province_id`);

--
-- Indexes for table `citizen_payment_methods`
--
ALTER TABLE `citizen_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citizen_payment_methods_user_id_index` (`user_id`),
  ADD KEY `citizen_payment_methods_user_id_is_default_index` (`user_id`,`is_default`);

--
-- Indexes for table `citizen_road_requests`
--
ALTER TABLE `citizen_road_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citizen_road_requests_user_id_index` (`user_id`),
  ADD KEY `citizen_road_requests_status_index` (`status`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contact_inquiries_ticket_number_unique` (`ticket_number`),
  ADD KEY `contact_inquiries_status_priority_index` (`status`,`priority`),
  ADD KEY `contact_inquiries_assigned_to_index` (`assigned_to`),
  ADD KEY `contact_inquiries_user_id_index` (`user_id`),
  ADD KEY `contact_inquiries_created_at_index` (`created_at`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `districts_city_id_foreign` (`city_id`);

--
-- Indexes for table `energy_facility_requests`
--
ALTER TABLE `energy_facility_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `energy_facility_requests_status_index` (`status`),
  ADD KEY `energy_facility_requests_preferred_date_index` (`preferred_date`),
  ADD KEY `energy_facility_requests_seminar_id_index` (`seminar_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`),
  ADD KEY `events_category_is_published_index` (`category`,`is_published`),
  ADD KEY `events_event_date_index` (`event_date`),
  ADD KEY `events_published_at_index` (`published_at`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facilities_location_id_index` (`location_id`),
  ADD KEY `facilities_facility_type_index` (`facility_type`),
  ADD KEY `facilities_status_index` (`status`),
  ADD KEY `facilities_is_available_index` (`is_available`),
  ADD KEY `facilities_display_order_index` (`display_order`),
  ADD KEY `facilities_deleted_at_index` (`deleted_at`),
  ADD KEY `facilities_latitude_longitude_index` (`latitude`,`longitude`),
  ADD KEY `facilities_city_index` (`city`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_category_id_is_published_index` (`category_id`,`is_published`),
  ADD KEY `faqs_sort_order_index` (`sort_order`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faq_categories_slug_unique` (`slug`),
  ADD KEY `faq_categories_sort_order_index` (`sort_order`);

--
-- Indexes for table `fund_requests`
--
ALTER TABLE `fund_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `government_program_bookings`
--
ALTER TABLE `government_program_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_articles`
--
ALTER TABLE `help_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `help_articles_slug_unique` (`slug`),
  ADD KEY `help_articles_category_is_published_index` (`category`,`is_published`),
  ADD KEY `help_articles_sort_order_index` (`sort_order`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_location_code_unique` (`location_code`),
  ADD KEY `locations_location_code_index` (`location_code`),
  ADD KEY `locations_is_active_index` (`is_active`),
  ADD KEY `locations_deleted_at_index` (`deleted_at`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_history_user_id_index` (`user_id`),
  ADD KEY `login_history_status_index` (`status`),
  ADD KEY `login_history_attempted_at_index` (`attempted_at`);

--
-- Indexes for table `message_templates`
--
ALTER TABLE `message_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_templates_category_type_index` (`category`,`type`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_slug_unique` (`slug`),
  ADD KEY `news_category_is_published_index` (`category`,`is_published`),
  ADD KEY `news_published_at_index` (`published_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `notification_campaigns`
--
ALTER TABLE `notification_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_campaigns_status_scheduled_at_index` (`status`,`scheduled_at`),
  ADD KEY `notification_campaigns_sent_by_index` (`sent_by`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_logs_campaign_id_status_index` (`campaign_id`,`status`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provinces_code_unique` (`code`),
  ADD KEY `provinces_region_id_index` (`region_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regions_code_unique` (`code`);

--
-- Indexes for table `road_assistance_requests`
--
ALTER TABLE `road_assistance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subsystems`
--
ALTER TABLE `subsystems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subsystems_name_unique` (`name`);

--
-- Indexes for table `subsystem_roles`
--
ALTER TABLE `subsystem_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_subsystem_role` (`subsystem_id`,`role_name`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`),
  ADD KEY `system_settings_category_key_index` (`category`,`key`),
  ADD KEY `system_settings_category_index` (`category`);

--
-- Indexes for table `trusted_devices`
--
ALTER TABLE `trusted_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trusted_devices_user_id_device_fingerprint_unique` (`user_id`,`device_fingerprint`),
  ADD KEY `trusted_devices_device_fingerprint_index` (`device_fingerprint`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_region_id_index` (`region_id`),
  ADD KEY `users_province_id_index` (`province_id`),
  ADD KEY `users_city_id_index` (`city_id`),
  ADD KEY `users_google_id_index` (`google_id`);

--
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_favorites_user_id_facility_id_unique` (`user_id`,`facility_id`),
  ADD KEY `user_favorites_user_id_index` (`user_id`),
  ADD KEY `user_favorites_facility_id_index` (`facility_id`);

--
-- Indexes for table `user_otps`
--
ALTER TABLE `user_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_otps_user_id_index` (`user_id`),
  ADD KEY `user_otps_user_id_otp_code_used_index` (`user_id`,`otp_code`,`used`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_sessions_session_id_unique` (`session_id`),
  ADD KEY `user_sessions_user_id_index` (`user_id`),
  ADD KEY `user_sessions_expires_at_index` (`expires_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `backup_downloads`
--
ALTER TABLE `backup_downloads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `citizen_payment_methods`
--
ALTER TABLE `citizen_payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `citizen_road_requests`
--
ALTER TABLE `citizen_road_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `energy_facility_requests`
--
ALTER TABLE `energy_facility_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fund_requests`
--
ALTER TABLE `fund_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `government_program_bookings`
--
ALTER TABLE `government_program_bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_articles`
--
ALTER TABLE `help_articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `message_templates`
--
ALTER TABLE `message_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notification_campaigns`
--
ALTER TABLE `notification_campaigns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `road_assistance_requests`
--
ALTER TABLE `road_assistance_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subsystems`
--
ALTER TABLE `subsystems`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subsystem_roles`
--
ALTER TABLE `subsystem_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `trusted_devices`
--
ALTER TABLE `trusted_devices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_otps`
--
ALTER TABLE `user_otps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `backup_downloads`
--
ALTER TABLE `backup_downloads`
  ADD CONSTRAINT `backup_downloads_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `barangays`
--
ALTER TABLE `barangays`
  ADD CONSTRAINT `barangays_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `barangays_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD CONSTRAINT `notification_logs_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `notification_campaigns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provinces`
--
ALTER TABLE `provinces`
  ADD CONSTRAINT `provinces_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subsystem_roles`
--
ALTER TABLE `subsystem_roles`
  ADD CONSTRAINT `subsystem_roles_subsystem_id_foreign` FOREIGN KEY (`subsystem_id`) REFERENCES `subsystems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trusted_devices`
--
ALTER TABLE `trusted_devices`
  ADD CONSTRAINT `trusted_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
