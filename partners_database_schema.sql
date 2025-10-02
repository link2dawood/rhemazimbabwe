-- =====================================================
-- PARTNERS MODULE - COMPLETE DATABASE SCHEMA
-- =====================================================
-- Run this SQL file to create all required tables
-- for the Partners Module (Phases 1-5)
-- =====================================================

-- 1. Giving Types Table
CREATE TABLE IF NOT EXISTS `giving_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default giving types
INSERT INTO `giving_types` (`name`, `code`, `description`, `is_active`) VALUES
('Tuition Support', 'tuition', 'Support for student tuition fees', 1),
('Scholarship Fund', 'scholarship', 'Contributions to scholarship fund', 1),
('Building Fund', 'building', 'Support for infrastructure development', 1),
('General Donation', 'general', 'General purpose donations', 1),
('Sponsorship', 'sponsorship', 'Student sponsorship program', 1);

-- 2. Giving Frequencies Table
CREATE TABLE IF NOT EXISTS `giving_frequencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `days_interval` int(11) DEFAULT NULL COMMENT 'Number of days between contributions',
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default giving frequencies
INSERT INTO `giving_frequencies` (`name`, `code`, `days_interval`, `description`, `is_active`) VALUES
('Once-Off', 'once_off', NULL, 'One time contribution', 1),
('Weekly', 'weekly', 7, 'Weekly contributions', 1),
('Monthly', 'monthly', 30, 'Monthly contributions', 1),
('Quarterly', 'quarterly', 90, 'Quarterly contributions', 1),
('Annually', 'annually', 365, 'Annual contributions', 1);

-- 3. Partners Table
CREATE TABLE IF NOT EXISTS `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_code` varchar(50) NOT NULL,
  `account_type` enum('individual','organization') NOT NULL DEFAULT 'individual',
  `organization_name` varchar(255) DEFAULT NULL,
  `organization_type` varchar(100) DEFAULT NULL COMMENT 'Church, Company, Foundation, NGO, Other',
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobileno` varchar(20) NOT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Zimbabwe',
  `zip_code` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `giving_type_id` int(11) DEFAULT NULL,
  `giving_frequency_id` int(11) DEFAULT NULL,
  `contribution_amount` decimal(10,2) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'USD',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL COMMENT 'Link to students table',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Link to staff table',
  `notes` text,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'inactive',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_code` (`partner_code`),
  KEY `giving_type_id` (`giving_type_id`),
  KEY `giving_frequency_id` (`giving_frequency_id`),
  KEY `student_id` (`student_id`),
  KEY `staff_id` (`staff_id`),
  KEY `status` (`status`),
  KEY `email` (`email`),
  KEY `mobileno` (`mobileno`),
  CONSTRAINT `partners_ibfk_1` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partners_ibfk_2` FOREIGN KEY (`giving_frequency_id`) REFERENCES `giving_frequencies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partners_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partners_ibfk_4` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Partner Contributions Table
CREATE TABLE IF NOT EXISTS `partner_contributions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `giving_type_id` int(11) DEFAULT NULL,
  `giving_frequency_id` int(11) DEFAULT NULL,
  `contribution_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'USD',
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'Cash, Bank Transfer, Mobile Money, etc.',
  `transaction_id` varchar(100) DEFAULT NULL,
  `receipt_no` varchar(50) DEFAULT NULL,
  `notes` text,
  `status` enum('pending','completed','cancelled','failed','refunded') NOT NULL DEFAULT 'completed',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt_no` (`receipt_no`),
  KEY `partner_id` (`partner_id`),
  KEY `giving_type_id` (`giving_type_id`),
  KEY `giving_frequency_id` (`giving_frequency_id`),
  KEY `contribution_date` (`contribution_date`),
  KEY `status` (`status`),
  CONSTRAINT `partner_contributions_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_contributions_ibfk_2` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partner_contributions_ibfk_3` FOREIGN KEY (`giving_frequency_id`) REFERENCES `giving_frequencies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Partner Permission Types Table
CREATE TABLE IF NOT EXISTS `partner_permission_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_code` varchar(50) NOT NULL,
  `permission_name` varchar(100) NOT NULL,
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_code` (`permission_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default permission types
INSERT INTO `partner_permission_types` (`permission_code`, `permission_name`, `description`, `is_active`) VALUES
('library_access', 'Library Access', 'Access to school library', 1),
('online_courses', 'Online Courses', 'Access to online learning platform', 1),
('download_centre', 'Download Centre', 'Access to download resources', 1),
('events_access', 'Events Access', 'Access to school events', 1),
('gmeet_access', 'GMeet Access', 'Access to Google Meet sessions', 1),
('zoom_access', 'Zoom Access', 'Access to Zoom sessions', 1);

-- 6. Partner Permissions Table
CREATE TABLE IF NOT EXISTS `partner_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `permission_code` varchar(50) NOT NULL,
  `is_granted` tinyint(1) NOT NULL DEFAULT '0',
  `granted_by` int(11) DEFAULT NULL,
  `granted_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_permission` (`partner_id`,`permission_code`),
  KEY `permission_code` (`permission_code`),
  CONSTRAINT `partner_permissions_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_permissions_ibfk_2` FOREIGN KEY (`permission_code`) REFERENCES `partner_permission_types` (`permission_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Partner Notes Table
CREATE TABLE IF NOT EXISTS `partner_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `note` text NOT NULL,
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `priority` (`priority`),
  CONSTRAINT `partner_notes_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Partner Reminders Table
CREATE TABLE IF NOT EXISTS `partner_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `reminder_date` date NOT NULL,
  `reminder_time` time DEFAULT NULL,
  `reminder_type` enum('contribution','follow_up','renewal','other') DEFAULT 'contribution',
  `send_via` enum('email','sms','both') DEFAULT 'email',
  `status` enum('pending','sent','failed') DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `reminder_date` (`reminder_date`),
  KEY `status` (`status`),
  CONSTRAINT `partner_reminders_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Partner Documents Table
CREATE TABLE IF NOT EXISTS `partner_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_type` varchar(100) DEFAULT NULL COMMENT 'Agreement, ID, Certificate, etc.',
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  CONSTRAINT `partner_documents_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Partner Activity Log Table
CREATE TABLE IF NOT EXISTS `partner_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `activity_type` varchar(50) NOT NULL COMMENT 'created, updated, contribution_added, etc.',
  `description` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `activity_type` (`activity_type`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `partner_activity_log_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INDEXES FOR PERFORMANCE OPTIMIZATION
-- =====================================================

-- Additional indexes for faster queries
CREATE INDEX `idx_partner_status_active` ON `partners` (`status`, `is_active`);
CREATE INDEX `idx_partner_created` ON `partners` (`created_at`);
CREATE INDEX `idx_contribution_date_status` ON `partner_contributions` (`contribution_date`, `status`);
CREATE INDEX `idx_contribution_amount` ON `partner_contributions` (`amount`);

-- =====================================================
-- SAMPLE DATA (OPTIONAL - FOR TESTING)
-- =====================================================

-- Sample Individual Partner
INSERT INTO `partners` (`partner_code`, `account_type`, `firstname`, `lastname`, `email`, `mobileno`, `address`, `city`, `country`, `giving_type_id`, `giving_frequency_id`, `contribution_amount`, `currency`, `status`, `created_at`) VALUES
('PTR-2025-0001', 'individual', 'John', 'Doe', 'john.doe@example.com', '+263771234567', '123 Main Street', 'Harare', 'Zimbabwe', 1, 3, 100.00, 'USD', 'active', NOW());

-- Sample Organization Partner
INSERT INTO `partners` (`partner_code`, `account_type`, `organization_name`, `organization_type`, `firstname`, `lastname`, `email`, `mobileno`, `address`, `city`, `country`, `giving_type_id`, `giving_frequency_id`, `contribution_amount`, `currency`, `status`, `created_at`) VALUES
('PTR-2025-0002', 'organization', 'Grace Foundation', 'Foundation', 'Mary', 'Smith', 'mary@gracefoundation.org', '+263777654321', '456 Church Road', 'Bulawayo', 'Zimbabwe', 2, 3, 500.00, 'USD', 'active', NOW());

-- Sample Contributions
INSERT INTO `partner_contributions` (`partner_id`, `giving_type_id`, `contribution_date`, `amount`, `currency`, `payment_method`, `receipt_no`, `status`, `created_at`) VALUES
(1, 1, '2025-01-15', 100.00, 'USD', 'Bank Transfer', 'RCT-20250115-0001', 'completed', NOW()),
(1, 1, '2025-02-15', 100.00, 'USD', 'Mobile Money', 'RCT-20250215-0002', 'completed', NOW()),
(2, 2, '2025-01-10', 500.00, 'USD', 'Bank Transfer', 'RCT-20250110-0003', 'completed', NOW());

-- =====================================================
-- VIEWS FOR REPORTING (OPTIONAL)
-- =====================================================

-- View: Partner Summary with Contributions
CREATE OR REPLACE VIEW `vw_partner_summary` AS
SELECT
    p.id,
    p.partner_code,
    p.account_type,
    p.organization_name,
    CONCAT(p.firstname, ' ', p.lastname) as partner_name,
    p.email,
    p.mobileno,
    p.status,
    gt.name as giving_type,
    gf.name as giving_frequency,
    p.contribution_amount,
    p.currency,
    COUNT(DISTINCT pc.id) as total_contributions,
    COALESCE(SUM(CASE WHEN pc.status = 'completed' THEN pc.amount ELSE 0 END), 0) as total_contributed,
    MAX(pc.contribution_date) as last_contribution_date,
    p.created_at as registration_date
FROM partners p
LEFT JOIN giving_types gt ON p.giving_type_id = gt.id
LEFT JOIN giving_frequencies gf ON p.giving_frequency_id = gf.id
LEFT JOIN partner_contributions pc ON p.id = pc.partner_id
GROUP BY p.id;

-- View: Monthly Contributions Report
CREATE OR REPLACE VIEW `vw_monthly_contributions` AS
SELECT
    DATE_FORMAT(contribution_date, '%Y-%m') as month,
    COUNT(*) as contribution_count,
    SUM(amount) as total_amount,
    currency,
    COUNT(DISTINCT partner_id) as unique_partners
FROM partner_contributions
WHERE status = 'completed'
GROUP BY DATE_FORMAT(contribution_date, '%Y-%m'), currency
ORDER BY month DESC;

-- =====================================================
-- STORED PROCEDURES (OPTIONAL)
-- =====================================================

DELIMITER //

-- Procedure: Get Partner Balance
CREATE PROCEDURE IF NOT EXISTS `sp_get_partner_balance`(IN p_partner_id INT)
BEGIN
    DECLARE v_expected DECIMAL(10,2);
    DECLARE v_contributed DECIMAL(10,2);

    -- Calculate expected amount based on frequency and duration
    SELECT
        CASE
            WHEN gf.days_interval IS NOT NULL THEN
                p.contribution_amount * FLOOR(DATEDIFF(CURDATE(), COALESCE(p.start_date, p.created_at)) / gf.days_interval)
            ELSE
                p.contribution_amount
        END INTO v_expected
    FROM partners p
    LEFT JOIN giving_frequencies gf ON p.giving_frequency_id = gf.id
    WHERE p.id = p_partner_id;

    -- Get total contributed
    SELECT COALESCE(SUM(amount), 0) INTO v_contributed
    FROM partner_contributions
    WHERE partner_id = p_partner_id AND status = 'completed';

    -- Return results
    SELECT
        p_partner_id as partner_id,
        v_expected as expected_amount,
        v_contributed as contributed_amount,
        (v_expected - v_contributed) as balance,
        CASE
            WHEN (v_expected - v_contributed) > (v_expected * 0.5) THEN 'Critical'
            WHEN (v_expected - v_contributed) > (v_expected * 0.25) THEN 'High'
            WHEN (v_expected - v_contributed) > 0 THEN 'Low'
            ELSE 'Up to Date'
        END as balance_status;
END //

DELIMITER ;

-- =====================================================
-- TRIGGERS FOR AUDIT TRAIL (OPTIONAL)
-- =====================================================

DELIMITER //

-- Trigger: Log partner creation
CREATE TRIGGER IF NOT EXISTS `trg_partner_created`
AFTER INSERT ON `partners`
FOR EACH ROW
BEGIN
    INSERT INTO partner_activity_log (partner_id, activity_type, description, performed_by)
    VALUES (NEW.id, 'created', CONCAT('Partner registered: ', NEW.partner_code), NEW.created_by);
END //

-- Trigger: Log partner updates
CREATE TRIGGER IF NOT EXISTS `trg_partner_updated`
AFTER UPDATE ON `partners`
FOR EACH ROW
BEGIN
    INSERT INTO partner_activity_log (partner_id, activity_type, description, performed_by)
    VALUES (NEW.id, 'updated', 'Partner record updated', NULL);
END //

-- Trigger: Log contribution added
CREATE TRIGGER IF NOT EXISTS `trg_contribution_added`
AFTER INSERT ON `partner_contributions`
FOR EACH ROW
BEGIN
    INSERT INTO partner_activity_log (partner_id, activity_type, description, performed_by)
    VALUES (NEW.partner_id, 'contribution_added',
            CONCAT('Contribution added: ', NEW.currency, ' ', NEW.amount),
            NEW.created_by);
END //

DELIMITER ;

-- =====================================================
-- GRANT PERMISSIONS (ADJUST AS NEEDED)
-- =====================================================

-- If you have specific user permissions, grant them here
-- GRANT SELECT, INSERT, UPDATE, DELETE ON rhemazimbabwe.partners TO 'your_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON rhemazimbabwe.partner_contributions TO 'your_user'@'localhost';

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Check if all tables were created successfully
SELECT TABLE_NAME, TABLE_ROWS, CREATE_TIME
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME LIKE 'partner%' OR TABLE_NAME LIKE 'giving%'
ORDER BY TABLE_NAME;

-- =====================================================
-- END OF SCHEMA
-- =====================================================
-- Total Tables Created: 10
-- Total Views Created: 2
-- Total Stored Procedures: 1
-- Total Triggers: 3
-- =====================================================
