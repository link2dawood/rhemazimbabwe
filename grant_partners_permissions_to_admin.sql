-- =====================================================
-- GRANT PARTNERS PERMISSIONS TO SUPER ADMIN
-- =====================================================

-- Get the Partners permission category ID
SET @partners_perm_cat_id = (SELECT id FROM permission_category WHERE short_code = 'partners' LIMIT 1);

-- Grant all permissions to Admin (role_id = 1)
-- View permission
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`)
SELECT 1, @partners_perm_cat_id, 1, 1, 1, 1, NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM roles_permissions WHERE role_id = 1 AND perm_cat_id = @partners_perm_cat_id
)
ON DUPLICATE KEY UPDATE
    can_view = 1,
    can_add = 1,
    can_edit = 1,
    can_delete = 1;

-- Verify
SELECT 'Partners Permissions Granted to Admin!' AS message;
SELECT rp.*, pc.name as permission_name
FROM roles_permissions rp
JOIN permission_category pc ON pc.id = rp.perm_cat_id
WHERE rp.role_id = 1 AND pc.short_code = 'partners';
