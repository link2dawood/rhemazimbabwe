-- =============================================
-- Giving Types Table - SQL Structure
-- =============================================
-- This file contains the SQL structure for the giving_types table
-- Used by the Giving Types CRUD system for managing partner giving types
--
-- Author: Rhema Zimbabwe School
-- Date: 2024
-- =============================================

-- Create giving_types table (if not exists)
CREATE TABLE IF NOT EXISTS `giving_types` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(50) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `sort_order` int(11) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_name` (`name`),
    UNIQUE KEY `unique_code` (`code`),
    KEY `idx_is_active` (`is_active`),
    KEY `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Sample Data (Optional)
-- =============================================
-- Uncomment the following lines to insert sample giving types

/*
INSERT INTO `giving_types` (`name`, `code`, `description`, `is_active`, `sort_order`) VALUES
('Tuition Support', 'TUITION', 'Direct financial support for student tuition fees and educational expenses', 1, 1),
('Building Fund', 'BUILDING', 'Contributions towards infrastructure development and facility improvements', 1, 2),
('Scholarship Fund', 'SCHOLARSHIP', 'Dedicated funding for student scholarships and financial aid programs', 1, 3),
('General Fund', 'GENERAL', 'Unrestricted donations that can be used for any school operational needs', 1, 4),
('Special Projects', 'PROJECTS', 'Support for specific initiatives, programs, or special school projects', 1, 5),
('Library Resources', 'LIBRARY', 'Funding for books, educational materials, and library resources', 1, 6),
('Technology Fund', 'TECHNOLOGY', 'Support for computer equipment, software, and technological infrastructure', 1, 7),
('Sports & Recreation', 'SPORTS', 'Contributions for sports equipment, facilities, and athletic programs', 1, 8),
('Staff Development', 'STAFFDEV', 'Support for teacher training, professional development, and staff welfare', 1, 9),
('Emergency Fund', 'EMERGENCY', 'Reserved for urgent needs, emergencies, and unforeseen circumstances', 1, 10);
*/

-- =============================================
-- Table Relationships
-- =============================================
-- This table is referenced by:
-- 1. partners.giving_type_id (one-to-many relationship)
-- 2. partner_giving_settings.giving_type_id (one-to-many relationship)
--
-- Note: Cannot delete giving_types that are currently in use by partners

-- =============================================
-- Indexes Explanation
-- =============================================
-- PRIMARY KEY (id): Main identifier for each giving type
-- UNIQUE KEY (name): Ensures no duplicate giving type names
-- UNIQUE KEY (code): Ensures no duplicate codes (if provided)
-- INDEX (is_active): Optimizes queries filtering by active status
-- INDEX (sort_order): Optimizes ordering queries

-- =============================================
-- Field Descriptions
-- =============================================
-- id: Auto-incrementing primary key
-- name: Display name of the giving type (required, unique)
-- code: Short code for identification (optional, unique if provided)
-- description: Detailed description of the giving type purpose
-- is_active: Status flag (1 = active, 0 = inactive)
-- sort_order: Display order in lists (lower numbers appear first)
-- created_at: Timestamp when record was created
-- updated_at: Timestamp when record was last modified

-- =============================================
-- Migration Notes
-- =============================================
-- If migrating from existing system:
-- 1. Check for existing data in partners.giving_type_id
-- 2. Ensure foreign key relationships are properly maintained
-- 3. Update any existing partner records to reference new giving_types
-- 4. Test CASCADE operations if foreign keys are added

-- =============================================
-- Version History
-- =============================================
-- v1.0 - Initial table creation
-- Migration #128 in CodeIgniter migrations
