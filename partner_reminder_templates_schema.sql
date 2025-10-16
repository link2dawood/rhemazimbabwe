-- =====================================================
-- PARTNER REMINDER TEMPLATES TABLE
-- =====================================================
-- This table stores reminder templates for partners
-- =====================================================

CREATE TABLE IF NOT EXISTS `partner_reminder_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(255) NOT NULL,
  `reminder_type` enum('contribution','follow_up','renewal','other') NOT NULL DEFAULT 'contribution',
  `timing` enum('before','after') NOT NULL DEFAULT 'before',
  `days_before` int(11) DEFAULT NULL COMMENT 'Number of days before due date',
  `days_after` int(11) DEFAULT NULL COMMENT 'Number of days after due date',
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reminder_type` (`reminder_type`),
  KEY `timing` (`timing`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default reminder templates
INSERT INTO `partner_reminder_templates` (`template_name`, `reminder_type`, `timing`, `days_before`, `subject`, `message`, `is_active`) VALUES
('Contribution Due - 7 Days Before', 'contribution', 'before', 7, 'Upcoming Contribution Due', 'Dear Partner,\n\nThis is a friendly reminder that your contribution is due in 7 days.\n\nThank you for your continued support.\n\nBest regards,\nSchool Administration', 1),
('Contribution Due - 3 Days Before', 'contribution', 'before', 3, 'Contribution Due Soon', 'Dear Partner,\n\nYour contribution is due in 3 days. Please ensure payment is made on time.\n\nThank you for your support.\n\nBest regards,\nSchool Administration', 1),
('Contribution Overdue - 1 Day After', 'contribution', 'after', 1, 'Contribution Overdue', 'Dear Partner,\n\nYour contribution was due yesterday and is now overdue. Please make payment as soon as possible.\n\nThank you for your understanding.\n\nBest regards,\nSchool Administration', 1),
('Contribution Overdue - 7 Days After', 'contribution', 'after', 7, 'Urgent: Contribution Overdue', 'Dear Partner,\n\nYour contribution has been overdue for 7 days. Please contact us immediately to resolve this matter.\n\nThank you for your attention to this matter.\n\nBest regards,\nSchool Administration', 1),
('Follow Up - 30 Days', 'follow_up', 'after', 30, 'Follow Up - Partnership Status', 'Dear Partner,\n\nWe hope this message finds you well. We would like to follow up on your partnership status and discuss future opportunities.\n\nPlease contact us at your convenience.\n\nBest regards,\nSchool Administration', 1),
('Renewal Reminder - 30 Days Before', 'renewal', 'before', 30, 'Partnership Renewal Reminder', 'Dear Partner,\n\nYour partnership agreement will expire in 30 days. We would love to continue our partnership with you.\n\nPlease contact us to discuss renewal terms.\n\nBest regards,\nSchool Administration', 1);

-- =====================================================
-- END OF SCHEMA
-- =====================================================
