-- =====================================================
-- ADD PARTNERS MODULE TO ADMIN SIDEBAR
-- =====================================================

-- Step 1: Check if Partners permission group exists, if not create it
INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `system`)
SELECT 'Partners', 'partners', 1, 0
WHERE NOT EXISTS (
    SELECT 1 FROM `permission_group` WHERE `short_code` = 'partners'
);

-- Get the permission_group_id for Partners
SET @partners_group_id = (SELECT id FROM permission_group WHERE short_code = 'partners' LIMIT 1);

-- Step 2: Add Partners main menu to sidebar_menus
INSERT INTO `sidebar_menus` (
    `product_name`,
    `permission_group_id`,
    `icon`,
    `menu`,
    `activate_menu`,
    `lang_key`,
    `system_level`,
    `level`,
    `sidebar_display`,
    `is_active`
)
SELECT
    'Partners',
    @partners_group_id,
    'fa fa-handshake-o',
    'Partners',
    'admin/partners',
    'partners',
    0,
    40,
    1,
    1
WHERE NOT EXISTS (
    SELECT 1 FROM sidebar_menus WHERE menu = 'Partners' AND activate_menu = 'admin/partners'
);

-- Get the sidebar_menu_id for Partners
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' AND activate_menu = 'admin/partners' LIMIT 1);

-- Step 3: Add Partner List submenu
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
    'Partner List',
    'partners_partner_list',
    'partner_list',
    'admin/partners',
    1,
    'partners',
    @partners_group_id,
    'partners',
    'index,show,add,edit,delete,getlist',
    1
WHERE NOT EXISTS (
    SELECT 1 FROM sidebar_sub_menus WHERE `key` = 'partners_partner_list'
);

-- Step 4: Add Partner Requests submenu
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
    'Partner Requests',
    'partners_requests',
    'partner_requests',
    'admin/partners/requests',
    2,
    'partners',
    @partners_group_id,
    'partners',
    'requests,approve,reject,getrequestslist',
    1
WHERE NOT EXISTS (
    SELECT 1 FROM sidebar_sub_menus WHERE `key` = 'partners_requests'
);

-- Step 5: Add Contributions submenu
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
    'Contributions',
    'partners_contributions',
    'contributions',
    'admin/partnercontributions',
    3,
    'partners',
    @partners_group_id,
    'partnercontributions',
    'index,add,edit,delete',
    1
WHERE NOT EXISTS (
    SELECT 1 FROM sidebar_sub_menus WHERE `key` = 'partners_contributions'
);

-- Step 6: Add Reports submenu
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
    'Partner Reports',
    'partners_reports',
    'partner_reports',
    'admin/partnerreports',
    4,
    'partners',
    @partners_group_id,
    'partnerreports',
    'index',
    1
WHERE NOT EXISTS (
    SELECT 1 FROM sidebar_sub_menus WHERE `key` = 'partners_reports'
);

-- =====================================================
-- VERIFICATION
-- =====================================================
SELECT 'Partners Menu Added Successfully!' AS message;
SELECT * FROM sidebar_menus WHERE menu = 'Partners';
SELECT * FROM sidebar_sub_menus WHERE sidebar_menu_id = @partners_menu_id;
