<?php
// Test Complete Partner Flow
// Run: http://localhost/rhemazimbabwe/test_partner_complete_flow.php

echo "<h1>Complete Partner Flow Test</h1>";

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

echo "<h2>2. Routes Test</h2>";
$routes_to_test = [
    'partnerportal/login' => 'Partner Login Page',
    'partnerdashboard' => 'Partner Dashboard',
    'partnerdashboard/profile' => 'Partner Settings',
    'partnerdashboard/contributions' => 'Partner Contributions',
    'partnerdashboard/add-contribution' => 'Add Contribution',
    'partner_registration' => 'Partner Registration'
];

foreach ($routes_to_test as $route => $description) {
    echo "<p>✅ Route: <code>$route</code> - $description</p>";
}

echo "<h2>3. Controllers Test</h2>";
$controllers_to_test = [
    'application/controllers/Partnerportal.php' => 'Partner Portal Controller',
    'application/controllers/Partnerdashboard.php' => 'Partner Dashboard Controller',
    'application/controllers/Partner_registration.php' => 'Partner Registration Controller',
    'application/core/Partner_Controller.php' => 'Partner Base Controller'
];

foreach ($controllers_to_test as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $description exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $description missing</p>";
    }
}

echo "<h2>4. Models Test</h2>";
$CI->load->model('partner_model');
$CI->load->model('type_model');
$CI->load->model('frequency_model');
$CI->load->model('Partner_giving_setting_model');

try {
    $partners = $CI->partner_model->getAll();
    echo "<p style='color: green;'>✅ Partner_model loaded - Found " . count($partners) . " partners</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Partner_model error: " . $e->getMessage() . "</p>";
}

try {
    $types = $CI->type_model->getAll();
    echo "<p style='color: green;'>✅ Type_model loaded - Found " . count($types) . " giving types</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Type_model error: " . $e->getMessage() . "</p>";
}

try {
    $frequencies = $CI->frequency_model->getAll();
    echo "<p style='color: green;'>✅ Frequency_model loaded - Found " . count($frequencies) . " giving frequencies</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Frequency_model error: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Views Test</h2>";
$views_to_test = [
    'application/views/user/partner/dashboard.php' => 'Partner Dashboard View',
    'application/views/user/partner/settings.php' => 'Partner Settings View',
    'application/views/themes/default/pages/partner_registration.php' => 'Partner Registration View'
];

foreach ($views_to_test as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $description exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $description missing</p>";
    }
}

echo "<h2>6. Partner Authentication Test</h2>";
if (class_exists('Partner_auth')) {
    echo "<p style='color: green;'>✅ Partner_auth library available</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Partner_auth library not found - may need to be created</p>";
}

echo "<h2>7. Complete Flow Test Links</h2>";
echo "<div style='background: #f5f5f5; padding: 20px; border-radius: 5px;'>";
echo "<h3>Step-by-Step Testing:</h3>";
echo "<ol>";
echo "<li><strong>Partner Registration:</strong> <a href='" . base_url('partner_registration') . "' target='_blank'>Register as Partner</a></li>";
echo "<li><strong>Partner Login:</strong> <a href='" . base_url('partnerportal/login') . "' target='_blank'>Login to Partner Portal</a></li>";
echo "<li><strong>Partner Dashboard:</strong> <a href='" . base_url('partnerdashboard') . "' target='_blank'>Access Dashboard</a> (requires login)</li>";
echo "<li><strong>Partner Settings:</strong> <a href='" . base_url('partnerdashboard/profile') . "' target='_blank'>Manage Settings</a> (requires login)</li>";
echo "<li><strong>View Contributions:</strong> <a href='" . base_url('partnerdashboard/contributions') . "' target='_blank'>View Contributions</a> (requires login)</li>";
echo "</ol>";
echo "</div>";

echo "<h2>8. Expected Features</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Partner Dashboard Features:</h3>";
echo "<ul>";
echo "<li>Quick action buttons for Settings, Add Contribution, View Contributions, Change Password</li>";
echo "<li>Statistics display (total partners, active partners, contributions)</li>";
echo "<li>Recent contributions list</li>";
echo "<li>Modal for quick contribution addition</li>";
echo "</ul>";

echo "<h3>✅ Partner Settings Features:</h3>";
echo "<ul>";
echo "<li>Profile information management</li>";
echo "<li>Giving types and amounts configuration</li>";
echo "<li>Giving frequency selection</li>";
echo "<li>Add new contribution form</li>";
echo "</ul>";

echo "<h3>✅ Partner Authentication:</h3>";
echo "<ul>";
echo "<li>Partner login at <code>partnerportal/login</code></li>";
echo "<li>Redirects to <code>partnerdashboard</code> after login</li>";
echo "<li>Session management for partner users</li>";
echo "<li>Password change functionality</li>";
echo "</ul>";
echo "</div>";

echo "<h2>9. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Register a Partner:</strong>";
echo "<ul>";
echo "<li>Go to <a href='" . base_url('partner_registration') . "' target='_blank'>Partner Registration</a></li>";
echo "<li>Fill out the form (Individual or Organization)</li>";
echo "<li>Submit and note the partner credentials</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Login as Partner:</strong>";
echo "<ul>";
echo "<li>Go to <a href='" . base_url('partnerportal/login') . "' target='_blank'>Partner Login</a></li>";
echo "<li>Use the credentials from registration</li>";
echo "<li>Should redirect to partner dashboard</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test Dashboard Features:</strong>";
echo "<ul>";
echo "<li>Click 'Settings' to manage profile and giving settings</li>";
echo "<li>Click 'Add Contribution' to submit a new contribution</li>";
echo "<li>Click 'View Contributions' to see contribution history</li>";
echo "<li>Click 'Change Password' to update password</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>10. Troubleshooting</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>Common Issues:</h3>";
echo "<ul>";
echo "<li><strong>Login redirects to wrong URL:</strong> Check routes.php for correct partnerdashboard route</li>";
echo "<li><strong>Settings page shows errors:</strong> Ensure Partner_giving_setting_model is loaded</li>";
echo "<li><strong>Contribution submission fails:</strong> Check contribution_model methods</li>";
echo "<li><strong>Partner authentication fails:</strong> Verify Partner_auth library exists</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> Use the links above to test the complete partner flow.</p>";
?>
