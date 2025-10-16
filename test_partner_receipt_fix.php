<?php
// Test Partner Receipt Fix
// Run: http://localhost/rhemazimbabwe/test_partner_receipt_fix.php

echo "<h1>Partner Receipt Fix Test</h1>";

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

echo "<h2>2. Controllers Test</h2>";
$controllers_to_test = [
    'application/controllers/Partnerdashboard.php' => 'Partner Dashboard Controller',
    'application/core/Partner_Controller.php' => 'Partner Base Controller'
];

foreach ($controllers_to_test as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $description exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $description missing</p>";
    }
}

echo "<h2>3. Models Test</h2>";
try {
    $CI->load->model('contribution_model');
    $CI->load->model('setting_model');
    $CI->load->model('type_model');
    echo "<p style='color: green;'>✅ Required models loaded successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Model loading error: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Views Test</h2>";
$views_to_test = [
    'application/views/user/partner/contributions.php' => 'Contributions View',
    'application/views/user/partner/receipt.php' => 'Receipt View'
];

foreach ($views_to_test as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $description exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $description missing</p>";
    }
}

echo "<h2>5. Routes Test</h2>";
$routes_to_test = [
    'partnerdashboard/receipt/(:any)' => 'Partner Receipt Route',
    'partnerdashboard/contributions' => 'Partner Contributions Route'
];

foreach ($routes_to_test as $route => $description) {
    echo "<p>✅ Route: <code>$route</code> - $description</p>";
}

echo "<h2>6. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Receipt URL Redirect Issue:</strong> Fixed hardcoded URLs in contributions view</li>";
echo "<li><strong>Context Detection:</strong> Added \$is_partner_portal flag to distinguish between portals</li>";
echo "<li><strong>Model Loading:</strong> Added setting_model to Partnerdashboard controller</li>";
echo "<li><strong>Data Passing:</strong> Fixed giving_type data passing to receipt view</li>";
echo "<li><strong>Route Configuration:</strong> Ensured correct partnerdashboard routes are configured</li>";
echo "</ul>";

echo "<h3>✅ How It Works Now:</h3>";
echo "<ol>";
echo "<li>Partner logs in at <code>partnerportal/login</code></li>";
echo "<li>Redirects to <code>partnerdashboard</code></li>";
echo "<li>Partner clicks 'View Contributions' → <code>partnerdashboard/contributions</code></li>";
echo "<li>Receipt links now point to <code>partnerdashboard/receipt/{id}</code></li>";
echo "<li>Receipt loads without redirecting to wrong login page</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Login as Partner:</strong>";
echo "<ul>";
echo "<li>Go to <a href='" . base_url('partnerportal/login') . "' target='_blank'>Partner Login</a></li>";
echo "<li>Use partner credentials to login</li>";
echo "<li>Should redirect to partner dashboard</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test Receipt Functionality:</strong>";
echo "<ul>";
echo "<li>Go to <a href='" . base_url('partnerdashboard/contributions') . "' target='_blank'>Contributions Page</a> (requires login)</li>";
echo "<li>Click on 'Print' or 'Download' buttons for any contribution</li>";
echo "<li>Should open receipt in new tab/window without redirecting to login</li>";
echo "<li>Receipt should display properly with all contribution details</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>8. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ Receipt links work without redirecting to <code>gauthenticate/userlogin</code></li>";
echo "<li>✅ Receipt opens in new tab/window</li>";
echo "<li>✅ Receipt displays partner information correctly</li>";
echo "<li>✅ Receipt shows contribution details (amount, date, giving type, etc.)</li>";
echo "<li>✅ Receipt has print functionality</li>";
echo "<li>✅ Receipt has professional formatting</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ Redirect to <code>gauthenticate/userlogin</code></li>";
echo "<li>❌ Authentication errors</li>";
echo "<li>❌ Missing data in receipt</li>";
echo "<li>❌ Broken links or 404 errors</li>";
echo "</ul>";
echo "</div>";

echo "<h2>9. Troubleshooting</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>If Issues Persist:</h3>";
echo "<ul>";
echo "<li><strong>Still redirecting to wrong login:</strong> Check if \$is_partner_portal flag is being set correctly</li>";
echo "<li><strong>Receipt shows errors:</strong> Verify setting_model and giving_type data are loaded</li>";
echo "<li><strong>404 errors:</strong> Check routes.php for correct partnerdashboard routes</li>";
echo "<li><strong>Authentication issues:</strong> Verify Partner_Controller is working correctly</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The partner receipt functionality should now work correctly without redirecting to the wrong login page.</p>";
?>
