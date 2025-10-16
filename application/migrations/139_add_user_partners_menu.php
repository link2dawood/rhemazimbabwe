<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_user_partners_menu extends CI_Migration {

    public function up()
    {
        // Add user-specific Partners menu item
        $this->db->insert('sidebar_menus', [
            'id' => 41,
            'product_name' => '',
            'permission_group_id' => 0, // No specific permission group for user menu
            'icon' => 'fa fa-handshake-o ftlayer',
            'menu' => 'Partners',
            'activate_menu' => 'user_partners',
            'lang_key' => 'partners',
            'system_level' => 36,
            'level' => 36,
            'sidebar_display' => 1,
            'access_permissions' => "('student', 'can_view') || ('parent', 'can_view') || ('staff', 'can_view')",
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Add sub-menu items for user Partners
        $sub_menus = [
            [
                'sidebar_menu_id' => 41,
                'menu' => 'My Partners',
                'key' => 'user_partner_list',
                'lang_key' => 'my_partners',
                'url' => 'user/partner_management',
                'level' => 1,
                'access_permissions' => "('student', 'can_view') || ('parent', 'can_view') || ('staff', 'can_view')",
                'permission_group_id' => 0,
                'activate_controller' => 'partner_management',
                'activate_methods' => 'index',
                'addon_permission' => '',
                'is_active' => 1
            ],
            [
                'sidebar_menu_id' => 41,
                'menu' => 'Add Partner',
                'key' => 'user_add_partner',
                'lang_key' => 'add_partner',
                'url' => 'user/partner_management/add',
                'level' => 2,
                'access_permissions' => "('student', 'can_view') || ('parent', 'can_view') || ('staff', 'can_view')",
                'permission_group_id' => 0,
                'activate_controller' => 'partner_management',
                'activate_methods' => 'add',
                'addon_permission' => '',
                'is_active' => 1
            ],
            [
                'sidebar_menu_id' => 41,
                'menu' => 'Register as Partner',
                'key' => 'user_register_partner',
                'lang_key' => 'register_as_partner',
                'url' => 'user/partner/register',
                'level' => 3,
                'access_permissions' => "('student', 'can_view') || ('parent', 'can_view') || ('staff', 'can_view')",
                'permission_group_id' => 0,
                'activate_controller' => 'partner',
                'activate_methods' => 'register',
                'addon_permission' => '',
                'is_active' => 1
            ]
        ];

        foreach ($sub_menus as $sub_menu) {
            $this->db->insert('sidebar_sub_menus', $sub_menu);
        }

        echo "User Partners menu added successfully!\n";
    }

    public function down()
    {
        // Remove sub-menus
        $this->db->where('sidebar_menu_id', 41);
        $this->db->delete('sidebar_sub_menus');

        // Remove main menu
        $this->db->where('id', 41);
        $this->db->delete('sidebar_menus');

        echo "User Partners menu removed successfully!\n";
    }
}
