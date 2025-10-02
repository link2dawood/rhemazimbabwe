<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_partners_menu extends CI_Migration {

    public function up() {
        // Insert permission group for Partners
        $this->db->insert('permission_group', [
            'id' => 32,
            'name' => 'Partners Management',
            'short_code' => 'partners',
            'is_active' => 1,
            'system' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Insert permission categories for Partners
        $permission_categories = [
            ['perm_group_id' => 32, 'name' => 'Partners', 'short_code' => 'partners', 'enable_view' => 1, 'enable_add' => 1, 'enable_edit' => 1, 'enable_delete' => 1],
            ['perm_group_id' => 32, 'name' => 'Partner Contributions', 'short_code' => 'partner_contributions', 'enable_view' => 1, 'enable_add' => 1, 'enable_edit' => 1, 'enable_delete' => 1],
            ['perm_group_id' => 32, 'name' => 'Giving Types', 'short_code' => 'giving_types', 'enable_view' => 1, 'enable_add' => 1, 'enable_edit' => 1, 'enable_delete' => 1],
            ['perm_group_id' => 32, 'name' => 'Giving Frequencies', 'short_code' => 'giving_frequencies', 'enable_view' => 1, 'enable_add' => 1, 'enable_edit' => 1, 'enable_delete' => 1],
            ['perm_group_id' => 32, 'name' => 'Partner Permissions', 'short_code' => 'partner_permissions', 'enable_view' => 1, 'enable_add' => 1, 'enable_edit' => 1, 'enable_delete' => 1],
            ['perm_group_id' => 32, 'name' => 'Partner Notes', 'short_code' => 'partner_notes', 'enable_view' => 1, 'enable_add' => 1, 'enable_edit' => 1, 'enable_delete' => 1],
            ['perm_group_id' => 32, 'name' => 'Partner Reminders', 'short_code' => 'partner_reminders', 'enable_view' => 1, 'enable_add' => 1, 'enable_edit' => 1, 'enable_delete' => 1],
            ['perm_group_id' => 32, 'name' => 'Partner Reports', 'short_code' => 'partner_reports', 'enable_view' => 1, 'enable_add' => 0, 'enable_edit' => 0, 'enable_delete' => 0],
        ];

        foreach ($permission_categories as $category) {
            $this->db->insert('permission_category', $category);
        }

        // Insert main sidebar menu for Partners
        $this->db->insert('sidebar_menus', [
            'id' => 40,
            'product_name' => '',
            'permission_group_id' => 32,
            'icon' => 'fa fa-handshake-o ftlayer',
            'menu' => 'Partners',
            'activate_menu' => 'partners',
            'lang_key' => 'partners',
            'system_level' => 35,
            'level' => 35,
            'sidebar_display' => 1,
            'access_permissions' => "(\'partners\', \'can_view\') || (\'partner_contributions\', \'can_view\') || (\'giving_types\', \'can_view\') || (\'giving_frequencies\', \'can_view\') || (\'partner_reports\', \'can_view\')",
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Insert sub-menu items for Partners
        $sub_menus = [
            [
                'sidebar_menu_id' => 40,
                'menu' => 'Partner List',
                'key' => 'partner_list',
                'lang_key' => 'partner_list',
                'url' => 'admin/partners',
                'level' => 1,
                'access_permissions' => "(\'partners\', \'can_view\')",
                'permission_group_id' => 32,
                'activate_controller' => 'partners',
                'activate_methods' => 'index',
                'addon_permission' => '',
                'is_active' => 1
            ],
            [
                'sidebar_menu_id' => 40,
                'menu' => 'Add Partner',
                'key' => 'add_partner',
                'lang_key' => 'add_partner',
                'url' => 'admin/partners/add',
                'level' => 2,
                'access_permissions' => "(\'partners\', \'can_add\')",
                'permission_group_id' => 32,
                'activate_controller' => 'partners',
                'activate_methods' => 'add',
                'addon_permission' => '',
                'is_active' => 1
            ],
            [
                'sidebar_menu_id' => 40,
                'menu' => 'Contributions',
                'key' => 'partner_contributions',
                'lang_key' => 'partner_contributions',
                'url' => 'admin/partnercontributions',
                'level' => 3,
                'access_permissions' => "(\'partner_contributions\', \'can_view\')",
                'permission_group_id' => 32,
                'activate_controller' => 'partnercontributions',
                'activate_methods' => 'index',
                'addon_permission' => '',
                'is_active' => 1
            ],
            [
                'sidebar_menu_id' => 40,
                'menu' => 'Giving Types',
                'key' => 'giving_types',
                'lang_key' => 'giving_types',
                'url' => 'admin/givingtypes',
                'level' => 4,
                'access_permissions' => "(\'giving_types\', \'can_view\')",
                'permission_group_id' => 32,
                'activate_controller' => 'givingtypes',
                'activate_methods' => 'index',
                'addon_permission' => '',
                'is_active' => 1
            ],
            [
                'sidebar_menu_id' => 40,
                'menu' => 'Giving Frequencies',
                'key' => 'giving_frequencies',
                'lang_key' => 'giving_frequencies',
                'url' => 'admin/givingfrequencies',
                'level' => 5,
                'access_permissions' => "(\'giving_frequencies\', \'can_view\')",
                'permission_group_id' => 32,
                'activate_controller' => 'givingfrequencies',
                'activate_methods' => 'index',
                'addon_permission' => '',
                'is_active' => 1
            ],
            [
                'sidebar_menu_id' => 40,
                'menu' => 'Partner Reports',
                'key' => 'partner_reports',
                'lang_key' => 'partner_reports',
                'url' => 'admin/partnerreports',
                'level' => 6,
                'access_permissions' => "(\'partner_reports\', \'can_view\')",
                'permission_group_id' => 32,
                'activate_controller' => 'partnerreports',
                'activate_methods' => 'index',
                'addon_permission' => '',
                'is_active' => 1
            ]
        ];

        foreach ($sub_menus as $sub_menu) {
            $this->db->insert('sidebar_sub_menus', $sub_menu);
        }

        echo "Partners menu added successfully!\n";
    }

    public function down() {
        // Remove sub-menus
        $this->db->where('sidebar_menu_id', 40);
        $this->db->delete('sidebar_sub_menus');

        // Remove main menu
        $this->db->where('id', 40);
        $this->db->delete('sidebar_menus');

        // Remove permission categories
        $this->db->where('perm_group_id', 32);
        $this->db->delete('permission_category');

        // Remove permission group
        $this->db->where('id', 32);
        $this->db->delete('permission_group');

        echo "Partners menu removed successfully!\n";
    }
}