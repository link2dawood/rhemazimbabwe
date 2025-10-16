-- Quick Fix for Partner Reminders Table
-- Run this SQL script in your database to fix the missing columns

-- Add message column if it doesn't exist
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `message` TEXT NULL AFTER `title`;

-- Add is_active column if it doesn't exist
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) DEFAULT 1 AFTER `status`;

-- Add created_by column if it doesn't exist
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `created_by` INT(11) UNSIGNED NULL AFTER `next_reminder_date`;

-- Add created_at column if it doesn't exist
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_by`;

-- Add updated_at column if it doesn't exist
ALTER TABLE `partner_reminders` 
ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Update reminder_type enum to include new values
ALTER TABLE `partner_reminders` 
MODIFY COLUMN `reminder_type` ENUM('contribution_due','missing_contribution','thank_you','custom','birthday','anniversary','renewal','payment_due','follow_up','meeting','other') NOT NULL DEFAULT 'contribution_due';

-- Verify the table structure
DESCRIBE `partner_reminders`;
