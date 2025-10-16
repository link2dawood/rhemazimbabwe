-- =====================================================
-- SETUP PARTNERS RBAC PERMISSIONS
-- =====================================================

-- Get the Partners permission group ID
SET @partners_group_id = (SELECT id FROM permission_group WHERE short_code = 'partners' LIMIT 1);

-- Add permission categories for Partners module
INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`)
SELECT @partners_group_id, 'Partners', 'partners', 1, 1, 1, 1, NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM permission_category WHERE perm_group_id = @partners_group_id AND short_code = 'partners'
);

-- Verify
SELECT 'Partners Permissions Setup Complete!' AS message;
SELECT * FROM permission_category WHERE perm_group_id = @partners_group_id;
