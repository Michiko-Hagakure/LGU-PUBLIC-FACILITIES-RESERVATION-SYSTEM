-- SQL for Road and Transportation Infrastructure Monitoring System
-- Table: my_road_assistance_requests
-- This table stores road assistance requests made to the Local Government Unit

CREATE TABLE IF NOT EXISTS `my_road_assistance_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `government_id` varchar(50) DEFAULT NULL COMMENT 'ID from LGU system for syncing',
  `event_name` varchar(255) NOT NULL,
  `event_description` text DEFAULT NULL,
  `event_location` varchar(500) NOT NULL,
  `event_date` date NOT NULL,
  `event_start_time` time DEFAULT NULL,
  `event_end_time` time DEFAULT NULL,
  `expected_attendees` int(11) DEFAULT NULL,
  `affected_roads` text DEFAULT NULL,
  `assistance_type` varchar(100) DEFAULT NULL,
  `special_requirements` text DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `feedback` text DEFAULT NULL,
  `assigned_personnel` varchar(500) DEFAULT NULL,
  `assigned_equipment` text DEFAULT NULL,
  `traffic_plan` text DEFAULT NULL,
  `deployment_date` date DEFAULT NULL,
  `deployment_start_time` time DEFAULT NULL,
  `deployment_end_time` time DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_government_id` (`government_id`),
  KEY `idx_status` (`status`),
  KEY `idx_event_date` (`event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
