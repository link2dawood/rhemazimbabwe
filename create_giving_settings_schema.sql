-- =====================================================
-- CREATE PARTNER GIVING SETTINGS TABLE
-- This table allows partners to have multiple giving types
-- with individual amounts for each type
-- =====================================================

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
  CONSTRAINT `partner_giving_settings_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_giving_settings_ibfk_2` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add index for better query performance
CREATE INDEX `idx_partner_active` ON `partner_giving_settings` (`partner_id`, `is_active`);

-- Insert sample data (optional)
-- This will be populated when partners configure their giving settings

SELECT 'Partner Giving Settings table created successfully!' AS status;






