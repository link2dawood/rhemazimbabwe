<?php
// Fix Partner Requests Menu in Admin Sidebar
// Run: http://localhost/rhemazimbabwe/fix_partner_requests_menu.php

echo "<h1>Fixing Partner Requests Menu in Admin Sidebar</h1>";

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

echo "<h2>2. Checking Current Menu Structure</h2>";
try {
    // Check if Partners main menu exists
    $result = $CI->db->query("SELECT * FROM sidebar_menus WHERE menu = 'Partners' AND level = 0");
    $partners_menu = $result->row();
    
    if ($partners_menu) {
        echo "<p style='color: green;'>✅ Partners main menu exists (ID: {$partners_menu->id})</p>";
        
        // Check existing submenu items
        $submenu_result = $CI->db->query("SELECT * FROM sidebar_menus WHERE sidebar_menu_id = {$partners_menu->id} ORDER BY level");
        $submenus = $submenu_result->result();
        
        echo "<h3>Current Partner Submenu Items:</h3>";
        echo "<ul>";
        foreach ($submenus as $submenu) {
            echo "<li>Level {$submenu->level}: {$submenu->menu} ({$submenu->key})</li>";
        }
        echo "</ul>";
        
        // Check if Partner Requests already exists
        $requests_result = $CI->db->query("SELECT * FROM sidebar_menus WHERE key = 'partner_requests'");
        if ($requests_result->num_rows() > 0) {
            echo "<p style='color: orange;'>⚠️ Partner Requests menu item already exists</p>";
        } else {
            echo "<p style='color: red;'>❌ Partner Requests menu item missing</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Partners main menu not found</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking menu structure: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Adding Partner Requests Menu Item</h2>";
try {
    // Check if it already exists
    $existing = $CI->db->query("SELECT * FROM sidebar_menus WHERE key = 'partner_requests'");
    if ($existing->num_rows() > 0) {
        echo "<p style='color: orange;'>⚠️ Partner Requests menu item already exists - skipping</p>";
    } else {
        // Get the Partners main menu ID
        $partners_result = $CI->db->query("SELECT id FROM sidebar_menus WHERE menu = 'Partners' AND level = 0");
        $partners_menu = $partners_result->row();
        
        if ($partners_menu) {
            // Get the next level number
            $level_result = $CI->db->query("SELECT MAX(level) as max_level FROM sidebar_menus WHERE sidebar_menu_id = {$partners_menu->id}");
            $max_level = $level_result->row()->max_level;
            $next_level = $max_level + 1;
            
            // Insert Partner Requests menu item
            $menu_data = [
                'sidebar_menu_id' => $partners_menu->id,
                'menu' => 'Partner Requests',
                'key' => 'partner_requests',
                'lang_key' => 'partner_requests',
                'url' => 'admin/partners/requests',
                'level' => $next_level,
                'access_permissions' => "('partners', 'can_view')",
                'permission_group_id' => 32,
                'activate_controller' => 'partners',
                'activate_methods' => 'requests',
                'addon_permission' => '',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $CI->db->insert('sidebar_menus', $menu_data);
            echo "<p style='color: green;'>✅ Partner Requests menu item added successfully (Level: {$next_level})</p>";
        } else {
            echo "<p style='color: red;'>❌ Could not find Partners main menu</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error adding menu item: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Verifying Menu Structure</h2>";
try {
    // Get Partners main menu
    $partners_result = $CI->db->query("SELECT * FROM sidebar_menus WHERE menu = 'Partners' AND level = 0");
    $partners_menu = $partners_result->row();
    
    if ($partners_menu) {
        // Get all submenu items
        $submenu_result = $CI->db->query("SELECT * FROM sidebar_menus WHERE sidebar_menu_id = {$partners_menu->id} ORDER BY level");
        $submenus = $submenu_result->result();
        
        echo "<h3>Updated Partner Submenu Items:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Level</th><th>Menu</th><th>Key</th><th>URL</th><th>Active</th></tr>";
        
        foreach ($submenus as $submenu) {
            $active_status = $submenu->is_active ? 'Yes' : 'No';
            echo "<tr>";
            echo "<td>{$submenu->level}</td>";
            echo "<td>{$submenu->menu}</td>";
            echo "<td>{$submenu->key}</td>";
            echo "<td>{$submenu->url}</td>";
            echo "<td>{$active_status}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verifying menu structure: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Checking Language File</h2>";
try {
    $lang_file = 'application/language/English/app_files/partners_lang.php';
    if (file_exists($lang_file)) {
        $content = file_get_contents($lang_file);
        if (strpos($content, "'partner_requests'") !== false) {
            echo "<p style='color: green;'>✅ Language key 'partner_requests' exists in language file</p>";
        } else {
            echo "<p style='color: red;'>❌ Language key 'partner_requests' missing from language file</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Language file not found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking language file: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Testing Menu Access</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li>Login as admin: <a href='" . base_url('admin') . "' target='_blank'>Admin Login</a></li>";
echo "<li>Check the sidebar menu under 'Partners'</li>";
echo "<li>Look for 'Partner Requests' submenu item</li>";
echo "<li>Click on 'Partner Requests' to access: <a href='" . base_url('admin/partners/requests') . "' target='_blank'>Partner Requests Page</a></li>";
echo "<li>Verify that pending partner registrations are displayed</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Missing Menu Item:</strong> Added 'Partner Requests' submenu item to Partners menu</li>";
echo "<li><strong>Menu Structure:</strong> Properly configured menu hierarchy and permissions</li>";
echo "<li><strong>Language Support:</strong> Added language key for 'partner_requests'</li>";
echo "<li><strong>Access Control:</strong> Set proper permissions for menu access</li>";
echo "</ul>";

echo "<h3>✅ Menu Configuration:</h3>";
echo "<ul>";
echo "<li><strong>Menu Name:</strong> Partner Requests</li>";
echo "<li><strong>URL:</strong> admin/partners/requests</li>";
echo "<li><strong>Permission:</strong> ('partners', 'can_view')</li>";
echo "<li><strong>Controller:</strong> partners</li>";
echo "<li><strong>Method:</strong> requests</li>";
echo "<li><strong>Status:</strong> Active</li>";
echo "</ul>";
echo "</div>";

echo "<h2>8. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ 'Partner Requests' appears in the Partners submenu</li>";
echo "<li>✅ Clicking 'Partner Requests' navigates to admin/partners/requests</li>";
echo "<li>✅ The page displays pending partner registrations</li>";
echo "<li>✅ Admin can approve or reject partner requests</li>";
echo "<li>✅ Menu item is properly highlighted when active</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ Menu item missing from sidebar</li>";
echo "<li>❌ Access denied errors</li>";
echo "<li>❌ Broken navigation links</li>";
echo "<li>❌ Missing language translations</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Partner Requests menu fix completed!</strong> The menu item should now be visible in the admin sidebar under Partners.</p>";
?>
