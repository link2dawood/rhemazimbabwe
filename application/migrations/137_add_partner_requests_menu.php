<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_partner_requests_menu extends CI_Migration {

    public function up()
    {
        // Add Partner Requests submenu item
        $this->db->insert('sidebar_menus', [
            'sidebar_menu_id' => 40, // Partners main menu ID
            'menu' => 'Partner Requests',
            'key' => 'partner_requests',
            'lang_key' => 'partner_requests',
            'url' => 'admin/partners/requests',
            'level' => 4,
            'access_permissions' => "('partners', 'can_view')",
            'permission_group_id' => 32,
            'activate_controller' => 'partners',
            'activate_methods' => 'requests',
            'addon_permission' => '',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        echo "Partner Requests menu item added successfully!\n";
    }

    public function down()
    {
        // Remove Partner Requests submenu item
        $this->db->where('key', 'partner_requests');
        $this->db->delete('sidebar_menus');

        echo "Partner Requests menu item removed successfully!\n";
    }
}
