-- =====================================================
-- SIMPLE FIX: ADD GIVING TYPES TO PARTNERS SIDEBAR
-- =====================================================

-- Get the Partners menu ID
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' LIMIT 1);
SET @partners_group_id = (SELECT id FROM permission_group WHERE short_code = 'partners' LIMIT 1);

-- Show what we're working with
SELECT 'Partners Menu ID:' as info, @partners_menu_id as value;
SELECT 'Partners Group ID:' as info, @partners_group_id as value;

-- Check current submenus
SELECT 'Current Partner Submenus (BEFORE):' as status;
SELECT id, menu, url, level, is_active FROM sidebar_sub_menus WHERE sidebar_menu_id = @partners_menu_id ORDER BY level;

-- Delete old entries if they exist with wrong data
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_types';
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_frequencies';
DELETE FROM sidebar_sub_menus WHERE `key` = 'partner_settings';

-- Add Giving Types to sidebar
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
    'index,add,edit,delete,show',
    1
);

-- Add Giving Frequencies to sidebar
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
    'index,save,delete,toggle_status,get',
    1
);

-- Verify they were added
SELECT 'Partner Submenus (AFTER):' as status;
SELECT id, menu, url, level, is_active FROM sidebar_sub_menus WHERE sidebar_menu_id = @partners_menu_id ORDER BY level;

-- =====================================================
SELECT '✓ DONE!' as status;
SELECT 'Admin needs to LOGOUT and LOGIN again to see new menu items!' as important_note;
-- =====================================================

