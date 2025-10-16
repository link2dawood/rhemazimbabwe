<?php
// Add User Partners Menu
// Run: http://localhost/rhemazimbabwe/add_user_partners_menu.php

echo "<h1>Adding User Partners Menu</h1>";

// Load CodeIgniter
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();

echo "<h2>1. Database Connection Test</h2>";
if ($CI->db->conn_id) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
}

echo "<h2>2. Adding User Partners Menu</h2>";

try {
    // Check if menu already exists
    $CI->db->where('id', 41);
    $existing = $CI->db->get('sidebar_menus');
    
    if ($existing->num_rows() > 0) {
        echo "<p style='color: orange;'>⚠️ User Partners menu already exists!</p>";
    } else {
        // Add user-specific Partners menu item
        $menu_data = [
            'id' => 41,
            'product_name' => '',
            'permission_group_id' => NULL, // No specific permission group for user menu
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
        ];
        
        $CI->db->insert('sidebar_menus', $menu_data);
        echo "<p style='color: green;'>✅ User Partners main menu added successfully!</p>";
    }
    
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
            'permission_group_id' => NULL,
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
            'permission_group_id' => NULL,
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
            'permission_group_id' => NULL,
            'activate_controller' => 'partner',
            'activate_methods' => 'register',
            'addon_permission' => '',
            'is_active' => 1
        ]
    ];

    foreach ($sub_menus as $sub_menu) {
        // Check if submenu already exists
        $CI->db->where('key', $sub_menu['key']);
        $existing_sub = $CI->db->get('sidebar_sub_menus');
        
        if ($existing_sub->num_rows() == 0) {
            $CI->db->insert('sidebar_sub_menus', $sub_menu);
            echo "<p style='color: green;'>✅ Submenu '{$sub_menu['menu']}' added successfully!</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Submenu '{$sub_menu['menu']}' already exists!</p>";
        }
    }
    
    echo "<h2>3. Testing Menu Access</h2>";
    echo "<p>Test these URLs in your browser:</p>";
    echo "<ul>";
    echo "<li><a href='" . base_url('user/partner_management') . "' target='_blank'>My Partners</a></li>";
    echo "<li><a href='" . base_url('user/partner_management/add') . "' target='_blank'>Add Partner</a></li>";
    echo "<li><a href='" . base_url('user/partner/register') . "' target='_blank'>Register as Partner</a></li>";
    echo "</ul>";
    
    echo "<h2>4. Expected Results</h2>";
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
    echo "<h3>✅ What Should Happen Now:</h3>";
    echo "<ul>";
    echo "<li><strong>Partners Menu Visible:</strong> Partners menu should appear in user sidebar</li>";
    echo "<li><strong>No Login Redirect:</strong> Clicking Partners should not redirect to userlogin</li>";
    echo "<li><strong>Proper Access:</strong> Users should be able to access partner management</li>";
    echo "<li><strong>Submenu Items:</strong> My Partners, Add Partner, Register as Partner should be visible</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h2>5. Troubleshooting</h2>";
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "<h3>🔧 If Issues Persist:</h3>";
    echo "<ul>";
    echo "<li><strong>Clear Browser Cache:</strong> Hard refresh the page (Ctrl+F5)</li>";
    echo "<li><strong>Check User Role:</strong> Ensure user has student, parent, or staff role</li>";
    echo "<li><strong>Check Permissions:</strong> Verify user has proper permissions</li>";
    echo "<li><strong>Check Menu Display:</strong> Ensure sidebar_display = 1</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<p><strong>User Partners menu setup completed!</strong> The sidebar should now show the Partners menu for users.</p>";
?>
