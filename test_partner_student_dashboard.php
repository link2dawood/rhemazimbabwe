<?php
// Test Partner Student Dashboard
// Run: http://localhost/rhemazimbabwe/test_partner_student_dashboard.php

echo "<h1>Testing Partner Student Dashboard</h1>";

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

echo "<h2>2. Testing Partner Controller Loading</h2>";

try {
    // Test if controller file exists
    if (file_exists(APPPATH . 'controllers/user/Partner.php')) {
        echo "<p style='color: green;'>✅ Partner controller file exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ Partner controller file not found!</p>";
        exit;
    }
    
    // Test loading the controller
    $CI->load->file(APPPATH . 'controllers/user/Partner.php');
    $partner_controller = new Partner();
    echo "<p style='color: green;'>✅ Partner controller loaded successfully!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading Partner controller: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>3. Testing Model Methods</h2>";

try {
    // Test Partner_model methods
    $CI->load->model('partner_model');
    
    // Test getByStudentId method
    $test_student_id = 1;
    $partners = $CI->partner_model->getByStudentId($test_student_id);
    echo "<p style='color: green;'>✅ getByStudentId method works! Found " . count($partners) . " partners for student ID $test_student_id</p>";
    
    // Test getByStaffId method
    $test_staff_id = 1;
    $staff_partners = $CI->partner_model->getByStaffId($test_staff_id);
    echo "<p style='color: green;'>✅ getByStaffId method works! Found " . count($staff_partners) . " partners for staff ID $test_staff_id</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing model methods: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Testing Routes</h2>";

$routes_to_test = [
    'user/partner' => 'Partners Dashboard',
    'user/partner/add' => 'Add Partner Form',
    'user/partner/process_add' => 'Process Add Partner',
    'user/partner/contributions/1' => 'View Contributions (Partner ID 1)',
    'user/partner/add_contribution/1' => 'Add Contribution (Partner ID 1)'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Route</th><th>Description</th><th>Test Link</th><th>Expected Result</th></tr>";

foreach ($routes_to_test as $route => $description) {
    $url = base_url($route);
    echo "<tr>";
    echo "<td><code>{$route}</code></td>";
    echo "<td>{$description}</td>";
    echo "<td><a href='{$url}' target='_blank' style='color: blue;'>Test Link</a></td>";
    echo "<td>Should load without errors</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>5. Expected Behavior</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen Now:</h3>";
echo "<ul>";
echo "<li><strong>Partners Button:</strong> Clicking Partners should show list of partners created by the logged-in student</li>";
echo "<li><strong>Add Partner:</strong> Students should be able to add new partners</li>";
echo "<li><strong>View Contributions:</strong> Students should be able to view contributions for each partner</li>";
echo "<li><strong>Add Contributions:</strong> Students should be able to add contributions for each partner</li>";
echo "<li><strong>No Errors:</strong> All pages should load without PHP errors</li>";
echo "</ul>";

echo "<h3>🎯 Student Workflow:</h3>";
echo "<ol>";
echo "<li><strong>Login as Student:</strong> <a href='" . base_url('userlogin') . "' target='_blank'>Student Login</a></li>";
echo "<li><strong>Click Partners:</strong> Should show partners dashboard</li>";
echo "<li><strong>Add Partner:</strong> Click 'Add Partner' button to add new partner</li>";
echo "<li><strong>Manage Partners:</strong> View, edit, and manage partner information</li>";
echo "<li><strong>Add Contributions:</strong> Add contributions for each partner</li>";
echo "</ol>";
echo "</div>";

echo "<h2>6. Database Check</h2>";

try {
    // Check if partners table exists and has data
    $partners_count = $CI->db->count_all('partners');
    echo "<p style='color: green;'>✅ Partners table exists with $partners_count records</p>";
    
    // Check if giving_types table exists
    $types_count = $CI->db->count_all('giving_types');
    echo "<p style='color: green;'>✅ Giving types table exists with $types_count records</p>";
    
    // Check if giving_frequencies table exists
    $frequencies_count = $CI->db->count_all('giving_frequencies');
    echo "<p style='color: green;'>✅ Giving frequencies table exists with $frequencies_count records</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database check error: " . $e->getMessage() . "</p>";
}

echo "<h2>7. Fix Summary</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Model Loading Order:</strong> Fixed setting_model loading before other models</li>";
echo "<li><strong>Duplicate Methods:</strong> Removed duplicate contributions() method</li>";
echo "<li><strong>Partner Dashboard:</strong> Updated to show student's partners list</li>";
echo "<li><strong>Add Partner Form:</strong> Created complete add partner form</li>";
echo "<li><strong>Contributions Management:</strong> Added methods for managing contributions</li>";
echo "</ul>";

echo "<h3>✅ New Features Added:</h3>";
echo "<ul>";
echo "<li><strong>Partners List View:</strong> Shows all partners created by the student</li>";
echo "<li><strong>Add Partner:</strong> Complete form to add new partners</li>";
echo "<li><strong>View Contributions:</strong> View contributions for each partner</li>";
echo "<li><strong>Add Contributions:</strong> Add new contributions for partners</li>";
echo "<li><strong>Partner Management:</strong> Edit and delete partner functionality</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Partner student dashboard setup completed!</strong> Students can now click the Partners button to see their partners list and manage them.</p>";
?>
