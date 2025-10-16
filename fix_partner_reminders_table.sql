-- Fix Partner Reminders Table Structure
-- Run this SQL script to add missing columns

-- Check if table exists, if not create it
CREATE TABLE IF NOT EXISTS `partner_reminders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) unsigned NOT NULL,
  `reminder_type` enum('contribution_due','missing_contribution','thank_you','custom','birthday','anniversary','renewal','payment_due','follow_up','meeting','other') NOT NULL DEFAULT 'contribution_due',
  `reminder_date` date NOT NULL,
  `reminder_time` time DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text,
  `send_via` enum('email','sms','both','notification') NOT NULL DEFAULT 'email',
  `status` enum('pending','sent','failed','cancelled') NOT NULL DEFAULT 'pending',
  `is_active` tinyint(1) DEFAULT 1,
  `sent_at` datetime DEFAULT NULL,
  `is_recurring` tinyint(1) DEFAULT 0,
  `recurrence_pattern` varchar(50) DEFAULT NULL COMMENT 'daily, weekly, monthly, yearly',
  `next_reminder_date` date DEFAULT NULL,
  `created_by` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `reminder_date` (`reminder_date`),
  KEY `status` (`status`),
  KEY `reminder_type` (`reminder_type`),
  CONSTRAINT `partner_reminders_partner_fk` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Add missing columns if they don't exist
-- Add message column if missing
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `message` text AFTER `title`;

-- Add is_active column if missing  
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) DEFAULT 1 AFTER `status`;

-- Add created_by column if missing
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `created_by` int(11) unsigned DEFAULT NULL AFTER `next_reminder_date`;

-- Add created_at column if missing
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_by`;

-- Add updated_at column if missing
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Update reminder_type enum to include new values
ALTER TABLE `partner_reminders` 
MODIFY COLUMN `reminder_type` enum('contribution_due','missing_contribution','thank_you','custom','birthday','anniversary','renewal','payment_due','follow_up','meeting','other') NOT NULL DEFAULT 'contribution_due';

-- Verify table structure
DESCRIBE `partner_reminders`;
