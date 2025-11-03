-- =====================================================
-- FIX: ADD GIVING TYPES & FREQUENCIES TO SIDEBAR
-- =====================================================

-- First, check if Partners menu exists
SELECT 'Checking Partners menu...' as status;
SELECT id, menu, activate_menu FROM sidebar_menus WHERE menu = 'Partners';

-- Get IDs
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' AND activate_menu = 'partners' LIMIT 1);
SET @partners_group_id = (SELECT id FROM permission_group WHERE short_code = 'partners' LIMIT 1);

-- Show current status
SELECT 'Current submenus under Partners:' as status;
SELECT id, menu, `key`, url, level, is_active 
FROM sidebar_sub_menus 
WHERE sidebar_menu_id = @partners_menu_id 
ORDER BY level;

-- Delete old Giving Types entry if exists (might have wrong permissions)
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_types';
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_frequencies';

-- Add Giving Types submenu with correct permissions
INSERT INTO sidebar_sub_menus (
    sidebar_menu_id,
    menu,
    `key`,
    lang_key,
    url,
    level,
    access_permissions,
    permission_group_id,
    activate_controller,
    activate_methods,
    addon_permission,
    is_active
) VALUES (
    @partners_menu_id,
    'Giving Types',
    'giving_types',
    'giving_types',
    'admin/givingtypes',
    5,
    "('partners', 'can_view')",
    @partners_group_id,
    'givingtypes',
    'index,add,edit,delete,show,getlist',
    '',
    1
);

-- Add Giving Frequencies submenu
INSERT INTO sidebar_sub_menus (
    sidebar_menu_id,
    menu,
    `key`,
    lang_key,
    url,
    level,
    access_permissions,
    permission_group_id,
    activate_controller,
    activate_methods,
    addon_permission,
    is_active
) VALUES (
    @partners_menu_id,
    'Giving Frequencies',
    'giving_frequencies',
    'giving_frequencies',
    'admin/givingfrequencies',
    6,
    "('partners', 'can_view')",
    @partners_group_id,
    'givingfrequencies',
    'index,add,edit,delete,show',
    '',
    1
);

-- Verify they were added
SELECT 'Updated submenus:' as status;
SELECT id, menu, `key`, url, level, is_active 
FROM sidebar_sub_menus 
WHERE sidebar_menu_id = @partners_menu_id 
ORDER BY level;

-- =====================================================
-- GRANT PERMISSIONS TO ADMIN ROLE
-- =====================================================

-- Check if permission categories exist
SELECT 'Checking permission categories...' as status;
SELECT id, name, short_code FROM permission_category WHERE perm_group_id = @partners_group_id;

-- Grant permissions to admin role (role_id = 1 is usually Super Admin)
SET @admin_role_id = 1;

-- Check current permissions
SELECT 'Current admin permissions for Partners:' as status;
SELECT pc.name, rp.can_view, rp.can_add, rp.can_edit, rp.can_delete
FROM permission_category pc
LEFT JOIN role_permission rp ON rp.perm_cat_id = pc.id AND rp.role_id = @admin_role_id
WHERE pc.perm_group_id = @partners_group_id;

-- If no permissions, grant them
INSERT INTO role_permission (role_id, perm_cat_id, can_view, can_add, can_edit, can_delete, created_at)
SELECT 
    @admin_role_id,
    pc.id,
    1,
    1,
    1,
    1,
    NOW()
FROM permission_category pc
WHERE pc.perm_group_id = @partners_group_id
AND pc.short_code IN ('giving_types', 'giving_frequencies')
AND NOT EXISTS (
    SELECT 1 FROM role_permission 
    WHERE role_id = @admin_role_id 
    AND perm_cat_id = pc.id
);

-- Update existing permissions if they're restricted
UPDATE role_permission rp
JOIN permission_category pc ON pc.id = rp.perm_cat_id
SET rp.can_view = 1, rp.can_add = 1, rp.can_edit = 1, rp.can_delete = 1
WHERE rp.role_id = @admin_role_id
AND pc.perm_group_id = @partners_group_id
AND pc.short_code IN ('giving_types', 'giving_frequencies', 'partners');

SELECT 'DONE! Admin should now see Giving Types and Giving Frequencies in sidebar.' as status;
SELECT 'Admin needs to LOGOUT and LOGIN again to see the new menu items!' as important_note;

