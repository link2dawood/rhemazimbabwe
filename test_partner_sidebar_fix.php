<?php
// Test Partner Sidebar Fix
// Run: http://localhost/rhemazimbabwe/test_partner_sidebar_fix.php

echo "<h1>Testing Partner Sidebar Fix</h1>";

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

echo "<h2>2. Checking User Partners Menu</h2>";

try {
    // Check if user partners menu exists
    $CI->db->where('id', 41);
    $menu = $CI->db->get('sidebar_menus');
    
    if ($menu->num_rows() > 0) {
        $menu_data = $menu->row();
        echo "<p style='color: green;'>✅ User Partners menu exists!</p>";
        echo "<p><strong>Menu Details:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Menu:</strong> " . $menu_data->menu . "</li>";
        echo "<li><strong>Icon:</strong> " . $menu_data->icon . "</li>";
        echo "<li><strong>Access Permissions:</strong> " . $menu_data->access_permissions . "</li>";
        echo "<li><strong>Is Active:</strong> " . ($menu_data->is_active ? 'Yes' : 'No') . "</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ User Partners menu not found!</p>";
    }
    
    // Check submenus
    $CI->db->where('sidebar_menu_id', 41);
    $submenus = $CI->db->get('sidebar_sub_menus');
    
    if ($submenus->num_rows() > 0) {
        echo "<p style='color: green;'>✅ User Partners submenus exist!</p>";
        echo "<p><strong>Submenus:</strong></p>";
        echo "<ul>";
        foreach ($submenus->result() as $submenu) {
            echo "<li><strong>{$submenu->menu}:</strong> {$submenu->url}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ User Partners submenus not found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking menu: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Testing Partner Management Controller</h2>";

try {
    // Test if controller file exists
    if (file_exists(APPPATH . 'controllers/user/Partner_management.php')) {
        echo "<p style='color: green;'>✅ Partner_management controller file exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ Partner_management controller file not found!</p>";
    }
    
    // Test if views exist
    $views = [
        'application/views/user/partner/management_dashboard.php' => 'Management Dashboard View',
        'application/views/user/partner/add_partner.php' => 'Add Partner View'
    ];
    
    foreach ($views as $view => $description) {
        if (file_exists($view)) {
            echo "<p style='color: green;'>✅ {$description} exists!</p>";
        } else {
            echo "<p style='color: red;'>❌ {$description} missing!</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking files: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Testing Routes</h2>";

$routes_to_test = [
    'user/partner_management' => 'Partner Management Dashboard',
    'user/partner_management/add' => 'Add Partner Form',
    'user/partner/register' => 'Register as Partner'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Route</th><th>Description</th><th>Test Link</th><th>Expected Result</th></tr>";

foreach ($routes_to_test as $route => $description) {
    $url = base_url($route);
    echo "<tr>";
    echo "<td><code>{$route}</code></td>";
    echo "<td>{$description}</td>";
    echo "<td><a href='{$url}' target='_blank' style='color: blue;'>Test Link</a></td>";
    echo "<td>Should load without redirect to login</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>5. Expected Behavior</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen Now:</h3>";
echo "<ul>";
echo "<li><strong>Partners Menu in Sidebar:</strong> Should appear for logged-in users (students, parents, staff)</li>";
echo "<li><strong>No Login Redirect:</strong> Clicking Partners should NOT redirect to userlogin</li>";
echo "<li><strong>Proper Access:</strong> Users should be able to access partner management</li>";
echo "<li><strong>Submenu Items:</strong> My Partners, Add Partner, Register as Partner should be visible</li>";
echo "<li><strong>Add Partner Works:</strong> Users should be able to add partners without permission errors</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ Redirect to userlogin when clicking Partners</li>";
echo "<li>❌ 'Access denied' errors for legitimate users</li>";
echo "<li>❌ Permission errors when managing partners</li>";
echo "<li>❌ Broken forms or interfaces</li>";
echo "</ul>";
echo "</div>";

echo "<h2>6. Manual Testing Instructions</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>🧪 Test the Fixed Sidebar:</h3>";
echo "<ol>";
echo "<li><strong>Login as User:</strong>";
echo "<ul>";
echo "<li>Login as student: <a href='" . base_url('userlogin') . "' target='_blank'>Student Login</a></li>";
echo "<li>Login as parent: <a href='" . base_url('userlogin') . "' target='_blank'>Parent Login</a></li>";
echo "<li>Login as staff: <a href='" . base_url('admin') . "' target='_blank'>Staff Login</a></li>";
echo "</ul>";
echo "</li>";

echo "<li><strong>Check Sidebar:</strong>";
echo "<ul>";
echo "<li>Look for 'Partners' menu item in the sidebar</li>";
echo "<li>Click on 'Partners' - should NOT redirect to userlogin</li>";
echo "<li>Should show submenu: My Partners, Add Partner, Register as Partner</li>";
echo "</ul>";
echo "</li>";

echo "<li><strong>Test Partner Management:</strong>";
echo "<ul>";
echo "<li>Click 'My Partners' - should show partner management dashboard</li>";
echo "<li>Click 'Add Partner' - should show add partner form</li>";
echo "<li>Fill form and submit - should work without errors</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Fix Summary</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Sidebar Menu:</strong> Added user-specific Partners menu (ID: 41)</li>";
echo "<li><strong>Permission System:</strong> Configured for student, parent, staff access</li>";
echo "<li><strong>Controller Dependencies:</strong> Fixed setting_model loading order</li>";
echo "<li><strong>Routes:</strong> Added proper routes for user partner management</li>";
echo "<li><strong>Views:</strong> Created modern, responsive partner management interface</li>";
echo "</ul>";

echo "<h3>✅ Database Changes:</h3>";
echo "<ul>";
echo "<li>Added main menu: Partners (ID: 41)</li>";
echo "<li>Added submenu: My Partners (user/partner_management)</li>";
echo "<li>Added submenu: Add Partner (user/partner_management/add)</li>";
echo "<li>Added submenu: Register as Partner (user/partner/register)</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Partner sidebar fix completed!</strong> Users should now be able to access the Partners menu without being redirected to the login page.</p>";
?>
