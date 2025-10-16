-- Create missing tables for Partners Module
-- Run this if tables don't exist

-- 1. Giving Types Table
CREATE TABLE IF NOT EXISTS `giving_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Giving Frequencies Table
CREATE TABLE IF NOT EXISTS `giving_frequencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `days_interval` int(11) DEFAULT NULL COMMENT 'Number of days between contributions',
  `description` text,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Partners Table (if not exists)
CREATE TABLE IF NOT EXISTS `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_code` varchar(50) NOT NULL,
  `account_type` enum('individual','organization') NOT NULL DEFAULT 'individual',
  `organization_name` varchar(255) DEFAULT NULL,
  `organization_type` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobileno` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `giving_type_id` int(11) DEFAULT NULL,
  `giving_frequency_id` int(11) DEFAULT NULL,
  `contribution_amount` decimal(10,2) DEFAULT 0.00,
  `currency` varchar(3) DEFAULT 'USD',
  `student_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `account_creation_status` enum('pending','completed','skipped') DEFAULT 'skipped',
  `notes` text,
  `status` enum('active','inactive','suspended','pending') DEFAULT 'pending',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_code` (`partner_code`),
  UNIQUE KEY `email` (`email`),
  KEY `giving_type_id` (`giving_type_id`),
  KEY `giving_frequency_id` (`giving_frequency_id`),
  KEY `student_id` (`student_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `fk_partners_giving_type` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_partners_giving_frequency` FOREIGN KEY (`giving_frequency_id`) REFERENCES `giving_frequencies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Partner Giving Settings Table
CREATE TABLE IF NOT EXISTS `partner_giving_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `giving_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) DEFAULT 'USD',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `giving_type_id` (`giving_type_id`),
  CONSTRAINT `fk_partner_giving_settings_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_partner_giving_settings_type` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Partner Contributions Table
CREATE TABLE IF NOT EXISTS `partner_contributions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `giving_type_id` int(11) DEFAULT NULL,
  `giving_frequency_id` int(11) DEFAULT NULL,
  `contribution_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'USD',
  `payment_method` varchar(50) DEFAULT NULL,
  `receipt_no` varchar(100) DEFAULT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `giving_type_id` (`giving_type_id`),
  KEY `giving_frequency_id` (`giving_frequency_id`),
  CONSTRAINT `fk_partner_contributions_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_partner_contributions_type` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_partner_contributions_frequency` FOREIGN KEY (`giving_frequency_id`) REFERENCES `giving_frequencies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default data
INSERT IGNORE INTO `giving_types` (`name`, `code`, `description`, `is_active`) VALUES
('General Fund', 'general', 'General school fund', 1),
('Scholarship Fund', 'scholarship', 'Student scholarships', 1),
('Infrastructure', 'infrastructure', 'Building and facilities', 1),
('Library Fund', 'library', 'Library resources and books', 1),
('Sports Fund', 'sports', 'Sports equipment and activities', 1);

INSERT IGNORE INTO `giving_frequencies` (`name`, `code`, `days_interval`, `description`, `is_active`) VALUES
('Once-Off', 'once_off', NULL, 'One time contribution', 1),
('Weekly', 'weekly', 7, 'Weekly contributions', 1),
('Monthly', 'monthly', 30, 'Monthly contributions', 1),
('Quarterly', 'quarterly', 90, 'Quarterly contributions', 1),
('Annually', 'annually', 365, 'Annual contributions', 1);

-- Show success message
SELECT 'Tables created successfully!' as message;
