-- Sample data for giving_types and giving_frequencies tables
-- Run this if your partner registration page shows "No giving types available" error

-- Insert Giving Types (if table is empty)
INSERT INTO `giving_types` (`name`, `description`, `code`, `is_active`, `sort_order`, `created_at`)
SELECT * FROM (
    SELECT 'Tithe' as name, '10% of income' as description, 'tithe' as code, 1 as is_active, 1 as sort_order, NOW() as created_at UNION ALL
    SELECT 'Building Fund', 'Church building project', 'building', 1, 2, NOW() UNION ALL
    SELECT 'Missions', 'Missionary support', 'missions', 1, 3, NOW() UNION ALL
    SELECT 'Offering', 'General offering', 'offering', 1, 4, NOW() UNION ALL
    SELECT 'Special Projects', 'Special ministry projects', 'special', 1, 5, NOW()
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM giving_types WHERE is_active = 1
) LIMIT 5;

-- Insert Giving Frequencies (if table is empty)
INSERT INTO `giving_frequencies` (`name`, `description`, `interval_type`, `interval_value`, `is_active`, `sort_order`, `created_at`)
SELECT * FROM (
    SELECT 'Once-Off' as name, 'One time contribution' as description, 'once' as interval_type, 0 as interval_value, 1 as is_active, 1 as sort_order, NOW() as created_at UNION ALL
    SELECT 'Weekly', 'Every week', 'weekly', 1, 1, 2, NOW() UNION ALL
    SELECT 'Monthly', 'Every month', 'monthly', 1, 1, 3, NOW() UNION ALL
    SELECT 'Quarterly', 'Every 3 months', 'quarterly', 3, 1, 4, NOW() UNION ALL
    SELECT 'Annually', 'Once per year', 'yearly', 1, 1, 5, NOW()
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM giving_frequencies WHERE is_active = 1
) LIMIT 5;

-- Verify data was inserted
SELECT COUNT(*) as active_giving_types FROM giving_types WHERE is_active = 1;
SELECT COUNT(*) as active_giving_frequencies FROM giving_frequencies WHERE is_active = 1;
