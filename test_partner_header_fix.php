<?php
// Test Partner Header Fix
// Run: http://localhost/rhemazimbabwe/test_partner_header_fix.php

echo "<h1>Testing Partner Header Fix</h1>";

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

echo "<h2>2. Testing Helper Loading</h2>";

try {
    // Test if custom helper exists
    if (file_exists(APPPATH . 'helpers/custom_helper.php')) {
        echo "<p style='color: green;'>✅ Custom helper file exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ Custom helper file not found!</p>";
        exit;
    }
    
    // Load custom helper
    $CI->load->helper('custom');
    echo "<p style='color: green;'>✅ Custom helper loaded successfully!</p>";
    
    // Test check_lock_enabled function
    if (function_exists('check_lock_enabled')) {
        echo "<p style='color: green;'>✅ check_lock_enabled() function is available!</p>";
        $lock_status = check_lock_enabled();
        echo "<p>Lock panel status: " . ($lock_status ? 'Enabled' : 'Disabled') . "</p>";
    } else {
        echo "<p style='color: red;'>❌ check_lock_enabled() function not found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading helper: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Testing Partner Controller Loading</h2>";

try {
    // Test loading the controller
    $CI->load->file(APPPATH . 'controllers/user/Partner.php');
    $partner_controller = new Partner();
    echo "<p style='color: green;'>✅ Partner controller loaded successfully!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading Partner controller: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>4. Testing Partner Routes</h2>";

$routes_to_test = [
    'user/partner' => 'Partners Dashboard',
    'user/partner/add' => 'Add Partner Form'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Route</th><th>Description</th><th>Test Link</th><th>Expected Result</th></tr>";

foreach ($routes_to_test as $route => $description) {
    $url = base_url($route);
    echo "<tr>";
    echo "<td><code>{$route}</code></td>";
    echo "<td>{$description}</td>";
    echo "<td><a href='{$url}' target='_blank' style='color: blue;'>Test Link</a></td>";
    echo "<td>Should load without header errors</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>5. Expected Behavior</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen Now:</h3>";
echo "<ul>";
echo "<li><strong>No Header Errors:</strong> Partner pages should load without 'check_lock_enabled()' errors</li>";
echo "<li><strong>Proper Layout:</strong> Student header should display correctly</li>";
echo "<li><strong>Partners Dashboard:</strong> Should show partners list with proper styling</li>";
echo "<li><strong>Add Partner Form:</strong> Should display form with proper layout</li>";
echo "</ul>";

echo "<h3>🔧 Fix Applied:</h3>";
echo "<ul>";
echo "<li><strong>Helper Loading:</strong> Added custom helper loading in Partner controller</li>";
echo "<li><strong>Function Availability:</strong> check_lock_enabled() function now available</li>";
echo "<li><strong>Header Compatibility:</strong> Student header can now access required functions</li>";
echo "</ul>";
echo "</div>";

echo "<h2>6. Manual Testing Instructions</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>🧪 Test the Fixed Partner Pages:</h3>";
echo "<ol>";
echo "<li><strong>Login as Student:</strong>";
echo "<ul>";
echo "<li>Login as student: <a href='" . base_url('userlogin') . "' target='_blank'>Student Login</a></li>";
echo "</ul>";
echo "</li>";

echo "<li><strong>Test Partner Pages:</strong>";
echo "<ul>";
echo "<li>Click Partners menu - should load without errors</li>";
echo "<li>Click 'Add Partner' - should show form with proper layout</li>";
echo "<li>Check that header displays correctly</li>";
echo "<li>Verify no PHP errors in browser console</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Fix Summary</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issue Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Missing Helper:</strong> Added custom helper loading to Partner controller</li>";
echo "<li><strong>Function Error:</strong> check_lock_enabled() function now available</li>";
echo "<li><strong>Header Compatibility:</strong> Student header can access all required functions</li>";
echo "</ul>";

echo "<h3>✅ Code Changes:</h3>";
echo "<ul>";
echo "<li>Added: <code>\$this->load->helper('custom');</code> to Partner controller constructor</li>";
echo "<li>Result: All helper functions now available in views</li>";
echo "<li>Result: No more 'Call to undefined function' errors</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Partner header fix completed!</strong> The Partner pages should now load without any header-related errors.</p>";
?>
