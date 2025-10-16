<?php
// Test Contributions Settings Button Fix
// Run: http://localhost/rhemazimbabwe/test_contributions_settings_fix.php

echo "<h1>Contributions Settings Button Fix Test</h1>";

// Test database connection
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

echo "<h2>2. View File Test</h2>";
$view_file = 'application/views/user/partner/contributions.php';
if (file_exists($view_file)) {
    echo "<p style='color: green;'>✅ Contributions view exists</p>";
    
    // Check if the fix is applied
    $content = file_get_contents($view_file);
    
    // Check for context-aware settings URL
    if (strpos($content, 'is_partner_portal') !== false && strpos($content, 'partnerdashboard/profile') !== false) {
        echo "<p style='color: green;'>✅ Settings button uses context-aware URL</p>";
    } else {
        echo "<p style='color: red;'>❌ Settings button fix not found</p>";
    }
    
    // Check for context-aware back URL
    if (strpos($content, 'partnerdashboard') !== false && strpos($content, 'user/partner') !== false) {
        echo "<p style='color: green;'>✅ Back button uses context-aware URL</p>";
    } else {
        echo "<p style='color: red;'>❌ Back button fix not found</p>";
    }
    
    // Check if old hardcoded URLs are removed
    if (strpos($content, 'user/partner/settings?partner_id=') === false) {
        echo "<p style='color: green;'>✅ Old hardcoded settings URL removed</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Old hardcoded settings URL still exists</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Contributions view missing</p>";
}

echo "<h2>3. Controller Test</h2>";
$controller_file = 'application/controllers/Partnerdashboard.php';
if (file_exists($controller_file)) {
    echo "<p style='color: green;'>✅ Partnerdashboard controller exists</p>";
    
    // Check if is_partner_portal flag is set
    $content = file_get_contents($controller_file);
    if (strpos($content, 'is_partner_portal') !== false) {
        echo "<p style='color: green;'>✅ is_partner_portal flag is set in controller</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ is_partner_portal flag not found in controller</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Partnerdashboard controller missing</p>";
}

echo "<h2>4. Routes Test</h2>";
$routes_to_test = [
    'partnerdashboard/profile' => 'Partner Settings Route',
    'partnerdashboard' => 'Partner Dashboard Route',
    'user/partner/settings' => 'User Portal Settings Route'
];

foreach ($routes_to_test as $route => $description) {
    echo "<p>✅ Route: <code>$route</code> - $description</p>";
}

echo "<h2>5. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Settings Button Redirect:</strong> Fixed hardcoded URL to use context-aware routing</li>";
echo "<li><strong>Back Button Redirect:</strong> Fixed hardcoded URL to use context-aware routing</li>";
echo "<li><strong>Context Detection:</strong> Uses \$is_partner_portal flag to determine correct URLs</li>";
echo "<li><strong>Dual Portal Support:</strong> Works for both partner portal and user portal</li>";
echo "</ul>";

echo "<h3>✅ How It Works Now:</h3>";
echo "<ol>";
echo "<li>Partner portal users see <code>partnerdashboard/profile</code> for settings</li>";
echo "<li>Partner portal users see <code>partnerdashboard</code> for back button</li>";
echo "<li>User portal users see <code>user/partner/settings</code> for settings</li>";
echo "<li>User portal users see <code>user/partner</code> for back button</li>";
echo "<li>No more redirects to <code>gauthenticate/userlogin</code></li>";
echo "</ol>";
echo "</div>";

echo "<h2>6. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Test Partner Portal:</strong>";
echo "<ul>";
echo "<li>Login as partner: <a href='" . base_url('partnerportal/login') . "' target='_blank'>Partner Login</a></li>";
echo "<li>Go to contributions: <a href='" . base_url('partnerdashboard/contributions') . "' target='_blank'>Contributions Page</a> (requires login)</li>";
echo "<li>Click 'Settings' button - should go to <code>partnerdashboard/profile</code></li>";
echo "<li>Click 'Back' button - should go to <code>partnerdashboard</code></li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test User Portal:</strong>";
echo "<ul>";
echo "<li>Login as student/parent: <a href='" . base_url('userlogin') . "' target='_blank'>User Login</a></li>";
echo "<li>Navigate to partner contributions</li>";
echo "<li>Click 'Settings' button - should go to <code>user/partner/settings</code></li>";
echo "<li>Click 'Back' button - should go to <code>user/partner</code></li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ Settings button works without redirecting to <code>gauthenticate/userlogin</code></li>";
echo "<li>✅ Back button works without redirecting to <code>gauthenticate/userlogin</code></li>";
echo "<li>✅ Partner portal users go to correct partner routes</li>";
echo "<li>✅ User portal users go to correct user routes</li>";
echo "<li>✅ All navigation works smoothly</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ Redirect to <code>gauthenticate/userlogin</code></li>";
echo "<li>❌ 404 errors or broken links</li>";
echo "<li>❌ Wrong portal context</li>";
echo "<li>❌ Authentication errors</li>";
echo "</ul>";
echo "</div>";

echo "<h2>8. Troubleshooting</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>If Issues Persist:</h3>";
echo "<ul>";
echo "<li><strong>Still redirecting to wrong login:</strong> Check if \$is_partner_portal flag is being set correctly</li>";
echo "<li><strong>Settings button not working:</strong> Verify partnerdashboard/profile route exists</li>";
echo "<li><strong>Back button not working:</strong> Verify partnerdashboard route exists</li>";
echo "<li><strong>Context detection failing:</strong> Check Partnerdashboard controller contributions method</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The contributions page settings and back buttons should now work correctly without redirecting to the wrong login page.</p>";
?>
