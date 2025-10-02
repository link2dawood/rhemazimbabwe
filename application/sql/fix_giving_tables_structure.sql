-- Fix giving_types and giving_frequencies table structure
-- Run this SQL in phpMyAdmin to add missing columns

-- Check and add missing columns to giving_types
ALTER TABLE `giving_types`
ADD COLUMN IF NOT EXISTS `sort_order` int(11) NOT NULL DEFAULT 0 AFTER `is_active`,
ADD COLUMN IF NOT EXISTS `code` varchar(50) DEFAULT NULL AFTER `description`,
ADD COLUMN IF NOT EXISTS `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `sort_order`,
ADD COLUMN IF NOT EXISTS `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Check and add missing columns to giving_frequencies
ALTER TABLE `giving_frequencies`
ADD COLUMN IF NOT EXISTS `sort_order` int(11) NOT NULL DEFAULT 0 AFTER `is_active`,
ADD COLUMN IF NOT EXISTS `code` varchar(50) DEFAULT NULL AFTER `description`,
ADD COLUMN IF NOT EXISTS `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `sort_order`,
ADD COLUMN IF NOT EXISTS `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Update existing records to have sort_order if they don't
UPDATE `giving_types` SET `sort_order` = `id` WHERE `sort_order` = 0;
UPDATE `giving_frequencies` SET `sort_order` = `id` WHERE `sort_order` = 0;

-- Insert sample data if tables are empty
INSERT INTO `giving_types` (`name`, `description`, `code`, `is_active`, `sort_order`, `created_at`)
SELECT * FROM (
    SELECT 'Tithe' as name, '10% of income' as description, 'tithe' as code, 1 as is_active, 1 as sort_order, NOW() as created_at UNION ALL
    SELECT 'Building Fund', 'Church building project', 'building', 1, 2, NOW() UNION ALL
    SELECT 'Missions', 'Missionary support', 'missions', 1, 3, NOW() UNION ALL
    SELECT 'Offering', 'General offering', 'offering', 1, 4, NOW() UNION ALL
    SELECT 'Special Projects', 'Special ministry projects', 'special', 1, 5, NOW()
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM giving_types WHERE is_active = 1) LIMIT 5;

INSERT INTO `giving_frequencies` (`name`, `description`, `interval_type`, `interval_value`, `is_active`, `sort_order`, `created_at`)
SELECT * FROM (
    SELECT 'Once-Off' as name, 'One time contribution' as description, 'once' as interval_type, 0 as interval_value, 1 as is_active, 1 as sort_order, NOW() as created_at UNION ALL
    SELECT 'Weekly', 'Every week', 'weekly', 1, 1, 2, NOW() UNION ALL
    SELECT 'Monthly', 'Every month', 'monthly', 1, 1, 3, NOW() UNION ALL
    SELECT 'Quarterly', 'Every 3 months', 'quarterly', 3, 1, 4, NOW() UNION ALL
    SELECT 'Annually', 'Once per year', 'yearly', 1, 1, 5, NOW()
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM giving_frequencies WHERE is_active = 1) LIMIT 5;
