-- Partner Registration Schema Updates
-- This file contains the database updates needed for the frontend partner registration

-- Add organization_type field to partners table
ALTER TABLE `partners` 
ADD COLUMN `organization_type` VARCHAR(100) DEFAULT NULL COMMENT 'Type of organization (Ministry, Church, Business, etc.)' AFTER `organization_name`;

-- Add password field for partner accounts
ALTER TABLE `partners` 
ADD COLUMN `password` VARCHAR(255) DEFAULT NULL COMMENT 'Encrypted password for partner login' AFTER `email`;

-- Add account creation status
ALTER TABLE `partners` 
ADD COLUMN `account_creation_status` ENUM('pending','completed','skipped') DEFAULT 'skipped' COMMENT 'Status of account creation' AFTER `password`;

-- Add zip_code field if not exists
ALTER TABLE `partners` 
ADD COLUMN `zip_code` VARCHAR(20) DEFAULT NULL COMMENT 'Postal/ZIP code' AFTER `country`;

-- Create partner login table for better security
CREATE TABLE IF NOT EXISTS `partner_logins` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `partner_id` int(11) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT '1',
    `last_login` timestamp NULL DEFAULT NULL,
    `login_attempts` int(11) NOT NULL DEFAULT '0',
    `locked_until` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    KEY `partner_id` (`partner_id`),
    CONSTRAINT `fk_partner_logins_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create partner registration tracking table
CREATE TABLE IF NOT EXISTS `partner_registrations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `partner_id` int(11) NOT NULL,
    `registration_type` ENUM('individual','organization') NOT NULL,
    `registration_source` ENUM('frontend','portal','admin') NOT NULL DEFAULT 'frontend',
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `referrer` varchar(255) DEFAULT NULL,
    `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `admin_notes` text DEFAULT NULL,
    `approved_by` int(11) DEFAULT NULL,
    `approved_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `partner_id` (`partner_id`),
    KEY `status` (`status`),
    KEY `registration_type` (`registration_type`),
    CONSTRAINT `fk_partner_registrations_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create partner communication preferences table
CREATE TABLE IF NOT EXISTS `partner_communication_preferences` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `partner_id` int(11) NOT NULL,
    `email_notifications` tinyint(1) NOT NULL DEFAULT '1',
    `sms_notifications` tinyint(1) NOT NULL DEFAULT '0',
    `newsletter_subscription` tinyint(1) NOT NULL DEFAULT '1',
    `contribution_reminders` tinyint(1) NOT NULL DEFAULT '1',
    `event_invitations` tinyint(1) NOT NULL DEFAULT '1',
    `impact_reports` tinyint(1) NOT NULL DEFAULT '1',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `partner_id` (`partner_id`),
    CONSTRAINT `fk_partner_comm_prefs_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default communication preferences for existing partners
INSERT INTO `partner_communication_preferences` (`partner_id`, `email_notifications`, `sms_notifications`, `newsletter_subscription`, `contribution_reminders`, `event_invitations`, `impact_reports`)
SELECT `id`, 1, 0, 1, 1, 1, 1 FROM `partners` WHERE `id` NOT IN (SELECT `partner_id` FROM `partner_communication_preferences`);

-- Create partner activity log table for tracking registration and other activities
CREATE TABLE IF NOT EXISTS `partner_activity_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `partner_id` int(11) NOT NULL,
    `activity_type` varchar(50) NOT NULL,
    `activity_description` text NOT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `metadata` json DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `partner_id` (`partner_id`),
    KEY `activity_type` (`activity_type`),
    KEY `created_at` (`created_at`),
    CONSTRAINT `fk_partner_activity_log_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for better performance
ALTER TABLE `partners` ADD INDEX `idx_account_type` (`account_type`);
ALTER TABLE `partners` ADD INDEX `idx_organization_type` (`organization_type`);
ALTER TABLE `partners` ADD INDEX `idx_account_creation_status` (`account_creation_status`);
ALTER TABLE `partners` ADD INDEX `idx_status_active` (`status`, `is_active`);

-- Update existing partners to have default values
UPDATE `partners` SET 
    `organization_type` = 'Other' 
WHERE `account_type` = 'organization' AND `organization_type` IS NULL;

UPDATE `partners` SET 
    `account_creation_status` = 'completed' 
WHERE `password` IS NOT NULL AND `account_creation_status` = 'skipped';

-- Create view for partner registration summary
CREATE OR REPLACE VIEW `partner_registration_summary` AS
SELECT 
    p.id,
    p.partner_code,
    p.account_type,
    p.organization_name,
    p.organization_type,
    CONCAT(p.firstname, ' ', p.lastname) as full_name,
    p.email,
    p.mobileno,
    p.status,
    p.is_active,
    p.account_creation_status,
    pr.registration_type,
    pr.registration_source,
    pr.status as registration_status,
    pr.created_at as registration_date,
    p.created_at as partner_created_at
FROM `partners` p
LEFT JOIN `partner_registrations` pr ON p.id = pr.partner_id
ORDER BY p.created_at DESC;

-- Insert sample data for testing (optional)
INSERT INTO `partner_registrations` (`partner_id`, `registration_type`, `registration_source`, `status`, `created_at`)
SELECT 
    `id`, 
    `account_type`, 
    'admin', 
    'approved', 
    `created_at`
FROM `partners` 
WHERE `id` NOT IN (SELECT `partner_id` FROM `partner_registrations`);

-- Log the schema update
INSERT INTO `partner_activity_log` (`partner_id`, `activity_type`, `activity_description`, `created_at`)
VALUES (1, 'system', 'Partner registration schema updated - added organization_type, password, account_creation_status fields', NOW());
