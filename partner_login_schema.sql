-- =====================================================
-- PARTNER LOGIN SYSTEM - DATABASE UPDATE
-- =====================================================
-- Run this to add login credentials to partners table
-- =====================================================

-- Add login credentials columns to partners table
ALTER TABLE `partners`
ADD COLUMN `username` VARCHAR(100) DEFAULT NULL UNIQUE AFTER `partner_code`,
ADD COLUMN `password` VARCHAR(255) DEFAULT NULL AFTER `username`,
ADD COLUMN `is_email_verified` TINYINT(1) NOT NULL DEFAULT 0 AFTER `email`,
ADD COLUMN `email_verification_token` VARCHAR(255) DEFAULT NULL AFTER `is_email_verified`,
ADD COLUMN `password_reset_token` VARCHAR(255) DEFAULT NULL AFTER `email_verification_token`,
ADD COLUMN `password_reset_expires` DATETIME DEFAULT NULL AFTER `password_reset_token`,
ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL AFTER `password_reset_expires`,
ADD COLUMN `login_attempts` INT(11) NOT NULL DEFAULT 0 AFTER `last_login`,
ADD COLUMN `locked_until` DATETIME DEFAULT NULL AFTER `login_attempts`;

-- Create index for username
CREATE INDEX `idx_username` ON `partners` (`username`);
CREATE INDEX `idx_email_verified` ON `partners` (`is_email_verified`);

-- Create partner sessions table
CREATE TABLE IF NOT EXISTS `partner_sessions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `partner_id` INT(11) NOT NULL,
  `session_id` VARCHAR(128) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `last_activity` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `partner_id` (`partner_id`),
  KEY `last_activity` (`last_activity`),
  CONSTRAINT `partner_sessions_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Update existing partners to have username (email-based)
UPDATE `partners`
SET `username` = SUBSTRING_INDEX(`email`, '@', 1)
WHERE `username` IS NULL;

-- =====================================================
-- VERIFICATION
-- =====================================================

-- Check if columns were added
DESCRIBE `partners`;

-- Check if partner_sessions table was created
SHOW TABLES LIKE 'partner_sessions';

-- =====================================================
-- END OF UPDATE
-- =====================================================
