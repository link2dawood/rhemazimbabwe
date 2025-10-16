<?php
// Test partner dashboard fix
// Run: http://localhost/rhemazimbabwe/test_partner_dashboard_fix.php

echo "<h2>Partner Dashboard Fix Test</h2>";

// Test database connection
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();

echo "<h3>Database Test:</h3>";
if ($CI->db->conn_id) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
}

// Test models
echo "<h3>Model Test:</h3>";
try {
    $CI->load->model('type_model');
    $types = $CI->type_model->getAll();
    echo "<p style='color: green;'>✅ Type_model loaded - Found " . count($types) . " giving types</p>";
    
    $CI->load->model('frequency_model');
    $frequencies = $CI->frequency_model->getAll();
    echo "<p style='color: green;'>✅ Frequency_model loaded - Found " . count($frequencies) . " giving frequencies</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Model error: " . $e->getMessage() . "</p>";
}

// Test partner dashboard controller
echo "<h3>Controller Test:</h3>";
try {
    // Check if Partner_Controller exists
    if (file_exists(APPPATH . 'core/Partner_Controller.php')) {
        echo "<p style='color: green;'>✅ Partner_Controller.php exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Partner_Controller.php not found</p>";
    }
    
    // Check if Partnerdashboard controller exists
    if (file_exists(APPPATH . 'controllers/Partnerdashboard.php')) {
        echo "<p style='color: green;'>✅ Partnerdashboard.php exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Partnerdashboard.php not found</p>";
    }
    
    // Check if dashboard view exists
    if (file_exists(APPPATH . 'views/user/partner/dashboard.php')) {
        echo "<p style='color: green;'>✅ Dashboard view exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Dashboard view not found</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Controller error: " . $e->getMessage() . "</p>";
}

echo "<h3>Expected Results:</h3>";
echo "<ul>";
echo "<li>✅ No more 'Undefined variable $giving_types' errors</li>";
echo "<li>✅ No more 'foreach() argument must be of type array|object, null given' errors</li>";
echo "<li>✅ Partner dashboard should load without errors</li>";
echo "<li>✅ Giving types should display properly in the dashboard</li>";
echo "</ul>";

echo "<h3>Test Links:</h3>";
echo "<ul>";
echo "<li><a href='" . base_url() . "'>Home Page</a></li>";
echo "<li><a href='" . base_url('partner_registration') . "'>Partner Registration</a></li>";
echo "<li><a href='" . base_url('userlogin') . "'>Login (to test partner dashboard)</a></li>";
echo "</ul>";

echo "<h3>Note:</h3>";
echo "<p>To test the partner dashboard, you need to:</p>";
echo "<ol>";
echo "<li>Register as a partner first</li>";
echo "<li>Login with partner credentials</li>";
echo "<li>Access the partner dashboard</li>";
echo "</ol>";
?>
