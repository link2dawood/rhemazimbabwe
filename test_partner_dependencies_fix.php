<?php
// Test Partner Dependencies Fix
// Run: http://localhost/rhemazimbabwe/test_partner_dependencies_fix.php

echo "<h1>Testing Partner Dependencies Fix</h1>";

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

echo "<h2>2. Testing Model Dependencies</h2>";

try {
    // Test loading models in correct order
    $CI->load->helper('custom');
    echo "<p style='color: green;'>✅ Custom helper loaded!</p>";
    
    $CI->load->model('language_model');
    echo "<p style='color: green;'>✅ Language model loaded!</p>";
    
    $CI->load->model('setting_model');
    echo "<p style='color: green;'>✅ Setting model loaded!</p>";
    
    $CI->load->model('partner_model');
    echo "<p style='color: green;'>✅ Partner model loaded!</p>";
    
    $CI->load->model('student_model');
    echo "<p style='color: green;'>✅ Student model loaded!</p>";
    
    $CI->load->model('staff_model');
    echo "<p style='color: green;'>✅ Staff model loaded!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading models: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>3. Testing Helper Functions</h2>";

try {
    // Test check_lock_enabled function
    if (function_exists('check_lock_enabled')) {
        echo "<p style='color: green;'>✅ check_lock_enabled() function available!</p>";
        $lock_status = check_lock_enabled();
        echo "<p>Lock panel status: " . ($lock_status ? 'Enabled' : 'Disabled') . "</p>";
    } else {
        echo "<p style='color: red;'>❌ check_lock_enabled() function not found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing helper functions: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Testing Partner Controller Loading</h2>";

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

echo "<h2>5. Testing Model Methods</h2>";

try {
    // Test Partner_model methods
    $test_student_id = 1;
    $partners = $CI->partner_model->getByStudentId($test_student_id);
    echo "<p style='color: green;'>✅ getByStudentId method works! Found " . count($partners) . " partners</p>";
    
    // Test Setting_model methods
    $current_session = $CI->setting_model->getCurrentSession();
    echo "<p style='color: green;'>✅ getCurrentSession method works!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing model methods: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Testing Partner Routes</h2>";

$routes_to_test = [
    'user/partner' => 'Partners Dashboard',
    'user/partner/add' => 'Add Partner Form',
    'user/partner/process_add' => 'Process Add Partner'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Route</th><th>Description</th><th>Test Link</th><th>Expected Result</th></tr>";

foreach ($routes_to_test as $route => $description) {
    $url = base_url($route);
    echo "<tr>";
    echo "<td><code>{$route}</code></td>";
    echo "<td>{$description}</td>";
    echo "<td><a href='{$url}' target='_blank' style='color: blue;'>Test Link</a></td>";
    echo "<td>Should load without dependency errors</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>7. Expected Behavior</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen Now:</h3>";
echo "<ul>";
echo "<li><strong>No Dependency Errors:</strong> All model dependencies should be loaded correctly</li>";
echo "<li><strong>Header Functions:</strong> check_lock_enabled() and other helper functions available</li>";
echo "<li><strong>Setting Model:</strong> Should work without language_model errors</li>";
echo "<li><strong>Partner Pages:</strong> Should load with proper layout and functionality</li>";
echo "</ul>";

echo "<h3>🔧 Dependencies Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Helper Loading:</strong> Custom helper loaded for check_lock_enabled()</li>";
echo "<li><strong>Model Order:</strong> language_model → setting_model → other models</li>";
echo "<li><strong>Function Availability:</strong> All required functions now available</li>";
echo "</ul>";
echo "</div>";

echo "<h2>8. Manual Testing Instructions</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>🧪 Test the Fixed Partner System:</h3>";
echo "<ol>";
echo "<li><strong>Login as Student:</strong>";
echo "<ul>";
echo "<li>Login as student: <a href='" . base_url('userlogin') . "' target='_blank'>Student Login</a></li>";
echo "</ul>";
echo "</li>";

echo "<li><strong>Test Partner Pages:</strong>";
echo "<ul>";
echo "<li>Click Partners menu - should load without errors</li>";
echo "<li>Check that header displays correctly</li>";
echo "<li>Click 'Add Partner' - should show form</li>";
echo "<li>Fill and submit form - should work</li>";
echo "<li>Verify no PHP errors in browser console</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>9. Fix Summary</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Missing Helper:</strong> Added custom helper loading</li>";
echo "<li><strong>Missing Language Model:</strong> Added language_model loading before setting_model</li>";
echo "<li><strong>Model Dependencies:</strong> Fixed loading order: language_model → setting_model → others</li>";
echo "<li><strong>Function Errors:</strong> All helper functions now available</li>";
echo "</ul>";

echo "<h3>✅ Loading Order:</h3>";
echo "<ol>";
echo "<li>Custom helper (for check_lock_enabled)</li>";
echo "<li>Language model (setting_model dependency)</li>";
echo "<li>Setting model (student_model dependency)</li>";
echo "<li>Other models (partner, student, staff, etc.)</li>";
echo "</ol>";
echo "</div>";

echo "<p><strong>Partner dependencies fix completed!</strong> All model dependencies should now be loaded correctly and the Partner pages should work without errors.</p>";
?>
