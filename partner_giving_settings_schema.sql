-- =====================================================
-- PARTNER GIVING SETTINGS - DATABASE SCHEMA
-- This file can be used to create or verify the
-- partner_giving_settings table
-- =====================================================

-- Create partner_giving_settings table
CREATE TABLE IF NOT EXISTS `partner_giving_settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `partner_id` INT(11) NOT NULL,
  `giving_type_id` INT(11) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(10) DEFAULT 'USD',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_giving_type` (`partner_id`, `giving_type_id`),
  KEY `partner_id` (`partner_id`),
  KEY `giving_type_id` (`giving_type_id`),
  KEY `is_active` (`is_active`),
  KEY `idx_partner_active` (`partner_id`, `is_active`),
  CONSTRAINT `partner_giving_settings_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_giving_settings_ibfk_2` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SAMPLE DATA (Optional - for testing)
-- =====================================================

-- This assumes you have at least one partner (id=1) and giving types
-- Uncomment below to insert sample data:

/*
-- Partner 1: Multiple giving types
INSERT INTO `partner_giving_settings` (`partner_id`, `giving_type_id`, `amount`, `currency`, `is_active`) VALUES
(1, 1, 100.00, 'USD', 1),  -- Tuition Support: $100
(1, 3, 50.00, 'USD', 1);    -- Building Fund: $50

-- Partner 2: Single giving type  
INSERT INTO `partner_giving_settings` (`partner_id`, `giving_type_id`, `amount`, `currency`, `is_active`) VALUES
(2, 2, 500.00, 'USD', 1);   -- Scholarship Fund: $500
*/

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Check table structure
DESCRIBE `partner_giving_settings`;

-- Count records
SELECT COUNT(*) as total_settings FROM `partner_giving_settings`;

-- Show settings by partner
SELECT 
    pgs.*,
    p.partner_code,
    CONCAT(p.firstname, ' ', p.lastname) as partner_name,
    gt.name as giving_type_name
FROM `partner_giving_settings` pgs
JOIN `partners` p ON p.id = pgs.partner_id
JOIN `giving_types` gt ON gt.id = pgs.giving_type_id
ORDER BY p.partner_code, gt.name;

-- Show partners with total giving amounts
SELECT 
    p.id,
    p.partner_code,
    CONCAT(p.firstname, ' ', p.lastname) as partner_name,
    COUNT(pgs.id) as number_of_types,
    SUM(pgs.amount) as total_giving_amount,
    p.currency,
    gf.name as frequency
FROM `partners` p
LEFT JOIN `partner_giving_settings` pgs ON pgs.partner_id = p.id AND pgs.is_active = 1
LEFT JOIN `giving_frequencies` gf ON gf.id = p.giving_frequency_id
GROUP BY p.id
ORDER BY total_giving_amount DESC;

-- =====================================================
-- CLEANUP (Use with caution!)
-- =====================================================

-- Uncomment below to drop the table (THIS WILL DELETE ALL DATA!)
-- DROP TABLE IF EXISTS `partner_giving_settings`;

-- =====================================================
-- END OF SCHEMA
-- =====================================================



