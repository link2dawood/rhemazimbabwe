-- =====================================================
-- ADD GIVING TYPES & SETTINGS TO PARTNERS SIDEBAR MENU
-- =====================================================

-- Get the sidebar_menu_id for Partners
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' AND activate_menu = 'partners' LIMIT 1);
SET @partners_group_id = (SELECT id FROM permission_group WHERE short_code = 'partners' LIMIT 1);

-- Check current submenus
SELECT 'Current Partner Submenus:' as info;
SELECT id, menu, url, level FROM sidebar_sub_menus WHERE sidebar_menu_id = @partners_menu_id ORDER BY level;

-- Add Giving Types submenu
INSERT INTO `sidebar_sub_menus` (
    `sidebar_menu_id`,
    `menu`,
    `key`,
    `lang_key`,
    `url`,
    `level`,
    `access_permissions`,
    `permission_group_id`,
    `activate_controller`,
    `activate_methods`,
    `is_active`
)
SELECT
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
WHERE NOT EXISTS (
    SELECT 1 FROM sidebar_sub_menus WHERE `key` = 'giving_types'
);

-- Add Giving Frequencies submenu
INSERT INTO `sidebar_sub_menus` (
    `sidebar_menu_id`,
    `menu`,
    `key`,
    `lang_key`,
    `url`,
    `level`,
    `access_permissions`,
    `permission_group_id`,
    `activate_controller`,
    `activate_methods`,
    `is_active`
)
SELECT
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
    1
WHERE NOT EXISTS (
    SELECT 1 FROM sidebar_sub_menus WHERE `key` = 'giving_frequencies'
);

-- Add Partner Settings submenu (consolidated settings page)
INSERT INTO `sidebar_sub_menus` (
    `sidebar_menu_id`,
    `menu`,
    `key`,
    `lang_key`,
    `url`,
    `level`,
    `access_permissions`,
    `permission_group_id`,
    `activate_controller`,
    `activate_methods`,
    `is_active`
)
SELECT
    @partners_menu_id,
    'Partner Settings',
    'partner_settings',
    'partner_settings',
    'admin/partner_settings',
    7,
    "('partners', 'can_view')",
    @partners_group_id,
    'partner_settings',
    'index,giving_types,giving_frequencies,permissions,reminders',
    1
WHERE NOT EXISTS (
    SELECT 1 FROM sidebar_sub_menus WHERE `key` = 'partner_settings'
);

-- Verify the menu items were added
SELECT 'Updated Partner Submenus:' as info;
SELECT id, menu, url, level, is_active 
FROM sidebar_sub_menus 
WHERE sidebar_menu_id = @partners_menu_id 
ORDER BY level;

-- Note: After running this, admin may need to logout and login to see the new menu items

