-- =============================================
-- PARTNERS MODULE - MULTIPLE GIVING TYPES
-- =============================================
-- This script adds support for multiple giving types per partner
-- with individual amounts for each type
-- Date: 2025-10-01

-- Create junction table for partner giving types
CREATE TABLE IF NOT EXISTS `partner_giving_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `giving_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) DEFAULT 'USD',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `giving_type_id` (`giving_type_id`),
  KEY `is_active` (`is_active`),
  CONSTRAINT `fk_partner_giving_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_partner_giving_type` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Add total_contribution_amount column to partners table (for quick reference)
ALTER TABLE `partners`
ADD COLUMN `total_contribution_amount` decimal(10,2) NOT NULL DEFAULT '0.00' AFTER `contribution_amount`;

-- =============================================
-- MIGRATION NOTES
-- =============================================
/*
After running this script:

1. Existing partners with single giving_type_id will still work
2. New registrations can select multiple types with amounts
3. The partner_giving_types table stores the breakdown
4. The total_contribution_amount stores the sum for quick display

To migrate existing data (optional):
*/

-- Migrate existing single giving types to new junction table
INSERT INTO `partner_giving_types` (`partner_id`, `giving_type_id`, `amount`, `currency`, `is_active`)
SELECT
    id as partner_id,
    giving_type_id,
    contribution_amount as amount,
    currency,
    1 as is_active
FROM partners
WHERE giving_type_id IS NOT NULL
  AND giving_type_id > 0
  AND contribution_amount > 0
ON DUPLICATE KEY UPDATE amount = VALUES(amount);

-- Update total_contribution_amount for existing partners
UPDATE partners p
SET total_contribution_amount = (
    SELECT COALESCE(SUM(amount), 0)
    FROM partner_giving_types
    WHERE partner_id = p.id AND is_active = 1
)
WHERE id IN (SELECT DISTINCT partner_id FROM partner_giving_types);

-- =============================================
-- VERIFICATION QUERIES
-- =============================================

-- Check if table was created
SELECT COUNT(*) as table_exists
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'partner_giving_types';

-- Check migrated data
SELECT
    p.partner_code,
    p.firstname,
    p.lastname,
    COUNT(pgt.id) as giving_types_count,
    SUM(pgt.amount) as total_amount,
    p.total_contribution_amount
FROM partners p
LEFT JOIN partner_giving_types pgt ON p.id = pgt.partner_id AND pgt.is_active = 1
GROUP BY p.id
LIMIT 10;

-- =============================================
-- ROLLBACK (If needed)
-- =============================================
/*
-- CAUTION: This will delete all multiple giving type data
-- DROP TABLE IF EXISTS `partner_giving_types`;
-- ALTER TABLE `partners` DROP COLUMN IF EXISTS `total_contribution_amount`;
*/
