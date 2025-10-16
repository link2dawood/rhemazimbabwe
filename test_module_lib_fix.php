<?php
// Test module_lib fix
// Run: http://localhost/rhemazimbabwe/test_module_lib_fix.php

echo "<h2>Module Library Fix Test</h2>";

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

// Test module_lib loading
echo "<h3>Module Library Test:</h3>";
try {
    $CI->load->library('module_lib');
    echo "<p style='color: green;'>✅ module_lib library loaded successfully!</p>";
    
    // Test if module_lib has the hasModule method
    if (method_exists($CI->module_lib, 'hasModule')) {
        echo "<p style='color: green;'>✅ module_lib->hasModule() method exists!</p>";
        
        // Test hasModule method
        $hasOnlineCourse = $CI->module_lib->hasModule('online_course');
        echo "<p style='color: blue;'>ℹ️ hasModule('online_course'): " . ($hasOnlineCourse ? 'true' : 'false') . "</p>";
    } else {
        echo "<p style='color: red;'>❌ module_lib->hasModule() method not found!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ module_lib error: " . $e->getMessage() . "</p>";
}

// Test controller loading
echo "<h3>Controller Test:</h3>";
try {
    $CI->load->library('session');
    $CI->load->model(array('partner_model', 'type_model', 'frequency_model'));
    echo "<p style='color: green;'>✅ Partner_registration controller dependencies loaded</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Controller error: " . $e->getMessage() . "</p>";
}

echo "<h3>Test Links:</h3>";
echo "<ul>";
echo "<li><a href='" . base_url() . "'>Home Page</a></li>";
echo "<li><a href='" . base_url('partner_registration') . "'>Partner Registration</a></li>";
echo "<li><a href='" . base_url('partner_registration/individual') . "'>Individual Registration</a></li>";
echo "<li><a href='" . base_url('partner_registration/organization') . "'>Organization Registration</a></li>";
echo "<li><a href='" . base_url('partner_registration/success') . "'>Success Page</a></li>";
echo "</ul>";

echo "<h3>Expected Results:</h3>";
echo "<ul>";
echo "<li>✅ No more 'Undefined property: CI_Loader::$module_lib' errors</li>";
echo "<li>✅ No more 'Call to a member function hasModule() on null' errors</li>";
echo "<li>✅ Partner registration pages should load without errors</li>";
echo "<li>✅ Theme footer should work properly</li>";
echo "</ul>";
?>
